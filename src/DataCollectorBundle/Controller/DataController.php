<?php

namespace DataCollectorBundle\Controller;

use DataCollectorBundle\Model\CollectorLog;
use DataCollectorBundle\Model\CollectorLogQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use DeviceBundle\Model\CbInput;
use DeviceBundle\Model\CbInputQuery;
use DeviceBundle\Model\ControllerBox;
use DeviceBundle\Model\ControllerBoxQuery;
use DeviceBundle\Model\DsTemperatureSensor;
use DeviceBundle\Model\DsTemperatureSensorQuery;

use AppBundle\Response\JsonResult;

class DataController extends Controller
{
    const CONTROLLER_V1 = 1;
    const CONTROLLER_V2 = 2;
    const CONTROLLER_V3 = 3;
    const CONTROLLER_V3_PACKET_LENGTH = 144;

    public function testPostDataAction(Request $request)
    {
        $url = "http://tcm:8888/data/collector";

        $data = "0307E03889AA340D0000C19328FF9E64851604B3002B28FF0B66851604D1009128FF0BB0841603F1002628FF1D61851604B7008628FFB9AE84160310003A28FF22AD8416035F002B";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));

        curl_exec($ch);
        curl_close($ch);

        echo printf('Send %s to %s', $data, $url);

        return new Response();
    }

    public function collectDataAction(Request $request)
    {
        $log = new CollectorLog();

        $fs = new Filesystem();
        $logger = $this->get('logger');

        $rootDir = $this->get('kernel')->getRootDir();
        $fileDir = '/data/collector';
        $file = '/data.txt';

        $data = $request->getContent();

        if (!$fs->exists($rootDir . $fileDir)) {
            try {
                $fs->mkdir($rootDir . $fileDir, 0700);
            } catch (IOExceptionInterface $e) {
                $logger->error('Can not create directory at ' . $e->getPath());
                return new Response();
            }
        }

        file_put_contents($rootDir . $fileDir . $file, $data);
        $this->collectControllerData($data);

        $log->setPacketData($data);
        $log->save();

        return new Response();
    }

    public function getPacketlogAction(Request $request)
    {
        $dataArr = [];

        $logs = CollectorLogQuery::create()
            ->orderById('DESC')
            ->limit(10)
            ->find();

        foreach ($logs as $log)
            $dataArr['logs'][] = $log->getCollectorLogDataArray()['log'];

        return JsonResult::create()
            ->setContents($dataArr)
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    protected function collectControllerData($data)
    {
        $version = hexdec(substr($data, 0, 2));
        switch ($version) {
            case self::CONTROLLER_V3:
                $this->parseControllerDataV3($version, $data);
                break;
        }
    }

    protected function parseControllerDataV3($version, $data)
    {
        if (strlen($data) != self::CONTROLLER_V3_PACKET_LENGTH)
            return;
        $inputs = hexdec(substr($data, 2, 2));
        $controllerUid = substr($data, 4, 12);
        $internalTemp = $this->getMCPTemp(substr($data, 16, 8));

        $controller = $this->updateControllerBox($version, $controllerUid, $internalTemp);
        $this->updateInputs($inputs, $controller);

        for ($temp = 0; $temp < ControllerBox::CONTROLLER_DS_OUTPUTS; $temp++) {
            $DsTemperatureData = $this->getDsTemperatureData(substr($data, 24 + $temp * 20, 20));
            $this->updateDsOutput($DsTemperatureData, (ControllerBox::CONTROLLER_DS_OUTPUTS - $temp), $controller);
        }
    }

    protected function updateControllerBox($version, $uid, $temperature)
    {
        $date = new \DateTime();

        $controller = ControllerBoxQuery::create()->findOneByUid($uid);

        if (empty($controller))
            $controller = new ControllerBox();
        $controller->setUid($uid);
        $controller->setVersion($version);
        $controller->setState(ControllerBox::STATE_ACTIVE);
        $controller->setDataCollectedAt($date);
        $controller->setInternalTemperature($temperature);
        $controller->save();
        return $controller;
    }

    protected function updateInputs($inputs, $controller = null)
    {
        if (empty($controller))
            return false;

        $date = new \DateTime();

        $bit = 0x01;

        for ($inp = 1; $inp <= ControllerBox::CONTROLLER_INPUTS; $inp++) {
            $input = CbInputQuery::create()
                ->findOneByArray(['ControllerBox' => $controller, 'InputNumber' => $inp]);

            if (empty($input))
                $input = new CbInput();

            $input->setUid($controller->getUid());
            $input->setInputNumber($inp);
            if ($inputs & $bit)
                $input->setSwitchState(false);
            else
                $input->setSwitchState(true);
            $input->setControllerBox($controller);
            $input->setDataCollectedAt($date);
            $input->save();
            $bit <<= 1;
        }
        return true;
    }

    protected function updateDsOutput($data, $output, $controller = null)
    {
        $uid = $data['uid'];

        if (empty($controller) || empty($uid) || !hexdec($uid))
            return false;

        $date = new \DateTime();

        $temperature = DsTemperatureSensorQuery::create()
            ->findOneByUid($uid);

        if (empty($temperature))
            $temperature = new DsTemperatureSensor();

        $temperature->setUid($uid);
        $temperature->setOutputNumber($output);
        $temperature->setTemperature($data['temperature']);
        $temperature->setControllerBox($controller);
        if (!empty($controller->getMainStore()))
            $temperature->setMainStore($controller->getMainStore());
        $temperature->setDataCollectedAt($date);
        $temperature->save();
        return true;
    }

    protected function getDsTemperatureData($data)
    {
        $dataArr = [];
        $dataArr['uid'] = null;
        $dataArr['temperature'] = null;

        $uid = substr($data, 0, 16);
        if ($this->checkCRC8($uid)) {
            $dataArr['uid'] = substr($data, 0, 16);
            $dataArr['temperature'] = $this->getDSTemp($data);
        }

        return $dataArr;
    }

    protected function getMCPTemp($data)
    {
        $upper = hexdec(substr($data, 4, 2));
        $lower = hexdec(substr($data, 6, 2));
        $upper = $upper & 0x1f;
        if (($upper & 0x10) == 0x10) {
            $upper = $upper & 0x0f;
            $temp = 256 - ($upper * 16) + ($lower / 16);
        } else
            $temp = ($upper * 16) + ($lower / 16);
        return $temp;
    }

    protected function getDSTemp($data)
    {
        $upper = hexdec(substr($data, 16, 2));
        $lower = hexdec(substr($data, 18, 2));

        $neg = false;
        $temp = ($upper << 8) + $lower;
        if ($temp & 0x8000) {
            $neg = true;
            $temp = ($temp ^ 0xffff) + 1;
        }
        $realtemp = (6 * $temp) + ($temp / 4);
        $temp = ($realtemp / 100);
        if ($neg)
            $temp = 0 - $temp;
        return $temp;
    }

    protected function checkCRC8($s)
    {
        $table = array(0x00, 0x5E, 0xBC, 0xE2, 0x61, 0x3F, 0xDD, 0x83,
            0xC2, 0x9C, 0x7E, 0x20, 0xA3, 0xFD, 0x1F, 0x41,
            0x9D, 0xC3, 0x21, 0x7F, 0xFC, 0xA2, 0x40, 0x1E,
            0x5F, 0x01, 0xE3, 0xBD, 0x3E, 0x60, 0x82, 0xDC,
            0x23, 0x7D, 0x9F, 0xC1, 0x42, 0x1C, 0xFE, 0xA0,
            0xE1, 0xBF, 0x5D, 0x03, 0x80, 0xDE, 0x3C, 0x62,
            0xBE, 0xE0, 0x02, 0x5C, 0xDF, 0x81, 0x63, 0x3D,
            0x7C, 0x22, 0xC0, 0x9E, 0x1D, 0x43, 0xA1, 0xFF,
            0x46, 0x18, 0xFA, 0xA4, 0x27, 0x79, 0x9B, 0xC5,
            0x84, 0xDA, 0x38, 0x66, 0xE5, 0xBB, 0x59, 0x07,
            0xDB, 0x85, 0x67, 0x39, 0xBA, 0xE4, 0x06, 0x58,
            0x19, 0x47, 0xA5, 0xFB, 0x78, 0x26, 0xC4, 0x9A,
            0x65, 0x3B, 0xD9, 0x87, 0x04, 0x5A, 0xB8, 0xE6,
            0xA7, 0xF9, 0x1B, 0x45, 0xC6, 0x98, 0x7A, 0x24,
            0xF8, 0xA6, 0x44, 0x1A, 0x99, 0xC7, 0x25, 0x7B,
            0x3A, 0x64, 0x86, 0xD8, 0x5B, 0x05, 0xE7, 0xB9,
            0x8C, 0xD2, 0x30, 0x6E, 0xED, 0xB3, 0x51, 0x0F,
            0x4E, 0x10, 0xF2, 0xAC, 0x2F, 0x71, 0x93, 0xCD,
            0x11, 0x4F, 0xAD, 0xF3, 0x70, 0x2E, 0xCC, 0x92,
            0xD3, 0x8D, 0x6F, 0x31, 0xB2, 0xEC, 0x0E, 0x50,
            0xAF, 0xF1, 0x13, 0x4D, 0xCE, 0x90, 0x72, 0x2C,
            0x6D, 0x33, 0xD1, 0x8F, 0x0C, 0x52, 0xB0, 0xEE,
            0x32, 0x6C, 0x8E, 0xD0, 0x53, 0x0D, 0xEF, 0xB1,
            0xF0, 0xAE, 0x4C, 0x12, 0x91, 0xCF, 0x2D, 0x73,
            0xCA, 0x94, 0x76, 0x28, 0xAB, 0xF5, 0x17, 0x49,
            0x08, 0x56, 0xB4, 0xEA, 0x69, 0x37, 0xD5, 0x8B,
            0x57, 0x09, 0xEB, 0xB5, 0x36, 0x68, 0x8A, 0xD4,
            0x95, 0xCB, 0x29, 0x77, 0xF4, 0xAA, 0x48, 0x16,
            0xE9, 0xB7, 0x55, 0x0B, 0x88, 0xD6, 0x34, 0x6A,
            0x2B, 0x75, 0x97, 0xC9, 0x4A, 0x14, 0xF6, 0xA8,
            0x74, 0x2A, 0xC8, 0x96, 0x15, 0x4B, 0xA9, 0xF7,
            0xB6, 0xE8, 0x0A, 0x54, 0xD7, 0x89, 0x6B, 0x35);

        $len = strlen($s);
        $calccrc = 0xff;
        if ($len <= 0xff) {
            for ($i = 0; $i < ($len); $i += 2)
                $calccrc = $table[($calccrc ^ hexdec(substr($s, $i, 2)))];
        }
        return ($calccrc == 0xdd);
    }
}
