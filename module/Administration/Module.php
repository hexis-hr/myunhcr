<?php

namespace Administration;

use Zend\Mvc\MvcEvent;
use Zend\Authentication\Storage;
use Zend\ModuleManager\ModuleManager;
use Zend\Console\Adapter\AdapterInterface as Console;

class Module {

    private $serviceManager;

    public function onBootstrap (MvcEvent $e) {

        $eventManager = $e->getApplication()->getEventManager();
        $this->serviceManager = $e->getApplication()->getServiceManager();
        $eventManager->attach('dispatch', array($this, 'loadConfiguration'), 100);

        //to get user identity in view with $this->auth->getIdentity()
        $viewModel = $e->getViewModel();
        $viewModel->auth = $this->serviceManager->get('AuthService');
        $viewModel->acl = $this->serviceManager->get('Administration\Acl');

        //construct helpers
        $this->serviceManager->get('viewhelpermanager')->setFactory('CountrySelection', function ($sm) use ($e) {
            return new \Administration\View\Helper\CountrySelection($sm);
        });

    }

    public function getConfig () {

        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig () {

        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * This method is defined in ConsoleBannerProviderInterface
     */
    public function getConsoleBanner(Console $console)
    {
        return 'Administration module - User management';
    }
    /**
     * This method is defined in ConsoleUsageProviderInterface
     */
    public function getConsoleUsage(Console $console)
    {
        return array(
            'user add  <userEmail>' => 'Add user',
            'user delete  <userEmail>' => 'Delete user',
            'user reset <userEmail>' => 'Reset user',
            'user role <userEmail>' => 'Set user role',
        );
    }

    public function getServiceConfig () {

        return array(
            'factories' => array(
                'AuthStorage' => function ($sm) {
                        return new \Administration\Model\AuthStorage('MyUnhcr');
                    },
                'AuthService' => function ($sm) {
                        $auth = $this->serviceManager->get('doctrine.authenticationservice.orm_default');
                        $auth->setStorage($sm->get('AuthStorage'));

                        return $auth;
                    },
                'CountryService' => function ($sm) {
                        return new \Administration\Service\CountryService($sm);
                    },
                'LocaleService' => function ($sm) {
                        return new \Administration\Service\LocaleService($sm);
                    },
            ),
        );
    }

    // changing layout for administration module
    public function init (ModuleManager $manager) {

        $events = $manager->getEventManager();
        $sharedEvents = $events->getSharedManager();
        $sharedEvents->attach('Administration', 'dispatch', function ($e) {
            $controller = $e->getTarget();
            $controller->layout('layout/administration');
        }, 100);
    }

    public function loadConfiguration (MvcEvent $e) {

        $application = $e->getApplication();
        $sharedManager = $application->getEventManager()->getSharedManager();

        $router = $this->serviceManager->get('router');
        $request = $this->serviceManager->get('request');

        $matchedRoute = $router->match($request);
        if (null !== $matchedRoute) {
            $sharedManager->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch',
                function ($e) {
                    $this->serviceManager->get('ControllerPluginManager')->get('Auth')->doAuthorization($e);
                }, 100);
        }
    }

}
