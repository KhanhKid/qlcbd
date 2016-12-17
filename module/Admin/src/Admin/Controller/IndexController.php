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

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $this->layout('layout/admin');
    }

}

