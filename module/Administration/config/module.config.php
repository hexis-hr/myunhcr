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
                    'add' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/addTranslation',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Translation',
                                'action' => 'add',
                            ),
                        ),
                        'may_terminate' => true,
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
                    'xmlDeliver' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/xmlDeliver[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Survey',
                                'action' => 'xmlDeliver',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'transformXml' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/transformXml[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Survey',
                                'action' => 'transformXml',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
            'incident' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/incident',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Administration\Controller',
                        'controller' => 'Incident',
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
                                'controller' => 'Incident',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'add' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/addIncident',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Incident',
                                'action' => 'add',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'edit' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/editIncident[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Incident',
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
                                'controller' => 'Incident',
                                'action' => 'delete',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'viewIncidentCategory' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/viewIncidentCategory',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Incident',
                                'action' => 'viewIncidentCategory',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'addIncidentCategory' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/addIncidentCategory',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Incident',
                                'action' => 'addIncidentCategory',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'editIncidentCategory' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/editIncidentCategory[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Incident',
                                'action' => 'editIncidentCategory',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'deleteIncidentCategory' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/deleteIncidentCategory[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Incident',
                                'action' => 'deleteIncidentCategory',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'viewIncidentType' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/viewIncidentType',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Incident',
                                'action' => 'viewIncidentType',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'addIncidentType' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/addIncidentType',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Incident',
                                'action' => 'addIncidentType',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'editIncidentType' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/editIncidentType[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Incident',
                                'action' => 'editIncidentType',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'deleteIncidentType' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/deleteIncidentType[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Incident',
                                'action' => 'deleteIncidentType',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'downloadImage' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/downloadImage[/:name]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Incident',
                                'action' => 'downloadImage',
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
                    'addUserCategory' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/addUserCategory',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Settings',
                                'action' => 'addUserCategory',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'editUserCategory' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/editUserCategory[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Settings',
                                'action' => 'editUserCategory',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'deleteUserCategory' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/deleteUserCategory[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Settings',
                                'action' => 'deleteUserCategory',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'addCountryLocation' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/addCountryLocation',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Settings',
                                'action' => 'addCountryLocation',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'editCountryLocation' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/editCountryLocation[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Settings',
                                'action' => 'editCountryLocation',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'deleteCountryLocation' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/deleteCountryLocation[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Settings',
                                'action' => 'deleteCountryLocation',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
            'complaint' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/complaint',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Administration\Controller',
                        'controller' => 'Complaint',
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
                                'controller' => 'Complaint',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'previewComplaint' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/previewComplaint[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Complaint',
                                'action' => 'previewComplaint',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'deleteComplaint' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/deleteComplaint[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Complaint',
                                'action' => 'deleteComplaint',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
            'appointment' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/appointment',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Administration\Controller',
                        'controller' => 'Appointment',
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
                                'controller' => 'Appointment',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'deleteAppointment' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/deleteAppointment[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Appointment',
                                'action' => 'deleteAppointment',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'viewAppointmentCategory' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/viewAppointmentCategory',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Appointment',
                                'action' => 'viewAppointmentCategory',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'addAppointmentCategory' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/addAppointmentCategory',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Appointment',
                                'action' => 'addAppointmentCategory',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'editAppointmentCategory' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/editAppointmentCategory[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Appointment',
                                'action' => 'editAppointmentCategory',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'deleteAppointmentCategory' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/deleteAppointmentCategory[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Appointment',
                                'action' => 'deleteAppointmentCategory',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
            'service' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/service',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Administration\Controller',
                        'controller' => 'Service',
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
                                'controller' => 'Service',
                                'action' => 'index',
                            ),
                        ),
                    ),
                    'addService' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/addService',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Service',
                                'action' => 'addService',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'editService' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/editService[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Service',
                                'action' => 'editService',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'deleteService' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/deleteService[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Service',
                                'action' => 'deleteService',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'viewOrganization' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/viewOrganization',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Service',
                                'action' => 'viewOrganization',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'addOrganization' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/addOrganization',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Service',
                                'action' => 'addOrganization',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'editOrganization' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/editOrganization[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Service',
                                'action' => 'editOrganization',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'deleteOrganization' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/deleteOrganization[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Service',
                                'action' => 'deleteOrganization',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'viewSector' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/viewSector',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Service',
                                'action' => 'viewSector',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'addSector' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/addSector',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Service',
                                'action' => 'addSector',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'editSector' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/editSector[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Service',
                                'action' => 'editSector',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'deleteSector' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/deleteSector[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Service',
                                'action' => 'deleteSector',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'viewActivity' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/viewActivity',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Service',
                                'action' => 'viewActivity',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'addActivity' => array(
                        'type' => 'Zend\Mvc\Router\Http\Literal',
                        'options' => array(
                            'route' => '/addActivity',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Service',
                                'action' => 'addActivity',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'editActivity' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/editActivity[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Service',
                                'action' => 'editActivity',
                            ),
                        ),
                        'may_terminate' => true,
                    ),
                    'deleteActivity' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/deleteActivity[/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'Administration\Controller',
                                'controller' => 'Service',
                                'action' => 'deleteActivity',
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
            'Administration\Controller\Incident' => 'Administration\Controller\IncidentController',
            'Administration\Controller\News' => 'Administration\Controller\NewsController',
            'Administration\Controller\Settings' => 'Administration\Controller\SettingsController',
            'Administration\Controller\Service' => 'Administration\Controller\ServiceController',
            'Administration\Controller\Complaint' => 'Administration\Controller\ComplaintController',
            'Administration\Controller\Appointment' => 'Administration\Controller\AppointmentController',
            'Administration\Controller\Console' => 'Administration\Controller\ConsoleController',
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
                'label' => 'Incidents',
                'route' => 'incident',
                'id' => 'incidents',
                'class' => 'glyphicons comments',
                'pages' => array(
                    array(
                        'label' => 'Incidents',
                        'route' => 'incident',
                        'class' => 'glyphicons comments',
                    ),
                    array(
                        'label' => 'Incident category',
                        'route' => 'incident/viewIncidentCategory',
                        'class' => 'glyphicons show_big_thumbnails',
                    ),
                    array(
                        'label' => 'Incidents type',
                        'route' => 'incident/viewIncidentType',
                        'class' => 'glyphicons show_big_thumbnails',
                    ),
                ),
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
            array(
                'label' => 'Complaint',
                'route' => 'complaint',
                'resource' => 'Administration',
                'privilege' => 'Admin',
                'class' => 'glyphicons warning_sign',
            ),
            array(
                'label' => 'Appointment',
                'route' => 'appointment',
                'id' => 'appointment',
                'class' => 'glyphicons group',
                'pages' => array(
                    array(
                        'label' => 'Appointment',
                        'route' => 'appointment',
                        'class' => 'glyphicons group',
                    ),
                    array(
                        'label' => 'Appointment category',
                        'route' => 'appointment/viewAppointmentCategory',
                        'class' => 'glyphicons parents',
                    ),
                ),
            ),
            array(
                'label' => 'Service',
                'route' => 'service',
                'id' => 'service',
                'class' => 'glyphicons circle_info',
                'pages' => array(
                    array(
                        'label' => 'Service',
                        'route' => 'service',
                        'class' => 'glyphicons circle_info',
                    ),
                    array(
                        'label' => 'Service Activity',
                        'route' => 'service/viewActivity',
                        'class' => 'glyphicons kiosk',
                    ),
                    array(
                        'label' => 'Service Organization',
                        'route' => 'service/viewOrganization',
                        'class' => 'glyphicons group',
                    ),
                    array(
                        'label' => 'Service Sector',
                        'route' => 'service/viewSector',
                        'class' => 'glyphicons global',
                    ),
                ),
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

    'console' => array(
        'router' => array(
            'routes' => array(
                'user-reset-password' => array(
                    'options' => array(
                        'route'    => 'user reset [--verbose|-v] <userEmail> [<password>]',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Administration\Controller',
                            'controller'    => 'Console',
                            'action'     => 'reset-password'
                        )
                    )
                ),
                'user-add' => array(
                    'options' => array(
                        'route'    => 'user add [--verbose|-v] <userEmail>',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Administration\Controller',
                            'controller'    => 'Console',
                            'action'     => 'add-user'
                        )
                    )
                ),
                'user-delete' => array(
                    'options' => array(
                        'route'    => 'user delete [--verbose|-v] <userEmail>',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Administration\Controller',
                            'controller'    => 'Console',
                            'action'     => 'user-delete'
                        )
                    )
                ),
                'user-role' => array(
                    'options' => array(
                        'route'    => 'user role [--verbose|-v] <userEmail> [<role>]',
                        'defaults' => array(
                            '__NAMESPACE__' => 'Administration\Controller',
                            'controller'    => 'Console',
                            'action'     => 'user-role'
                        )
                    )
                ),
            )
        )
    ),

);
