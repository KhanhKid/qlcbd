<?php
//Khai bao namespace 
namespace Manager\Controller;

//Load lớp AbstractActionController vào CONTROLLER
use Zend\Mvc\Controller\AbstractActionController;

//Load lớp ViewModel vào CONTROLLER
use Zend\View\Model\ViewModel;

class BackupController extends AbstractActionController
{
    public function indexAction()
    {
        $this->layout('layout/home');


    }
}

