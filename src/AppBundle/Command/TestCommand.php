<?php

namespace AppBundle\Command;

use CompanyBundle\Model\Company;
use CompanyBundle\Model\Informant;
use CompanyBundle\Model\InformantQuery;
use DeviceBundle\Model\ControllerBox;
use DeviceBundle\Model\DeviceGroup;
use DeviceBundle\Model\DeviceGroupQuery;
use DeviceBundle\Model\DsTemperatureSensor;
use StoreBundle\Model\StoreQuery;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use UserBundle\Model\User;
use UserBundle\Model\UserAddress;
use UserBundle\Model\UserQuery;
use UserBundle\Model\Email;
use UserBundle\Model\UserGender;
use UserBundle\Model\UserGenderQuery;
use UserBundle\Model\UserTitle;
use UserBundle\Model\UserTitleQuery;
use UserBundle\Model\Address;
use UserBundle\Model\Phone;
use StoreBundle\Model\Store;


/**
 * Class TestCommand
 * @package AppBundle\Command
 */
class TestCommand extends ContainerAwareCommand {

    protected function configure()
    {
        $this->setName('test')
            ->setDescription('Test command for whatever purposes');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Test command");

        //$this->createUser($output);
        //$this->getUsers($output);
        //$this->getFullUserTemplate($output);
        //$this->addEmailAddress($output);
        //$this->userLanguage($output);
        //$this->sendEmail($output);
        //$this->companyTest($output);
        //$this->postcodeApiTest($output);
        //$this->makeDeviceGroup($output);
        $this->showDeviceGroup($output);
        $output->writeln("Ready.");
    }

    protected function showDeviceGroup(OutputInterface $output)
    {

        $store = StoreQuery::create()
            ->findOneById(1);

        var_dump($store->getName());
        $groups = $store->getDeviceGroups();

        foreach ($groups as $group)
        {
            var_dump($group->getName());
            $ds20s = $group->getDsTemperatureSensors();
            foreach ($ds20s as $ds20)
                var_dump($ds20->getName());
        }

    }

    protected function makeDeviceGroup(OutputInterface $output)
    {
        $store = StoreQuery::create()
            ->findOneById(1);

        $group = new DeviceGroup();
        $group->setName('Bierbar');
        $group->setDescription('Dit is een lange bierbar');
        $group->setMainStore($store->getId());
        $group->save();

        $ds20 = new DsTemperatureSensor();
        $ds20->setName('Sensor 1');
        $ds20->setDescription('Sensor 1 test');
        $ds20->setUid('1234567890');
        $ds20->setGroup($group->getId());
        $ds20->save();

        $cbox = new ControllerBox();
        $cbox->setName('Controller box 1');
        $cbox->setGroup($group->getId());
        $cbox->save();

    }

    protected function postcodeApiTest(OutputInterface $output)
    {
        $address = $this->getContainer()->get('usoft.postcode.client')->getAddress('2652HA', 1);
        if (!empty($address)) {
            $output->writeln($address->getStreet());
            $output->writeln($address->getNumber());
            $output->writeln($address->getCity());
            $output->writeln($address->getProvince());
            $output->writeln($address->getZipcode());
        }
    }

    protected function companyTest(OutputInterface $output)
    {
        $user = UserQuery::create()->findOneByUsername('jvisser');

        if (!empty($user)) {
            $company = new Company();
            $company
                ->setName('Jeroen Visser BV')
                ->setDescription('Gewoon een leuk bedrijf')
                ->save();
            $output->writeln('Company created!');
        }
    }

    protected function sendEmail(OutputInterface $output)
    {
        $user = UserQuery::create()->findOneByUsername('jeroen');
        $password = 'hallo';

        // Send registration / verification mail
        $translator = $this->getContainer()->get('translator');
        $templating = $this->getContainer()->get('templating');
        $mailer = $this->getContainer()->get('mailer');
        $domain = $this->getContainer()->getParameter('domain_name');

        $translator->setLocale(strtolower($user->getLanguageCode()));

        $email = $user->getEmails()->getFirst();
        if (empty($email))
        {
            $output->writeln('User has no email address!');
            return;
        }
        $email = $email->getEmail();

        $mail = \Swift_Message::newInstance()
            ->setTo($email, $user->getUsername())
            ->setFrom('noreply@' . $domain, $translator->trans('email.credentials.from') . $domain)
            ->setSubject($translator->trans('email.credentials.subject'))
            ->setBody($templating->render('UserBundle:email:credentials.html.twig', array('User' => $user, 'Password' => $password, 'Domain' => $domain)), 'text/html');

        $mailer->send($mail);
    }

    protected function userLanguage(OutputInterface $output)
    {
        $user = UserQuery::create()->findOneById(3);
        $email = 'j.visser@vinq-it.nl';
        $result = $user->getEmails();
        var_dump($result);
    }

    protected function addEmailAddress(OutputInterface $output)
    {
        $user = UserQuery::create()->findOneById(3);

        $email = new Email();
        $email->setEmail('j.viser@vinq-it.nl');
        $email->save();

        $user->addEmail($email);
        $user->save();
    }

    protected function getFullUserTemplate(OutputInterface $output)
    {
        $user = new User();
        var_dump($user->getFullUserTemplateArray());
    }

    protected function createUser(OutputInterface $output)
    {
        $user = new User();
        $address = new Address();
        $email = new Email();
        $phone = new Phone();

        $title = UserTitleQuery::create()->findOneByName(UserTitle::MISTER);

        $email->setEmail('j.visser@vinq-it.nl');
        $email->save();

        $phone->setPhoneNumber('+31622200082');
        $phone->save();

        $user->setTitle($title->getId());
        $user->setFirstname('Jeroen');
        $user->setLastname('Visser');
        $user->setUsername('Jeroen');
        $user->setBirthDate(strtotime('19-06-1972'));

        $address->setStreetName('Lunet');
        $address->setHouseNumber('1');
        $address->setPostalCode('2652HA');
        $address->setCity('Berkel en Rodenrijs');
        $address->save();

        $user->addAddress($address);
        $user->addEmail($email);
        $user->addPhone($phone);
        $user->save();
    }

    protected function getUsers(OutputInterface $output)
    {
        $users = UserQuery::create()->find();
        foreach ($users as $user)
            $this->getUser($user, $output);
    }

    protected function getUser(User $user, OutputInterface $output)
    {
        if (empty($user))
            return null;

        $output->writeln($user->getName());

        $arr['user'] = $user->toArray();
        $arr['title'] = UserTitleQuery::create()->findOneById($user->getTitle());
        $arr['email'] = $user->getEmails()->toArray();
        $arr['phone'] = $user->getPhones()->toArray();
        $arr['address'] = $user->getAddresses()->toArray();

        return $arr;
    }
}
