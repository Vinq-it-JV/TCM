<?php

namespace AppBundle\Controller;

use AppBundle\Response\JsonResult;
use CollectionBundle\Model\Collection;
use CollectionBundle\Model\CollectionQuery;
use CollectionBundle\Model\CollectionType;
use CollectionBundle\Model\CollectionTypeQuery;
use CompanyBundle\Model\Company;
use CompanyBundle\Model\CompanyQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use StoreBundle\Model\Store;
use StoreBundle\Model\StoreQuery;
use StoreBundle\Model\StoreType;
use StoreBundle\Model\StoreTypeQuery;
use CompanyBundle\Model\PaymentMethod;
use CompanyBundle\Model\PaymentMethodQuery;
use CompanyBundle\Model\Regions;
use CompanyBundle\Model\RegionsQuery;
use UserBundle\Model\Address;
use UserBundle\Model\AddressQuery;
use UserBundle\Model\AddressType;
use UserBundle\Model\AddressTypeQuery;
use UserBundle\Model\Countries;
use UserBundle\Model\CountriesQuery;
use UserBundle\Model\Email;
use UserBundle\Model\EmailQuery;
use UserBundle\Model\Phone;
use UserBundle\Model\PhoneQuery;
use UserBundle\Model\User;
use UserBundle\Model\UserQuery;

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

        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $stores = StoreQuery::create()
                ->filterByIsDeleted(false)
                ->filterByIsEnabled(true)
                ->orderByName('ASC')
                ->find();
        }
        else {
            $stores = StoreQuery::create()
                ->filterByIsDeleted(false)
                ->filterByIsEnabled(true)
                ->filterByOwner($this->getUser())
                ->orderByName('ASC')
                ->find();
        }

        foreach ($stores as $store) {
            $storesArr[] = $store->getStoreDataArray()['store'];
        }

        return JsonResult::create()
            ->setContents(array('stores' => $storesArr))
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * Get single store information
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
     * Update sensor information
     * @param Request $request
     * @param $storeid
     * @return mixed
     */
    public function updateSensorsAction(Request $request, $storeid)
    {
        $dataArr = [];

        $store = StoreQuery::create()->findOneById($storeid);
        if (!empty($store))
            $dataArr = $this->getStoreDeviceGroups($store);

        return JsonResult::create()
            ->setContents($dataArr)
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * getStoreData($store)
     * @param $store
     * @return array
     */
    protected function getStoreData($store)
    {
        $dataArr = [];
        $listsArr = [];

        $dataArr = array_merge($dataArr, $store->getStoreDataArray(), ['template' => $store->getFullStoreTemplateArray()]);
        $dataArr = array_merge($dataArr, $this->getStoreDeviceGroups($store));

        $dataArr = array_merge($dataArr, ['lists' => $listsArr]);
        return $dataArr;
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
     * Get store maintenance log
     * @param Request $request
     * @param $storeid
     */
    public function getStoreMaintenanceAction(Request $request, $storeid)
    {
        $dataArr = [];
        $maintenanceLogs = [];
        $date = new \DateTime();

        $type = CollectionTypeQuery::create()->findOneById(CollectionType::TYPE_MAINTENANCE_ID);

        $collections = CollectionQuery::create()
            ->filterByCollectionType($type)
            ->filterByCollectionStore($storeid)
            ->filterByIsPublished(true)
            ->filterByIsDeleted(false)
            ->orderByDate('DESC')
            ->find();

        foreach ($collections as $collection)
            $maintenanceLogs[] = $collection->getCollectionDataArray()['collection'];

        $collection = new Collection();
        $collection->setId(0);
        $collection->setType(CollectionType::TYPE_MAINTENANCE_ID);
        $collection->setDate($date);
        $collection->setCollectionStore($storeid);

        $dataArr['collections'] = $maintenanceLogs;
        $dataArr['template'] = $collection->getFullCollectionTemplateArray()['collection'];

        return JsonResult::create()
            ->setContents($dataArr)
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * Get store inventory log
     * @param Request $request
     * @param $storeid
     */
    public function getStoreInventoryAction(Request $request, $storeid)
    {
        $dataArr = [];
        $inventoryLogs = [];
        $date = new \DateTime();

        $type = CollectionTypeQuery::create()->findOneById(CollectionType::TYPE_INVENTORY_ID);

        $collections = CollectionQuery::create()
            ->filterByCollectionType($type)
            ->filterByCollectionStore($storeid)
            ->filterByIsPublished(true)
            ->filterByIsDeleted(false)
            ->orderByDate('DESC')
            ->find();

        foreach ($collections as $collection)
            $inventoryLogs[] = $collection->getCollectionDataArray()['collection'];

        $collection = new Collection();
        $collection->setId(0);
        $collection->setType(CollectionType::TYPE_INVENTORY_ID);
        $collection->setDate($date);
        $collection->setCollectionStore($storeid);

        $dataArr['collections'] = $inventoryLogs;
        $dataArr['template'] = $collection->getFullCollectionTemplateArray()['collection'];

        return JsonResult::create()
            ->setContents($dataArr)
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * Get page controller
     * @return object
     */
    protected function getPageController()
    {
        $pc = $this->container->get('page_controller');
        $pc->container = $this->container;
        return $pc;
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
