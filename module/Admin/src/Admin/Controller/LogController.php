<?php
//Khai bao namespace 
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

//exception
use Zend\Db\Adapter\Exception\InvalidQueryException;

class LogController extends AbstractActionController
{
    public function indexAction()
    {
        $this->layout('layout/admin');
        $helper = $this->getServiceLocator()->get('viewhelpermanager');
        $headScript = $helper->get('headscript');
        $headScript->appendFile(ROOT_PATH . 'public/script/datatable/media/js/jquery.dataTables.js');
        $headScript->appendFile(ROOT_PATH . 'public/script/datatable/extras/TableTools/media/js/ZeroClipboard.js');
        $headScript->appendFile(ROOT_PATH . 'public/script/datatable/extras/TableTools/media/js/TableTools.js');
        $headScript->appendFile(ROOT_PATH . 'public/script/datatable/extras/ColReorder/media/js/ColReorder.js');

        $logModel = $this->getServiceLocator()->get('Admin\Model\LogModel');



        try{
            $view['defaultData'] = $logModel->getLog();
        }catch(\InvalidQueryException $exc){

        }




        return new ViewModel($view);
    }

    public function viewlogAction()
    {
        $logModel = $this->getServiceLocator()->get('Admin\Model\LogModel');

        try{
            echo json_encode($logModel->getAllLog());
        }catch(\InvalidQueryException $exc){

        }

        return $this->response;
    }

    public function loadlogAction()
    {
        $begin  = $this->getRequest()->getQuery('begin');
        $end  = $this->getRequest()->getQuery('end');
        $logModel = $this->getServiceLocator()->get('Admin\Model\LogModel');


        if (isset($begin)){
            $data = $logModel->getLog($begin, $end);
        }
        else{
            $data = $logModel->getLog();
        }

        $userModel = $this->getServiceLocator()->get('Admin\Model\UserModel');
        foreach ($data as &$row){
            $user = $userModel->getInfo($row['Ma_User_Thuc_Hien']);
            $row['TaiKhoan'] = $user['Username'];
            $row['HoTen'] = $user['Ho_Ten_CB'];

        }

        echo json_encode($data);
        return $this->response;
    }

    public function getuserAction()
    {
        $id = $this->params('id');
        $logModel = $this->getServiceLocator()->get('Admin\Model\LogModel');
        echo json_encode($logModel->getLogOfUser($id));
        return $this->response;
    }

}

