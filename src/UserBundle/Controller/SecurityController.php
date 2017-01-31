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
        $userdata['loggedin'] = false;
        $userdata['name'] = "USER.WELCOME";
        $userdata['roles'] = "";

        $user = $this->get('security.token_storage')->getToken()->getUser();

        if (is_object($user))
        {
            $userdata['loggedin'] = true;
            $userdata['username'] = $user->getUsername();
            if (method_exists($user, "getEmail"))
            {
                $userdata['email'] = $user->getEmail();
                $userdata['name'] = $user->getName();
                $userdata['firstname'] = $user->getFirstname();
                $userdata['lastname'] = $user->getLastname();

                $dbuser = UserQuery::create()
                    ->findPk($user->getUsername());

                if ($dbuser)
                {
                    $userdata['language'] = $dbuser->getLanguage();
                }
            }
            else
            {
                $userdata['name'] = $user->getUsername();
            }
            $userdata['roles'] = $user->getRoles();
        }

        return JsonResult::create()
            ->setContents(array('contents' => $userdata))
            ->make();
    }

    public function changePasswordAction(Request $request)
    {
        $passworddata = json_decode($request->getContent(), true);
        //$username = $this->getUser()->getusername();
        sleep(10);
        //$user = UserQuery::create()
        //    ->findOneByUsername($username);

        /*
        if ($user)
        {
            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $passworddata['password']);
            $user->setPassword($encoded);
            $user->save();

            return JsonResult::create()
                ->setMessage('PASSWORD_CHANGED')
                ->make();
        }
        */
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
}
