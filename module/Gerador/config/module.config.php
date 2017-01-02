<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Gerador\Controller\Index' => 'Gerador\Controller\IndexController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'gerador' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/gerador',
                    'defaults' => array(
                        'controller' => 'Gerador\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'gerador/index/index' => __DIR__ . '/../view/gerador/index/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
