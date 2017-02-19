<?php
namespace Admin\Model;

use Zend\Db\Adapter\Adapter;

//exception
use Zend\Db\Adapter\Exception\InvalidQueryException;

class UserModel  extends  AbstractModel
{
    public function fetchAll()
    {
        $sql = 'SELECT * FROM user LEFT JOIN can_bo ON (user.Identifier_Info = canbo.Ma_Can_Bo) WHERE can_bo.DangHoatDong = 1;';
        $result = null;
        try{
            $sm = $this->adapter->createStatement($sql,null);
            $result = $sm->execute();
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

    public function getAllUserCanBoInfo(){
        $sql = 'SELECT UserID, Username, user.Role_Name, Role_Display_Name, Ma_Can_Bo, Ho_Ten_CB, Ngay_Sinh, So_CMND
                FROM user LEFT JOIN role ON (role.Role_Name = user.Role_Name)
                          LEFT JOIN can_bo ON (user.Identifier_Info = can_bo.Ma_Can_Bo)
                          LEFT JOIN ly_lich ON (can_bo.Ma_Can_Bo = ly_lich.Ma_CB)
                WHERE can_bo.DangHoatDong = 1;';

        //excute
        $result = null;
        try{
            $sm = $this->adapter->createStatement($sql,null);
            $result = $sm->execute();
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

    public function getInfo($userID){
        $sql = 'SELECT `Username`, user.Role_Name, `Role_Display_Name` , `Identifier_Info`, `Status_Code`, Ho_Ten_CB, Ngay_Sinh, So_CMND
                FROM `user` LEFT JOIN role ON (role.Role_Name = user.Role_Name)
                            LEFT JOIN can_bo ON (`user`.Identifier_Info = can_bo.Ma_Can_Bo)
                            LEFT JOIN ly_lich ON (ly_lich.Ma_CB = can_bo.Ma_Can_Bo)
                WHERE `UserID` = :userID
                LIMIT 1;';

        $parameters = array(
            'userID' => $userID
        );

        //get data to array
        $data = $this->query($sql,$parameters);
        $value = $data[0];


        return $value;
    }

    public function getRoleList(){
        $sql = 'select Role_Name,Role_Display_Name from role';
        $result = null;
        try{
            $sm = $this->adapter->createStatement($sql,null);
            $result = $sm->execute();
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

    public function createNew($username, $password, $role=null, $identifierInfo=null){
        //encode
        //echo $password;
        $passwordKey = date('d/m/Y h:m:s:ms', time());
        $password = md5(md5($password.$passwordKey));




        $sql = 'insert into user (Username, Password, Password_Key, Role_Name, Identifier_Info)
                          values (:username, :password, :passwordKey, :role, :identifierInfo)';
        $parameters = array(
            'username' => $username,
            'password' => $password,
            'passwordKey' => $passwordKey,
            'role' => $role,
            'identifierInfo'  =>  $identifierInfo
        );

        //var_dump($parameters);exit;


        $result = $this->executeNonQuery($sql,$parameters);

        return $result;
    }

    public function changeRole($userId, $role){
        $sql = 'UPDATE `qlcbd`.`user` SET `Role_Name` = :role
                WHERE `UserID` = :userID;';

        $parameters = array(
            'userId'    => $userId,
            'role'      => $role,
        );

        $result = $this->executeNonQuery($sql,$parameters);

        return $result;
    }

    public function editInfo($userId,
                             $username, $role = null, $identifierInfo=null,
                             $status = 1
    ){
        $sql = 'UPDATE `qlcbd`.`user` SET Username = :username,
                                          Role_Name = :role,
                                          Identifier_Info=:identifierInfo,
                                          Status_Code = :status
                WHERE `UserID` = :userId;';

        $parameters = array(
            'userId'    => $userId,
            'username' => $username,
            'role'      => $role,
            'identifierInfo' =>    $identifierInfo,
            'status'    => $status
        );

        //var_dump($parameters);exit;

        $result = $this->executeNonQuery($sql,$parameters);

        return $result;
    }


    public function delete($userId){
        $sql = 'DELETE FROM `user` WHERE `UserID` = "userId';

        $parameters = array(
            'userId'    => $userId,
        );

        $result = $this->executeNonQuery($sql,$parameters);

        return $result;
    }

    public function changePassword($userid = null, $password = null){

        //encode
        $passwordKey = date('d/m/Y h:m:s:ms', time());
        $password = md5(md5($password.$passwordKey));

        //echo $password; exit;


        $sql = 'UPDATE user SET Password = :password, Password_Key = :passwordKey
                WHERE UserID = :userid';
        $parameters = array(
            'userid'        => $userid,
            'password'      => $password,
            'passwordKey'   => $passwordKey
        );



        $result = null;
        try{
            $sm = $this->adapter->createStatement($sql,$parameters);
            $result = $sm->execute();
        } catch (Exception $exc){
            var_dump($exc);
        }


        //var_dump($result);exit;

        return $this->executeNonQuery($sql,$parameters);

    }
}