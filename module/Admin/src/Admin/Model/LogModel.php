<?php
namespace Admin\Model;

use Zend\Db\Adapter\Adapter;

//exception
use Zend\Db\Adapter\Exception\InvalidQueryException;

class LogModel  extends  AbstractModel{

    /**
     * Lấy toàn bộ giá trị log
     * @return array|null
     */
    public function getAllLog()
    {
        $sql = 'select * from user_log';
        $data = $this->query($sql);
        return $data;
    }

    /**
     * Lấy danh sách log từ ngày $begin đến $end
     * Nếu trống thì lấy log trong ngày hiện tại
     * @param null $begin
     * @param null $end
     * @return array|null
     */
    public function getLog($begin = null, $end = null){
        if ($begin == null || $end == null){
            $sql = 'select * from user_log where date(Thoi_Gian) = :thoigian';
            $parameters = array(
                'thoigian'    => date('Y-m-d')
            );
            $data = $this->query($sql, $parameters);
            return $data;
        }
        else{
            $sql        = 'select * from user_log where date(Thoi_Gian) >= :begin and date(Thoi_Gian) <= :end';
            $parameters = array(
                'begin' => $begin,
                'end'   => $end
            );
            $data = $this->query($sql, $parameters);
            return $data;
        }
    }

    /**
     * Lấy danh sách log theo user, nếu user = true thì lấy theo user
     * @param $uid
     * @return array|null
     */
    public function getLogOfUser($uid)
    {
        $data = array();
        $sql = 'select * from user_log where Ma_User_Thuc_Hien = :id';
        $parameters = array(
            'id'    => $uid
        );
        $data = $this->query($sql, $parameters);
        return $data;
    }

    public function createNew($time, $user, $canbo=null, $chucnang, $noidung){

        $sql = 'insert into user_log (Thoi_Gian, Ma_User_Thuc_Hien, Ma_CB_Thuc_Hien, Chuc_Nang, Noi_Dung)
                          values (:thoigian, :userid, :macb, :chucnang, :noidung)';
        $parameters = array(
            'thoigian' => $time,
            'userid' => $user,
            'macb' => $canbo,
            'chucnang' => $chucnang,
            'noidung'  =>  $noidung
        );

        //var_dump($parameters);exit;


        $result = $this->executeNonQuery($sql,$parameters);

        return $result;
    }
}