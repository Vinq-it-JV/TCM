<?php

namespace CollectionBundle\Model;

use CollectionBundle\Model\om\BaseCollection;
use UserBundle\Model\UserQuery;
use \Criteria;

class Collection extends BaseCollection
{
    /**
     * getCollectionDataArray()
     * @return array
     */
    public function getCollectionDataArray()
    {
        $data = [];
        $data['collection'] = $this->toArray();

        $c = new Criteria();
        $c->addAscendingOrderByColumn('attachment.position');

        $data['collection']['Attachments'] = [];
        if (!$this->getAttachments()->isEmpty())
            foreach ($this->getAttachments($c) as $attachment)
                $data['collection']['Attachments'][] = $attachment->getAttachmentDataArray()['attachment'];

        return $data;
    }

    /**
     * getCollectionTemplateArray()
     * @return array
     */
    public function getCollectionTemplateArray()
    {
        return $this->getCollectionDataArray();
    }

    /**
     * Get full collection template array
     * @return array
     */
    public function getFullCollectionTemplateArray()
    {
        $data = [];
        $data = array_merge($data, $this->getCollectionTemplateArray());
        return $data;
    }

    /**
     * Get created by user
     * @return int
     */
    public function getCreatedBy()
    {
        $user =  parent::getCreatedBy();
        if (is_numeric($user) && !empty($user)) {
            $user = UserQuery::create()->findOneById($user)->getUserDataArray()['user'];
        }
        return $user;
    }

    /**
     * Get edited by user
     * @return int
     */
    public function getEditedBy()
    {
        $user = parent::getEditedBy();
        if (is_numeric($user) && !empty($user)) {
            $user = UserQuery::create()->findOneById($user)->getUserDataArray()['user'];
        }
        return $user;
    }
}
