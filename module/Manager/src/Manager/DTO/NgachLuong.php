<?php
namespace Manager\DTO;


class NgachLuong
{
    private $maSoNgach;
    private $kyhieu;
    private $tenNgach;

    public function maSoNgach($maSoNgach = null){
        if(is_null($maSoNgach)){
            return  $this->maSoNgach;
        }else{
            return $this->maSoNgach = $maSoNgach;
        }
    }

    public function kyhieu($kyhieu = null){

        if(null==$kyhieu){
            return  $this->kyhieu;
        }else{
            return $this->kyhieu = $kyhieu;
        }
    }

    public function tenNgach($tenNgach = null){
        if(null==$tenNgach){
            return  $this->tenNgach;
        }else{
            return $this->tenNgach = $tenNgach;
        }
    }

}