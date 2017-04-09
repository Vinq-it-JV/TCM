<?php

namespace StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function dashboardAction()
    {
        return $this->render('CompanyBundle:dashboard:dashboard.html.twig');
    }
}
