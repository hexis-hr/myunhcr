<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $sessionConfig = new SessionConfig();
        $sessionConfig->setOptions(array(
            'use_cookies' => true,
            'cookie_httponly' => true,
            'gc_maxlifetime' => 2678400,
            'cookie_lifetime' => 2678400,
        ));
        $manager = new SessionManager($sessionConfig);

        $sessionContainer = new Container('userSettings');
        $sessionContainer::setDefaultManager($manager);

        if (!isset($sessionContainer->id)) {
//            $url = $e->getApplication()->getMvcEvent()->getRouter()->assemble(array(), array('name' => 'home'));
            $url = '/';
            $response = $e->getApplication()->getMvcEvent()->getResponse();
            $response->setHeaders($response->getHeaders()->addHeaderLine('Location', $url));
            $response->setStatusCode(302);
            $response->sendHeaders();
            $sessionContainer->id = false;
            exit ();
        }


        // test if session language exists
        if(!$sessionContainer->offsetExists('userlocale')){
            // if not use the browser locale
            if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
                $sessionContainer->offsetSet('userlocale', \Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']));
            }else{
                $sessionContainer->offsetSet('userlocale', 'en_US');
            }
        }

        $translator = $e->getApplication()->getServiceManager()->get('translator');
        $translator ->setLocale($sessionContainer->userlocale);
        $translator->setFallbackLocale('en_US');

        $sharedEvents        = $eventManager->getSharedManager();
        $sharedEvents->attach(__NAMESPACE__, 'dispatch', function($e) {
            $result = $e->getResult();
            if ($result instanceof \Zend\View\Model\ViewModel) {
                $result->setTerminal($e->getRequest()->isXmlHttpRequest());
            }
        });
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

    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'formelementerrors' => 'Application\Form\View\Helper\FormElementErrors',
                'formfile' => 'Application\Form\View\Helper\FormFile',
            ),
        );
    }
}
