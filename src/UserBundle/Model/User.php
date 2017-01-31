<?php

namespace UserBundle\Model;

use \PropelPDO;
use UserBundle\Model\om\BaseUser;
use MoneyBundle\Model\WalletQuery;
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

    public function getName()
    {
        return $this->getFirstname().' '.$this->getLastname();
    }

    public function setLanguage($language)
    {
        if (is_null($language))
            $language = 'en';
        return parent::setLanguage($language);
    }

    public function generatePassword($encoder)
    {
        $password = strtoupper(bin2hex(openssl_random_pseudo_bytes(3)));
        $encoded = $encoder->encodePassword($this, $password);
        $this->setPassword($encoded);
        return $password;
    }

    public function generateSecret()
    {
        $secret = strtoupper(bin2hex(openssl_random_pseudo_bytes(8)));
        $this->setSecret($secret);
        return $secret;
    }

    public function getFirstname()
    {
        $firstname = parent::getFirstname();
        if (is_null($firstname))
            $firstname = '';
        return $firstname;
    }

    public function getLastname()
    {
        $lastname = parent::getLastname();
        if (is_null($lastname))
            $lastname = '';
        return $lastname;
    }

    public function getWalletCredits()
    {
        $credits = 0;

        $wallet = WalletQuery::create()
            ->findOneById($this->getId());

        if ($wallet)
            $credits = $wallet->getCredits();
        return $credits;
    }

    public function getSalt()
    {
        return null;
    }

    /* (non-PHPdoc)
     * @see \Symfony\Component\Security\Core\User\UserInterface::getRoles()
     */
    public function getRoles($criteria = null, PropelPDO $con = null)
    {
        $roles = RoleQuery::create()
            ->filterByUser($this)
            ->select(array('Name'))
            ->find();

        if(!$roles)
        {
            return array('ROLE_USER');
        }
        return $roles->toArray();
    }
}
