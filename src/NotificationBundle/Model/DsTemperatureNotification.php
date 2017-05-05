<?php

namespace NotificationBundle\Model;

use DeviceBundle\Model\DsTemperatureSensor;
use DeviceBundle\Model\DsTemperatureSensorQuery;
use NotificationBundle\Model\om\BaseDsTemperatureNotification;
use UserBundle\Model\UserQuery;

class DsTemperatureNotification extends BaseDsTemperatureNotification
{
    const REASON_INACTIVE = 1;
    const REASON_TEMPERATURE = 2;

    /**
     * getSensor()
     * @return \DeviceBundle\Model\DsTemperatureSensor|int
     */
    public function getSensor()
    {
        $sensor = parent::getSensor();
        if (is_numeric($sensor))
            $sensor = DsTemperatureSensorQuery::create()->findOneById($sensor);
        return $sensor;
    }

    /**
     * getHandledBy()
     * @return int|\UserBundle\Model\User
     */
    public function getHandledBy()
    {
        $user = parent::getHandledBy();
        if (is_numeric($user))
            $user = UserQuery::create()->findOneById($user);
        return $user;
    }

    /**
     * handleNotification($user)
     * @param $user
     */
    public function handleNotification($user)
    {
        $this->setIsHandled(true);
        $this->setHandledBy($user->getId());

        $sensor = $this->getSensor();
        $sensor->setNotifyStartedAt(NULL);
        $sensor->setState(DsTemperatureSensor::STATE_ACTIVE);
        $sensor->save();

        $this->save();
    }
}
