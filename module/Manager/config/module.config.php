<?php
return array(
    'router' => array(
        'routes' => array(
            'manager' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/manager',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Manager\Controller',
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
                                'id'         => '[a-zA-Z0-9_-]+',
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
            ),//'manager'

        ),//'routes'
    ), //'router'
    
    //Bat buoc phai co khong thi se co loi
    'controllers' => array(
        'invokables' => array(
            'Manager\Controller\Index'  => 'Manager\Controller\IndexController',
            'Manager\Controller\User'   => 'Manager\Controller\UserController',
            'Manager\Controller\Canbo'   => 'Manager\Controller\CanboController',
            'Manager\Controller\Cocau'   => 'Manager\Controller\CocauController',
            'Manager\Controller\Search'   => 'Manager\Controller\SearchController',
            'Manager\Controller\Baocao'   => 'Manager\Controller\BaocaoController',
            'Manager\Controller\Backup'   => 'Manager\Controller\BackupController',

            'Manager\Controller\Test'   => 'Manager\Controller\TestController',
        ),
    ),
    //Bat buoc phai co thì mới load duoc View
    'view_manager' => array(
        'doctype'                  => 'HTML5',
        'display_not_found_reason' => true,
        'not_found_template'       => 'error/404',
        'template_map' => array(
            'error/404'         => __DIR__ . '/../view/error/404.phtml',
            'layout/home'       => __DIR__ . '/../view/layout/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    )
);
