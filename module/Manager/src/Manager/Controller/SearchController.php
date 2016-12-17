<?php
//Khai bao namespace 
namespace Manager\Controller;

//Load lớp AbstractActionController vào CONTROLLER
use Zend\Mvc\Controller\AbstractActionController;

//Load lớp ViewModel vào CONTROLLER
use Zend\View\Model\ViewModel;

class SearchController extends AbstractActionController
{
    public function indexAction()
    {
        $this->layout('layout/home');
        $view['TenCanBo'] = '';
        $view['ThongBao'] = '';
        $view['TimThay'] = false;
        if($this->getRequest()->isPost()){
            $parameters = $this->getRequest()->getPost();

            //to model
            $thongtin = $parameters->toArray();
            $view['TenCanBo'] = $thongtin['ten'];
            $canboModel = $this->getServiceLocator()->get('Manager\Model\CanBoModel');
            $view['dsCanBo'] = $canboModel->timTheoTen($thongtin['ten']); //load "danh sach Cán Bộ" from database, với thông tin công tác
            $view['TimThay'] = ($view['dsCanBo'][0] != null);
        }
        return new ViewModel($view);
    }
}

