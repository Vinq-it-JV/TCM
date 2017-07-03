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

    public function getClosedInputNotificationsAction(Request $request)
    {
        $tableState = json_decode($request->getContent());
        $order = 'id';
        $reverse = false;

        // reverse: false = ASC, true = DESC
        $total = $inputNotifications = CbInputNotificationQuery::create()
            ->filterByIsHandled(true)
            ->count();

        if (!empty((array)$tableState->sort)) {
            if (!empty($tableState->sort->predicate))
            {   switch ($tableState->sort->predicate)
                {   case 'CreatedAt.date':
                        $order = 'created_at';
                        break;
                    case 'HandledBy.Name':
                        $order = 'user.Firstname';
                        break;
                    case 'Sensor.MainStore.Name':
                        $order = 'store.Name';
                        break;
                    case 'Sensor.Name':
                        $order = 'sensor.Name';
                        break;
                }
            }
            if (!empty($tableState->sort->reverse))
                $reverse = $tableState->sort->reverse;
        }

        $inputNotifications = CbInputNotificationQuery::create()
            ->filterByIsHandled(true)
            ->useCbInputQuery('sensor')
                ->useStoreQuery('store')
                ->endUse()
            ->endUse()
            ->useUserQuery('user')
            ->endUse()
            ->orderBy($order, $reverse ? 'DESC' : 'ASC')
            ->offset($tableState->pagination->start)
            ->limit($tableState->pagination->number)
            ->find();

        $tableState->pagination->numberOfPages = ceil($total / $tableState->pagination->number);
        $tableState->pagination->totalItemCount = $total;

        $notificationsArr = $this->getNotificationsDataArray($inputNotifications);

        return JsonResult::create()
            ->setContents(array('notifications' => $notificationsArr, 'tableState' => $tableState))
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    public function getClosedTemperatureNotificationsAction(Request $request)
    {
        $tableState = json_decode($request->getContent());
        $order = 'id';
        $reverse = false;

        // reverse: false = ASC, true = DESC
        $total = $temperatureNotifications = DsTemperatureNotificationQuery::create()
            ->filterByIsHandled(true)
            ->count();

        if (!empty((array)$tableState->sort)) {
            if (!empty($tableState->sort->predicate))
            {   switch ($tableState->sort->predicate)
                {   case 'CreatedAt.date':
                        $order = 'created_at';
                        break;
                    case 'HandledBy.Name':
                        $order = 'user.Firstname';
                        break;
                    case 'Sensor.MainStore.Name':
                        $order = 'store.Name';
                        break;
                    case 'Sensor.Name':
                        $order = 'sensor.Name';
                        break;
                }
            }
            if (!empty($tableState->sort->reverse))
                $reverse = $tableState->sort->reverse;
        }

        $temperatureNotifications = DsTemperatureNotificationQuery::create()
            ->filterByIsHandled(true)
            ->useDsTemperatureSensorQuery('sensor')
                ->useStoreQuery('store')
                ->endUse()
            ->endUse()
            ->useUserQuery('user')
            ->endUse()
            ->orderBy($order, $reverse ? 'DESC' : 'ASC')
            ->offset($tableState->pagination->start)
            ->limit($tableState->pagination->number)
            ->find();

        $tableState->pagination->numberOfPages = ceil($total / $tableState->pagination->number);
        $tableState->pagination->totalItemCount = $total;

        $notificationsArr = $this->getNotificationsDataArray($temperatureNotifications);

        return JsonResult::create()
            ->setContents(array('notifications' => $notificationsArr, 'tableState' => $tableState))
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

    protected function getNotificationsDataArray($notifications)
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
            if (!empty($notification->getHandledBy()))
                $notificationArr['HandledBy'] = $notification->getHandledBy()->getUserDataArray()['user'];

            $dataArr[] = $notificationArr;
        }
        return $dataArr;
    }
}
