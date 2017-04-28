<?php

namespace CompanyBundle\Controller;

use AppBundle\Response\JsonResult;
use CompanyBundle\Model\Company;
use CompanyBundle\Model\CompanyQuery;
use CompanyBundle\Model\CompanyType;
use CompanyBundle\Model\CompanyTypeQuery;
use CompanyBundle\Model\Owner;
use CompanyBundle\Model\OwnerQuery;
use CompanyBundle\Model\PaymentMethod;
use CompanyBundle\Model\PaymentMethodQuery;
use CompanyBundle\Model\Regions;
use CompanyBundle\Model\RegionsQuery;
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
use UserBundle\Model\UserQuery;

class DataController extends Controller
{
    /**
     * Get list of all companies
     * @param Request $request
     * @return mixed
     */
    public function getCompaniesAction(Request $request)
    {
        $companiesArr = [];

        $companies = CompanyQuery::create()
            ->filterByIsDeleted(false)
            ->orderByName('ASC')
            ->find();

        foreach ($companies as $company) {
            $companiesArr[] = $company->getCompanyDataArray()['company'];
        }

        return JsonResult::create()
            ->setContents(array('companies' => $companiesArr))
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * Get single company information
     * @param Request $request
     * @param $companyid
     * @return mixed
     */
    public function getCompanyAction(Request $request, $companyid)
    {
        $company = CompanyQuery::create()->findOneById($companyid);

        $dataArr = $this->getCompanyData($company);

        return JsonResult::create()
            ->setContents($dataArr)
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * New company
     * @param Request $request
     * @return mixed
     */
    public function newCompanyAction(Request $request)
    {
        $company = new Company();

        $dataArr = $this->getCompanyData($company);

        return JsonResult::create()
            ->setContents($dataArr)
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * Save company
     * @param Request $request
     * @param $companyid
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveCompanyAction(Request $request, $companyid)
    {
        if ($request->isMethod('POST')) {
            $postData = $request->request->all();
            if (!empty($postData)) {
                $this->saveCompanyData((object)$postData);
                return $this->redirectToRoute('administration_companies');
            }
        }
    }

    /**
     * Delete company
     * @param Request $request
     * @param $companyid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteCompanyAction(Request $request, $companyid)
    {
        $company = CompanyQuery::create()->findOneById($companyid);
        if (!empty($company)) {
            $company->setIsDeleted(true)->save();
            return JsonResult::create()
                ->setErrorcode(JsonResult::SUCCESS)
                ->make();
        }
        return JsonResult::create()
            ->setMessage('Company not deleted!')
            ->setErrorcode(JsonResult::DANGER)
            ->make();
    }

    /**
     * Delete company email
     * @param Request $request
     * @param $companyid
     * @param $emailid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteCompanyEmailAction(Request $request, $companyid, $emailid)
    {
        $company = CompanyQuery::create()->findOneById($companyid);
        if (!empty($company)) {
            $email = EmailQuery::create()->findOneById($emailid);
            if (!empty($email)) {
                $company->removeEmail($email);
                $email->delete();
                $company->save();
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
     * Delete company phone
     * @param Request $request
     * @param $companyid
     * @param $phoneid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteCompanyPhoneAction(Request $request, $companyid, $phoneid)
    {
        $company = CompanyQuery::create()->findOneById($companyid);
        if (!empty($company)) {
            $phone = PhoneQuery::create()->findOneById($phoneid);
            if (!empty($phone)) {
                $company->removePhone($phone);
                $phone->delete();
                $company->save();
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
     * Delete company address
     * @param Request $request
     * @param $companyid
     * @param $addressid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteCompanyAddressAction(Request $request, $companyid, $addressid)
    {
        $company = CompanyQuery::create()->findOneById($companyid);
        if (!empty($company)) {
            $address = AddressQuery::create()->findOneById($addressid);
            if (!empty($address)) {
                $company->removeAddress($address);
                $address->delete();
                $company->save();
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
     * getCompanyData($company)
     * @param $company
     * @return array
     */
    private function getCompanyData($company)
    {
        $dataArr = [];
        $listsArr = [];

        $dataArr = array_merge($dataArr, $company->getCompanyDataArray(), ['template' => $company->getFullCompanyTemplateArray()]);

        $listsArr = array_merge($listsArr, Countries::getCountryListArray());
        $listsArr = array_merge($listsArr, AddressType::getAddressTypeListArray());
        $listsArr = array_merge($listsArr, CompanyType::getCompanyTypeListArray());
        $listsArr = array_merge($listsArr, PaymentMethod::getPaymentMethodListArray());
        $listsArr = array_merge($listsArr, Regions::getRegionsListArray());
        $listsArr = array_merge($listsArr, User::getUsersListArray());
        $listsArr = array_merge($listsArr, User::getInformantsListArray());

        $dataArr = array_merge($dataArr, ['lists' => $listsArr]);
        return $dataArr;
    }

    /**
     * saveCompanyData($companyData)
     * @param $companyData
     * @return bool
     */
    private function saveCompanyData($companyData)
    {
        $helper = $this->getHelper();

        if (isset($companyData->Id)) {
            $company = CompanyQuery::create()->findOneById($companyData->Id);
            if (empty($company))
                $company = new Company();
        } else
            return false;
        if (isset($companyData->IsEnabled))
            $company->setIsEnabled($helper->getBooleanValue($companyData->IsEnabled));
        if (isset($companyData->Type)) {
            if (is_numeric($companyData->Type)) {
                $type = CompanyTypeQuery::create()->findOneById($companyData->Type);
                if (!empty($type))
                    $company->setCompanyType($type);
            }
        }
        if (isset($companyData->Name))
            $company->setName($companyData->Name);
        if (isset($companyData->Code))
            $company->setCode($companyData->Code);
        if (isset($companyData->Description))
            $company->setDescription($companyData->Description);
        if (isset($companyData->Website))
            $company->setWebsite($companyData->Website);
        if (isset($companyData->VatNumber))
            $company->setVatNumber($companyData->VatNumber);
        if (isset($companyData->CocNumber))
            $company->setCocNumber($companyData->CocNumber);
        if (isset($companyData->Region)) {
            if (is_numeric($companyData->Region)) {
                $region = RegionsQuery::create()->findOneById($companyData->Region);
                if (!empty($region))
                    $company->setRegion($region->getId());
            }
        }
        if (isset($companyData->PaymentMethod)) {
            if (is_numeric($companyData->PaymentMethod)) {
                $method = PaymentMethodQuery::create()->findOneById($companyData->PaymentMethod);
                if (!empty($method))
                    $company->setPaymentMethod($method->getId());
            }
        }
        if (isset($companyData->BankAccountNumber))
            $company->setBankAccountNumber($companyData->BankAccountNumber);
        if (isset($companyData->emails)) {
            if (!empty($companyData->emails) && is_array($companyData->emails)) {
                foreach ($companyData->emails as $id => $email) {
                    $email = (object)$email;
                    $_email = $company->hasEmail($email->Email);
                    if (empty($_email))
                        $_email = new Email();
                    if ($id == $companyData->EmailPrimary)
                        $_email->setPrimary(true);
                    else
                        $_email->setPrimary(false);
                    $_email->setEmail(strtolower($email->Email))->save();
                    $company->addEmail($_email);
                }
            }
        }
        if (isset($companyData->phones)) {
            if (!empty($companyData->phones) && is_array($companyData->phones)) {
                foreach ($companyData->phones as $id => $phone) {
                    $phone = (object)$phone;
                    $_phone = $company->hasPhone($phone->PhoneNumber);
                    if (empty($_phone))
                        $_phone = new Phone();
                    if ($id == $companyData->PhonePrimary)
                        $_phone->setPrimary(true);
                    else
                        $_phone->setPrimary(false);
                    $_phone->setPhoneNumber($phone->PhoneNumber)->save();
                    $company->addPhone($_phone);
                }
            }
        }
        if (isset($companyData->addresses)) {
            if (!empty($companyData->addresses) && is_array($companyData->addresses)) {
                foreach ($companyData->addresses as $address) {
                    $address = (object)$address;
                    $_address = $company->hasAddress($address);
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
                    $_address->setMapUrl($address->MapUrl);
                    $_address->save();
                    $company->addAddress($_address);
                }
            }
        }
        if (isset($companyData->owners)) {
            if (!empty($companyData->owners) && is_array($companyData->owners)) {
                $owners = $company->getOwnersIdArray();
                $_owners = [];
                foreach ($companyData->owners as $id => $owner)
                    $_owners[] = $id;
                $toAdd = array_diff($_owners, $owners);
                $toRemove = array_diff($owners, $_owners);
                foreach ($toAdd as $owner) {
                    $user = UserQuery::create()->findOneById($owner);
                    if (!empty($user))
                        $company->addOwner($user);
                }
                foreach ($toRemove as $owner) {
                    $user = UserQuery::create()->findOneById($owner);
                    if (!empty($user))
                        $company->removeOwner($user);
                }
            }
        }
        if (isset($companyData->contacts)) {
            if (!empty($companyData->contacts) && is_array($companyData->contacts)) {
                $contacts = $company->getContactsIdArray();
                $_contacts = [];
                foreach ($companyData->contacts as $id => $contact)
                    $_contacts[] = $id;
                $toAdd = array_diff($_contacts, $contacts);
                $toRemove = array_diff($contacts, $_contacts);
                foreach ($toAdd as $contact) {
                    $user = UserQuery::create()->findOneById($contact);
                    if (!empty($user))
                        $company->addContact($user);
                }
                foreach ($toRemove as $contact) {
                    $user = UserQuery::create()->findOneById($contact);
                    if (!empty($user))
                        $company->removeContact($user);
                }
            }
        }
        if (isset($companyData->informants)) {
            if (!empty($companyData->informants) && is_array($companyData->informants)) {
                $informants = $company->getInformantsIdArray();
                $_informants = [];
                foreach ($companyData->informants as $id => $informant)
                    $_informants[] = $id;
                $toAdd = array_diff($_informants, $informants);
                $toRemove = array_diff($informants, $_informants);
                foreach ($toAdd as $informant) {
                    $user = UserQuery::create()->findOneById($informant);
                    if (!empty($user))
                        $company->addInformant($user);
                }
                foreach ($toRemove as $informant) {
                    $user = UserQuery::create()->findOneById($informant);
                    if (!empty($user))
                        $company->removeInformant($user);
                }
            }
        }
        $company->save();
        return true;
    }

    /**
     * Get list of all regions
     * @param Request $request
     * @return mixed
     */
    public function getRegionsAction(Request $request)
    {
        $regionsArr = [];

        $regions = RegionsQuery::create()
            ->find();

        foreach ($regions as $region) {
            $regionsArr[] = $region->getRegionDataArray()['region'];
        }

        return JsonResult::create()
            ->setContents(array('regions' => $regionsArr))
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * Get single region information
     * @param Request $request
     * @param $regionid
     * @return mixed
     */
    public function getRegionAction(Request $request, $regionid)
    {
        $region = RegionsQuery::create()->findOneById($regionid);

        $dataArr = $this->getRegionData($region);

        return JsonResult::create()
            ->setContents($dataArr)
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * New region
     * @param Request $request
     * @return mixed
     */
    public function newRegionAction(Request $request)
    {
        $region = new Regions();

        $dataArr = $this->getRegionData($region);

        return JsonResult::create()
            ->setContents($dataArr)
            ->setErrorcode(JsonResult::SUCCESS)
            ->make();
    }

    /**
     * getRegionData($region)
     * @param $region
     * @return array
     */
    private function getRegionData($region)
    {
        $dataArr = [];
        $listsArr = [];

        $dataArr = array_merge($dataArr, $region->getRegionDataArray(), ['template' => $region->getFullRegionTemplateArray()]);
        $dataArr = array_merge($dataArr, ['lists' => $listsArr]);
        return $dataArr;
    }

    /**
     * Save region
     * @param Request $request
     * @param $regionid
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveRegionAction(Request $request, $regionid)
    {
        if ($request->isMethod('POST')) {
            $postData = $request->request->all();
            if (!empty($postData)) {
                $this->saveRegionData((object)$postData);
                return $this->redirectToRoute('administration_regions');
            }
        }
    }

    /**
     * Delete region
     * @param Request $request
     * @param $regionid
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteRegionAction(Request $request, $regionid)
    {
        $region = RegionsQuery::create()->findOneById($regionid);
        if (!empty($region)) {
            $region->delete();

            return JsonResult::create()
                ->setErrorcode(JsonResult::SUCCESS)
                ->make();
        }
        return JsonResult::create()
            ->setMessage('Region not deleted!')
            ->setErrorcode(JsonResult::DANGER)
            ->make();
    }

    /**
     * saveRegionData($regionData)
     * @param $regionData
     * @return bool
     */
    private function saveRegionData($regionData)
    {
        $helper = $this->getHelper();

        if (isset($regionData->Id)) {
            $region = RegionsQuery::create()->findOneById($regionData->Id);
            if (empty($region))
                $region = new Regions();
        } else
            return false;
        if (isset($regionData->Name))
            $region->setName($regionData->Name);
        if (isset($regionData->Description))
            $region->setDescription($regionData->Description);
        $region->save();

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
