<?php

namespace CollectionBundle\Model;

use CollectionBundle\Model\om\BaseAttachment;

class Attachment extends BaseAttachment
{
    const TYPE_UNKNOWN = 0;
    const TYPE_IMAGE = 1;
    const TYPE_DOCUMENT = 2;

    /**
     * getAttachmentDataArray()
     * @return array
     */
    public function getAttachmentDataArray()
    {
        $data = [];
        $data['attachment'] = $this->toArray();

        return $data;
    }

}
