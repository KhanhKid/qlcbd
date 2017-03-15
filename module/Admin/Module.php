<?php
namespace Admin;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\Db\Adapter\Adapter;

//manager module
use Zend\ModuleManager\ModuleManager;

//Session
use Zend\Session\Container; //Session

//auth
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
                } else {
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
                    $controller->layout('layout/admin');

                    //init model
                    $controller->canboModel = $controller->getServiceLocator()->get('Manager\Model\CanBoModel');

                    $controller->danhsachbanModel = $controller->getServiceLocator()->get('Manager\Model\DanhSachBanModel');
                }

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
        return array(
            'factories' => array(
                //user-info manager
                'Admin\Model\UserModel' => function ($sm){
                    $adapter = $sm->get('QlcbdAdapter');
                    $model = new Model\UserModel($adapter);
                    return $model;
                },
                //log model
                'Admin\Model\LogModel' => function ($sm){
                    $adapter = $sm->get('QlcbdAdapter');
                    $model = new Model\LogModel($adapter);
                    return $model;
                },
                //DANH SACH BAN
                'Manager\Model\DanhSachBanModel' => function ($sm){
                    $adapter = $sm->get('QlcbdAdapter');
                    $model = new \Manager\Model\DanhSachBanModel($adapter);
                    return $model;
                }



            )
        );
    }
}
