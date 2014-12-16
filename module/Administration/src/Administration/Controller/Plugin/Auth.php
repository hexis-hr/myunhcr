<?php

namespace Administration\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\Container as SessionContainer;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\View\Helper\Navigation;

class Auth extends AbstractPlugin {

    protected $sesscontainer;

    private function getSessContainer () {
        if (!$this->sesscontainer) {
            $this->sesscontainer = new SessionContainer('myunhcr');
        }
        return $this->sesscontainer;
    }

    public function doAuthorization ($e) {
        $acl = $this->getController()->getServiceLocator()->get('Administration\Acl');

        $controller = $e->getTarget();
        $controllerClass = get_class($controller);

        $matches = $e->getRouteMatch();
        $action = $matches->getParam('action', '');

        $namespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
        $class = explode('\\', $controllerClass);
        $privilege = str_replace("Controller", "", end($class));

        $identity = $this->getController()->getServiceLocator()->get('AuthService')->getIdentity();

        if (!$this->getController()->getServiceLocator()->get('AuthService')->hasIdentity()) {
            $role = "anonymous";
        } else {
            $role = $identity->getType();
        }

        $role = new Role($role);

        Navigation::setDefaultAcl($acl);
        Navigation::setDefaultRole($role);

        if (!($acl->isAllowed($role, $privilege, $action) || $acl->isAllowed($role, $namespace, $privilege))) {

            $router = $e->getRouter();
            $url = $router->assemble(array(), array('name' => 'login'));

            $response = $e->getResponse();
            $response->setStatusCode(302);
            //redirect to login route...
            /* change with header('location: '.$url); if code below not working */
            $response->getHeaders()->addHeaderLine('Location', $url);
            $e->stopPropagation();
        }
    }
}
