<?php

namespace DeviceBundle\Model;

use DeviceBundle\Model\om\BaseDeviceGroup;

class DeviceGroup extends BaseDeviceGroup
{

    const TYPE_ID = 1;

    const STATUS_OK = 0;
    const STATUS_NOTIFY = 2;

    /**
     * getDeviceGroupDataArray
     * @param null $parentArr
     * @return array
     */
    public function getDeviceGroupDataArray()
    {
        $data = [];
        $data['devicegroup'] = $this->toArray();
        $data['devicegroup']['TypeId'] = self::TYPE_ID;
        $data['devicegroup']['devices'] = [];
        $data['devicegroup']['State'] = $this->getDeviceGroupStatus();
        $data['devicegroup']['IsCopy'] = false;

        $deviceArr = [];
        if (!$this->getDsTemperatureSensors()->isEmpty()) {
            foreach ($this->getDsTemperatureSensors() as $sensor)
                $deviceArr[] = $sensor->getDsTemperatureSensorDataArray()['dstemperaturesensor'];
        }

        if (!$this->getControllerBoxen()->isEmpty()) {
            foreach ($this->getControllerBoxen() as $controller) {
                $deviceArr[] = $controller->getControllerBoxDataArray()['controllerbox'];
            }
        }

        if (!$this->getCbInputs()->isEmpty()) {
            foreach ($this->getCbInputs() as $input)
                $deviceArr[] = $input->getCbInputDataArray()['cbinput'];
        }

        foreach ($this->getDeviceCopies() as $copy) {
            $copyArr = $copy->getDeviceCopyArray($this->getId());
            if (!empty($copyArr))
                $deviceArr = array_merge($deviceArr, $copyArr);
        }

        usort($deviceArr, function ($a, $b) {
            $a = (object)$a;
            $b = (object)$b;
            if (isset($a->Position) && isset($b->Position))
                return $a->Position > $b->Position;
            return false;
        });

        $data['devicegroup']['devices'] = $deviceArr;

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

    /**
     * getDeviceGroupStatus()
     * @return int
     */
    public function getDeviceGroupStatus()
    {
        $inputs = $this->getCbInputs();
        foreach ($inputs as $input) {
            if ($input->getIsEnabled())
                if ($input->getState() == CbInput::STATE_NOTIFY) {
                    $this->setState(self::STATUS_NOTIFY);
                    $this->save();
                    return self::STATUS_NOTIFY;
                }
        }

        $sensors = $this->getDsTemperatureSensors();
        foreach ($sensors as $sensor) {
            if ($sensor->getIsEnabled())
                if ($sensor->getState() == DsTemperatureSensor::STATE_NOTIFY) {
                    $this->setState(self::STATUS_NOTIFY);
                    $this->save();
                    return self::STATUS_NOTIFY;
                }
        }
        $this->setState(self::STATUS_NOTIFY);
        $this->save();
        return self::STATUS_OK;
    }

}