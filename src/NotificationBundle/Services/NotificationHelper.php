<?php
namespace NotificationBundle\Services;

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

        foreach ($store->getInformants() as $informant) {
            $translator->setLocale(strtolower($informant->getLanguageCode()));

            $email = $informant->getPrimaryEmail();

            if (empty($email))
                continue;

            $mail = \Swift_Message::newInstance()
                ->setTo($email->getEmail(), $informant->getName())
                ->setFrom('notifications@' . $domain, $translator->trans('email.notification.from') . $domain)
                ->setSubject($translator->trans('email.notification.subject'))
                ->setBody($templating->render('NotificationBundle:email:notification.html.twig', array('Informant' => $informant, 'Store' => $store, 'Domain' => $domain)), 'text/html');

            $result += $mailer->send($mail);
        }

        return $result;
    }


}