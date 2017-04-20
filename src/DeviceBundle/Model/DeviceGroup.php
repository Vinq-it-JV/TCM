<?php

namespace DeviceBundle\Model;

use DeviceBundle\Model\om\BaseDeviceGroup;

class DeviceGroup extends BaseDeviceGroup
{

    const TYPE_ID = 1;

    /**
     * getDeviceGroupDataArray()
     * @return array
     */
    public function getDeviceGroupDataArray()
    {
        $data = [];
        $data['devicegroup'] = $this->toArray();
        $data['devicegroup']['TypeId'] = self::TYPE_ID;
        $data['devicegroup']['devices'] = [];

        $deviceArr = [];
        if (!$this->getDsTemperatureSensors()->isEmpty()) {
            foreach ($this->getDsTemperatureSensors() as $sensor) {
                $deviceArr[] = $sensor->getDsTemperatureSensorDataArray()['dstemperaturesensor'];
            }
        }

        if (!$this->getControllerBoxen()->isEmpty()) {
            foreach ($this->getControllerBoxen() as $controller) {
                $deviceArr[] = $controller->getControllerBoxDataArray()['controllerbox'];
            }
        }

        if (!$this->getCbInputs()->isEmpty()) {
            foreach ($this->getCbInputs() as $input) {
                $deviceArr[] = $input->getCbInputDataArray()['cbinput'];
            }
        }

        usort($deviceArr, function ($a, $b) {
            $a = (object)$a;
            $b = (object)$b;
            return $a->Position > $b->Position;
        });

        $data['devicegroup']['devices'] = $deviceArr;

//        foreach ($deviceArr as $device)
//        {   $_device = (object)$device;
//            if (!empty($_device->Position))
//                $data['devicegroup']['devices'][$_device->Position] = $device;
//            else
//                $data['devicegroup']['devices'][] = $device;
//        }

//        print "<pre>";
//        var_dump($data['devicegroup']['devices']);
//        print "</pre>";
//        die();

        unset($data['devicegroup']['CreatedAt']);
        unset($data['devicegroup']['UpdatedAt']);

        return $data;
    }

    /**
     * getDeviceGroupTemplateArray()
     * @return array
     */
    public function getDeviceGroupTemplateArray()
    {
        $group = new DeviceGroup();
        return $group->getDeviceGroupDataArray();
    }

}