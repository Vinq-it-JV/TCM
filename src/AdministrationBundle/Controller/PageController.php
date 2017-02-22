<?php

namespace AdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Model\User;
use UserBundle\Model\UserQuery;

class PageController extends Controller
{
    public function dashboardAction()
    {
        return $this->render('AdministrationBundle:dashboard:dashboard.html.twig');
    }

    public function usersAction()
    {
        return $this->render('AdministrationBundle:users:users.html.twig');
    }

    public function editUserAction(Request $request, $userid)
    {
        $user = UserQuery::create()->findOneById($userid);
        if (empty($user))
            return $this->usersAction();

        $userArr = $user->getUserDataArray();

        return $this->render('AdministrationBundle:users:edit_user.html.twig', $userArr);
    }

    public function addUserAction(Request $request)
    {

        $user = new User();
        $user->setId(0);

        $userArr = $user->getUserDataArray();

        return $this->render('AdministrationBundle:users:add_user.html.twig', $userArr);
    }

}
