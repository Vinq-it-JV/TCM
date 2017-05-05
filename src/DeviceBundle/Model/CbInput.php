<?php

namespace DeviceBundle\Model;

use DeviceBundle\Model\om\BaseCbInput;
use \Criteria;
use NotificationBundle\Model\CbInputNotification;

class CbInput extends BaseCbInput
{
    const TYPE_ID = 4;

    const STATE_ACTIVE = 0;
    const STATE_INACTIVE = 1;
    const STATE_NOTIFY = 2;

    const INACTIVITY_TIME = 300;    // 5 minutes

    /**
     * getCbInputDataArray()
     * @return array
     */
    public function getCbInputDataArray()
    {
        $data = [];
        $data['cbinput'] = $this->toArray();
        $data['cbinput']['TypeId'] = self::TYPE_ID;

        unset($data['cbinput']['CreatedAt']);
        unset($data['cbinput']['UpdatedAt']);

        return $data;
    }

    /**
     * getCbInputTemplateArray()
     * @return array
     */
    public function getCbInputTemplateArray()
    {
        $input = new CbInput();
        return $input->getCbInputDataArray();
    }

    /**
     * checkSensorSrtatus()
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

        if ($this->getSwitchState() == $this->getSwitchWhen()) {
            if (empty($this->getNotifyStartedAt())) {
                $this->setNotifyStartedAt($date);
                $this->save();
            }
            $started = $this->getNotifyStartedAt('Y-m-d H:i:s');
            $diffSeconds = strtotime($now) - strtotime($started);
            if ($diffSeconds >= $this->getNotifyAfter()) {
                $notification = new CbInputNotification();
                $notification->setSwitchState($this->getSwitchState());
                $notification->setReason(CbInputNotification::REASON_SWITCH_STATE);
                $notification->save();
                $this->setState(self::STATE_NOTIFY);
                $this->addCbInputNotification($notification);
                $this->save();
                return true;
            }
        }
        return false;
    }

    /**
     * hasOpenNotification()
     * @return bool
     */
    public function hasOpenNotification()
    {
        $c = new Criteria();
        $c->addDescendingOrderByColumn('created_at');
        $c->setLimit(1);

        $notification = $this->getCbInputNotifications($c);
        if (!$notification->isEmpty()) {
            if (!$notification[0]->getIsHandled())
                return true;
        }
        return false;
    }

}
