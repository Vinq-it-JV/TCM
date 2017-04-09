<?php

namespace AdministrationBundle\Controller;

use AppBundle\Response\JsonResult;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
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
use UserBundle\Model\RoleQuery;
use UserBundle\Model\User;
use UserBundle\Model\UserGender;
use UserBundle\Model\UserQuery;
use UserBundle\Model\UserTitle;
use UserBundle\Model\Role;

class DataController extends Controller
{
    /**
     * Get page controller
     * @return object
     */
    private function getPageController()
    {
        $pc = $this->container->get('page_controller');
        $pc->container = $this->container;
        return $pc;
    }

    /**
     * Get class helper
     * @return object
     */
    private function getHelper()
    {
        $helper = $this->container->get('class_helper');
        return $helper;
    }
}
