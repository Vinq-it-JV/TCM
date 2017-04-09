<?php

namespace CompanyBundle\Model;

use CompanyBundle\Model\om\BaseCompany;
use StoreBundle\Model\StoreQuery;
use UserBundle\Model\Address;
use UserBundle\Model\Email;
use UserBundle\Model\EmailQuery;
use UserBundle\Model\Phone;
use \Criteria;

class Company extends BaseCompany
{
    /**
     * getCompanyDataArray
     * @param bool $getStores is used to prevent recursion, stores gets companies get stores etc....
     * @return array
     */
    public function getCompanyDataArray($getStores = true)
    {
        $data = [];
        $data['company'] = $this->toArray();
        $data['company']['Name'] = $this->getName();

        $c = new Criteria();
        $c->addDescendingOrderByColumn('email.primary');
        if (!$this->getEmails()->isEmpty())
            foreach ($this->getEmails($c) as $email)
                $data['company']['Emails'][] = $email->getEmailDataArray()['email'];

        $c = new Criteria();
        $c->addDescendingOrderByColumn('phone.primary');
        if (!$this->getPhones()->isEmpty())
            foreach ($this->getPhones($c) as $phone)
                $data['company']['Phones'][] = $phone->getPhoneDataArray()['phone'];

        if (!$this->getAddresses()->isEmpty())
            foreach ($this->getAddresses() as $address)
                $data['company']['Addresses'][] = $address->getAddressDataArray()['address'];

        if (!$this->getOwners()->isEmpty())
            foreach ($this->getOwners() as $owner)
                $data['company']['Owners'][] = $owner->getUserDataArray()['user'];

        if (!$this->getContacts()->isEmpty())
            foreach ($this->getContacts() as $contact)
                $data['company']['Contacts'][] = $contact->getUserDataArray()['user'];

        if (!$this->getInformants()->isEmpty())
            foreach ($this->getInformants() as $informant)
                $data['company']['Informants'][] = $informant->getUserDataArray()['user'];

        if (!$this->getStores()->isEmpty() && $getStores)
            foreach ($this->getStores() as $store)
                $data['company']['Stores'][] = $store->getStoreDataArray()['store'];

        unset($data['company']['CreatedAt']);
        unset($data['company']['UpdatedAt']);

        return $data;
    }

    /**
     * getCompanyListArray()
     * @return array
     */
    static public function getCompanyListArray()
    {
        $listArr = [];
        $companies = CompanyQuery::create()
            ->filterByIsDeleted(false)
            ->orderByName('ASC')
            ->find();

        foreach ($companies as $company)
        {
            $companyArr = $company->getCompanyDataArray();
            $listArr['companies'][] = $companyArr['company'];
        }
        return $listArr;
    }

    /**
     * getType()
     * @return array|CompanyType|int
     */
    public function getType()
    {
        $type = parent::getType();
        if (is_numeric($type))
            $type = CompanyTypeQuery::create()->findOneById($type);
        if (empty($type))
            $type = CompanyTypeQuery::create()->findOneByName(CompanyType::BAR_NAME);
        if (!empty($type))
            $type = $type->toArray();
        return $type;
    }

    /**
     * getRegion()
     * @return array|Regions|int
     */
    public function getRegion()
    {
        $region = parent::getRegion();
        if (is_numeric($region))
            $region = RegionsQuery::create()->findOneById($region);
        if (!empty($region))
            $region = $region->toArray();
        return $region;
    }

    /**
     * getPaymentMethod()
     * @return array|PaymentMethod|int
     */
    public function getPaymentMethod()
    {
        $method = parent::getPaymentMethod();
        if (is_numeric($method))
            $method = PaymentMethodQuery::create()->findOneById($method);
        if (!empty($method))
            $method = $method->toArray();
        return $method;
    }

    /**
     * getCompanyTemplateArray()
     * @return array
     */
    public function getCompanyTemplateArray()
    {
        $company = new Company();
        return $company->getCompanyDataArray();
    }

    /**
     * getFullCompanyTemplateArray()
     * Get full company template Array
     * @return array
     */
    public function getFullCompanyTemplateArray()
    {
        $data = [];

        $address = new Address();
        $email = new Email();
        $phone = new Phone();

        $data = array_merge($data, $this->getCompanyTemplateArray());
        $data = array_merge($data, $address->getAddressTemplateArray());
        $data = array_merge($data, $email->getEmailTemplateArray());
        $data = array_merge($data, $phone->getPhoneTemplateArray());

        return $data;
    }

    /**
     * getOwnersIdArray()
     * @return array
     */
    public function getOwnersIdArray()
    {
        $ownerArr = [];
        foreach ($this->getOwners() as $owner)
            $ownerArr[] = $owner->getId();
        return $ownerArr;
    }

    /**
     * getContactsIdArray()
     * @return array
     */
    public function getContactsIdArray()
    {
        $contactArr = [];
        foreach ($this->getContacts() as $contact)
            $contactArr[] = $contact->getId();
        return $contactArr;
    }

    /**
     * getInformantsIdArray()
     * @return array
     */
    public function getInformantsIdArray()
    {
        $informantArr = [];
        foreach ($this->getInformants() as $informant)
            $informantArr[] = $informant->getId();
        return $informantArr;
    }

    /**
     * hasEmail($emailaddress)
     * @param $emailaddress
     * @return mixed|null|Email
     */
    public function hasEmail($emailaddress)
    {
        foreach ($this->getEmails() as $email)
            if (strcmp(strtolower($email->getEmail()), strtolower($emailaddress)) === 0)
                return $email;
        return null;
    }

    /**
     * hasPhone($phonenumber)
     * @param $phonenumber
     * @return mixed|null|Phone
     */
    public function hasPhone($phonenumber)
    {
        foreach ($this->getPhones() as $phone)
            if (strcmp($phone->getPhoneNumber(), $phonenumber) === 0)
                return $phone;
        return null;
    }

    /**
     * hasAddress($address)
     * @param $address
     * @return mixed|null|Address
     */
    public function hasAddress($address)
    {
        $address = (object)$address;
        foreach ($this->getAddresses() as $_address) {
            if ($_address->getHouseNumber() == $address->HouseNumber)
                if (strcmp(strtoupper($_address->getPostalCode()), strtoupper(str_replace(' ', '', $address->PostalCode))) === 0)
                    return $_address;
        }
        return null;
    }

    /**
     * hasOwner($ownerId)
     * @param $ownerId
     * @return mixed|null|\UserBundle\Model\User
     */
    public function hasOwner($ownerId)
    {
        foreach ($this->getOwners() as $_owner) {
            if ($_owner->getId() == $ownerId)
                return $_owner;
        }
        return null;
    }

}
