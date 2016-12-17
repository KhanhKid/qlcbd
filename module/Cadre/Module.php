<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Cadre;

//View- Model
use Zend\View\Model\ViewModel;

//Event
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

//Db
use Zend\Db\Adapter\Adapter;

//Auth
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService;

//acl
use Zend\Permissions\Acl\Acl;




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
                //auth user
                // if do not auth
                $auth = (new AuthenticationService());
                if(!$auth->hasIdentity()){

                    //redirect
                    $controller->redirect()->toRoute('application/default', array(
                        'controller'    => 'user',
                        'action'    => 'login'
                    ));
                }
                //do something (if has auth)
                //...

                //======================================================================================================
                //check permisstion with acl
                //get current Module, Controller, Action
                $route = explode('\\',$controller->params()->fromRoute('controller'));
                $moduleName = $route[0];
                $controllerName = $route[2];
                $actionName = $controller->params()->fromRoute('action');
                //get resource and privilege
                $resource = $moduleName;
                $privilege = $controllerName.':'.$actionName;


                //get role (get their role after auth them)
                $role = $auth->getIdentity()->Role_Name;

                //check and do something
                $acl = $controller->getServiceLocator()->get('acl');
                if(!$acl->isAllowed($role,$resource,$privilege)){
                    //redirect if not permission
                    $controller->redirect()->toRoute($role.'/default', array(
                        'controller'    => 'user',
                        'action'    => 'notPermission'
                    ));
                }
                //======================================================================================================

                //do somethings else (if have permission)
                //...

                //set default layout
                $controller->layout('layout/cadre');




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
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }


    //config service
    public function getServiceConfig(){

    }
}
