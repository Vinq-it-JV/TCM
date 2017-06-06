<?php

namespace NotificationBundle\Controller;

use AppBundle\Response\JsonResult;
use NotificationBundle\Model\CbInputNotificationQuery;
use NotificationBundle\Model\DsTemperatureNotificationQuery;
use StoreBundle\Model\Store;
use StoreBundle\Model\StoreQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;


class DataController extends Controller
{
    public function getOpenNotificationsAction(Request $request)
    {
        $notificationsArr = [];

        $inputNotifications = CbInputNotificationQuery::create()
            ->filterByIsHandled(false)
            ->orderById('DESC')
            ->find();

        $temperatureNotifications = DsTemperatureNotificationQuery::create()
            ->filterByIsHandled(false)
            ->orderById('DESC')
            ->find();

        $notificationsArr['Inputs'] = $this->getNotificationsDataArray($inputNotifications);
        $notificationsArr['Temperatures'] = $this->getNotificationsDataArray($temperatureNotifications);

        return JsonResult::create()
            ->setContents(array('notifications' => $notificationsArr))
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    public function handleInputNotificationAction(Request $request, $notificationid)
    {
        $notification = CbInputNotificationQuery::create()
            ->findOneById($notificationid);

        if (!empty($notification))
        {
            $notification->handleNotification($this->getUser());
            return JsonResult::create()
                ->setErrorcode(JsonResult::SUCCESS)
                ->make();
        }

        return JsonResult::create()
            ->setErrorcode(JsonResult::DANGER)
            ->make();
    }

    public function handleTemperatureNotificationAction(Request $request, $notificationid)
    {
        $notification = DsTemperatureNotificationQuery::create()
            ->findOneById($notificationid);

        if (!empty($notification))
        {
            $notification->handleNotification($this->getUser());
            return JsonResult::create()
                ->setErrorcode(JsonResult::SUCCESS)
                ->make();
        }

        return JsonResult::create()
            ->setErrorcode(JsonResult::DANGER)
            ->make();
    }

    private function getNotificationsDataArray($notifications)
    {
        $dataArr = [];
        foreach ($notifications as $notification)
        {
            $notificationArr = $notification->toArray();
            if (!empty($notification->getSensor())) {
                $notificationArr['Sensor'] = $notification->getSensor()->toArray();
                $store = StoreQuery::create()->findOneById($notificationArr['Sensor']['MainStore']);
                if (!empty($store))
                    $notificationArr['Sensor']['MainStore'] = $store->toArray();
            }

            $dataArr[] = $notificationArr;
        }
        return $dataArr;
    }
}
