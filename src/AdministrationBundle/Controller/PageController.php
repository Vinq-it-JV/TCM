<?php

namespace AdministrationBundle\Controller;

use CompanyBundle\Model\Company;
use CompanyBundle\Model\CompanyQuery;
use CompanyBundle\Model\Regions;
use CompanyBundle\Model\RegionsQuery;
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

    public function usersAction()
    {
        return $this->render('AdministrationBundle:users:users.html.twig');
    }

    public function editUserAction(Request $request, $userid)
    {
        $user = UserQuery::create()->findOneById($userid);
        if (empty($user))
            return $this->usersAction();

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

    public function companiesAction()
    {
        return $this->render('AdministrationBundle:companies:companies.html.twig');
    }

    public function editCompanyAction(Request $request, $companyid)
    {
        $company = CompanyQuery::create()->findOneById($companyid);
        if (empty($company))
            return $this->companiesAction();

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

    public function regionsAction()
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
            return $this->regionsAction();

        $regionArr = $region->getRegionDataArray();

        return $this->render('AdministrationBundle:regions:edit_region.html.twig', $regionArr);
    }

    public function storesAction()
    {
        return $this->render('AdministrationBundle:stores:stores.html.twig');
    }

    public function editStoreAction(Request $request, $storeid)
    {
        $store = StoreQuery::create()->findOneById($storeid);
        if (empty($store))
            return $this->storesAction();

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

}
