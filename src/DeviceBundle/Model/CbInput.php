<?php

namespace DeviceBundle\Model;

use DeviceBundle\Model\om\BaseCbInput;

class CbInput extends BaseCbInput
{
    const TYPE_ID = 4;

    /**
     * getCbInputDataArray()
     * @return array
     */
    public function getCbInputDataArray()
    {
        $data = [];
        $data['cbinput'] = $this->toArray();
        $data['cbinput']['TypeId'] = self::TYPE_ID;

        unset($data['cbinput']['CreatedAt']);
        unset($data['cbinput']['UpdatedAt']);

        return $data;
    }

    /**
     * getCbInputTemplateArray()
     * @return array
     */
    public function getCbInputTemplateArray()
    {
        $input = new CbInput();
        return $input->getCbInputDataArray();
    }
}
