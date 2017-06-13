<?php
namespace NotificationBundle\Services;

use UserBundle\Model\RoleQuery;
use UserBundle\Model\Role;
use UserBundle\Model\UserQuery;
use UserBundle\Model\EmailQuery;
use StoreBundle\Model\Store;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class NotificationHelper
{
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function info()
    {
        return "This class holds functions for sending notification emails";
    }

    public function sendNotificationsEmail($store)
    {
        $translator = $this->container->get('translator');
        $templating = $this->container->get('templating');
        $mailer = $this->container->get('mailer');
        $domain = $this->container->getParameter('domain_name');
        $result = 0;

        // Send to all informants of the store
        foreach ($store->getInformants() as $informant) {
            $translator->setLocale(strtolower($informant->getLanguageCode()));

            $email = $informant->getPrimaryEmail();

            if (empty($email))
                continue;

            $mail = \Swift_Message::newInstance()
                ->setTo(array($email->getEmail() => $informant->getName()))
                ->setFrom('notifications@' . $domain, $translator->trans('email.notification.from') . $domain)
                ->setSubject($translator->trans('email.notification.subject'))
                ->setBody($templating->render('NotificationBundle:email:notification.html.twig', array('Informant' => $informant, 'Store' => $store, 'Domain' => $domain)), 'text/html');

            $result += $mailer->send($mail);
        }

        // And send to all Super Admins!
        $emailList = $this->getSuperAdminEmailList();

        $mail = \Swift_Message::newInstance()
            ->setTo($emailList)
            ->setFrom('notifications@' . $domain, $translator->trans('email.notification.from') . $domain)
            ->setSubject($translator->trans('email.notification.subject'))
            ->setBody($templating->render('NotificationBundle:email:notification.html.twig', array('Informant' => null, 'Store' => $store, 'Domain' => $domain)), 'text/html');

        $result += $mailer->send($mail);

        return $result;
    }

    public function getSuperAdminEmailList()
    {
        $emailArr = [];

        $role = RoleQuery::create()
            ->findOneById(Role::ROLE_SUPER_ADMIN_ID);

        $users = UserQuery::create()
            ->filterByRole($role)
            ->filterByIsEnabled(true)
            ->find();

        foreach ($users as $user) {
            $email = $user->getPrimaryEmail();
            if (!empty($email))
                $emailArr[] = $email->getEmail();
        }

        return array_unique($emailArr);
    }
}