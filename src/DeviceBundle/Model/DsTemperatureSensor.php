<?php

namespace DeviceBundle\Model;

use DeviceBundle\Model\om\BaseDsTemperatureSensor;
use \Criteria;
use NotificationBundle\Model\DsTemperatureNotification;
use StoreBundle\Model\StoreQuery;

class DsTemperatureSensor extends BaseDsTemperatureSensor
{
    const TYPE_ID = 3;

    const STATE_ACTIVE = 0;
    const STATE_INACTIVE = 1;
    const STATE_NOTIFY = 2;

    const INACTIVITY_TIME = 300;    // 5 minutes

    /**
     * getDsTemperatureSensorDataArray()
     * @return array
     */
    public function getDsTemperatureSensorDataArray()
    {
        $data = [];
        $data['dstemperaturesensor'] = $this->toArray();
        $data['dstemperaturesensor']['TypeId'] = self::TYPE_ID;
        $data['dstemperaturesensor']['IsCopy'] = false;

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

    /**
     * getDeviceCopiesArray
     * @param int $groupId
     * @return array
     */
    public function getDeviceCopiesArray($groupId = 0)
    {
        $copyArr = [];
        foreach ($this->getDeviceCopies() as $copy) {
            $sensor = DsTemperatureSensorQuery::create()->findOneById($copy->getCopyOfSensor());
            $sensorArr = $sensor->getDsTemperatureSensorDataArray()['dstemperaturesensor'];
            $sensorArr['Uid'] = $copy->getUid();
            $sensorArr['Name'] = $copy->getName();
            $sensorArr['Position'] = $copy->getPosition();
            $sensorArr['Group'] = $copy->getGroup();
            $sensorArr['MainStore'] = $copy->getMainStore();
            $sensorArr['IsCopy'] = true;
            if ($copy->getGroup() == $groupId)
                $copyArr[] = $sensorArr;
        }
        return $copyArr;
    }

    /**
     * checkSensorInactive()
     * @return bool
     */
    public function checkSensorInactive()
    {
        $store = StoreQuery::create()->findOneById($this->getMainStore());
        if (!empty($store))
            if ($store->getIsMaintenance())
                return false;

        $date = new \DateTime();
        $now = $date->format('Y-m-d H:i:s');
        $updated = $this->getDataCollectedAt('Y-m-d H:i:s');
        $diffSeconds = strtotime($now) - strtotime($updated);
        if ($this->getState() != self::STATE_NOTIFY) {
            if ($diffSeconds >= self::INACTIVITY_TIME) {
                $this->setState(self::STATE_INACTIVE);
                $this->save();
                return true;
            }
            else {
                $this->setState(self::STATE_ACTIVE);
                $this->save();
            }
        }
        return false;
    }

    /**
     * checkSensorNotify()
     * @return bool
     */
    public function checkSensorNotify()
    {
        $date = new \DateTime();
        $now = $date->format('Y-m-d H:i:s');

        if ($this->getNotifyAfter() == -1)
            return false;

        $store = StoreQuery::create()->findOneById($this->getMainStore());
        if (!empty($store))
            if ($store->getIsMaintenance())
                return false;

        if ($this->getState() == self::STATE_NOTIFY)
            return true;

        $temperature = round(floatval($this->getTemperature()), 2);
        $low = $this->getLowLimit();
        $high = $this->getHighLimit();

        if (($temperature < $low) || ($temperature > $high)) {
            $date = new \DateTime();
            if (empty($this->getNotifyStartedAt())) {
                $this->setNotifyStartedAt($date);
                $this->save();
            }
            $started = $this->getNotifyStartedAt('Y-m-d H:i:s');
            $diffSeconds = strtotime($now) - strtotime($started);
            if ($diffSeconds >= $this->getNotifyAfter())
            {   $notification = new DsTemperatureNotification();
                $notification->setTemperature((string)$temperature);
                $notification->setReason(DsTemperatureNotification::REASON_TEMPERATURE);
                $notification->save();
                $this->setState(self::STATE_NOTIFY);
                $this->addDsTemperatureNotification($notification);
                $this->save();
                return true;
            }
        }
        else {
            $this->setNotifyStartedAt(NULL);
            $this->save();
        }
        return false;
    }

    /**
     * getNotificationAfterListArray()
     * @return array
     */
    static public function getNotificationAfterListArray()
    {
        $list = [];
        $list[0]['Name'] = 'NOTIFY_AFTER.DIRECTLY';
        $list[0]['Value'] = 0;
        $list[1]['Name'] = 'NOTIFY_AFTER.30_SEC';
        $list[1]['Value'] = 30;
        $list[2]['Name'] = 'NOTIFY_AFTER.1_MIN';
        $list[2]['Value'] = 60;
        $list[3]['Name'] = 'NOTIFY_AFTER.2_MIN';
        $list[3]['Value'] = 120;
        $list[4]['Name'] = 'NOTIFY_AFTER.5_MIN';
        $list[4]['Value'] = 300;
        $list[5]['Name'] = 'NOTIFY_AFTER.10_MIN';
        $list[5]['Value'] = 600;
        $list[6]['Name'] = 'NOTIFY_AFTER.15_MIN';
        $list[6]['Value'] = 900;
        $list[7]['Name'] = 'NOTIFY_AFTER.30_MIN';
        $list[7]['Value'] = 1800;
        $list[8]['Name'] = 'NOTIFY_AFTER.1_HOUR';
        $list[8]['Value'] = 3600;
        $list[9]['Name'] = 'NOTIFY_AFTER.2_HOUR';
        $list[9]['Value'] = 7200;
        $list[10]['Name'] = 'NOTIFY_AFTER.5_HOUR';
        $list[10]['Value'] = 18000;
        $list[11]['Name'] = 'NOTIFY_AFTER.10_HOUR';
        $list[11]['Value'] = 36000;
        $list[12]['Name'] = 'NOTIFY_AFTER.1_DAY';
        $list[12]['Value'] = 86400;
        $list[13]['Name'] = 'NOTIFY_AFTER.NEVER';
        $list[13]['Value'] = -1;

        $arr = [];
        $arr['notify_after'] = $list;
        return $arr;
    }

    /**
     * hasOpenNotification
     * @return bool
     */
    public function hasOpenNotification()
    {
        $c = new Criteria();
        $c->addDescendingOrderByColumn('created_at');
        $c->setLimit(1);

        $notification = $this->getDsTemperatureNotifications($c);
        if (!$notification->isEmpty()) {
            if (!$notification[0]->getIsHandled())
                return true;
        }
        return false;
    }

}
