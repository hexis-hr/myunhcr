<?php

namespace Administration;

return array(

    'router' => array(
        'routes' => array(
            'login' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/login[/:notFound]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Administration\Controller',
                        'controller' => 'Auth',
                        'action' => 'login',
                    ),
                ),
            ),
            'process' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/login/process',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Administration\Controller',
                        'controller' => 'Auth',
                        'action' => 'authenticate',
                    ),
                ),
            ),
            'logout' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Administration\Controller',
                        'controller' => 'Auth',
                        'action' => 'logout',
                    ),
                ),
            ),
            'administration' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/administration',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Administration\Controller',
                        'controller' => 'Index',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]][/:id][/:confirm]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Auth',
                                'action' => 'login',
                            ),
                        ),
                    ),
                ),
            ),
            'account' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/account',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Administration\Controller',
                        'controller' => 'Account',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]][/:id][/:confirm]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Account',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'add' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/add',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Account',
                                'action' => 'add',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'edit' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/edit[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Account',
                                'action' => 'edit',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'delete' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/delete[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Account',
                                'action' => 'delete',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'profile' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/profile[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Account',
                                'action' => 'profile',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
            'translation' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/translation',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Administration\Controller',
                        'controller' => 'Translation',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]][/:id][/:confirm]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Translation',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'downloadTranslation' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/downloadTranslation[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Translation',
                                'action' => 'downloadTranslation',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
            'faq' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/faq',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Administration\Controller',
                        'controller' => 'Faq',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]][/:id][/:confirm]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Faq',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'add' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/addFaq',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Faq',
                                'action' => 'add',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'edit' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/editFaq[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Faq',
                                'action' => 'edit',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'delete' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/delete[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Faq',
                                'action' => 'delete',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'viewFaqCategory' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/viewFaqCategory',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Faq',
                                'action' => 'viewFaqCategory',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'addFaqCategory' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/addFaqCategory',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Faq',
                                'action' => 'addFaqCategory',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'editFaqCategory' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/editFaqCategory[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Faq',
                                'action' => 'editFaqCategory',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'deleteFaqCategory' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/deleteFaqCategory[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Faq',
                                'action' => 'deleteFaqCategory',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
            'survey' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/survey',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Administration\Controller',
                        'controller' => 'Survey',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]][/:id][/:confirm]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Survey',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'add' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/addODKSurvey',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Survey',
                                'action' => 'add',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'manageActiveStatus' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/manageActiveStatus[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Survey',
                                'action' => 'manageActiveStatus',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'downloadSurvey' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/survey/downloadSurvey[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Survey',
                                'action' => 'downloadSurvey',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'delete' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/survey/delete[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Survey',
                                'action' => 'delete',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
            'news' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/news',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Administration\Controller',
                        'controller' => 'News',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]][/:id][/:confirm]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'News',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'add' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/addNews',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'News',
                                'action' => 'add',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'edit' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/editNews[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'News',
                                'action' => 'edit',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'delete' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/delete[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'News',
                                'action' => 'delete',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
            'countrySelection' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/countrySelection[/:id]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Administration\Controller',
                        'controller' => 'Index',
                        'action' => 'countrySelection',
                    ),
                ),
                'may_terminate' => true,
            ),
            'settings' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/settings',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Administration\Controller',
                        'controller' => 'Settings',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]][/:id][/:confirm]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Settings',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'activateCountry' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/activateCountry',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Settings',
                                'action' => 'activateCountry',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'editActiveCountry' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/editActiveCountry[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Settings',
                                'action' => 'editActiveCountry',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'editCountryLanguage' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/editCountryLanguage[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Settings',
                                'action' => 'editCountryLanguage',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'deleteActiveCountry' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/deleteActiveCountry[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Settings',
                                'action' => 'deleteActiveCountry',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'deleteActiveLanguage' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/deleteActiveLanguage[/:id][/:language]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Settings',
                                'action' => 'deleteActiveLanguage',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
        ),
    ),

    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(getcwd() . '/module/' . __NAMESPACE__ . '/src/' . __NAMESPACE__ . '/Entity/')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        ),
        'authentication' => array(
            'orm_default' => array(
                //should be the key you use to get doctrine's entity manager out of zf2's service locator
                'objectManager' => 'Doctrine\ORM\EntityManager',
                //fully qualified name of your user class
                'identityClass' => '\Administration\Entity\User',
                //the identity property of your class
                'identityProperty' => 'email',
                //the password property of your class
                'credentialProperty' => 'password',
                //a callable function to hash the password with
                'credentialCallable' => 'Administration\Entity\User::hashPassword'
            ),
        ),
    ),

    'doctrinefixtures' => array(
        'paths' => array(getcwd() . '/module/' . __NAMESPACE__ . '/src/' . __NAMESPACE__ . '/DataFixtures/ORM')
    ),

    'controller_plugins' => array(
        'invokables' => array(
            'Auth' => 'Administration\Controller\Plugin\Auth',
        )
    ),

    'controllers' => array(
        'invokables' => array(
            'Administration\Controller\Auth' => 'Administration\Controller\AuthController',
            'Administration\Controller\Index' => 'Administration\Controller\IndexController',
            'Administration\Controller\Account' => 'Administration\Controller\AccountController',
            'Administration\Controller\Translation' => 'Administration\Controller\TranslationController',
            'Administration\Controller\Faq' => 'Administration\Controller\FaqController',
            'Administration\Controller\Survey' => 'Administration\Controller\SurveyController',
            'Administration\Controller\News' => 'Administration\Controller\NewsController',
            'Administration\Controller\Settings' => 'Administration\Controller\SettingsController',
        ),
        'factories' => array(
            'entityManagerController' => 'Administration\Factory\EntityManagerFactory'
        )
    ),

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/administration' => getcwd() . '/module/' . __NAMESPACE__ . '/view/layout/administration.phtml',
            'layout/auth' => getcwd() . '/module/' . __NAMESPACE__ . '/view/layout/auth.phtml',
            'administration/index/index' => getcwd() . '/module/' . __NAMESPACE__ . '/view/administration/index/index.phtml',
            'error/404' => getcwd() . '/module/' . __NAMESPACE__ . '/view/error/404.phtml',
            'error/index' => getcwd() . '/module/' . __NAMESPACE__ . '/view/error/index.phtml',
        ),
        'template_path_stack' => array(
            'administration' => getcwd() . '/module/' . __NAMESPACE__ . '/view/',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'limitEcho' => 'Administration\View\Helper\LimitEcho',
            'countrySelection' => 'Administration\View\Helper\CountrySelection',
        ),
    ),

    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                __DIR__ . '/../public',
            ),
        ),
    ),

    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Dashboard',
                'route' => 'administration',
                'class' => 'glyphicons home',
            ),
            array(
                'label' => 'Accounts',
                'route' => 'account',
                'resource' => 'Administration',
                'privilege' => 'Admin',
                'class' => 'glyphicons group',
            ),
            array(
                'label' => 'My Profile',
                'route' => 'account/profile',
                'class' => 'glyphicons user',
            ),
            array(
                'label' => 'Translations',
                'route' => 'translation',
                'resource' => 'Administration',
                'privilege' => 'Admin',
                'class' => 'glyphicons translate',
            ),
            array(
                'label' => 'FAQ',
                'route' => 'faq',
                'id' => 'faq',
                'class' => 'glyphicons comments',
                'pages' => array(
                    array(
                        'label' => 'FAQ',
                        'route' => 'faq',
                        'class' => 'glyphicons comments',
                    ),
                    array(
                        'label' => 'FAQ category',
                        'route' => 'faq/viewFaqCategory',
                        'class' => 'glyphicons show_big_thumbnails',
                    ),
                ),
            ),
            array(
                'label' => 'Surveys',
                'route' => 'survey',
                'resource' => 'Administration',
                'privilege' => 'Admin',
                'class' => 'glyphicons notes_2',
            ),
            array(
                'label' => 'News',
                'route' => 'news',
                'resource' => 'Administration',
                'privilege' => 'Admin',
                'class' => 'glyphicons keynote',
            ),
            array(
                'label' => 'Settings',
                'route' => 'settings',
                'resource' => 'Administration',
                'privilege' => 'Admin',
                'class' => 'glyphicons settings',
            ),
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'Administration\Acl' => 'Administration\Service\AclFactory',
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
    ),

    'session' => array(
        'config' => array(
            'class' => 'Zend\Session\Config\SessionConfig',
            'options' => array(
                'name' => 'MyUnhcr',
            ),
        ),
        'storage' => 'Zend\Session\Storage\SessionArrayStorage',
        'validators' => array(
            'Zend\Session\Validator\RemoteAddr',
            'Zend\Session\Validator\HttpUserAgent',
        ),
        'remember_me_seconds' => 2592000,
        'use_cookies' => true,
        'cookie_httponly' => true,
    ),

);
