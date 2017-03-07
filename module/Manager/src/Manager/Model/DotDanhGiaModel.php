<?php
namespace Manager\Model;

use Manager\DTO\DotDanhGia;
use Zend\Db\Adapter\Adapter;

//exception
use Zend\Db\Adapter\Exception\InvalidQueryException;

class DotDanhGiaModel extends AbstractModel {

	public function checkDanhGia() {
		$currentDay = date("Y-m-d");
		// Delete before
		$sql        = 'SELECT * FROM `dot_danh_gia` WHERE  `ngay_bat_dau`<="'.$currentDay.'" and `ngay_ket_thuc`>="'.$currentDay.'" and status =1;';
		return $this->query($sql);
	}
	public function getAllDot() {
		// Delete before
		$sql = 'SELECT * FROM `dot_danh_gia`';
		return $this->query($sql);
	}

	public function ThemNgayDanhGia($name, $Ngay_Bat_Dau, $Ngay_Ket_Thuc, $Note, $Owner_ID) {

		// FormatDate
		$Ngay_Bat_Dau = $this->formatDateForDB($Ngay_Bat_Dau);
		$Ngay_Ket_Thuc = $this->formatDateForDB($Ngay_Ket_Thuc);

		$sql = 'Insert Into dot_danh_gia (name,ngay_bat_dau, ngay_ket_thuc, note, owner_id)
                                             VALUE (:name,:Ngay_Bat_Dau, :Ngay_Ket_Thuc, :Note, :Owner_ID);';
		$parameters = array(
			'name'     => $name,
			'Ngay_Bat_Dau'     => $Ngay_Bat_Dau,
			'Ngay_Ket_Thuc'    => $Ngay_Ket_Thuc,
			'Note' => $Note,
			'Owner_ID'    => $Owner_ID,
		);
		return $this->executeNonQuery($sql, $parameters);
	}
}