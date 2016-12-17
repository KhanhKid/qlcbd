<?php
return array(
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
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
            ),//'admin'

        ),//'routes'
    ), //'router'
    
    //declare controller
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Index'    => 'Admin\Controller\IndexController',
            'Admin\Controller\User'     => 'Admin\Controller\UserController',
            'Admin\Controller\Cocau'    => 'Admin\Controller\CocauController',
            'Admin\Controller\Backup'   => 'Admin\Controller\BackupController',
            'Admin\Controller\Restore'  => 'Admin\Controller\RestoreController',
            'Admin\Controller\Log'      => 'Admin\Controller\LogController'

        ),
    ),

    //view manager
    'view_manager' => array(
        'doctype'                  => 'HTML5',
        'display_not_found_reason' => true,
        'not_found_template'       => 'error/404',

        'template_map' => array(
            'layout/admin'        => __DIR__ . '/../view/layout/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    )
);
