<?php
namespace Manager\DTO;


class DotDanhGia
{
    //basic
    private  $id;
    private $ngay_bat_dau;
    private $ngay_ket_thuc;    
    private $note;
    private $owner_id;

    //work
    private $status;

    public function id($id = null){
        if(is_null($id)){
            return  $this->id;
        }else{
            return $this->id = $id;
        }
    }
    public function ngay_bat_dau($ngay_bat_dau = null){
        if(is_null($ngay_bat_dau)){
            return  $this->ngay_bat_dau;
        }else{
            return $this->ngay_bat_dau = $ngay_bat_dau;
        }
    }
    public function ngay_ket_thuc($ngay_ket_thuc = null){
        if(is_null($ngay_ket_thuc)){
            return  $this->ngay_ket_thuc;
        }else{
            return $this->ngay_ket_thuc = $ngay_ket_thuc;
        }
    }
    public function note($note = null){
        if(is_null($note)){
            return  $this->note;
        }else{
            return $this->note = $note;
        }
    }
    public function owner_id($owner_id = null){
        if(is_null($owner_id)){
            return  $this->owner_id;
        }else{
            return $this->owner_id = $owner_id;
        }
    }
    public function status($status = null){
        if(is_null($status)){
            return  $this->status;
        }else{
            return $this->status = $status;
        }
    }
}