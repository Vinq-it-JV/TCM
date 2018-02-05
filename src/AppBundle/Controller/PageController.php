<?php

namespace AppBundle\Controller;

use StoreBundle\Model\StoreQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
    public function splashAction(Request $request)
    {
        return $this->render('AppBundle:splash:splash.html.twig', [
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..')
        ]);
    }

    public function dashboardAction(Request $request)
    {
        return $this->render('AppBundle:dashboard:dashboard.html.twig', [
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..'),
        ]);
    }

    public function showStoreAction(Request $request, $storeid)
    {
        $store = StoreQuery::create()->findOneById($storeid);
        if (empty($store))
            return $this->storesAction($request);

        $storeArr = $store->getStoreDataArray();

        return $this->render('AppBundle:store:store.html.twig', $storeArr);
    }

    public function storesAction(Request $request)
    {
        return $this->render('AppBundle:stores:stores.html.twig');
    }

    public function storeMaintenanceAction(Request $request, $storeid)
    {
        $store = StoreQuery::create()->findOneById($storeid);
        $storeArr = $store->getStoreDataArray();
        $storeArr['collectionType'] = 'maintenance';

        return $this->render('AppBundle:store:collections.html.twig', $storeArr);
    }

    public function storeInventoryAction(Request $request, $storeid)
    {
        $store = StoreQuery::create()->findOneById($storeid);
        $storeArr = $store->getStoreDataArray();
        $storeArr['collectionType'] = 'inventory';

        return $this->render('AppBundle:store:collections.html.twig', $storeArr);
    }

    public function storeBeerTechAction(Request $request, $storeid)
    {
        $store = StoreQuery::create()->findOneById($storeid);
        $storeArr = $store->getStoreDataArray();
        $storeArr['collectionType'] = 'beertech';

        return $this->render('AppBundle:store:collections.html.twig', $storeArr);
    }

}
