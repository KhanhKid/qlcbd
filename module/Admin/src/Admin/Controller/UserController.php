<?php
//Khai bao namespace 
namespace Admin\Controller;

//Load lớp AbstractActionController vào CONTROLLER
use Zend\Mvc\Controller\AbstractActionController;

//Load lớp ViewModel vào CONTROLLER
use Zend\View\Model\ViewModel;

//Chứng thực, phân quyền
use Zend\Authentication\Result;
use Zend\Permissions\Acl\Acl;
use Zend\Session\Container; //Session
use Zend\Authentication\AuthenticationService;

//exception
use Zend\Db\Adapter\Exception\InvalidQueryException;

class UserController extends AbstractActionController
{
    public function indexAction()
    {
        $this->layout('layout/admin');  
    }

    public function thongtinAction(){
        $helper = $this->getServiceLocator()->get('viewhelpermanager');

        $headScript = $helper->get('headscript');
        $headScript->appendFile('/script/datatable/media/js/jquery.dataTables.js');
        $headScript->appendFile('/script/datatable/extras/TableTools/media/js/ZeroClipboard.js');
        $headScript->appendFile('/script/datatable/extras/TableTools/media/js/TableTools.js');
        $headScript->appendFile('/script/datatable/extras/ColReorder/media/js/ColReorder.js');
        $headScript->appendFile('/template/js/combobox.js');
        //init Model
        $userModel = $this->getServiceLocator()->get('Admin\Model\UserModel');

        //
        $view['dsUser'] = $userModel->getAllUserCanBoInfo();

        return new ViewModel($view);
    }

    public function createAccountAction(){

        $helper = $this->getServiceLocator()->get('viewhelpermanager');
        $headScript = $helper->get('headscript');
        $headScript->appendFile('/template/js/combobox.js');
        //init model
        $userModel = $this->getServiceLocator()->get('Admin\Model\UserModel');
        $canboModel = $this->getServiceLocator()->get('Manager\Model\CanBoModel');

        if($this->getRequest()->isPost()){
            //$parameters = new Parameters();
            $parameters = $this->getRequest()->getPost();

            $parameters['macanbo'] = (''==$parameters['macanbo'])?null:$parameters['macanbo'];
            $parameters['role'] = (''==$parameters['role'])?null:$parameters['role'];

            //excute model
            try{
                $userModel->createNew(
                    $parameters['username'],
                    $parameters['password'],
                    $parameters['role'],
                    $parameters['macanbo']
                );
                $view['message'] = 'Tạo tài khoản thành công';
            }catch(InvalidQueryException $exc){
                $view['message'] = 'Không tạo được tài khoản [lỗi dữ liệu: trùng tài khoản,...]';
            }



        }

        //ViewModel to view
        $view['role_list'] = $userModel->getRoleList();

        $param['createNew'] = 1; // set if have user dont show
        $view['dsCanbo'] = $canboModel->getAllBriefInfo($param);

        return new ViewModel($view);
    }

    public function voidDeleteAction() {
        //init model
        $userModel = $this->getServiceLocator()->get('Admin\Model\UserModel');

        //get GET parameters
        $userid = $this->params('id'); //user id
        $currentId = (new AuthenticationService)->getIdentity()->UserID;
        $userid = (null==$userid)?0:$userid;

        //On Submit
        if($this->getRequest()->isPost()){
            $message =null;

            //execute model
            try{ 
                $canboModel = $this->getServiceLocator()->get('Manager\Model\CanBoModel'); 
                $canboModel->deleteCanbo($userid);
                $message = 'Xóa cán bộ thành công'; 
            }catch (InvalidQueryException $exc){
                $message = 'Không thể xóa cán bộ [Lỗi xử lý dữ liệu]';
            }


            //view
            $view['message'] = $message;

            $basePath = $this->getRequest()->getBasePath();
            $this->redirect()->toUrl( $basePath.'/admin/user/thongtin');
        }


        $view['user'] =  $userModel->getInfo($userid);

        return new ViewModel($view);
    }

    public function changePasswordAction(){
        //init model
        $userModel = $this->getServiceLocator()->get('Admin\Model\UserModel');

        //get GET parameters
        $userid = $this->params('id'); //user id
        $currentId = (new AuthenticationService)->getIdentity()->UserID;
        $userid = (null==$userid)?$currentId:$userid;

        //On Submit
        if($this->getRequest()->isPost()){
            $message =null;

            //get parameter from View
            $password_new=$this->getRequest()->getPost('password_new');


            //execute model
            try{
                $userModel->changePassword($userid, $password_new);
                $message = 'Đổi mật khẩu thành công';
            }catch (InvalidQueryException $exc){
                $message = 'Đổi mật khẩu thất bại [Lỗi xử lý dữ liệu]';
            }


            //view
            $view['message'] = $message;
        }


        $view['user'] =  $userModel->getInfo($userid);

        //var_dump($userid);

        return new ViewModel($view);
    }

    public function deleteAction(){
        //init model
        $userModel = $this->getServiceLocator()->get('Admin\Model\UserModel');
        $canboModel = $this->getServiceLocator()->get('Manager\Model\CanBoModel');

        //get GET parameters
        $userid = $this->params('id'); //user id


        //process request (GET)
        if($this->getRequest()){

            //delete
            $this->$userModel->delete(
                $userid
            );
        }


        $basePath = $this->getRequest()->getBasePath();
        $this->redirect()->toUrl( $basePath.'/admin/user/thongtin');
    }

    public function accessControlAction(){
        $this->layout('layout/admin');


    }

    public function authorizationAction(){
        $helper = $this->getServiceLocator()->get('viewhelpermanager');
        $headScript = $helper->get('headscript');
        $headScript->appendFile('/template/js/combobox.js');
        //init model
        $userModel = $this->getServiceLocator()->get('Admin\Model\UserModel');
        $canboModel = $this->getServiceLocator()->get('Manager\Model\CanBoModel');


        //get GET parameters
        $userid = $this->params('id'); //user id

        //on request
        if($this->getRequest()->isPost()){
            //$parameters = new Parameters();
            $parameters = $this->getRequest()->getPost();

            //null value
            $parameters['macanbo'] = (''==$parameters['macanbo'])?null:$parameters['macanbo'];
            $parameters['role'] = (''==$parameters['role'])?null:$parameters['role'];


            //var_dump($parameters);exit;

            //excute model
            $flag= true;
            if($parameters['macanbo'] == null){
                $view['message'] = 'Không thực hiện được lỗi không có tên cán bộ';
                $flag= false;
            }

            if($flag){
                try{
                    $userModel->editInfo(
                        $userid,
                        $parameters['username'],
                        $parameters['role'],
                        $parameters['macanbo'],
                        $parameters['status']
                    );
                    $view['message'] = 'Sửa khoản thành công';
                }catch(InvalidQueryException $exc){
                    //throw $exc;
                    $view['message'] = 'Không thực hiện được [lỗi dữ liệu: trùng tài khoản,...]';
                }
            }
        }

        //init view
        $view['user'] =  $userModel->getInfo($userid);
        $view['role_list'] = $userModel->getRoleList();
        $view['dsCanbo'] = $canboModel->getAllBriefInfo();


        return new ViewModel($view);
    }



    public function notPermissionAction(){

    }

}

