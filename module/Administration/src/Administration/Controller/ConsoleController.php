<?php

namespace Administration\Controller;

use Administration\Provider\ProvidesEntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Administration\Entity\User;

class ConsoleController extends AbstractActionController {

    use ProvidesEntityManager;

    public function addUserAction () {

        $request = $this->getRequest();

        // Make sure that we are running in a console and the user has not tricked our
        // application into running this action from a public web server.
        if (!$request instanceof \Zend\Console\Request) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        // Get user email from console
        $userEmail = $request->getParam('userEmail');

        $user = new User();
        $user->setEmail($userEmail);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return "User $userEmail added successfully. \n";
    }

    public function deleteUserAction () {

        $request = $this->getRequest();

        // Make sure that we are running in a console and the user has not tricked our
        // application into running this action from a public web server.
        if (!$request instanceof \Zend\Console\Request) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        // Get user email from console
        $userEmail = $request->getParam('userEmail');

        $user = $this->getEntityManager()->getRepository('Administration\Entity\User')->findOneBy(array("email" => $userEmail));

        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();

        return "Done! User $userEmail is succesfully deleted.\n";
    }

    public function resetPasswordAction () {

        $request = $this->getRequest();

        // Make sure that we are running in a console and the user has not tricked our
        // application into running this action from a public web server.
        if (!$request instanceof \Zend\Console\Request) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        // Get user email from console
        $userEmail = $request->getParam('userEmail');
        $password = $request->getParam('password', false);

        $user = $this->getEntityManager()->getRepository('Administration\Entity\User')->findOneBy(array("email" => $userEmail));
        if (!$password)
            $password = uniqid();
        $user->setPassword($password, $user->getSalt());

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return "Done! New password for user $userEmail is '$password'.\n";
    }

    public function userRoleAction () {

        $request = $this->getRequest();

        // Make sure that we are running in a console and the user has not tricked our
        // application into running this action from a public web server.
        if (!$request instanceof \Zend\Console\Request) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        // Get user email from console
        $userEmail = $request->getParam('userEmail');
        $userRole = $request->getParam('role', false);

        $user = $this->getEntityManager()->getRepository('Administration\Entity\User')->findOneBy(array("email" => $userEmail));
        if (!$userRole)
            $userRole = 'user';
        $user->setType($userRole);

        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return "Done! New role for user $userEmail is '$userRole'.\n";
    }

}
