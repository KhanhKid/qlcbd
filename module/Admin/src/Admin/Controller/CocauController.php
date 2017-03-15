<?php
namespace Admin\Controller;

//Load lớp AbstractActionController vào CONTROLLER
use Zend\Mvc\Controller\AbstractActionController;

//Load lớp ViewModel vào CONTROLLER
use Zend\View\Model\ViewModel;

//Chứng thực, phân quyền
use Zend\Authentication\Result;
use Zend\Permissions\Acl\Acl;
use Zend\Session\Container; //Session

class CocauController extends AbstractActionController
{	
    public function indexAction()
    {
    	$view['flag'] = false;
        //$this->layout('layout/admin');
        return new ViewModel($view);
    }

    /**
     * thành lập đơn vị mới
     * @return ViewModel
     */
    public function danhsachbanAction() {
        //init model

        //check submit
        if ($this->getRequest()->isPost()) {
            //get parameter
            $parameters = $this->getRequest()->getPost();
            $this->danhsachbanModel->insertPhongBan($parameters);
        }
        //echo '<pre>',var_dump($this->danhsachbanModel),'</pre>';die();
        $view['dsBan'] = $this->danhsachbanModel->getAllBriefInfo();


        $this->layout('layout/admin');

        return new ViewModel($view);
    }
    public function danhsachthanhvienbanAction() {
        //check submit
        if ($this->getRequest()->isPost()) {
            //get parameter
            $parameters = $this->getRequest()->getPost();
            $this->danhsachbanModel->insertPhongBan($parameters);
        }
        $view['dsBan'] = $this->danhsachbanModel->getAllBriefInfo();
        $view['dsCanBo'] = $this->canboModel->getAllWorkInfo();
        //init view
        $helper     = $this->getServiceLocator()->get('viewhelpermanager');
        $headScript = $helper->get('headscript');

        $this->layout('layout/admin');

        return new ViewModel($view);
    }
    public function xoabanAction() {
        //init model
        $paras  = explode('-', $this->params('id'));
        $id     = (int)$paras[0];
        $this->danhsachbanModel->deleteBan($id);
        
        $this->redirect()->toRoute('admin/default', array(
                'controller'    => 'cocau',
                'action'    => 'danhsachban',
        ));
    }

}

