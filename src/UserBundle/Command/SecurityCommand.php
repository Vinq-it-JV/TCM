<?php

namespace UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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
        $this->setName('admin:initialize:users-roles')
            ->setDescription('Genererate admins, users and roles');
    }

    protected function configRoles()
    {
        $this->rolesArr['ROLE_USER'] = Role::ROLE_USER;
        $this->rolesArr['ROLE_VIP_USER'] = Role::ROLE_VIP_USER;
        $this->rolesArr['ROLE_BOOKMAKER'] = Role::ROLE_BOOKMAKER;
        $this->rolesArr['ROLE_AFFILIATE'] = Role::ROLE_AFFILIATE;
        $this->rolesArr['ROLE_ADMIN'] = Role::ROLE_ADMIN;
        $this->rolesArr['ROLE_SUPER_ADMIN'] = Role::ROLE_SUPER_ADMIN;
    }

    protected function configAdmins()
    {
        $this->rolesArr = RoleQuery::create()
            ->find()
            ->toArray();

        $this->adminsArr['superadmin'] = Array('ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_USER');
        $this->adminsArr['j.visser'] = Array('ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_USER');
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
        foreach ($rolesArr as $role => $description)
        {
            $therole = RoleQuery::create()
                ->findOneByName($role);

            if (!$therole)
            {
                $therole = new Role();
                $therole->setName($role);
                $therole->setDescription($description);
                $therole->save();

                $output->writeln("Role: '" . $role . "' (" . $description . ") created.");
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

                $admin = new User();
                $admin->setFirstname($name);
                $admin->setEmail($name . '@' . $domain);
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
