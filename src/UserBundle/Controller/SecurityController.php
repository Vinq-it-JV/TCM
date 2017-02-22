<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Response\JsonResult;
use UserBundle\Model\User;
use UserBundle\Model\UserQuery;

class SecurityController extends Controller
{
    public function loginAction()
    {
        $helper = $this->get('security.authentication_utils');
        $auth = $helper->getLastAuthenticationError();
        $message = '';

        // Get last login error message
        if ($auth)
            $message = $auth->getMessage();

        // Prevent loggedin users to view login screen
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))
            return new RedirectResponse('/');

        // Render the login screen
        return $this->render('UserBundle:login:login.html.twig', array(
            'last_username' => $helper->getLastUsername(),
            'error_message' => $this->preTranslateErrorMessage($message)
        ));
    }

    public function loginStatusAction()
    {
        $userdata = [];

        $user = $this->get('security.token_storage')->getToken()->getUser();

        if (is_object($user))
        {
            $dbuser = UserQuery::create()
                ->findOneByUsername($user->getUsername());

            if (!empty($dbuser))
            {
                $userdata = $dbuser->getUserDataArray();
                $userdata['user']['IsLoggedin'] = true;
            }
        }

        return JsonResult::create()
            ->setContents($userdata)
            ->make();
    }

    public function changePasswordAction(Request $request)
    {
        $passworddata = (object)json_decode($request->getContent(), true);
        $user = $this->getUser();
        if (!empty($user))
        {
            $password = $passworddata->password;
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $password);
            $user->setPassword($encoded);
            $user->save();

            $this->sendCredentialsEmail($user, $password);

            return JsonResult::create()
                ->setMessage('PASSWORD_CHANGED')
                ->make();
        }
        return JsonResult::create()
            ->setMessage('PASSWORD_NOT_CHANGED')
            ->setErrorcode(JsonResult::WARNING)
            ->make();
    }

    protected function preTranslateErrorMessage($message)
    {
        switch ($message)
        {
            case 'User is locked out':
                return 'AUTH.USER_LOCKED_OUT';
            case 'User is not activated':
                return 'AUTH.USER_NOT_ACTIAVED';
            case 'User has no role(s)':
                return 'AUTH.NO_USER_ROLES';
            case 'Invalid username or password':
                return 'AUTH.INVALID_USER_PASS';
            case 'This authentication method requires a session.':
                return 'AUTH.METHOD_REQUIRES_SESSION';
            case 'Your session has timed out, or you have disabled cookies.':
                return 'AUTH.SESSION_TIMEOUT';
        }
        return $message;
    }

    /**
     * sendCredentialsEmail($user, $password)
     * @param $user
     * @param $password
     * @return bool
     */
    protected function sendCredentialsEmail($user, $password)
    {
        $translator = $this->container->get('translator');
        $templating = $this->container->get('templating');
        $mailer = $this->container->get('mailer');
        $domain = $this->container->getParameter('domain_name');

        $translator->setLocale(strtolower($user->getLanguageCode()));

        $email = $user->getEmails()->getFirst();
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
