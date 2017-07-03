<?php

namespace StoreBundle\Controller;

use AppBundle\Response\JsonResult;
use CompanyBundle\Model\Company;
use CompanyBundle\Model\CompanyQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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

        $stores = StoreQuery::create()
            ->filterByIsDeleted(false)
            ->orderByName('ASC')
            ->find();

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
     * New store
     * @param Request $request
     * @return mixed
     */
    public function newStoreAction(Request $request)
    {
        $store = new Store();

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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveStoreAction(Request $request, $storeid)
    {
        if ($request->isMethod('POST')) {
            $postData = $request->request->all();
            if (!empty($postData)) {
                $this->saveStoreData((object)$postData);
                return $this->redirectToRoute('administration_stores');
            }
        }
    }

    /**
     * Delete store
     * @param Request $request
     * @param $storeid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteStoreAction(Request $request, $storeid)
    {
        $store = StoreQuery::create()->findOneById($storeid);
        if (!empty($store)) {
            $store->setIsDeleted(true)->save();
            return JsonResult::create()
                ->setErrorcode(JsonResult::SUCCESS)
                ->make();
        }
        return JsonResult::create()
            ->setMessage('Store not deleted!')
            ->setErrorcode(JsonResult::DANGER)
            ->make();
    }

    /**
     * Delete store email
     * @param Request $request
     * @param $storeid
     * @param $emailid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteStoreEmailAction(Request $request, $storeid, $emailid)
    {
        $store = StoreQuery::create()->findOneById($storeid);
        if (!empty($store)) {
            $email = EmailQuery::create()->findOneById($emailid);
            if (!empty($email)) {
                $store->removeEmail($email);
                $email->delete();
                $store->save();
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
     * Delete store phone
     * @param Request $request
     * @param $storeid
     * @param $phoneid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteStorePhoneAction(Request $request, $storeid, $phoneid)
    {
        $store = StoreQuery::create()->findOneById($storeid);
        if (!empty($store)) {
            $phone = PhoneQuery::create()->findOneById($phoneid);
            if (!empty($phone)) {
                $store->removePhone($phone);
                $phone->delete();
                $store->save();
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
     * Delete store address
     * @param Request $request
     * @param $storeid
     * @param $addressid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteStoreAddressAction(Request $request, $storeid, $addressid)
    {
        $store = StoreQuery::create()->findOneById($storeid);
        if (!empty($store)) {
            $address = AddressQuery::create()->findOneById($addressid);
            if (!empty($address)) {
                $store->removeAddress($address);
                $address->delete();
                $store->save();
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
     * getStoreData($store)
     * @param $store
     * @return array
     */
    protected function getStoreData($store)
    {
        $dataArr = [];
        $listsArr = [];

        $dataArr = array_merge($dataArr, $store->getStoreDataArray(), ['template' => $store->getFullStoreTemplateArray()]);

        $listsArr = array_merge($listsArr, Company::getCompanyListArray());
        $listsArr = array_merge($listsArr, Countries::getCountryListArray());
        $listsArr = array_merge($listsArr, AddressType::getAddressTypeListArray());
        $listsArr = array_merge($listsArr, StoreType::getStoreTypeListArray());
        $listsArr = array_merge($listsArr, PaymentMethod::getPaymentMethodListArray());
        $listsArr = array_merge($listsArr, Regions::getRegionsListArray());
        $listsArr = array_merge($listsArr, User::getUsersListArray());
        $listsArr = array_merge($listsArr, User::getInformantsListArray());

        $dataArr = array_merge($dataArr, ['lists' => $listsArr]);
        return $dataArr;
    }

    /**
     * saveStoreData($storeData)
     * @param $storeData
     * @return bool
     */
    protected function saveStoreData($storeData)
    {
        $helper = $this->getHelper();

        if (isset($storeData->Id)) {
            $store = StoreQuery::create()->findOneById($storeData->Id);
            if (empty($store))
                $store = new Store();
        } else
            return false;
        if (isset($storeData->IsEnabled))
            $store->setIsEnabled($helper->getBooleanValue($storeData->IsEnabled));
        if (isset($storeData->Type)) {
            if (is_numeric($storeData->Type)) {
                $type = StoreTypeQuery::create()->findOneById($storeData->Type);
                if (!empty($type))
                    $store->setStoreType($type);
            }
        }
        if (isset($storeData->Company)) {
            if (is_numeric($storeData->Company)) {
                $company = CompanyQuery::create()->findOneById($storeData->Company);
                if (!empty($company))
                    $store->setMainCompany($company->getId());
            }
        }
        if (isset($storeData->Name))
            $store->setName($storeData->Name);
        if (isset($storeData->Code))
            $store->setCode($storeData->Code);
        if (isset($storeData->Description))
            $store->setDescription($storeData->Description);
        if (isset($storeData->Website))
            $store->setWebsite($storeData->Website);
        if (isset($storeData->VatNumber))
            $store->setVatNumber($storeData->VatNumber);
        if (isset($storeData->CocNumber))
            $store->setCocNumber($storeData->CocNumber);
        if (isset($storeData->Region)) {
            if (is_numeric($storeData->Region)) {
                $region = RegionsQuery::create()->findOneById($storeData->Region);
                if (!empty($region))
                    $store->setRegion($region->getId());
            }
        }
        if (isset($storeData->PaymentMethod)) {
            if (is_numeric($storeData->PaymentMethod)) {
                $method = PaymentMethodQuery::create()->findOneById($storeData->PaymentMethod);
                if (!empty($method))
                    $store->setPaymentMethod($method->getId());
            }
        }
        if (isset($storeData->BankAccountNumber))
            $store->setBankAccountNumber($storeData->BankAccountNumber);
        if (isset($storeData->emails)) {
            if (!empty($storeData->emails) && is_array($storeData->emails)) {
                foreach ($storeData->emails as $id => $email) {
                    $email = (object)$email;
                    $_email = $store->hasEmail($email->Email);
                    if (empty($_email))
                        $_email = new Email();
                    if ($id == $storeData->EmailPrimary)
                        $_email->setPrimary(true);
                    else
                        $_email->setPrimary(false);
                    $_email->setEmail(strtolower($email->Email))->save();
                    $store->addEmail($_email);
                }
            }
        }
        if (isset($storeData->phones)) {
            if (!empty($storeData->phones) && is_array($storeData->phones)) {
                foreach ($storeData->phones as $id => $phone) {
                    $phone = (object)$phone;
                    $_phone = $store->hasPhone($phone->PhoneNumber);
                    if (empty($_phone))
                        $_phone = new Phone();
                    if ($id == $storeData->PhonePrimary)
                        $_phone->setPrimary(true);
                    else
                        $_phone->setPrimary(false);
                    $_phone->setPhoneNumber($phone->PhoneNumber)->save();
                    $store->addPhone($_phone);
                }
            }
        }
        if (isset($storeData->addresses)) {
            if (!empty($storeData->addresses) && is_array($storeData->addresses)) {
                foreach ($storeData->addresses as $address) {
                    $address = (object)$address;
                    $_address = $store->hasAddress($address);
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
                    $store->addAddress($_address);
                }
            }
        }
        if (isset($storeData->owners)) {
            if (!empty($storeData->owners) && is_array($storeData->owners)) {
                $owners = $store->getOwnersIdArray();
                $_owners = [];
                foreach ($storeData->owners as $id => $owner)
                    $_owners[] = $id;
                $toAdd = array_diff($_owners, $owners);
                $toRemove = array_diff($owners, $_owners);
                foreach ($toAdd as $owner) {
                    $user = UserQuery::create()->findOneById($owner);
                    if (!empty($user))
                        $store->addOwner($user);
                }
                foreach ($toRemove as $owner) {
                    $user = UserQuery::create()->findOneById($owner);
                    if (!empty($user))
                        $store->removeOwner($user);
                }
            }
        }
        if (isset($storeData->contacts)) {
            if (!empty($storeData->contacts) && is_array($storeData->contacts)) {
                $contacts = $store->getContactsIdArray();
                $_contacts = [];
                foreach ($storeData->contacts as $id => $contact)
                    $_contacts[] = $id;
                $toAdd = array_diff($_contacts, $contacts);
                $toRemove = array_diff($contacts, $_contacts);
                foreach ($toAdd as $contact) {
                    $user = UserQuery::create()->findOneById($contact);
                    if (!empty($user))
                        $store->addContact($user);
                }
                foreach ($toRemove as $contact) {
                    $user = UserQuery::create()->findOneById($contact);
                    if (!empty($user))
                        $store->removeContact($user);
                }
            }
        }
        if (isset($storeData->informants)) {
            if (!empty($storeData->informants) && is_array($storeData->informants)) {
                $informants = $store->getInformantsIdArray();
                $_informants = [];
                foreach ($storeData->informants as $id => $informant)
                    $_informants[] = $id;
                $toAdd = array_diff($_informants, $informants);
                $toRemove = array_diff($informants, $_informants);
                foreach ($toAdd as $informant) {
                    $user = UserQuery::create()->findOneById($informant);
                    if (!empty($user))
                        $store->addInformant($user);
                }
                foreach ($toRemove as $informant) {
                    $user = UserQuery::create()->findOneById($informant);
                    if (!empty($user))
                        $store->removeInformant($user);
                }
            }
        }
        $store->save();
        return true;
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
