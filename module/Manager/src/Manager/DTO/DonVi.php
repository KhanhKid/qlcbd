<?php
namespace Manager\DTO;


class DonVi
{
    private $id;
    private $name;
    private $establishmentDate;
    private $task;
    private $address;
    private $email;
    private $phoneNumber;
    private $description;


    public function id($id = null){
        if(is_null($id)){
            return  $this->id;
        }else{
            return $this->id = $id;
        }
    }

    public function name($name = null){

        if(null==$name){
            return  $this->name;
        }else{
            return $this->name = $name;
        }
    }

    public function address($address = null){
        if(null==$address){
            return  $this->address;
        }else{
            return $this->address = $address;
        }
    }

    public function task($task = null){
        if(null==$task){
            return  $this->task;
        }else{
            return $this->task = $task;
        }
    }

    public function email($email = null){
        if(null==$email){
            return  $this->email;
        }else{
            return $this->email = $email;
        }
    }

    public function phoneNumber($phoneNumber = null){
        if(null==$phoneNumber){
            return  $this->phoneNumber;
        }else{
            return $this->phoneNumber = $phoneNumber;
        }
    }

    public function description($description = null){
        if(null==$description){
            return  $this->description;
        }else{
            return $this->description = $description;
        }
    }

    public function establishmentDate($establishmentDate = null){
        if(null==$establishmentDate){
            return  $this->establishmentDate;
        }else{
            return $this->establishmentDate = $establishmentDate;
        }
    }

}