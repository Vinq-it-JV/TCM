<?php

namespace DataCollectorBundle\Controller;

use DataCollectorBundle\Model\CollectorLog;
use DataCollectorBundle\Model\CollectorLogQuery;
use DeviceBundle\Model\CbInputLog;
use DeviceBundle\Model\DsTemperatureSensorLog;
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

    public function collectDataAction(Request $request)
    {
        $log = new CollectorLog();
        $data = $request->getContent();
        $this->collectControllerData($data);
        $log->setPacketData($data);
        $log->save();

        return new Response();
    }

    public function getPacketlogAction(Request $request)
    {
        $dataArr = [];
        $helper = $this->getCollectorHelper();

        $logs = CollectorLogQuery::create()
            ->orderById('DESC')
            ->limit(10)
            ->find();

        foreach ($logs as $log) {
            $logArr = [];
            $data = $log->getPacketData();
            $logArr['Id'] = $log->getId();
            $logArr['Uid'] = substr($data, 4, 12);
            $logArr['Internal'] = $helper->getMCPTemp(substr($data, 16, 8));
            $logArr['InputStates'] = $helper->getCbInputData(hexdec(substr($data, 2, 2)));
            $logArr['Outputs'] = [];
            $logArr['DisplayMode'] = 0;
            for ($temp = 0; $temp < ControllerBox::CONTROLLER_DS_OUTPUTS; $temp++) {
                $output = $helper->getDsTemperatureData(substr($data, 24 + $temp * 20, 20));
                $sensor = DsTemperatureSensorQuery::create()->findOneByUid($output['uid']);
                if (!empty($sensor))
                    $output['name'] = $sensor->getName();
                else
                    $output['name'] = '';
                $logArr['Outputs'][] = $output;
            }
            $logArr['Name'] = '';
            $logArr['StoreName'] = '';
            $controller = ControllerBoxQuery::create()->findOneByUid($logArr['Uid']);
            if (!empty($controller)) {
                $logArr['Name'] = $controller->getName();
                $store = $controller->getStore();
                if (!empty($store))
                    $logArr['StoreName'] = $store->getName();
            }
            $dataArr['packetLog'][] = $logArr;
        }
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

        $helper = $this->getCollectorHelper();
        $inputs = hexdec(substr($data, 2, 2));
        $controllerUid = substr($data, 4, 12);
        $internalTemp = $helper->getMCPTemp(substr($data, 16, 8));

        $controller = $this->updateControllerBox($version, $controllerUid, $internalTemp);
        $this->updateInputs($inputs, $controller);

        for ($temp = 0; $temp < ControllerBox::CONTROLLER_DS_OUTPUTS; $temp++) {
            $DsTemperatureData = $helper->getDsTemperatureData(substr($data, 24 + $temp * 20, 20));
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

        $helper = $this->getCollectorHelper();
        $date = new \DateTime();

        $bit = 0x01;
        $inputNr = 1;
        $inputStates = $helper->getCbInputData($inputs);

        foreach ($inputStates as $input) {
            $_input = CbInputQuery::create()
                ->findOneByArray(['ControllerBox' => $controller, 'InputNumber' => $inputNr]);
            if (empty($_input))
                $_input = new CbInput();
            if (!empty($controller)) {
                $_input->setUid($controller->getUid());
                $_input->setControllerBox($controller);
            }
            $_input->setInputNumber($inputNr)
                ->setSwitchState($input)
                ->setDataCollectedAt($date)
                ->save();

            // Log input data
            $log = new CbInputLog();
            $log->setCbInput($_input)
                ->setSwitchState($input)
                ->setSwitchWhen($_input->getSwitchWhen())
                ->setRawData($inputs)
                ->setDataCollectedAt($date)
                ->save();

            $bit <<= 1;
            $inputNr++;
        }
        return true;
    }

    protected function updateDsOutput($data, $output, $controller = null)
    {
        $uid = $data['uid'];

        if (empty($controller) || empty($uid))
            return false;

        $date = new \DateTime();

        $temperature = DsTemperatureSensorQuery::create()
            ->findOneByUid($uid);

        if (empty($temperature))
            $temperature = new DsTemperatureSensor();

        $temperature->setUid($uid);
        $temperature->setOutputNumber($output);
        $temperature->setTemperature($data['temperature']);
        if (!empty($controller)) {
            $temperature->setControllerBox($controller);
            if (!empty($controller->getMainStore()))
                $temperature->setMainStore($controller->getMainStore());
        }
        $temperature->setDataCollectedAt($date);
        $temperature->save();

        // Log sensor data
        $log = new DsTemperatureSensorLog();
        $log->setDsTemperatureSensor($temperature)
            ->setTemperature($data['temperature'])
            ->setLowLimit($temperature->getLowLimit())
            ->setHighLimit($temperature->getHighLimit())
            ->setRawData($data['raw'])
            ->setDataCollectedAt($date)
            ->save();

        return true;
    }

    /**
     * Get collector helper
     * @return object
     */
    protected function getCollectorHelper()
    {
        $helper = $this->container->get('collector_helper');
        return $helper;
    }

}
