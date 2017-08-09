<?php

namespace DeviceBundle\Model;

use DeviceBundle\Model\om\BaseDeviceCopy;

class DeviceCopy extends BaseDeviceCopy
{
    /**
     * Get device copy array
     * @param int $groupId
     * @return array
     */
    public function getDeviceCopyArray($groupId = 0)
    {
        $copyArr = [];
        if (!empty($this->getCopyOfSensor())) {
            $sensor = DsTemperatureSensorQuery::create()->findOneById($this->getCopyOfSensor());
            if (!empty($sensor)) {
                $sensorArr = $sensor->getDsTemperatureSensorDataArray()['dstemperaturesensor'];
                $sensorArr['Uid'] = $this->getUid();
                $sensorArr['Name'] = $this->getName();
                $sensorArr['Position'] = $this->getPosition();
                $sensorArr['Group'] = $this->getGroup();
                $sensorArr['MainStore'] = $this->getMainStore();
                $sensorArr['IsCopy'] = true;
                if ($this->getGroup() == $groupId)
                    $copyArr[] = $sensorArr;
            }
        }
        if (!empty($this->getCbInput())) {
            $input = CbInputQuery::create()->findOneById($this->getCopyOfInput());
            if (!empty($input)) {
                $inputArr = $input->getCbInputDataArray()['cbinput'];
                $inputArr['Uid'] = $this->getUid();
                $inputArr['Name'] = $this->getName();
                $inputArr['Position'] = $this->getPosition();
                $inputArr['Group'] = $this->getGroup();
                $inputArr['MainStore'] = $this->getMainStore();
                $inputArr['IsCopy'] = true;
                if ($this->getGroup() == $groupId)
                    $copyArr[] = $inputArr;
            }
        }
        return $copyArr;
    }
}
