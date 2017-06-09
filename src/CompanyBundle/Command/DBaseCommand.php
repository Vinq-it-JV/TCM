<?php

namespace CompanyBundle\Command;

use CompanyBundle\Model\CompanyType;
use CompanyBundle\Model\CompanyTypeQuery;
use CompanyBundle\Model\PaymentMethod;
use CompanyBundle\Model\PaymentMethodQuery;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DBaseCommand
 * @package CompanyBundle\Command
 */
class DBaseCommand extends ContainerAwareCommand
{

    protected $companyTypeArr = [];
    protected $paymentMethodsArr = [];

    protected function configure()
    {
        $this->setName('dbase:company:initialize')
            ->setDescription('Initialize standard lists for the company database');
    }

    protected function configCompanyTypeList()
    {
        $this->companyTypeArr[CompanyType::BAR_ID]['Name'] = CompanyType::BAR_NAME;
        $this->companyTypeArr[CompanyType::BAR_ID]['Description'] = CompanyType::BAR_DESCRIPTION;
        $this->companyTypeArr[CompanyType::SERVICE_ID]['Name'] = CompanyType::SERVICE_NAME;
        $this->companyTypeArr[CompanyType::SERVICE_ID]['Description'] = CompanyType::SERVICE_DESCRIPTION;
        $this->companyTypeArr[CompanyType::RESTAURANT_ID]['Name'] = CompanyType::RESTAURANT_NAME;
        $this->companyTypeArr[CompanyType::RESTAURANT_ID]['Description'] = CompanyType::RESTAURANT_DESCRIPTION;
        $this->companyTypeArr[CompanyType::BRASSERIE_ID]['Name'] = CompanyType::BRASSERIE_NAME;
        $this->companyTypeArr[CompanyType::BRASSERIE_ID]['Description'] = CompanyType::BRASSERIE_DESCRIPTION;
        $this->companyTypeArr[CompanyType::EATERY_ID]['Name'] = CompanyType::EATERY_NAME;
        $this->companyTypeArr[CompanyType::EATERY_ID]['Description'] = CompanyType::EATERY_DESCRIPTION;
        $this->companyTypeArr[CompanyType::CINEMA_ID]['Name'] = CompanyType::CINEMA_NAME;
        $this->companyTypeArr[CompanyType::CINEMA_ID]['Description'] = CompanyType::CINEMA_DESCRIPTION;
        $this->companyTypeArr[CompanyType::HOTEL_ID]['Name'] = CompanyType::HOTEL_NAME;
        $this->companyTypeArr[CompanyType::HOTEL_ID]['Description'] = CompanyType::HOTEL_DESCRIPTION;
        $this->companyTypeArr[CompanyType::NIGHTSPOT_ID]['Name'] = CompanyType::NIGHTSPOT_NAME;
        $this->companyTypeArr[CompanyType::NIGHTSPOT_ID]['Description'] = CompanyType::NIGHTSPOT_DESCRIPTION;
        $this->companyTypeArr[CompanyType::PARENT_ID]['Name'] = CompanyType::PARENT_NAME;
        $this->companyTypeArr[CompanyType::PARENT_ID]['Description'] = CompanyType::PARENT_DESCRIPTION;
    }

    protected function configPaymentMethodsList()
    {
        $this->paymentMethodsArr['CASH']['Name'] = PaymentMethod::METHOD_CASH_NAME;
        $this->paymentMethodsArr['BANK']['Name'] = PaymentMethod::METHOD_BANK_NAME;
        $this->paymentMethodsArr['INVOICE']['Name'] = PaymentMethod::METHOD_INVOICE_NAME;
        $this->paymentMethodsArr['AUTOMATIC']['Name'] = PaymentMethod::METHOD_AUTOMATIC_NAME;
        $this->paymentMethodsArr['CASH']['Description'] = PaymentMethod::METHOD_CASH_DESCRIPTION;
        $this->paymentMethodsArr['BANK']['Description'] = PaymentMethod::METHOD_BANK_DESCRIPTION;
        $this->paymentMethodsArr['INVOICE']['Description'] = PaymentMethod::METHOD_INVOICE_DESCRIPTION;
        $this->paymentMethodsArr['AUTOMATIC']['Description'] = PaymentMethod::METHOD_AUTOMATIC_DESCRIPTION;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Start database init of company.");
        $this->configCompanyTypeList();
        $this->configPaymentMethodsList();
        $this->generateCompanyTypeList($output);
        $this->generatePaymentMethodsList($output);
        $output->writeln("Ready.");
    }

    protected function generateCompanyTypeList(OutputInterface $output)
    {
        foreach ($this->companyTypeArr as $k => $type) {
            $_type = CompanyTypeQuery::create()
                ->findOneById($k);
            if (empty($_type)) {
                $_type = new CompanyType();
                $_type
                    ->setName($type['Name'])
                    ->setDescription($type['Description'])
                    ->save();
                $output->writeln(sprintf('Created company type: %s', $k));
            }
        }
    }

    protected function generatePaymentMethodsList(OutputInterface $output)
    {
        foreach ($this->paymentMethodsArr as $k => $method) {
            $_method = PaymentMethodQuery::create()
                ->findOneByName($method['Name']);
            if (empty($_method)) {
                $_method = new PaymentMethod();
                $_method
                    ->setName($method['Name'])
                    ->setDescription($method['Description'])
                    ->save();
                $output->writeln(sprintf('Created payment method: %s', $k));
            }
        }
    }

}

