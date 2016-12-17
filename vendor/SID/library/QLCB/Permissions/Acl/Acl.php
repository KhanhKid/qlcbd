<?php

namespace QLCB\Permissions\Acl;

class Acl extends \Zend\Permissions\Acl\Acl{

    public function __contructor(){
        $this->addRole('admin');
        $this->addRole('manager');
        $this->addRole('cadre');
        $this->inheritsRole('manager', 'cadre'); //Cadre have all perssions of Cadre
        //resource
        $this->addResource('Admin');
        $this->addResource('Manager');
        $this->addResource('Cadre');

        //allow (grant permission)
        $this->allow(null, 'Admin', 'User:notPermission'); //all user can go to not-permission page
        $this->allow('admin', 'Admin', null); // admin can do all things in module Admin
        $this->allow('manager', 'Manager', null); // Manager can do all things in module Manager
        $this->allow('cadre', 'Cadre', null); // Cadre can do all things in module Cadre
    }
}
