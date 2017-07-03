<?php

namespace DeviceBundle\Controller;

use DeviceBundle\Model\CbInput;
use DeviceBundle\Model\CbInputQuery;
use DeviceBundle\Model\ControllerBox;
use DeviceBundle\Model\ControllerBoxQuery;
use DeviceBundle\Model\DeviceGroup;
use DeviceBundle\Model\DeviceGroupQuery;
use DeviceBundle\Model\DsTemperatureSensor;
use DeviceBundle\Model\DsTemperatureSensorQuery;
use \Criteria;
use StoreBundle\Model\Store;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Response\JsonResult;
use StoreBundle\Model\StoreQuery;

class DataController extends Controller
{

    /**
     * Get list of all stores
     * @param Request $request
     * @return mixed
     */
    public function getStoresAction(Request $request)
    {
        $storesArr = [];

        $stores = StoreQuery::create()
            ->filterByIsDeleted(false)
            ->find();

        foreach ($stores as $store) {
            $storeArr = $store->getStoreDataArray()['store'];
            if (!$store->getDeviceGroups()->isEmpty())
                foreach ($store->getDeviceGroups() as $group)
                    $storeArr['DeviceGroups'][] = $group->getDeviceGroupDataArray();
            $storesArr[] = $storeArr;
        }

        return JsonResult::create()
            ->setContents(array('stores' => $storesArr))
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * Get store
     * @param Request $request
     * @param $storeid
     * @return mixed
     */
    public function getStoreAction(Request $request, $storeid)
    {
        $store = StoreQuery::create()->findOneById($storeid);

        $dataArr = $this->getStoreData($store);

        return JsonResult::create()
            ->setContents($dataArr)
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * Save store
     * @param Request $request
     * @param $storeid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function saveStoreAction(Request $request, $storeid)
    {
        if ($request->isMethod('PUT')) {
            $putData = $request->getContent();
            $storeData = json_decode($putData, true);
            if (!empty($storeData)) {
                $this->saveStoreData((object)$storeData, $storeid);
            }
        }
        return JsonResult::create()
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * Get uninstalled sensors
     * @param Request $request
     * @return mixed
     */
    public function getSensorsAction(Request $request)
    {
        $sensorsArr = [];
        $controllers = ControllerBoxQuery::create()->filterByMainStore(null)->find();

        /* @var $controller ControllerBox */
        foreach ($controllers as $controller) {
            $sensorsArr[] = $controller->getControllerBoxDataArray()['controllerbox'];
            if (!$controller->getCbInputs()->isEmpty()) {
                foreach ($controller->getCbInputs() as $input)
                    if (empty($input->getMainStore()))
                        $sensorsArr[] = $input->getCbInputDataArray()['cbinput'];
            }
            if (!$controller->getDsTemperatureSensors()->isEmpty()) {
                foreach ($controller->getDsTemperatureSensors() as $temperature)
                    if (empty($temperature->getMainStore()))
                        $sensorsArr[] = $temperature->getDsTemperatureSensorDataArray()['dstemperaturesensor'];
            }
        }

        return JsonResult::create()
            ->setContents(array('sensors' => $sensorsArr))
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * Get sensor
     * @param Request $request
     * @param $sensorid
     * @param $typeid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getSensorAction(Request $request, $sensorid, $typeid)
    {
        $dataArr = $this->getSensorData($sensorid, $typeid);

        return JsonResult::create()
            ->setContents($dataArr)
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * Save sensor
     * @param Request $request
     * @param $sensorid
     * @param $typeid
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveSensorAction(Request $request, $sensorid, $typeid)
    {
        if ($request->isMethod('POST')) {
            $postData = $request->request->all();
            $this->saveSensorData((object)$postData, $sensorid, $typeid);
            return $this->redirectToRoute('configuration_installation');
        }

    }

    /**
     * Save sensor data
     * @param $sensorData
     * @param $sensorid
     * @param $typeid
     * @return bool
     */
    protected function saveSensorData($sensorData, $sensorid, $typeid)
    {
        $helper = $this->getHelper();

        $sensor = $this->getSensor($sensorid, $typeid)['sensor'];

        if (isset($sensorData->IsEnabled))
            $sensor->setIsEnabled($helper->getBooleanValue($sensorData->IsEnabled));
        if (isset($sensorData->Name))
            $sensor->setName($sensorData->Name);
        if (isset($sensorData->Description))
            $sensor->setDescription($sensorData->Description);
        if (isset($sensorData->LowLimit))
            $sensor->setLowLimit($sensorData->LowLimit);
        if (isset($sensorData->HighLimit))
            $sensor->setHighLimit($sensorData->HighLimit);
        if (isset($sensorData->TriggerWhen))
            $sensor->setTriggerWhen($sensorData->TriggerWhen);
        if (isset($sensorData->Store)) {
            if (is_numeric($sensorData->Store)) {
                $store = StoreQuery::create()->findOneById($sensorData->Store);
                if (!empty($store)){
                    if ($typeid == ControllerBox::TYPE_ID)
                        $controller = $sensor;
                    else
                        $controller = $sensor->getControllerBox();
                    if (!empty($controller)) {
                        $controller->linkChildSensorsToStore($store);
                    }
                }
            }
        }

        $sensor->Save();
        return true;
    }

    /**
     * Get sensor data
     * @param $sensorid
     * @param $typeid
     * @return array
     */
    protected function getSensorData($sensorid, $typeid)
    {
        $dataArr = [];
        $listsArr = [];

        $sensor = $this->getSensor($sensorid, $typeid);
        $dataArr['sensor'] = $sensor['data'];

        $stores = StoreQuery::create()
            ->filterByIsDeleted(false)
            ->find();

        foreach ($stores as $store)
            $listsArr['stores'][] = $store->getStoreListArray()['store'];

        $dataArr = array_merge($dataArr, ['lists' => $listsArr, 'template' => []]);
        return $dataArr;
    }

    /**
     * Get sensor
     * @param $sensorid
     * @param $typeid
     * @return array
     */
    protected function getSensor($sensorid, $typeid)
    {
        $sensor = null;
        $data = [];
        switch ($typeid) {
            case DsTemperatureSensor::TYPE_ID:
                $sensor = DsTemperatureSensorQuery::create()->findOneById($sensorid);
                $data = $sensor->getDsTemperatureSensorDataArray()['dstemperaturesensor'];
                break;
            case ControllerBox::TYPE_ID:
                $sensor = ControllerBoxQuery::create()->findOneById($sensorid);
                $data = $sensor->getControllerBoxDataArray()['controllerbox'];
                break;
            case CbInput::TYPE_ID:
                $sensor = CbInputQuery::create()->findOneById($sensorid);
                $data = $sensor->getCbInputDataArray()['cbinput'];
                break;
        }
        return array('sensor' => $sensor, 'data' => $data);
    }

    /**
     * Get store data
     * @param $store
     * @return array
     */
    protected function getStoreData($store)
    {
        $dataArr = [];
        $listsArr = [];

        $dataArr = array_merge($dataArr, $store->getStoreDataArray(), ['template' => $this->getFullStoreTemplateArray($store)]);
        $dataArr = array_merge($dataArr, $this->getStoreDeviceGroups($store));

        $listsArr = array_merge($listsArr, CbInput::getNotificationAfterListArray());

        $dataArr = array_merge($dataArr, ['lists' => $listsArr]);
        return $dataArr;
    }

    /**
     * Get full store template array
     * @param $store
     * @return array
     */
    protected function getFullStoreTemplateArray($store)
    {
        $data = [];
        $group = new DeviceGroup();
        $controller = new ControllerBox();
        $sensor = new DsTemperatureSensor();

        $data = array_merge($data, $store->getFullStoreTemplateArray());
        $data = array_merge($data, $group->getDeviceGroupTemplateArray());
        $data = array_merge($data, $controller->getControllerBoxTemplateArray());
        $data = array_merge($data, $sensor->getDsTemperatureSensorTemplateArray());

        return $data;
    }

    /**
     * Get store device groups
     * @param $store
     * @return array
     */
    protected function getStoreDeviceGroups($store)
    {
        $dataArr = [];
        $deviceArr = [];
        /* @var $store Store */
        if (!$store->getDeviceGroups()->isEmpty()) {
            foreach ($store->getDeviceGroups() as $group) {
                $deviceArr[] = $group->getDeviceGroupdataArray()['devicegroup'];
            }
        }

        if (!$store->getDsTemperatureSensors()->isEmpty()) {
            foreach ($store->getDsTemperatureSensors() as $sensor) {
                if (empty($sensor->getGroup()))
                    $deviceArr[] = $sensor->getDsTemperatureSensorDataArray()['dstemperaturesensor'];
            }
        }

        if (!$store->getCbInputs()->isEmpty()) {
            foreach ($store->getCbInputs() as $input) {
                if (empty($input->getDeviceGroup()))
                    $deviceArr[] = $input->getCbInputDataArray()['cbinput'];
            }
        }

        if (!$store->getControllerBoxen()->isEmpty()) {
            foreach ($store->getControllerBoxen() as $controller)
                if (empty($controller->getDeviceGroup()))
                    $deviceArr[] = $controller->getControllerBoxDataArray()['controllerbox'];
        }

        usort($deviceArr, function ($a, $b) {
            $a = (object)$a;
            $b = (object)$b;
            return $a->Position > $b->Position;
        });

        $dataArr['devicegroups'] = $deviceArr;
        return $dataArr;
    }


    /**
     * saveStoreData($storeData)
     * @param $storeData
     * @return bool
     */
    protected function saveStoreData($storeData, $storeid)
    {
        $helper = $this->getHelper();

        $store = StoreQuery::create()->findOneById($storeid);

        if (!empty($store)) {
            $groups = $store->getDeviceGroupsIdArray();
            $_groups = [];

            foreach ($storeData as $index => $device) {
                $device = (object)$device;

                if ($device->TypeId == DeviceGroup::TYPE_ID) {
                    if ($device->Id)
                        $_groups[] = $device->Id;
                    $this->updateDeviceGroup($device, $index);
                    if (!empty($device->devices)) {
                        foreach ($device->devices as $position => $sensor) {
                            $sensor = (object)$sensor;
                            if (empty($sensor->MainStore))
                                $this->updateSensor($sensor, $position);
                            else {
                                $this->updateSensor($sensor, $position, $device);
                            }
                        }
                    }
                } else {
                    $device->Group = 0;
                    $sensor = $device;
                    $this->updateSensor($sensor, $index);
                }
            }
            $toRemove = array_diff($groups, $_groups);

            foreach ($toRemove as $group => $id) {
                $_group = DeviceGroupQuery::create()->findOneById($id);
                if (!empty($_group))
                    $store->removeDeviceGroup($_group);
            }
            $store->save();
        }

        return true;
    }

    /**
     * Update device group (create if new)
     * @param $group
     * @param $index
     */
    protected function updateDeviceGroup(&$group, $index)
    {
        $_group = DeviceGroupQuery::create()->findOneById($group->Id);
        if (empty($_group))
            $_group = new DeviceGroup();
        $_group->setName($group->Name);
        $_group->setDescription($group->Description);
        $_group->setMainStore($group->MainStore);
        $_group->setPosition($index);
        $_group->setIsEnabled($group->IsEnabled);
        $_group->save();
        $group->Id = $_group->getId();
    }

    /**
     * Update sensor data
     * @param $sensor
     * @param int $index
     * @param null $group
     */
    protected function updateSensor($sensor, $index = 0, $group = null)
    {
        $groupDetach = false;
        if (!empty($group))
            $_group = DeviceGroupQuery::create()->findOneById($group->Id);
        else
            $groupDetach = true;
        switch ($sensor->TypeId) {
            case DsTemperatureSensor::TYPE_ID:
                $_sensor = DsTemperatureSensorQuery::create()->findOneById($sensor->Id);
                if (!empty($_sensor)) {
                    $_sensor->setName($sensor->Name);
                    $_sensor->setDescription($sensor->Description);
                    $_sensor->setMainStore($sensor->MainStore);
                    $_sensor->setLowLimit($sensor->LowLimit);
                    $_sensor->setHighLimit($sensor->HighLimit);
                    $_sensor->setIsEnabled($sensor->IsEnabled);
                    $_sensor->setNotifyAfter($sensor->NotifyAfter);
                    $_sensor->setPosition($index);
                    if (!empty($_group))
                        $_sensor->setDeviceGroup($_group);
                    if ($groupDetach)
                        $_sensor->setDeviceGroup(null);
                    $_sensor->save();
                }
                break;
            case ControllerBox::TYPE_ID:
                $_controller = ControllerBoxQuery::create()->findOneById($sensor->Id);
                if (!empty($_controller)) {
                    $_controller->setName($sensor->Name);
                    $_controller->setDescription($sensor->Description);
                    $_controller->setMainStore($sensor->MainStore);
                    $_controller->setIsEnabled($sensor->IsEnabled);
                    $_controller->setPosition($index);
                    if (!empty($_group))
                        $_controller->setDeviceGroup($_group);
                    if ($groupDetach)
                        $_controller->setDeviceGroup(null);
                    $_controller->save();
                }
                break;
            case CbInput::TYPE_ID:
                $_input = CbInputQuery::create()->findOneById($sensor->Id);
                if (!empty($_input)) {
                    $_input->setName($sensor->Name);
                    $_input->setDescription($sensor->Description);
                    $_input->setMainStore($sensor->MainStore);
                    $_input->setIsEnabled($sensor->IsEnabled);
                    $_input->setSwitchWhen($sensor->SwitchWhen);
                    $_input->setNotifyAfter($sensor->NotifyAfter);
                    $_input->setPosition($index);
                    if (!empty($_group))
                        $_input->setDeviceGroup($_group);
                    if ($groupDetach)
                        $_input->setDeviceGroup(null);
                    $_input->save();
                }
                break;
        }
    }

    /**
     * Get class helper
     * @return object
     */
    protected function getHelper()
    {
        $helper = $this->container->get('class_helper');
        return $helper;
    }
}
