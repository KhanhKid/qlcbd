<?php
//Khai bao namespace
namespace Manager\Controller;

//Load lớp AbstractActionController vào CONTROLLER
use Zend\Mvc\Controller\AbstractActionController;

//Load lớp ViewModel vào CONTROLLER
use Zend\View\Model\ViewModel;

//Auth
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;
//ACL
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\Permissions\Acl\Role\GenericRole as Role;

class TestController extends AbstractActionController
{
    public function indexAction()
    {
        //$this->layout('layout/home');
    }

    public function viewDsKhoiAction()
    {
        $this->layout('layout/home');

        $khoiModel = $this->getServiceLocator()->get('Manager\Model\KhoiModel');

        $data = $khoiModel->fetchAll();


        $donviModel = $this->getServiceLocator()->get('Manager\Model\DonViModel');

        //test
        echo $donviModel->getBCH_ID();
        exit;

        return new ViewModel(array(
            'khoiList' => $data,
        ));
    }

    public function viewMaBCHAction()
    {
        $this->layout('layout/home');


        $donviModel = $this->getServiceLocator()->get('Manager\Model\DonViModel');

        //test
        echo $donviModel->getBCH_ID(2);
        exit;

        return new ViewModel(array(
        ));
    }

    public function viewDsCBAction()
    {
        $this->layout('layout/home');

        $canboModel = $this->getServiceLocator()->get('Manager\Model\CanBoModel');


        //test
        print_r( $canboModel->getAllBriefInfo());
        exit;

        return new ViewModel(array(
            'khoiList' => $data,
        ));
    }

    public function thanhlapDonViAction(){
        $donviModel = $this->getServiceLocator()->get('Manager\Model\DonViModel');

//        $donviModel->establish(
//            'QD11',
//            '2',
//            'Quận Đoàn 11',
//            null,
//            null
//        );

        $donviModel->getKhoiList();
    }

    public function testACLAction(){
        $acl = new Acl();

        //role
        $acl->addRole(new Role('guest'))
            ->addRole(new Role('member'))
            ->addRole(new Role('admin'));

        $acl->addRole(new Role('staff'));
        $acl->addRole(new Role('marketing'), 'staff');


        // Create Resources for the rules
        // newsletter
        $acl->addResource(new Resource('newsletter'));
        // news
        $acl->addResource(new Resource('news'));
        // latest news
        $acl->addResource(new Resource('latest'), 'news');
        // announcement news
        $acl->addResource(new Resource('announcement'), 'news');


        // Marketing must be able to publish and archive newsletters and the
        // latest news
        $acl->allow('marketing',
            array('newsletter', 'latest'),
            array('publish', 'archive'));

        // Staff (and marketing, by inheritance), are denied permission to
        // revise the latest news
        $acl->deny('staff', 'latest', 'revise');

        // Everyone (including administrators) are denied permission to
        // archive news announcements
        $acl->deny(null, 'announcement', 'archive');



        //serialize


        echo serialize($acl);
        exit;

        //unserialize();
    }

    public function testAuthAction(){
        $authApdater = new CredentialTreatmentAdapter();


    }

    public function soapAction(){

    }
}

