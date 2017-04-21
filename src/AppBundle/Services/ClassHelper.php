<?php
namespace AppBundle\Services;

use UserBundle\Model\EmailQuery;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class ClassHelper
{
    public function __construct(Container $container)
    {   $this->container = $container;
    }

    public function info()
    {
        return "This class holds various functions that can be used in the controllers";
    }

    public function isValidDate($date)
    {
        $d = \DateTime::createFromFormat('d-m-Y', $date);
        return $d && $d->format('d-m-Y') === $date;
    }

    public function getBooleanValue($value)
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function debug($data)
    {
        print '<pre>';
        var_dump($data);
        Print '</pre>';
    }

    public function sendCredentialsEmail($user, $password)
    {
        $translator = $this->container->get('translator');
        $templating = $this->container->get('templating');
        $mailer = $this->container->get('mailer');
        $domain = $this->container->getParameter('domain_name');

        $translator->setLocale(strtolower($user->getLanguageCode()));

        $email = EmailQuery::create()
            ->findOneByArray(['Primary' => true, 'User' => $user]);
        if (empty($email))
            return false;

        $mail = \Swift_Message::newInstance()
            ->setTo($email->getEmail(), $user->getUsername())
            ->setFrom('noreply@' . $domain, $translator->trans('email.password.from') . $domain)
            ->setSubject($translator->trans('email.password.subject'))
            ->setBody($templating->render('UserBundle:email:password.html.twig', array('User' => $user, 'Password' => $password, 'Domain' => $domain)), 'text/html');

        $mailer->send($mail);
        return true;
    }


}