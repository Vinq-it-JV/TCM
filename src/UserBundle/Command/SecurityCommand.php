<?php

namespace UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use UserBundle\Model\Email;
use UserBundle\Model\EmailQuery;
use UserBundle\Model\Role;
use UserBundle\Model\RoleQuery;
use UserBundle\Model\User;
use UserBundle\Model\UserQuery;
use UserBundle\Model\UserRole;
use UserBundle\Model\UserRoleQuery;

class SecurityCommand extends ContainerAwareCommand {

    protected $rolesArr = [];
    protected $adminsArr = [];

    protected function configure()
    {
        $this->setName('user:initialize:users-roles')
            ->setDescription('Create admins, users and roles');
    }

    protected function configRoles()
    {
        $this->rolesArr['ROLE_USER']['Description'] = Role::ROLE_USER;
        $this->rolesArr['ROLE_USER']['Style'] = Role::ROLE_USER_STYLE;
        $this->rolesArr['ROLE_ADMIN']['Description'] = Role::ROLE_ADMIN;
        $this->rolesArr['ROLE_ADMIN']['Style'] = Role::ROLE_ADMIN_STYLE;
        $this->rolesArr['ROLE_SUPER_ADMIN']['Description'] = Role::ROLE_SUPER_ADMIN;
        $this->rolesArr['ROLE_SUPER_ADMIN']['Style'] = Role::ROLE_SUPER_ADMIN_STYLE;
    }

    protected function configAdmins()
    {
        $this->rolesArr = RoleQuery::create()
            ->find()
            ->toArray();

        $this->adminsArr['superadmin'] = Array('ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_USER');
        $this->adminsArr['j.visser'] = Array('ROLE_USER');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Start users and roles initialization.");
        $this->configRoles();
        $this->generateRoles($this->rolesArr, $input, $output);
        $this->configAdmins();
        $this->generateAdmins($this->adminsArr, $input, $output);
        $output->writeln("Ready.");
    }

    protected function generateRoles($rolesArr, InputInterface $input, OutputInterface $output)
    {
        foreach ($rolesArr as $k => $role)
        {
            $therole = RoleQuery::create()
                ->findOneByName($k);

            if (!$therole)
            {
                $therole = new Role();
                $therole->setName($k);
                $therole->setDescription($role['Description']);
                $therole->setStyle($role['Style']);
                $therole->save();

                $output->writeln("Role: '" . $k . "' (" . $role['Description'] . ") created.");
            }
        }
    }

    protected function generateAdmins($adminsArr, InputInterface $input, OutputInterface $output)
    {
        $encoder = $this->getContainer()->get('security.password_encoder');
        $domain = $this->getContainer()->getParameter('domain_name');

        foreach ($adminsArr as $name => $roles)
        {
            $admin = UserQuery::create()
                ->findOneByUsername($name);

            if (!$admin)
            {
                $output->writeln("Creating admin: " . $name);

                $email = new Email();
                $email->setEmail($name . '@' . $domain);
                $email->save();

                $admin = new User();
                $admin->setFirstname($name);
                $admin->addEmail($email);
                $admin->setUsername($name);
                $admin->setLanguage('en');
                $password = $name;
                $encoded = $encoder->encodePassword($admin, $password);
                $admin->setPassword($encoded);
                $admin->save();
            }

            $output->writeln("Creating roles for: " . $name);
            foreach ($roles as $role)
            {
                $roleArr = $this->findRole($role);
                if ($roleArr)
                {
                    $therole = UserRoleQuery::create()
                        ->filterByUserId($admin->getId())
                        ->findOneByRoleId($roleArr['Id']);

                    if (is_null($therole))
                    {
                        $userrole = new UserRole();
                        $userrole->setRoleId($roleArr['Id']);
                        $userrole->setUserId($admin->getId());
                        $userrole->save();
                        $output->writeln(" ->" . $role);
                        $therole = null;
                    }
                }
            }
        }
    }

    protected function findRole($roleid)
    {
        foreach ($this->rolesArr as $role)
            if ($role['Name'] == $roleid)
                return $role;
        return null;
    }
}
