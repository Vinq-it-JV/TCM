<?php

namespace DeviceBundle\Model;

use DeviceBundle\Model\om\BaseDsTemperatureSensor;

class DsTemperatureSensor extends BaseDsTemperatureSensor
{
    const TYPE_ID = 3;

    /**
     * getDsTemperatureSensorDataArray()
     * @return array
     */
    public function getDsTemperatureSensorDataArray()
    {
        $data = [];
        $data['dstemperaturesensor'] = $this->toArray();
        $data['dstemperaturesensor']['TypeId'] = self::TYPE_ID;

        unset($data['dstemperaturesensor']['CreatedAt']);
        unset($data['dstemperaturesensor']['UpdatedAt']);

        return $data;
    }

    /**
     * getDsTemperatureSensorTemplateArray()
     * @return array
     */
    public function getDsTemperatureSensorTemplateArray()
    {
        $sensor = new DsTemperatureSensor();
        return $sensor->getDsTemperatureSensorDataArray();
    }

}
