<?php

namespace AppBundle\Command;

use CollectionBundle\Model\Collection;
use CollectionBundle\Model\CollectionType;
use CollectionBundle\Model\CollectionTypeQuery;
use CompanyBundle\Model\Company;
use Criteria;
use DeviceBundle\Model\CbInputQuery;
use DeviceBundle\Model\ControllerBox;
use DeviceBundle\Model\DeviceGroup;
use DeviceBundle\Model\DsTemperatureSensor;
use DeviceBundle\Model\DsTemperatureSensorQuery;
use NotificationBundle\Model\CbInputNotificationQuery;
use NotificationBundle\Model\DsTemperatureNotificationQuery;
use StoreBundle\Model\StoreQuery;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UserBundle\Model\Address;
use UserBundle\Model\Email;
use UserBundle\Model\Phone;
use UserBundle\Model\User;
use UserBundle\Model\UserQuery;
use UserBundle\Model\UserTitle;
use UserBundle\Model\UserTitleQuery;


/**
 * Class TestCommand
 * @package AppBundle\Command
 */
class TestCommand extends ContainerAwareCommand
{

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
        //$this->showDeviceGroup($output);
        //$this->checkInputs($output);
        //$this->showNotifications($output);
        //$this->testNotificationMail($output);
        //$this->showInputNotifications($output);
        //$this->getSuperAdminEmail($output);
        //$this->addCollection($output);
        //$this->createUUID($output);
        //$this->checkTimeDiff($output);
        //$this->getSensorLog($output);

        $this->copySensor($output);
        $output->writeln("Ready.");
    }

    protected function copySensor(OutputInterface $output)
    {
        $sensor = DsTemperatureSensorQuery::create()->findOneById(6);

//        $copy = new DeviceCopy();
//        $copy->setCopyOfSensor(6);
//        $copy->setGroup(2);
//        $copy->save();
//
//        $sensor->addDeviceCopy($copy);
//        $sensor->save();

        if (!$sensor->getDeviceCopies()->isEmpty()) {
            $output->writeln(sprintf('device has %d copies:', $sensor->getDeviceCopies()->count()));
            foreach ($sensor->getDeviceCopies() as $i => $deviceCopy) {
                $output->writeln(sprintf(' - %d placed in group: %s ', $i + 1, $deviceCopy->getDeviceGroup()->getName()));
            }
        }
    }

    protected function getSensorLog(OutputInterface $output)
    {
        $sensor = DsTemperatureSensorQuery::create()->findOneById(2);
        if (!empty($sensor)) {
            $logs = $sensor->getDsTemperatureSensorLogs();
            if (!empty($logs)) {
                foreach ($logs as $log)
                    $output->writeln($log->getTemperature());
            }
        }
    }

    protected function checkTimeDiff(OutputInterface $output)
    {
        $date = new \DateTime();

        $sensor = DsTemperatureSensorQuery::create()->findOneByUid('28FFA05A91150176');
        if (!empty($sensor)) {
            $updated = $sensor->getDataCollectedAt('Y-m-d H:i:s'); //'2017-07-04 09:23:10';
            $now = $date->format('Y-m-d H:i:s');
            $diffSeconds = strtotime($now) - strtotime($updated);
            $output->writeln(sprintf("now: %d, updated at: %d = diff: %d", strtotime($now), strtotime($updated), $diffSeconds));
        } else
            $output->writeln('Sensor not found');
    }

    protected function createUUID(OutputInterface $output)
    {
        $helper = $this->getContainer()->get('class_helper');

        $output->writeln($helper->createUUID());

        $inputs = "6";
        $bit = 0x04;
        for ($i = 1; $i <= 3; $i++) {

            $output->writeln($inputs & $bit);
            $bit >>= 1;
        }
    }

    protected function addCollection(OutputInterface $output)
    {
        $store = StoreQuery::create()->findOneById(1);
        $collectionType = CollectionTypeQuery::create()->findOneById(CollectionType::TYPE_INVENTORY_ID);
        $date = new \DateTime();

        $collection = new Collection();
        $collection->setStore($store);
        $collection->setCollectionType($collectionType);
        $collection->setName("Just some maintenance");
        $collection->setDescription("Here we can add a very long text!");
        $collection->setDate($date);
        $collection->setIsPublished(true);
        $collection->save();

        $output->writeln(printf("Collection is created for store %s", $store->getName()));
    }

    protected function getSuperAdminEmail(OutputInterface $output)
    {
        $helper = $this->getContainer()->get('notification_helper');
        var_dump($helper->getSuperAdminEmailList());
    }

    protected function showInputNotifications(OutputInterface $output)
    {
        $c = new Criteria();
        $c->add('cb_input_notification.is_handled', false);

        $input = CbInputQuery::create()->findOneById(1);
        if (!empty($input)) {
            $notifications['Inputs'] = $input->getCbInputNotifications($c)->toArray();
            var_dump($notifications);
        }
    }

    protected function testNotificationMail(OutputInterface $output)
    {
        $output->writeln('Start notification mail check.');
        $helper = $this->getContainer()->get('notification_helper');

        $store = StoreQuery::create()->findOneById(1);

        $helper->sendNotificationsEmail($store);
    }

    protected function showNotifications(OutputInterface $output)
    {
        $output->writeln('Show notifications:');
        $user = UserQuery::create()->findOneById(1);

        $notifications = CbInputNotificationQuery::create()->findByIsHandled(false);
        foreach ($notifications as $notification) {
            $this->showNotification($output, $notification);
            if (!empty($user)) {
                $notification->handleNotification($user);
                $output->writeln(sprintf('handled by: %s', $notification->getHandledBy()->getName()));
            }
        }
        $notifications = DsTemperatureNotificationQuery::create()->findByIsHandled(false);
        foreach ($notifications as $notification) {
            $this->showNotification($output, $notification);
            if (!empty($user))
                $notification->handleNotification($user);
        }
    }

    protected function showNotification(OutputInterface $output, $notification)
    {
        $output->writeln(sprintf('%s on %s', $notification->getSensor()->getName(), $notification->getCreatedAt('d-m-Y H:i:s')));
    }

    protected function checkInputs(OutputInterface $output)
    {
        $output->writeln('start input test');

        $inputs = "07";

        $output->writeln(sprintf('Inputs set to: %s (decimal=%d)', $inputs, hexdec($inputs)));

        $inputs = hexdec($inputs);

        $bit = 0x01;

        for ($inp = 1; $inp <= 3; $inp++) {
            if ($inputs & $bit)
                $output->writeln(sprintf('input %d is set', $inp));
            $bit <<= 1;
        }
    }

    protected function showDeviceGroup(OutputInterface $output)
    {

        $store = StoreQuery::create()
            ->findOneById(1);

        var_dump($store->getName());
        $groups = $store->getDeviceGroups();

        foreach ($groups as $group) {
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
        if (empty($email)) {
            $output->writeln('User has no email address!');

            return;
        }
        $email = $email->getEmail();

        $mail = \Swift_Message::newInstance()
            ->setTo($email, $user->getUsername())
            ->setFrom('noreply@' . $domain, $translator->trans('email.credentials.from') . $domain)
            ->setSubject($translator->trans('email.credentials.subject'))
            ->setBody($templating->render('UserBundle:email:credentials.html.twig', ['User' => $user, 'Password' => $password, 'Domain' => $domain]), 'text/html');

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
