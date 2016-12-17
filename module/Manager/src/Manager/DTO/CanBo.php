<?php
namespace Manager\DTO;


class CanBo
{
    //basic
    private  $maCanBo;
    private $tenCanBo;
    private $gioiTinh;
    private $ngaySinh;
    private $ngayGiaNhap;

    //work
    private $maChucvuChinh;

    //
    public $thongTinLuong;

    public function maCanBo($maCanBo = null){
        if(is_null($maCanBo)){
            return  $this->maCanBo;
        }else{
            return $this->maCanBo = $maCanBo;
        }
    }

    public function tenCanBo($tenCanBo = null){

        if(null==$tenCanBo){
            return  $this->tenCanBo;
        }else{
            return $this->tenCanBo = $tenCanBo;
        }
    }

    public function ngaySinh($ngaySinh = null){
        if(null==$ngaySinh){
            return  $this->ngaySinh;
        }else{
            return $this->ngaySinh = $ngaySinh;
        }
    }

    public function ngayGiaNhap($ngayGiaNhap = null){
        if(null==$ngayGiaNhap){
            return  $this->ngayGiaNhap;
        }else{
            return $this->ngayGiaNhap = $ngayGiaNhap;
        }
    }

    public function machucvuChinh($maChucvuChinh = null){
        if(null==$maChucvuChinh){
            return  $this->maChucvuChinh;
        }else{
            return $this->maChucvuChinh = $maChucvuChinh;
        }
    }
}