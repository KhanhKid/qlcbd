<?php
namespace Manager\Model;

use Zend\Db\Adapter\Adapter;

//exception
use Zend\Db\Adapter\Exception\InvalidQueryException;

class DanhSachBanModel extends AbstractModel {

	
	public function getAllBriefInfo() {
		//chú ý chuyển đồi format của ngày tháng khi lấy thông tin lên
		$sql = "SELECT * FROM danh_sach_ban";

		$parameters = null;

		$result = null;
		try {
			$sm = $this->adapter->createStatement();
			$sm->prepare($sql);
			$result = $sm->execute(null);
		} catch (Exception $exc) {
			var_dump($exc);
		}

		//get data to array
		$data = array();
		while (($result->valid())) {
			$row    = $result->current();
			$data[] = $row;

			$result->next();
		};

		return $data;
	}
	public function insertPhongBan($data)
	{
        //save new
        $sql = "INSERT INTO danh_sach_ban (	ten_ban)
                           VALUES (:ten_ban);";
        $parameters = array(
            'ten_ban'=> $data['ten_ban']
        );
        //process database
        $sm = $this->adapter->createStatement($sql,$parameters);
        $sm->prepare();
        try{
            $sm->execute();
        } catch (Exception $exc){
            //throw exception
            var_dump($exc);
        }
	}
	public function deleteBan($maban) {
		//
		$sql = 'DELETE FROM `danh_sach_ban`
                    WHERE `id`=:id';

		$parameters = array(
			'id' => $maban
		);

		$result = $this->executeNonQuery($sql, $parameters);

		return $result;
	}

}