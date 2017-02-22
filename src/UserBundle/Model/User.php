<?php

namespace UserBundle\Model;

use \PropelPDO;
use UserBundle\Model\om\BaseUser;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

class User extends BaseUser implements AdvancedUserInterface
{

    /* (non-PHPdoc)
     * @see \Symfony\Component\Security\Core\User\AdvancedUserInterface::isAccountNonExpired()
     */
    public function isAccountNonExpired()
    {
        return !$this->getIsExpired();
    }

    /* (non-PHPdoc)
     * @see \Symfony\Component\Security\Core\User\AdvancedUserInterface::isAccountNonLocked()
     */
    public function isAccountNonLocked()
    {
        return !$this->getIsLocked();
    }

    /* (non-PHPdoc)
     * @see \Symfony\Component\Security\Core\User\AdvancedUserInterface::isCredentialsNonExpired()
     */
    public function isCredentialsNonExpired()
    {
        return !$this->getIsExpired();
    }

    /* (non-PHPdoc)
     * @see \Symfony\Component\Security\Core\User\AdvancedUserInterface::isEnabled()
     */
    public function isEnabled()
    {
        return $this->getIsEnabled();
    }

    /* (non-PHPdoc)
     * @see \Symfony\Component\Security\Core\User\UserInterface::eraseCredentials()
     */
    public function eraseCredentials()
    {
        // TODO Auto-generated method stub
        return;
    }

    /**
     * setLanguage()
     * @param string $language
     * @return User
     */
    public function setLanguage($language)
    {
        if (is_null($language))
            $language = 'en';
        return parent::setLanguage($language);
    }

    /**
     * generatePassword()
     * @param $encoder
     * @return string
     */
    public function generatePassword($encoder)
    {
        $password = strtoupper(bin2hex(openssl_random_pseudo_bytes(3)));
        $encoded = $encoder->encodePassword($this, $password);
        $this->setPassword($encoded);
        return $password;
    }

    /**
     * generateSecret()
     * @return string
     */
    public function generateSecret()
    {
        $secret = strtoupper(bin2hex(openssl_random_pseudo_bytes(8)));
        $this->setSecret($secret);
        return $secret;
    }

    /**
     * getSalt()
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * getName()
     * @return string
     */
    public function getName()
    {
        return $this->getFirstname() . $this->getMiddlename() . $this->getLastname();
    }

    /**
     * getBirthDate()
     * @param null $format
     * @return \DateTime|mixed
     */
    public function getBirthDate($format = null)
    {
        $birthdate = parent::getBirthDate($format);
        if (empty($birthdate)) {
            $birthdate = new \DateTime();
            $birthdate->setTimestamp(0);
            $birthdate->format($format);
        }
        return $birthdate;
    }

    /**
     * getFirstname()
     * @return string
     */
    public function getFirstname()
    {
        $firstname = parent::getFirstname();
        if (empty($firstname))
            $firstname = '';
        return $firstname;
    }

    /**
     * getMiddlename()
     * @return string
     */
    public function getMiddlename()
    {
        $middlename = parent::getMiddlename();
        if (empty($middlename))
            $middlename = ' ';
        return $middlename;
    }

    /**
     * getLastname()
     * @return string
     */
    public function getLastname()
    {
        $lastname = parent::getLastname();
        if (empty($lastname))
            $lastname = '';
        return $lastname;
    }

    /**
     * getTitle()
     * @return array|int|UserTitle
     */
    public function getTitle()
    {
        $title = parent::getTitle();
        if (is_numeric($title))
            $title = UserTitleQuery::create()->findOneById($title);
        if (empty($title))
            $title = UserTitleQuery::create()->findOneById(UserTitle::MISTER_ID);
        if (!empty($title))
            $title = $title->toArray();
        return $title;
    }

    /**
     * getGender()
     * @return array|int|UserGender
     */
    public function getGender()
    {
        $gender = parent::getGender();
        if (is_numeric($gender))
            $gender = UserGenderQuery::create()->findOneById($gender);
        if (empty($gender))
            $gender = UserGenderQuery::create()->findOneById(UserGender::MALE_ID);
        if (!empty($gender))
            $gender = $gender->toArray();
        return $gender;
    }

    /**
     * getUserDataArray()
     * @return array
     */
    public function getUserDataArray()
    {   $data = [];
        $data['user'] = $this->toArray();
        $data['user']['Name'] = $this->getName();
        $data['user']['Roles'] = $this->getRolesArray();

        if (!$this->getEmails()->isEmpty())
            foreach ($this->getEmails() as $email)
                $data['user']['Emails'][] = $email->getEmailDataArray()['email'];

        if (!$this->getPhones()->isEmpty())
            foreach ($this->getPhones() as $phone)
                $data['user']['Phones'][] = $phone->getPhoneDataArray()['phone'];

        if (!$this->getAddresses()->isEmpty()) {
            foreach ($this->getAddresses() as $address)
                $data['user']['Addresses'][] = $address->getAddressDataArray()['address'];
        }

        $data['user']['LanguageCode'] = $this->getLanguageCode();

        unset($data['user']['Password']);
        unset($data['user']['Secret']);
        unset($data['user']['CreatedAt']);
        unset($data['user']['UpdatedAt']);

        return $data;
    }

    /**
     * getLanguage()
     * @return array|int|Countries
     */
    public function getLanguage()
    {
        $language = parent::getLanguage();
        if (is_numeric($language))
            $language = CountriesQuery::create()->findOneById($language);
        if (empty($language))
            $language = CountriesQuery::create()->findOneById(Countries::COUNTRY_GB);
        if (!empty($language))
            $language = $language->toArray();
        return $language;
    }

    /**
     * getLanguageCode()
     * @return string
     */
    public function getLanguageCode()
    {   $language = parent::getLanguage();
        if (!empty($language))
        {   $country = CountriesQuery::create()
                ->findOneById($language);
            if (!empty($country))
                return $country->getLanguageCode();

        }

        return 'EN';
    }

    /**
     * getUserTemplateArray()
     * @return array
     */
    public function getUserTemplateArray()
    {
        $user = new User();
        return $user->getUserDataArray();
    }

    /**
     * getFullUserTemplateArray()
     * Get full user template Array
     * @return array
     */
    public function getFullUserTemplateArray()
    {
        $data = [];

        $address = new Address();
        $email = new Email();
        $phone = new Phone();
        $role = new Role();

        $data = array_merge($data, $this->getUserTemplateArray());
        $data = array_merge($data, $address->getAddressTemplateArray());
        $data = array_merge($data, $email->getEmailTemplateArray());
        $data = array_merge($data, $phone->getPhoneTemplateArray());
        $data = array_merge($data, $role->getRoleTemplateArray());

        return $data;
    }

    /**
     * hasEmail($emailaddress)
     * @param $emailaddress
     * @return mixed|null|Email
     */
    public function hasEmail($emailaddress)
    {   foreach ($this->getEmails() as $email)
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
    {   foreach ($this->getPhones() as $phone)
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
    {   $address = (object)$address;
        foreach ($this->getAddresses() as $_address)
        {   if ($_address->getHouseNumber() == $address->HouseNumber)
                if (strcmp(strtoupper($_address->getPostalCode()), strtoupper(str_replace(' ', '', $address->PostalCode))) === 0)
                    return $_address;
        }
        return null;
    }

    /**
     * Get roles array
     * @return array
     */
    public function getRolesArray()
    {
        $roleArr = [];
        $roles = RoleQuery::create()
            ->filterByUser($this)
            ->orderById('ASC')
            ->find();

        if($roles->isEmpty())
            $roles = RoleQuery::create()->findById(Role::ROLE_USER_ID);

        foreach ($roles as $role)
            $roleArr[] = $role->getRoleDataArray()['role'];
        return $roleArr;
    }

    /**
     * Get roles array
     * @return array
     */
    public function getRolesNamesArray()
    {
        $roles = RoleQuery::create()
            ->filterByUser($this)
            ->orderById('ASC')
            ->select(array('Name'))
            ->find();

        if($roles->isEmpty())
            return array('ROLE_USER');

        return $roles->toArray();
    }

    /**
     * hasRole($userrole)
     * @param $userrole
     * @return bool
     */
    public function hasRole($userrole)
    {
        $roles = $this->getRolesArray();
        if (!empty($roles))
        {   foreach ($roles as $role)
            {   $role = (object)$role;
                if (strcmp(strtoupper($role->Name), strtoupper($userrole)) === 0)
                    return true;
            }
        }
        return false;
    }

    /**
     * Delete user, also related data
     * @param PropelPDO|null $con
     */
    public function delete(PropelPDO $con = null)
    {
        $this->getAddresses()->delete();
        $this->getEmails()->delete();
        $this->getPhones()->delete();
        parent::delete($con);
    }
}
