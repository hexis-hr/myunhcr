<?php

namespace Administration\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Acl;

class AclFactory implements FactoryInterface {
    /**
     * Create a new ACL Instance
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Demande
     */
    public function createService (ServiceLocatorInterface $serviceLocator) {

        $acl = new Acl();

        $acl->addRole(new Role('anonymous'));
        $acl->addRole(new Role('user'), 'anonymous');
        $acl->addRole(new Role('admin'), 'user');

        // controllers from administration for controller - action ACL handling
        $acl->addResource(new Resource('Account'));
        $acl->addResource(new Resource('Administration'));
        $acl->addResource(new Resource('Auth'));
        $acl->addResource(new Resource('Compliant'));
        $acl->addResource(new Resource('Faq'));
        $acl->addResource(new Resource('Incident'));
        $acl->addResource(new Resource('Index'));
        $acl->addResource(new Resource('News'));
        $acl->addResource(new Resource('Settings'));
        $acl->addResource(new Resource('Survey'));
        $acl->addResource(new Resource('Translation'));

        //enable work from console
        $acl->addResource(new Resource('Console'));
        $acl->allow('anonymous', 'Console');
        $acl->addResource(new Resource('Cli'));
        $acl->allow('anonymous', 'Cli');

        //application module if fully accessible
        $acl->addResource(new Resource('Application'));
        $acl->allow('anonymous', 'Application');

        //global ACL's
        $acl->allow('anonymous', 'Administration', 'Auth');
        $acl->allow('anonymous', 'Survey', 'xmlDeliver');
        $acl->allow('anonymous', 'Survey', 'transformXml');

        $acl->allow('user', 'Administration', 'Index');
        $acl->allow('user', 'Account', 'profile');

        $acl->allow('admin', 'Administration');

        return $acl;
    }
}
