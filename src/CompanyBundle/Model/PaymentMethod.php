<?php

namespace CompanyBundle\Model;

use CompanyBundle\Model\om\BasePaymentMethod;

class PaymentMethod extends BasePaymentMethod
{
    // cash
    // bank transfer
    // invoice
    // automatic payment

    const METHOD_CASH_NAME = "PAYMENT.NAME.METHOD_CASH";
    const METHOD_BANK_NAME = "PAYMENT.NAME.METHOD_BANK_TRANSFER";
    const METHOD_INVOICE_NAME = "PAYMENT.NAME.METHOD_INVOICE";
    const METHOD_AUTOMATIC_NAME = "PAYMENT.NAME.METHOD_AUTOMATIC_PAYMENT";

    const METHOD_CASH_DESCRIPTION = "PAYMENT.DESCRIPTION.METHOD_CASH";
    const METHOD_BANK_DESCRIPTION = "PAYMENT.DESCRIPTION.METHOD_BANK_TRANSFER";
    const METHOD_INVOICE_DESCRIPTION = "PAYMENT.DESCRIPTION.METHOD_INVOICE";
    const METHOD_AUTOMATIC_DESCRIPTION = "PAYMENT.DESCRIPTION.METHOD_AUTOMATIC_PAYMENT";

    /**
     * getPaymentMethodListArray()
     * @return mixed
     */
    static public function getPaymentMethodListArray()
    {
        $methods = PaymentMethodQuery::create()
            ->orderByName('ASC')
            ->find();

        $methodsArr['payment_methods'] = $methods->toArray();
        return $methodsArr;
    }


}
