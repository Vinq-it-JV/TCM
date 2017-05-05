<?php

namespace NotificationBundle\Model;

use DeviceBundle\Model\CbInput;
use DeviceBundle\Model\CbInputQuery;
use NotificationBundle\Model\om\BaseCbInputNotification;
use UserBundle\Model\UserQuery;

class CbInputNotification extends BaseCbInputNotification
{
    const REASON_INACTIVE = 1;
    const REASON_SWITCH_STATE = 2;

    /**
     * getSensor()
     * @return \DeviceBundle\Model\CbInput|int
     */
    public function getSensor()
    {
        $sensor = parent::getSensor();
        if (is_numeric($sensor))
            $sensor = CbInputQuery::create()->findOneById($sensor);
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
        $sensor->setState(CbInput::STATE_ACTIVE);
        $sensor->save();

        $this->save();
    }
}
