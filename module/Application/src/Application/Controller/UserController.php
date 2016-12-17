<?php
//Khai bao namespace
namespace Application\Controller;

//Load lớp AbstractActionController vào CONTROLLER
use Zend\Mvc\Controller\AbstractActionController;

//Load lớp ViewModel vào CONTROLLER
use Zend\View\Model\ViewModel;

//Chứng thực
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Zend\Authentication\Storage\Session;
use Zend\Authentication\Storage;
use Zend\Session\Container; //Session

//phân quyền
use Zend\Permissions\Acl\Acl;



class UserController extends AbstractActionController
{
    public function indexAction()
    {
        $this->layout('layout/application');

        //redirect
        $this->redirect()->toRoute('application/default', array(
            'controller'    => 'user',
            'action'    => 'login'
        ));

    }

    public function accountAction(){
        $this->layout('layout/cadre');

        //get user info from their auth
        $auth = (new AuthenticationService());

        $userInfo = $auth->getIdentity();

        //to view
        $view['userInfo'] = $userInfo;

        return new ViewModel($view);
    }

    public function loginAction()
    {

        //init Model

        //init View
        $this->layout('layout/login');

        //On Submit
        if($this->getRequest()->isPost()){
            //parameters
            $username = $this->getRequest()->getPost('username');
            $password = $this->getRequest()->getPost('password');


            //auth by data from database
            $username = (null==$username)?'anonymous':$username; //check if Identity is null
            $authAdapter = $this->getServiceLocator()->get('AuthAdapter');
            $authAdapter
                ->setIdentity($username)
                ->setCredential($password)
            ;

            // instantiate the authentication service, and aduthenticate
            $auth = new AuthenticationService();
            $result = $auth->authenticate($authAdapter); //aduthenticate






            //if successful
            if($result->isValid()){
                //get user info ( get data from table) and save them
                $getInfo = $authAdapter->getResultRowObject(null,array('Password')); //don't get password
                $auth->getStorage()->write($getInfo); //store them

                //redirect
                $role = $auth->getIdentity()->Role_Name; //get role to go to conform module

                try {
                    $this->redirect()->toRoute($role, array(
                        'controller'    => 'index'
                    ));
                }catch (Exception $e){
                    //default
                    $this->redirect()->toRoute('cadre/canbo', array(
                        'controller'    => 'index'
                    ));
                }

            }
        }


        //do something else
    }

    public function logoutAction()
    {
        //set session
        $auth = new AuthenticationService();
        $auth->clearIdentity();
        //...


        //$this->redirect()->toUrl('http://localhost/qlcbd/public/manager/user');
        $this->redirect()->toRoute('application/default', array(
            'controller'    => 'user',
            'action'    => 'login'
        ));

        //Không render ra giao diện
        return $this->response;
    }

    public function welcomeAction(){

    }

    public function notPermissionAction(){
        $this->layout('layout/home');
    }

}

