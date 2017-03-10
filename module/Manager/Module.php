<?php
namespace Manager;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;



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
    //
    public function init(ModuleManager $moduleManager) {
        $moduleManager->getEventManager()
            ->getSharedManager()
            ->attach(__NAMESPACE__, 'dispatch',
                function($e) {
                    $controller = $e->getTarget();
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

                    }else{
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
                                'action'    => 'not-permission'
                            ));
                        }
                        //======================================================================================================

                        //do somethings else (if have permission)
                        //...

                        //set default layout
                        $controller->layout('layout/home');

                        //init model
                        $controller->khoiModel  = $controller->getServiceLocator()->get('Manager\Model\KhoiModel');
                        $controller->donviModel = $controller->getServiceLocator()->get('Manager\Model\DonViModel');
                        $controller->banModel   = $controller->getServiceLocator()->get('Manager\Model\BanModel');
                        $controller->canboModel = $controller->getServiceLocator()->get('Manager\Model\CanBoModel');
                        $controller->logModel  = $controller->getServiceLocator()->get('Admin\Model\LogModel');
                    }
            },
            100);

    }

    //
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

    }

    //
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    //
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

                //Khoi Cap
                'Manager\Model\KhoiModel' => function ($sm){
                    $adapter = $sm->get('QlcbdAdapter');
                    $model = new Model\KhoiModel($adapter);
                    return $model;
                },
                //Don Vi
                'Manager\Model\DonViModel' => function ($sm){
                    $adapter = $sm->get('QlcbdAdapter');
                    $model = new Model\DonViModel($adapter);
                    return $model;
                },

                //Ban
                'Manager\Model\BanModel' => function ($sm){
                    $adapter = $sm->get('QlcbdAdapter');
                    $model = new Model\BanModel($adapter);
                    return $model;
                },

                //Can Bo
                'Manager\Model\CanBoModel' => function ($sm){
                    $adapter = $sm->get('QlcbdAdapter');
                    $model = new Model\CanBoModel($adapter);
                    return $model;
                },
                //Can Bo
                'Manager\Model\DotDanhGiaModel' => function ($sm){
                    $adapter = $sm->get('QlcbdAdapter');
                    $model = new Model\DotDanhGiaModel($adapter);
                    return $model;
                },
                //Admin
                'Admin\Model\LogModel' => function ($sm){
                    $adapter = $sm->get('QlcbdAdapter');
                    $model = new Model\LogModel($adapter);
                    return $model;
                },


            ),
        );
    }
}
