<?php

namespace DeviceBundle\Model;

use DeviceBundle\Model\om\BaseControllerBox;

class ControllerBox extends BaseControllerBox
{
    const TYPE_ID = 2;
    const CONTROLLER_INPUTS = 3;
    const CONTROLLER_DS_OUTPUTS = 6;

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

        if (!empty($store)) {
            $this->setStore($store);
            foreach ($inputs as $input)
                $input->setStore($store);
            foreach ($temperatures as $temperature)
                $temperature->setStore($store);
            $this->save();
        }
    }
}
