<?php
return array(
    'router' => array(
        'routes' => array(
            'cadre' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/cadre',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Cadre\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),//'defaults'
                ),//'options'
                'may_terminate' => true,
                'child_routes' => array(
                    'edit' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action[/:id[/]]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'         => '[0-9]+',
                            ),//'constraints'
                            'defaults' => array(
                                'controller'    => 'Index',
                                'action'        => 'index',
                                'id'            => '-1',
                            ),//'defaults'
                        ),//'options'
                    ),//'default'
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action][/]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),//'constraints'
                            'defaults' => array(
                                'controller'    => 'Index',
                                'action'        => 'index'
                            ),//'defaults'
                        ),//'options'
                    ),//'default'
                ),//'child_routes'
            ),//'application'

        ),//'routes'
    ), //'router'
    
    //Bat buoc phai co khong thi se co loi
    'controllers' => array(
        'invokables' => array(
            'Cadre\Controller\Index'    => 'Cadre\Controller\IndexController',
            'Cadre\Controller\User'     => 'Cadre\Controller\UserController',
            'Cadre\Controller\CanBo'    => 'Cadre\Controller\CanBoController',

        ),
    ),
    //Bat buoc phai co thì mới load duoc View
    'view_manager' => array(
        'doctype'                  => 'HTML5',
        'display_not_found_reason' => true,
        'not_found_template'       => 'error/404',
        'template_map' => array(
            'layout/cadre'        => __DIR__ . '/../view/layout/index.phtml',
        ),

        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    )
);
