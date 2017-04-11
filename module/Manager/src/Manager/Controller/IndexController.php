<?php
//Khai bao namespace 
namespace Manager\Controller;

//Load lớp AbstractActionController vào CONTROLLER
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationService;

//Load lớp ViewModel vào CONTROLLER
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	$auth   = (new AuthenticationService());
		$infoCbCur = $auth->getIdentity();

		$view['infoCbCur'] = $infoCbCur;
        $this->layout('layout/home');
        return new ViewModel($view);
    }
}

