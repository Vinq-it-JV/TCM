<?php

namespace DeviceBundle\Model;

use DeviceBundle\Model\om\BaseControllerBox;

class ControllerBox extends BaseControllerBox
{
    const TYPE_ID = 2;
    const CONTROLLER_INPUTS = 3;
    const CONTROLLER_DS_OUTPUTS = 6;

    const STATE_ACTIVE = 0;
    const STATE_INACTIVE = 1;
    const STATE_NOTIFY = 2;

    /**
     * getControllerBoxDataArray()
     * @return array
     */
    public function getControllerBoxDataArray()
    {
        $data = [];
        $data['controllerbox'] = $this->toArray();
        $data['controllerbox']['TypeId'] = self::TYPE_ID;

        unset($data['controllerbox']['CreatedAt']);
        unset($data['controllerbox']['UpdatedAt']);

        return $data;
    }

    /**
     * getControllerBoxTemplateArray()
     * @return array
     */
    public function getControllerBoxTemplateArray()
    {
        $controller = new ControllerBox();
        return $controller->getControllerBoxDataArray();
    }

    /**
     * linkChildSensorsToStore($store)
     * @param $store
     */
    public function linkChildSensorsToStore($store)
    {
        $inputs = $this->getCbInputs();
        $temperatures = $this->getDsTemperatureSensors();

        $group = $this->getDeviceGroup();
        if (empty($group)) {
            $group = new DeviceGroup();
            $group->setName($this->getUid());
            $group->setStore($store);
            $group->save();
            $this->setDeviceGroup($group);
        }
        if (!empty($store)) {
            $this->setStore($store);
            foreach ($inputs as $input) {
                $input->setStore($store);
                $input->setDeviceGroup($group);
            }
            foreach ($temperatures as $temperature) {
                $temperature->setStore($store);
                $temperature->setDeviceGroup($group);
            }
            $this->save();
        }
    }
}
