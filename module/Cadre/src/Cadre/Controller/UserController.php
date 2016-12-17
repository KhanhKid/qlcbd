<?php
//Khai bao namespace 
namespace Cadre\Controller;

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
        $this->layout('layout/cadre');



    }

    public function accountAction(){
        //$this->layout('layout/login');

        //get user info from their auth
        $auth = (new AuthenticationService());
        $userInfo = (!$auth->hasIdentity())
            ?array('username' => 'you', 'roleName' => 'guest')
            :$auth->getIdentity();



        //to view
        $view['userInfo'] = $userInfo;
        return new ViewModel($view);
    }

    public function changePasswordAction(){
        //On Submit
        if($this->getRequest()->isPost()){
            $message =null;
            //init service
            $auth = new AuthenticationService();

            //parameters
            $username = $auth->getIdentity()->Username; //curren Username
            $password_old = $this->getRequest()->getPost('password_old'); //test old password
            //check if Identity is null
            $username = (null==$username)?'anonymous':$username;


            //auth by data from database
            $authAdapter = $this->getServiceLocator()->get('AuthAdapter');
            $authAdapter
                ->setIdentity($username)
                ->setCredential($password_old)
            ;

            //test old password by aduthenticating
            $result = $authAdapter->authenticate(); //aduthenticate

            //if successful
            if($result->isValid()){
                //init model
                $userModel = $this->getServiceLocator()->get('Admin\Model\UserModel');

                //get user id (from auth info)
                $userid = $auth->getIdentity()->UserID;

                //get parameter from View
                $password_new=$this->getRequest()->getPost('password_new');

                $userModel->changePassword($userid, $password_new);

                $message = 'Đổi mật khẩu thành công';
            }else{
                $message = 'Sai mật khẩu cũ';
            }

            //view
            $view['message'] = $message;
            return new ViewModel($view);
        }


        //do something else
    }

    /**
     * When user is not permission to a page
     */
    public function notPermissionAction(){

    }
}

