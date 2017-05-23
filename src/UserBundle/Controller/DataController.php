<?php

namespace UserBundle\Controller;

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
     * Get list of all users
     * @param Request $request
     * @return mixed
     */
    public function getUsersAction(Request $request)
    {
        $usersArr = [];

        $users = UserQuery::create()->find();
        foreach ($users as $user) {
            $usersArr[] = $user->getUserDataArray()['user'];
        }

        return JsonResult::create()
            ->setContents(array('users' => $usersArr))
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * Get single user information
     * @param Request $request
     * @param $userid
     * @return mixed
     */
    public function getUserAction(Request $request, $userid)
    {
        $user = UserQuery::create()->findOneById($userid);

        $dataArr = $this->getUserData($user);

        return JsonResult::create()
            ->setContents($dataArr)
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * New user
     * @param Request $request
     * @return mixed
     */
    public function newUserAction(Request $request)
    {
        $user = new User();

        $dataArr = $this->getUserData($user);

        return JsonResult::create()
            ->setContents($dataArr)
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * Save user
     * @param Request $request
     * @param $userid
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveUserAction(Request $request, $userid)
    {
        if ($request->isMethod('POST')) {
            $postData = $request->request->all();
            if (!empty($postData)) {
                $this->saveUserData((object)$postData);
                return $this->redirectToRoute('administration_users');
            }
        }
    }

    /**
     * Delete user
     * @param Request $request
     * @param $userid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteUserAction(Request $request, $userid)
    {
        $user = UserQuery::create()->findOneById($userid);
        if (!empty($user)) {
            $user->delete();
            return JsonResult::create()
                ->setErrorcode(JsonResult::SUCCESS)
                ->make();
        }
        return JsonResult::create()
            ->setMessage('User not deleted!')
            ->setErrorcode(JsonResult::DANGER)
            ->make();
    }

    /**
     * Delete user email
     * @param Request $request
     * @param $userid
     * @param $emailid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteUserEmailAction(Request $request, $userid, $emailid)
    {
        $user = UserQuery::create()->findOneById($userid);
        if (!empty($user)) {
            $email = EmailQuery::create()->findOneById($emailid);
            if (!empty($email)) {
                $user->removeEmail($email);
                $email->delete();
                $user->save();
                return JsonResult::create()
                    ->setErrorcode(JsonResult::SUCCESS)
                    ->make();
            }
        }
        return JsonResult::create()
            ->setMessage('Email not deleted!')
            ->setErrorcode(JsonResult::DANGER)
            ->make();
    }

    /**
     * Delete user phone
     * @param Request $request
     * @param $userid
     * @param $phoneid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteUserPhoneAction(Request $request, $userid, $phoneid)
    {
        $user = UserQuery::create()->findOneById($userid);
        if (!empty($user)) {
            $phone = PhoneQuery::create()->findOneById($phoneid);
            if (!empty($phone)) {
                $user->removePhone($phone);
                $phone->delete();
                $user->save();
                return JsonResult::create()
                    ->setErrorcode(JsonResult::SUCCESS)
                    ->make();
            }
        }
        return JsonResult::create()
            ->setMessage('Phone not deleted!')
            ->setErrorcode(JsonResult::DANGER)
            ->make();
    }

    /**
     * Delete user role
     * @param Request $request
     * @param $userid
     * @param $roleid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteUserRoleAction(Request $request, $userid, $roleid)
    {
        $user = UserQuery::create()->findOneById($userid);
        if (!empty($user)) {
            $role = RoleQuery::create()->findOneById($roleid);
            if (!empty($role)) {
                $user->removeRole($role);
                $user->save();
                return JsonResult::create()
                    ->setErrorcode(JsonResult::SUCCESS)
                    ->make();
            }
        }
        return JsonResult::create()
            ->setMessage('Role not removed!')
            ->setErrorcode(JsonResult::DANGER)
            ->make();
    }

    /**
     * Delete user address
     * @param Request $request
     * @param $userid
     * @param $addressid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteUserAddressAction(Request $request, $userid, $addressid)
    {
        $user = UserQuery::create()->findOneById($userid);
        if (!empty($user)) {
            $address = AddressQuery::create()->findOneById($addressid);
            if (!empty($address)) {
                $user->removeAddress($address);
                $address->delete();
                $user->save();
                return JsonResult::create()
                    ->setErrorcode(JsonResult::SUCCESS)
                    ->make();
            }
        }
        return JsonResult::create()
            ->setMessage('Address not deleted!')
            ->setErrorcode(JsonResult::DANGER)
            ->make();
    }

    /**
     * getUserData($user)
     * @param $user
     * @return array
     */
    private function getUserData($user)
    {
        $dataArr = [];
        $listsArr = [];

        $dataArr = array_merge($dataArr, $user->getUserDataArray(), ['template' => $user->getFullUserTemplateArray()]);

        $listsArr = array_merge($listsArr, UserGender::getGenderListArray());
        $listsArr = array_merge($listsArr, UserTitle::getTitleListArray());
        $listsArr = array_merge($listsArr, Countries::getCountryListArray());
        $listsArr = array_merge($listsArr, AddressType::getAddressTypeListArray());
        $listsArr = array_merge($listsArr, Role::getRoleListArray());

        $dataArr = array_merge($dataArr, ['lists' => $listsArr]);
        return $dataArr;
    }

    /**
     * saveUserData($userData)
     * @param $userData
     * @return bool
     */
    private function saveUserData($userData)
    {
        $helper = $this->getHelper();
        $encoder = $this->container->get('security.password_encoder');

        if (isset($userData->Id)) {
            $user = UserQuery::create()->findOneById($userData->Id);
            if (empty($user))
                $user = new User();
        } else
            return false;
        if (isset($userData->Username))
            $user->setUsername(strtolower($userData->Username));
        if (isset($userData->Firstname))
            $user->setFirstname($userData->Firstname);
        if (isset($userData->Middlename))
            $user->setMiddlename($userData->Middlename);
        if (isset($userData->Lastname))
            $user->setLastname($userData->Lastname);
        if (isset($userData->BirthDate))
            if ($helper->isValidDate($userData->BirthDate))
                $user->setBirthDate($userData->BirthDate);
        if (isset($userData->Gender))
            if (is_numeric($userData->Gender))
                $user->setGender($userData->Gender);
        if (isset($userData->Title))
            if (is_numeric($userData->Title))
                $user->setTitle($userData->Title);
        if (isset($userData->Language))
            if (is_numeric($userData->Language))
                $user->setLanguage($userData->Language);
        if (isset($userData->emails)) {
            if (!empty($userData->emails) && is_array($userData->emails)) {
                foreach ($userData->emails as $id => $email) {
                    $email = (object)$email;
                    $_email = $user->hasEmail($email->Email);
                    if (empty($_email))
                        $_email = new Email();
                    if ($id == $userData->EmailPrimary)
                        $_email->setPrimary(true);
                    else
                        $_email->setPrimary(false);
                    $_email->setEmail(strtolower($email->Email))->save();
                    $user->addEmail($_email);
                }
            }
        }
        if (isset($userData->phones)) {
            if (!empty($userData->phones) && is_array($userData->phones)) {
                foreach ($userData->phones as $id => $phone) {
                    $phone = (object)$phone;
                    $_phone = $user->hasPhone($phone->PhoneNumber);
                    if (empty($_phone))
                        $_phone = new Phone();
                    if ($id == $userData->PhonePrimary)
                        $_phone->setPrimary(true);
                    else
                        $_phone->setPrimary(false);
                    $_phone->setPhoneNumber($phone->PhoneNumber)->save();
                    $user->addPhone($_phone);
                }
            }
        }
        if (isset($userData->addresses)) {
            if (!empty($userData->addresses) && is_array($userData->addresses)) {
                foreach ($userData->addresses as $address) {
                    $address = (object)$address;
                    $_address = $user->hasAddress($address);
                    if (empty($_address))
                        $_address = new Address();
                    $type = AddressTypeQuery::create()->findOneById($address->Type);
                    if (!empty($type))
                        $_address->setType($type->getId());
                    $_address->setStreetName(ucwords($address->StreetName));
                    $_address->setHouseNumber($address->HouseNumber);
                    $_address->setPostalCode(strtoupper(str_replace(' ', '', $address->PostalCode)));
                    $_address->setCity(ucwords($address->City));
                    $country = CountriesQuery::create()->findOneByCountryCode($address->Country);
                    if (!empty($country))
                        $_address->setCountry($country->getId());
                    $_address->setMapCoordinates($address->MapCoordinates);
                    $_address->save();
                    $user->addAddress($_address);
                }
            }
        }
        if (isset($userData->roles)) {
            if (!empty($userData->roles) && is_array($userData->roles)) {
                $roles = $user->getRolesNamesArray();
                $toAdd = array_diff($userData->roles, $roles);
                $toRemove = array_diff($roles, $userData->roles);
                foreach ($toAdd as $role) {
                    $_role = RoleQuery::create()->findOneByName($role);
                    if (!empty($_role))
                        $user->addRole($_role);
                }
                foreach ($toRemove as $role) {
                    $_role = RoleQuery::create()->findOneByName($role);
                    if (!empty($_role))
                        $user->removeRole($_role);
                }
                if (!$user->hasRole('ROLE_USER')) {
                    $_role = RoleQuery::create()->findOneById(Role::ROLE_USER_ID);
                    if (!empty($_role))
                        $user->addRole($_role);
                }
            }
        }

        $user->save();
        if (empty($user->getPassword())) {
            $user->setLogins($this->container->getParameter('logins'));
            $password = $user->generatePassword($encoder);
            $helper->sendCredentialsEmail($user, $password);
            $user->save();
        }

        return true;
    }

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
