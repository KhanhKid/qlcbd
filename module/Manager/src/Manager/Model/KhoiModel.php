<?php
namespace Manager\Model;

use Zend\Db\Adapter\Adapter;

class KhoiModel extends  AbstractModel
{

    /**
     * @return array
     */
    public function getAllInfo()
    {
        $sql = 'select * from Khoi';
        $result = null;
        try{
            $sm = $this->adapter->createStatement();
            $sm->prepare($sql);
            $result = $sm->execute(null);
        } catch (Exception $exc){
            var_dump($exc);
        }

        //get data to array
        $data = array();
        while(($result->valid())) {
            $row = $result->current();
            $data[]= $row;

            $result->next();
        };



        return $data;
    }

    /**
     * Lấy danh sách các Cấp Khối, gồm các thông tin vấn tắt như Mã Cấp Khối, Tên Cấp Khối
     */
    public function getBriefInfo(){
        //init
        $sql = 'SELECT Ma_Khoi, Ten_Khoi FROM Khoi;';
        $parameters = null;

        //process database
        $result = null;
        try{
            $sm = $this->adapter->createStatement();
            $sm->prepare($sql);
            $result = $sm->execute($parameters);
        } catch (Exception $exc){
            var_dump($exc);
        }

        //data to array
        $data = array();
        while(($result->valid())) {
            $row = $result->current();
            $data[]= $row;

            $result->next();
        };

        return $data;
    }

    /**
     * lấy danh sách các đơn vị (đang còn hoạt động) thuộc một Khối
     * @param $maKhoi
     * @return array|null
     */
    public function getDonViThuoc($maKhoi){
         //sql
         $sql = 'SELECT Ma_ĐV, Ky_Hieu_ĐV, Ten_Đon_Vi, Ngay_Thanh_Lap
                FROM `đon_vi`
                WHERE `Ma_Khoi` = :maKhoi;
                  AND `Trang_Thai` = 1';

         //paras
         $parameters = array(
             'maKhoi'     =>  $maKhoi
         );

        //excute
        $data = $this->query($sql,$parameters);

        return $data;
    }

}