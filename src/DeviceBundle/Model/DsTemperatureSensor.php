<?php

namespace DeviceBundle\Model;

use DeviceBundle\Model\om\BaseDsTemperatureSensor;
use \Criteria;
use NotificationBundle\Model\DsTemperatureNotification;

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
     * checkSensorStatus()
     * @return bool
     */
    public function checkSensorStatus()
    {
        $state = self::STATE_ACTIVE;

        if ($this->checkSensorInactive())
            $state = self::STATE_INACTIVE;

        // This check prevents excessive database checks on hasOpenNotification
        if ($this->getState() == self::STATE_NOTIFY)
            return self::STATE_NOTIFY;

        if ($this->hasOpenNotification())
            return self::STATE_NOTIFY;

        if ($this->checkSensorNotify())
            return self::STATE_NOTIFY;

        return $state;
    }

    /**
     * checkSensorInactive()
     * @return bool
     */
    public function checkSensorInactive()
    {
        $date = new \DateTime();
        $now = $date->format('Y-m-d H:i:s');
        $updated = $this->getDataCollectedAt('Y-m-d H:i:s');
        $diffSeconds = strtotime($now) - strtotime($updated);

        if ($diffSeconds >= self::INACTIVITY_TIME) {
            $this->setState(self::STATE_INACTIVE);
            $this->save();
            return true;
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
        return false;
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
