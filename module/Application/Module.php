<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;


//Event
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

//Db
use Zend\Db\Adapter\Adapter;

//Auth
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService;

//ACL
use Zend\Permissions\Acl\Acl;

//
use QLCB\Permissions\Acl\Acl as SidACL;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        //
        $eventManager = $e->getApplication()->getEventManager();
        $shareEvent = $eventManager->getSharedManager();
        $shareEvent->attach(__NAMESPACE__, 'dispatch',
            function($ev) {
                $controller = $ev->getTarget();
                //do something in controller
                //...


                    //======================================================================================================

                    //do somethings else (if have permission)
                    //...

                    //set default layout
                    //$controller->layout('layout/admin');

                    //init model


            },100);
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
                    __NAMESPACE__   =>   __DIR__ . '/src/' . __NAMESPACE__,
                    //'QLCB'          =>   dirname(__DIR__).'/../vendor/SID/library/QLCB', //add by Dk
                ),
            ),
        );
    }


    //config service
    public function getServiceConfig(){
        return array(
            'factories' => array(
                //qlcbd database - Adapter
                'QlcbdAdapter' =>  function($sm) {
                    $configs = $sm->get('config');
                    $config = $configs['qlcbd_db'];

                    $dbAdapter = new Adapter($config);
                    return $dbAdapter;
                },

                'websiteAdapter' =>  function($sm) {
                    $configs = $sm->get('config');
                    $config = $configs['website_db'];

                    $dbAdapter = new Adapter($config);
                    return $dbAdapter;
                },


                'AuthAdapter' => function ($sm){
                    $dbAdapter = $sm->get('QlcbdAdapter');

                    $authAdapter = new AuthAdapter($dbAdapter,
                        'user',
                        'username',
                        'password',
                        'MD5(MD5(CONCAT(?,Password_Key))) AND Status_Code = "1"'
                        //'MD5(CONCAT(MD5(?),MD5(Password_Key))) AND Status_Code = "1"'
                    );


                    return $authAdapter;
                },


                'acl' => function ($sm){
                    //TO DO: add this in a action, exam "login action" (do "init" once). Load from database

                    //inti acl
                    $acl = new Acl();
                    //role
                    $acl->addRole('cadre');
                    //$acl->addRole('chief');
                    $acl->addRole('permanent_cadre','cadre'); //Permanent Cadre have all perssions of Cadre
                    $acl->addRole('organizer_cadre','cadre'); //Organizer Cadre have all perssions of Cadre
                    //Note: A Organizer-Cadre who is set "management permission" is A Manager

                    $acl->addRole('manager','cadre'); //Manager have all perssions of Cadre
                    $acl->addRole('admin', 'manager'); //Admin have all perssions of Manager


                    //resource
                    $acl->addResource('Admin');
                    $acl->addResource('Manager');
                    $acl->addResource('Cadre');

                    //allow (grant permission)
                    $acl->allow('admin', 'Admin', null); // admin can do all things in module Admin
                    $acl->allow('manager', 'Manager', null); // Manager can do all things in module Manager
                    //$acl->allow('manager', 'Cadre', null); // Manager can do all things in module Cadre
                    $acl->allow('cadre', 'Cadre', null); // Cadre can do all things in module Cadre




                    return $acl;

                    //return new SidACL();
                },
            )
        );
    }
}
