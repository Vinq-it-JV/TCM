<?php

namespace UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use UserBundle\Model\AddressType;
use UserBundle\Model\AddressTypeQuery;
use UserBundle\Model\Countries;
use UserBundle\Model\CountriesQuery;
use UserBundle\Model\User;
use UserBundle\Model\UserQuery;
use UserBundle\Model\Email;
use UserBundle\Model\UserGender;
use UserBundle\Model\UserGenderQuery;
use UserBundle\Model\UserTitle;
use UserBundle\Model\UserTitleQuery;

/**
 * Class DBaseCommand
 * @package UserBundle\Command
 */
class DBaseCommand extends ContainerAwareCommand {

    protected $genderArr = [];
    protected $titleArr = [];
    protected $countryArr = [];
    protected $addressTypeArr = [];

    protected function configure()
    {
        $this->setName('dbase:initialize')
            ->setDescription('Initialize standard lists');
    }

    protected function configGenderList()
    {
        $this->genderArr['MALE']['Name'] = UserGender::MALE;
        $this->genderArr['MALE']['Short'] = UserGender::MALE_SHORT;
        $this->genderArr['FEMALE']['Name'] = UserGender::FEMALE;
        $this->genderArr['FEMALE']['Short'] = UserGender::FEMALE_SHORT;
        $this->genderArr['OTHER']['Name'] = UserGender::OTHER;
        $this->genderArr['OTHER']['Short'] = UserGender::OTHER_SHORT;
    }

    protected function configTitleList()
    {
        $this->titleArr['MISTER']['Name'] = UserTitle::MISTER;
        $this->titleArr['MISTER']['Short'] = UserTitle::MISTER_SHORT;
        $this->titleArr['MISSES']['Name'] = UserTitle::MISSES;
        $this->titleArr['MISSES']['Short'] = UserTitle::MISSES_SHORT;
        $this->titleArr['MISS']['Name'] = UserTitle::MISS;
        $this->titleArr['MISS']['Short'] = UserTitle::MISS_SHORT;
    }

    protected function configCountryList()
    {
        $countryCodes = ['NL'=>'NL', 'BE'=>'NL', 'DE'=>'EN', 'FR'=>'EN', 'ES'=>'EN', 'IT'=>'EN', 'PT'=>'EN', 'GB'=>'EN'];

        foreach ($countryCodes as $code => $langauge) {
            $this->countryArr[$code]['Name'] = "COUNTRY." . $code . "_NAME";
            $this->countryArr[$code]['Language'] = $langauge;
        }
    }

    protected function configAddressTypeList()
    {
        $this->addressTypeArr['POST']['Name'] = AddressType::POST_NAME;
        $this->addressTypeArr['POST']['Description'] = AddressType::POST_DESCRIPTION;
        $this->addressTypeArr['VISIT']['Name'] = AddressType::VISIT_NAME;
        $this->addressTypeArr['VISIT']['Description'] = AddressType::VISIT_DESCRIPTION;
        $this->addressTypeArr['INVOICE']['Name'] = AddressType::INVOICE_NAME;
        $this->addressTypeArr['INVOICE']['Description']= AddressType::INVOICE_DESCRIPTION;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Start database init of lists.");
        $this->configGenderList();
        $this->configTitleList();
        $this->configCountryList();
        $this->configAddressTypeList();
        $this->generateGenderList($output);
        $this->generateTitleList($output);
        $this->generateCountryList($output);
        $this->generateAddressTypeList($output);
        $output->writeln("Ready.");
    }

    protected function generateGenderList(OutputInterface $output)
    {
        foreach ($this->genderArr as $k => $gender)
        {
            $_gender = UserGenderQuery::create()
                ->findOneByName($gender['Name']);

            if (empty($_gender))
            {   $_gender = new UserGender();
                $_gender
                    ->setName($gender['Name'])
                    ->setNameShort($gender['Short'])
                    ->save();
                $output->writeln(sprintf('Created gender: %s', $k));
            }
        }
    }

    protected function generateTitleList(OutputInterface $output)
    {
        foreach ($this->titleArr as $k => $title)
        {
            $_title = UserTitleQuery::create()
                ->findOneByName($title['Name']);

            if (empty($_title))
            {   $_title = new UserTitle();
                $_title
                    ->setName($title['Name'])
                    ->setNameShort($title['Short'])
                    ->save();
                $output->writeln(sprintf('Created title: %s', $k));
            }
        }
    }

    protected function generateCountryList(OutputInterface $output)
    {
        foreach ($this->countryArr as $code => $country)
        {
            $_country = CountriesQuery::create()
                ->findOneByCountryCode($code);

            if (empty($_country))
            {   $_country = new Countries();
                $_country
                    ->setCountryCode($code)
                    ->setName($country['Name'])
                    ->setLanguageCode($country['Language'])
                    ->setFlag(strtolower($code) . '.png')
                    ->save();
                $output->writeln(sprintf('Created country %s', $code));
            }
        }
    }

    protected function generateAddressTypeList(OutputInterface $output)
    {
        foreach ($this->addressTypeArr as $k => $type)
        {
            $_type = AddressTypeQuery::create()
                ->findOneByName($type['Name']);

            if (empty($_type))
            {   $_type = new AddressType();
                $_type
                    ->setName($type['Name'])
                    ->setDescription($type['Description'])
                    ->save();
                $output->writeln(sprintf('Creates address type %s', $k));
            }
        }
    }
}

