<?php

namespace UserBundle\Model;

use UserBundle\Model\om\BaseEmail;

class Email extends BaseEmail
{
    /**
     * getEmailDataArray()
     * @return array
     */
    public function getEmailDataArray()
    {   $data = [];
        $data['email'] = $this->toArray();

        unset($data['email']['CreatedAt']);
        unset($data['email']['UpdatedAt']);

        return $data;
    }

    /**
     * getEmailTemplateArray()
     * @return array
     */
    public function getEmailTemplateArray()
    {
        $email = new Email();
        return $email->getEmailDataArray();
    }
}
