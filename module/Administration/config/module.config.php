<?php

namespace Administration;

return array(
    'router' => array(
        'routes' => array(
            'login' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/login',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Administration\Controller',
                        'controller'    => 'Auth',
                        'action'        => 'login',
                    ),
                ),
            ),
            'process' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/login/process',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Administration\Controller',
                        'controller'    => 'Auth',
                        'action'        => 'authenticate',
                    ),
                ),
            ),
            'logout' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/logout',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Administration\Controller',
                        'controller'    => 'Auth',
                        'action'        => 'logout',
                    ),
                ),
            ),
            'administration' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/administration',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Administration\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]][/:id][/:confirm]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller'    => 'Auth',
                                'action'        => 'login',
                            ),
                        ),
                    ),
                ),
            ),
            'account' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/account',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Administration\Controller',
                        'controller'    => 'Account',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]][/:id][/:confirm]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller'    => 'Account',
                                'action'        => 'index',
                            ),
                        ),
                    ),
                    'add' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'    => '/add',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller'    => 'Account',
                                'action'        => 'add',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'edit' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'    => '/edit[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller'    => 'Account',
                                'action'        => 'edit',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'delete' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route'    => '/delete[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller'    => 'Account',
                                'action'        => 'delete',
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
                'paths' => array(getcwd() . '/module/' . __NAMESPACE__ . '/src/'. __NAMESPACE__ .'/Entity/')
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
        'paths' => array( getcwd() . '/module/' . __NAMESPACE__ . '/src/'. __NAMESPACE__ .'/DataFixtures/ORM' )
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
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/administration'      => getcwd() . '/module/'. __NAMESPACE__ . '/view/layout/administration.phtml',
            'layout/auth'                => getcwd() . '/module/'. __NAMESPACE__ . '/view/layout/auth.phtml',
            'administration/index/index' => getcwd() . '/module/'. __NAMESPACE__ . '/view/administration/index/index.phtml',
            'error/404'               => getcwd() . '/module/'. __NAMESPACE__ . '/view/error/404.phtml',
            'error/index'             => getcwd() . '/module/'. __NAMESPACE__ . '/view/error/index.phtml',
        ),
        'template_path_stack' => array(
            'administration' => getcwd() . '/module/'. __NAMESPACE__ . '/view/',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
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
                'class' => 'glyphicons home'
            ),
            array(
                'label' => 'Accounts',
                'route' => 'account',
                'class' => "glyphicons group"
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Administration\Acl' => 'Administration\Service\AclFactory',
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
    ),

);
