<?php

namespace Administration;

use Zend\Mvc\MvcEvent;
use Zend\Authentication\Storage;
use Zend\ModuleManager\ModuleManager;

class Module
{
    private $serviceManager;

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $this->serviceManager = $e->getApplication()->getServiceManager();
        $eventManager->attach('route', array($this, 'loadConfiguration'), 2);

        //to get user identity in view with $this->auth->getIdentity()
        $myService = $this->serviceManager->get('AuthService');
        $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
        $viewModel->auth = $myService;
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories'=>array(
                'AuthStorage' => function($sm){
                        return new \Administration\Model\AuthStorage('myunhcr');
                    },
                'AuthService' => function($sm) {
                        $auth = $this->serviceManager->get('doctrine.authenticationservice.orm_default');
                        $auth->setStorage($sm->get('AuthStorage'));

                        return $auth;
                    },
            ),
        );
    }

    //quickfix
    public function init(ModuleManager $manager)
    {
        $events = $manager->getEventManager();
        $sharedEvents = $events->getSharedManager();
        $sharedEvents->attach('Administration', 'dispatch', function($e) {
            $controller = $e->getTarget();
            $controller->layout('layout/administration');
        }, 100);
    }

    public function loadConfiguration(MvcEvent $e)
    {
        $application   = $e->getApplication();
        $sharedManager = $application->getEventManager()->getSharedManager();

        $router = $this->serviceManager->get('router');
        $request = $this->serviceManager->get('request');

        $matchedRoute = $router->match($request);
        if (null !== $matchedRoute) {
            $sharedManager->attach('Zend\Mvc\Controller\AbstractActionController','dispatch',
                function($e) {
                    $this->serviceManager->get('ControllerPluginManager')->get('Auth')->doAuthorization($e);
                },2
            );
        }
    }

}
