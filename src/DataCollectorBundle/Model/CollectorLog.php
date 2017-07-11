<?php

namespace DataCollectorBundle\Model;

use DataCollectorBundle\Model\om\BaseCollectorLog;

class CollectorLog extends BaseCollectorLog
{
    /**
     * getCollectorLogDataArray()
     * @return array
     */
    public function getCollectorLogDataArray()
    {
        $data = [];
        $data['log'] = $this->toArray();

        unset($data['cbinput']['CreatedAt']);
        unset($data['cbinput']['UpdatedAt']);

        return $data;
    }

}
