<?php

namespace DeviceBundle\Model;

use DeviceBundle\Model\om\BaseCbInput;
use \Criteria;
use NotificationBundle\Model\CbInputNotification;
use StoreBundle\Model\StoreQuery;

class CbInput extends BaseCbInput
{
    const TYPE_ID = 4;

    const STATE_ACTIVE = 0;
    const STATE_INACTIVE = 1;
    const STATE_NOTIFY = 2;

    const INACTIVITY_TIME = 900;    // 15 minutes

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

        $store = StoreQuery::create()->findOneById($this->getMainStore());
        if ($store->getIsMaintenance())
            return $state;

        if ($this->checkSensorInactive())
            $state = self::STATE_INACTIVE;

        // This check prevents excessive database checks on hasOpenNotification
        if ($this->getState() == self::STATE_NOTIFY)
            return self::STATE_NOTIFY;

        if ($this->checkSensorNotify())
            if ($this->hasOpenNotification())
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

        if ($this->getNotifyAfter() == -1)
            return false;

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
