<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

return array(
    'router' => array(
        'router_class' => 'Zend\Mvc\Router\Http\TranslatorAwareTreeRouteStack',
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'app' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/[:action].html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'takeASurvey' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/takeASurvey[/:survey][/:authentification][/:date]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action'     => 'takeASurvey',
                    ),
                ),
            ),
            'newsArticle' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/newsArticle[/:id]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action'     => 'newsArticle',
                    ),
                ),
            ),
            'newsAndEvents' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/newsAndEvents',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action'     => 'newsAndEvents',
                    ),
                ),
            ),
            'newsAndEventsPartial' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/newsAndEventsPartial[/:offset]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action'     => 'newsAndEventsPartial',
                    ),
                ),
            ),
            'reportAnIncident' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/report-an-incident.html',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action'     => 'reportAnIncident',
                    ),
                ),
            ),
            'uploadFile' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/upload-file',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action'     => 'uploadFile',
                    ),
                ),
            ),
            'fileAComplaint' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/fileAComplaint',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action'     => 'fileAComplaint',
                    ),
                ),
            ),
            'bookAnAppointment' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/bookAnAppointment',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action'     => 'bookAnAppointment',
                    ),
                ),
            ),
            'bookAnAppointment2' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/bookAnAppointment2[/:appointmentType][/:authentification]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action'     => 'bookAnAppointment2',
                    ),
                ),
            ),
            'showCountryLocation' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/showCountryLocation',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action'     => 'showCountryLocation',
                    ),
                ),
            ),
            'getAjaxMarkers' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/getAjaxMarkers',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'getAjaxMarkers',
                    ),
                ),
            ),
            'listOfOrganizations' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/listOfOrganizations',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'listOfOrganizations',
                    ),
                ),
            ),
            'organizationDetails' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/organizationDetails[/:id]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'organizationDetails',
                    ),
                ),
            ),
            'listBySector' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/listBySector',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'listBySector',
                    ),
                ),
            ),
            'sectorDetails' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/sectorDetails',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'sectorDetails',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    'asset_manager' => array(
        'resolver_configs' => array(
            'collections' => array(
                'scripts/primary.js' => array(
                    'scripts/external/zepto.js',
                    'scripts/external/load.js',
                    'scripts/primary/_core.js',
                    'scripts/external/fastclick.js',
                    'scripts/external/url.js',
                    'scripts/primary/_helpers.js',
                    'scripts/primary/_design.js',
                    'scripts/primary/_call.js',
                    'scripts/primary/_historyState.js',
                    'scripts/primary/_ajax.js',
                    'scripts/primary/_forms.js',
                    'scripts/primary/_unsorted.js',
                    'scripts/primary/_exec.js'
                ),
                'scripts/secondary.js' => array(
                    'scripts/external/geoPosition.js',
                    'scripts/secondary/_core.js',
                    'scripts/secondary/_sectionTooltip.js',
                    'scripts/secondary/_forms.js',
                    'scripts/secondary/_dateAndTime.js',
                    'scripts/secondary/_fileUpload.js',
                    'scripts/secondary/_infiniteScroll.js',
                    'scripts/secondary/_openMaps.js',
                    'scripts/secondary/_geoLocation.js',
                    'scripts/secondary/_maps.js',
                    'scripts/secondary/_exec.js'
                ),
            ),
        'paths' => array(
                __DIR__ . '/../public',
            ),
        ),
        'filters' => array(
            'js' => array(
                array(
                    // Note: You will need to require the classes used for the filters yourself.
                    'filter' => 'Assetic\\Filter\\JSMinFilter',  // Allowed format is Filtername[Filter]. Can also be FQCN
                ),
            ),
        ),
        'caching' => array(
            'default' => array(
                'cache'     => 'AssetManager\\Cache\\FilePathCache',
                'options' => array(
                    'dir' => 'public/', // path/to/cache
                ),
            ),
        ),
    ),
);
