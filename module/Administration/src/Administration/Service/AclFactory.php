<?php
namespace Administration\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Acl;

class AclFactory implements FactoryInterface
{
    /**
     * Create a new ACL Instance
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Demande
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $acl = new Acl();

        $acl->addRole(new Role('anonymous'));
        $acl->addRole(new Role('user'), 'anonymous');
        $acl->addRole(new Role('admin'), 'user');

        $acl->addResource(new Resource('Administration'));
        $acl->addResource(new Resource('Application'));

        $acl->allow('anonymous', 'Application');
        $acl->allow('anonymous', 'Administration', 'Auth');

        $acl->allow('user', 'Administration');

        return $acl;
    }
}
