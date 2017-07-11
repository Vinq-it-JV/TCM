<?php

namespace AdministrationBundle\Controller;

use CollectionBundle\Model\CollectionQuery;
use CollectionBundle\Model\CollectionType;
use CollectionBundle\Model\CollectionTypeQuery;
use CompanyBundle\Model\Company;
use CompanyBundle\Model\CompanyQuery;
use CompanyBundle\Model\Regions;
use CompanyBundle\Model\RegionsQuery;
use DeviceBundle\Model\CbInput;
use DeviceBundle\Model\CbInputQuery;
use DeviceBundle\Model\ControllerBox;
use DeviceBundle\Model\ControllerBoxQuery;
use DeviceBundle\Model\DsTemperatureSensor;
use DeviceBundle\Model\DsTemperatureSensorQuery;
use StoreBundle\Model\Store;
use StoreBundle\Model\StoreQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Model\User;
use UserBundle\Model\UserQuery;

class PageController extends Controller
{
    public function dashboardAction()
    {
        return $this->render('AdministrationBundle:dashboard:dashboard.html.twig');
    }

    public function usersAction(Request $request)
    {
        return $this->render('AdministrationBundle:users:users.html.twig');
    }

    public function editUserAction(Request $request, $userid)
    {
        $user = UserQuery::create()->findOneById($userid);
        if (empty($user))
            return $this->usersAction($request);

        $userArr = $user->getUserDataArray();

        return $this->render('AdministrationBundle:users:edit_user.html.twig', $userArr);
    }

    public function addUserAction(Request $request)
    {

        $user = new User();
        $user->setId(0);

        $userArr = $user->getUserDataArray();

        return $this->render('AdministrationBundle:users:add_user.html.twig', $userArr);
    }

    public function companiesAction(Request $request)
    {
        return $this->render('AdministrationBundle:companies:companies.html.twig');
    }

    public function editCompanyAction(Request $request, $companyid)
    {
        $company = CompanyQuery::create()->findOneById($companyid);
        if (empty($company))
            return $this->companiesAction($request);

        $companyArr = $company->getCompanyDataArray();

        return $this->render('AdministrationBundle:companies:edit_company.html.twig', $companyArr);
    }

    public function addCompanyAction(Request $request)
    {
        $company = new Company();
        $company->setId(0);

        $companyArr = $company->getCompanyDataArray();

        return $this->render('AdministrationBundle:companies:add_company.html.twig', $companyArr);
    }

    public function regionsAction(Request $request)
    {
        return $this->render('AdministrationBundle:regions:regions.html.twig');
    }

    public function addRegionAction(Request $request)
    {
        $region = new Regions();
        $region->setId(0);

        $regionArr = $region->getRegionDataArray();

        return $this->render('AdministrationBundle:regions:add_region.html.twig', $regionArr);
    }

    public function editRegionAction(Request $request, $regionid)
    {
        $region = RegionsQuery::create()->findOneById($regionid);
        if (empty($region))
            return $this->regionsAction($request);

        $regionArr = $region->getRegionDataArray();

        return $this->render('AdministrationBundle:regions:edit_region.html.twig', $regionArr);
    }

    public function storesAction(Request $request)
    {
        return $this->render('AdministrationBundle:stores:stores.html.twig');
    }

    public function editStoreAction(Request $request, $storeid)
    {
        $store = StoreQuery::create()->findOneById($storeid);
        if (empty($store))
            return $this->storesAction($request);

        $storeArr = $store->getStoreDataArray();

        return $this->render('AdministrationBundle:stores:edit_store.html.twig', $storeArr);
    }

    public function addStoreAction(Request $request)
    {
        $store = new Store();
        $store->setId(0);

        $storeArr = $store->getStoreDataArray();

        return $this->render('AdministrationBundle:stores:add_store.html.twig', $storeArr);
    }

    public function configurationStoresAction(Request $request)
    {
        return $this->render('AdministrationBundle:configuration:stores.html.twig');
    }

    public function editConfigurationStoreAction(Request $request, $storeid)
    {
        $store = StoreQuery::create()->findOneById($storeid);
        if (empty($store))
            return $this->storesAction($request);

        $storeArr = $store->getStoreDataArray();
        if (!$store->getDeviceGroups()->isEmpty())
            foreach ($store->getDeviceGroups() as $group)
                $storeArr['DeviceGroups'][] = $group->getDeviceGroupDataArray();

        return $this->render('AdministrationBundle:configuration:edit_store.html.twig', $storeArr);
    }

    public function installationSensorsAction(Request $request)
    {
        return $this->render('AdministrationBundle:installation:sensors.html.twig');
    }

    public function packetLogAction(Request $request)
    {
        return $this->render('AdministrationBundle:packetlog:log.html.twig');
    }

    public function editSensorAction(Request $request, $sensorid, $typeid)
    {
        $sensorArr = [];

        switch ($typeid) {
            case DsTemperatureSensor::TYPE_ID:
                $sensor = DsTemperatureSensorQuery::create()->findOneById($sensorid);
                $sensorArr['sensor'] = $sensor->getDsTemperatureSensorDataArray()['dstemperaturesensor'];
                break;
            case ControllerBox::TYPE_ID:
                $controller = ControllerBoxQuery::create()->findOneById($sensorid);
                $sensorArr['sensor'] = $controller->getControllerBoxDataArray()['controllerbox'];
                break;
            case CbInput::TYPE_ID:
                $input = CbInputQuery::create()->findOneById($sensorid);
                $sensorArr['sensor'] = $input->getCbInputDataArray()['cbinput'];
                break;
        }

        if (empty($sensorArr))
            return $this->installationSensorsAction($request);

        return $this->render('AdministrationBundle:installation:edit_sensor.html.twig', $sensorArr);
    }

    public function maintenancePeriodicallyStoresAction(Request $request)
    {
        return $this->render('AdministrationBundle:maintenance:periodically/stores.html.twig');
    }

    public function maintenanceGeneralStoresAction(Request $request)
    {
        return $this->render('AdministrationBundle:maintenance:general/stores.html.twig');
    }

    public function maintenanceStoreAction(Request $request, $storeid)
    {
        $store = StoreQuery::create()->findOneById($storeid);
        $storeArr = $store->getStoreDataArray();
        $storeArr['collectionType'] = 'administration_maintenance';

        return $this->render('AdministrationBundle:maintenance:periodically/store.html.twig', $storeArr);
    }

    public function inventoryStoresAction(Request $request)
    {
        return $this->render('AdministrationBundle:inventory:stores.html.twig');
    }

    public function inventoryStoreAction(Request $request, $storeid)
    {
        $store = StoreQuery::create()->findOneById($storeid);
        $storeArr = $store->getStoreDataArray();
        $storeArr['collectionType'] = 'administration_inventory';

        return $this->render('AdministrationBundle:inventory:store.html.twig', $storeArr);
    }

    public function openNotificationsAction(Request $request)
    {
        return $this->render('AdministrationBundle:notifications:open_notifications.html.twig');
    }

    public function closedNotificationsAction(Request $request)
    {
        return $this->render('AdministrationBundle:notifications:closed_notifications.html.twig');
    }
}
