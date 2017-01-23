<?php

namespace Application;

use Application\Controller\AdmController;
use Application\Controller\IndexController;

return array(
    # definir e gerenciar controllers
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => IndexController::class
        ),
        'factories' => array(
            'Application\Controller\Cadastro' => 'Application\Controller\Factory\CadastroControllerFactory',
        ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'cadastro' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/cadastro[:action][/:token]',
                    'constraints' => array(
                        'action' => '[a-zA-Z]+',
                        'token' => '[a-zA-Z0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Cadastro',
                        'action' => 'index',
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
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'layout/adm' => __DIR__ . '/../view/layout/layout-adm.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'application/adm/empresa' => __DIR__ . '/../view/application/adm/empresa.phtml',
            'application/adm/empresa-cadastro' => __DIR__ . '/../view/application/adm/empresa-cadastro.phtml',
            'application/adm/empresas' => __DIR__ . '/../view/application/adm/empresas.phtml',
            'application/cadsatro/index' => __DIR__ . '/../view/application/cadastro/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    # definir driver, classes anotadas para o doctrine e quem faz autenticação
    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/Model/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Application\Model\Entity' => 'application_entities'
                )
            )
        ),
//        'authentication' => array(
//            'orm_default' => array(
//                'object_manager' => 'Doctrine\ORM\EntityManager',
//                'identity_class' => 'Application\Model\Entity\Pessoa',
//                'identity_property' => 'email',
//                'credential_property' => 'senha',
//            ),
//        ),
    ),
);
