<?php

namespace StoreBundle\Model;

use CompanyBundle\Model\CompanyQuery;
use CompanyBundle\Model\PaymentMethodQuery;
use CompanyBundle\Model\RegionsQuery;
use DeviceBundle\Model\CbInput;
use DeviceBundle\Model\DsTemperatureSensor;
use StoreBundle\Model\om\BaseStore;
use \Criteria;
use UserBundle\Model\Address;
use UserBundle\Model\Email;
use UserBundle\Model\Phone;

class Store extends BaseStore
{
    /**
     * getStoreDataArray()
     * @return array
     */
    public function getStoreDataArray()
    {
        $data = [];
        $data['store'] = $this->toArray();
        $data['store']['Name'] = $this->getName();

        $c = new Criteria();
        $c->addDescendingOrderByColumn('email.primary');
        if (!$this->getEmails()->isEmpty())
            foreach ($this->getEmails($c) as $email)
                $data['store']['Emails'][] = $email->getEmailDataArray()['email'];

        $c = new Criteria();
        $c->addDescendingOrderByColumn('phone.primary');
        if (!$this->getPhones()->isEmpty())
            foreach ($this->getPhones($c) as $phone)
                $data['store']['Phones'][] = $phone->getPhoneDataArray()['phone'];

        if (!$this->getAddresses()->isEmpty())
            foreach ($this->getAddresses() as $address)
                $data['store']['Addresses'][] = $address->getAddressDataArray()['address'];

        if (!$this->getOwners()->isEmpty())
            foreach ($this->getOwners() as $owner)
                $data['store']['Owners'][] = $owner->getUserDataArray()['user'];

        if (!$this->getContacts()->isEmpty())
            foreach ($this->getContacts() as $contact)
                $data['store']['Contacts'][] = $contact->getUserDataArray()['user'];

        if (!$this->getInformants()->isEmpty())
            foreach ($this->getInformants() as $informant)
                $data['store']['Informants'][] = $informant->getUserDataArray()['user'];

        $data['store']['Notifications'] = $this->getNotifications();

        unset($data['store']['CreatedAt']);
        unset($data['store']['UpdatedAt']);

        return $data;
    }

    /**
     * Get store list array (for ui-select store / company)
     * @return array
     */
    public function getStoreListArray()
    {
        $data = [];
        $data['store'] = $this->toArray();
        $data['store']['Name'] = $this->getName();
        $data['store']['CompanyName'] = $this->getMainCompany()['Name'];

        unset($data['store']['CreatedAt']);
        unset($data['store']['UpdatedAt']);

        return $data;
    }

    /**
     * getType()
     * @return array|int|StoreType
     */
    public function getType()
    {
        $type = parent::getType();
        if (is_numeric($type))
            $type = StoreTypeQuery::create()->findOneById($type);
        if (empty($type))
            $type = StoreTypeQuery::create()->findOneByName(StoreType::BAR_NAME);
        if (!empty($type))
            $type = $type->toArray();
        return $type;
    }

    /**
     * getMainCompany()
     * @return array|\CompanyBundle\Model\Company|int
     */
    public function getMainCompany()
    {
        $company = parent::getMainCompany();
        if (is_numeric($company))
            $company = CompanyQuery::create()->findOneById($company);
        if (!empty($company))
            $company = $company->getCompanyDataArray(false)['company'];
        return $company;
    }

    /**
     * getRegion()
     * @return array|\CompanyBundle\Model\Regions|int
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
     * @return array|\CompanyBundle\Model\PaymentMethod|int
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
    public function getStoreTemplateArray()
    {
        $company = new Store();
        return $company->getStoreDataArray();
    }

    /**
     * getFullStoreTemplateArray()
     * @return array
     */
    public function getFullStoreTemplateArray()
    {
        $data = [];

        $address = new Address();
        $email = new Email();
        $phone = new Phone();

        $data = array_merge($data, $this->getStoreTemplateArray());
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
     * getDeviceGroupsIdArray()
     * @return array
     */
    public function getDeviceGroupsIdArray()
    {
        $groupsArr = [];
        foreach ($this->getDeviceGroups() as $group)
            $groupsArr[] = $group->getId();
        return $groupsArr;
    }

    /**
     * getInformantsIdArray()
     * @return array|int
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
     * @return mixed|null|\UserBundle\Model\Email
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
     * @return mixed|null|\UserBundle\Model\Phone
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
     * @return mixed|null|\UserBundle\Model\Address
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

    /**
     * @return array
     */
    public function getNotifications()
    {
        $notifications = [];
        $count = 0;

        $c = new Criteria();
        $c->add('cb_input_notification.is_handled', false);

        $inputs = $this->getCbInputs();
        foreach ($inputs as $input) {
            if ($input->getState() == CbInput::STATE_NOTIFY && $input->getIsEnabled()) {
                $notifications['Inputs'] = $input->getCbInputNotifications($c)->toArray();
                $count++;
            }
        }

        $c = new Criteria();
        $c->add('ds_temperature_notification.is_handled', false);

        $sensors = $this->getDsTemperatureSensors();
        foreach ($sensors as $sensor) {
            if ($sensor->getState() == DsTemperatureSensor::STATE_NOTIFY && $sensor->getIsEnabled()) {
                $notifications['Temperatures'] = $sensor->getDsTemperatureNotifications($c)->toArray();
                $count++;
            }
        }

        $notifications['Count'] = $count;

        return $notifications;
    }
}
