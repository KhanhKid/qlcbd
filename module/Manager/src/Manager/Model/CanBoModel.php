<?php
namespace Manager\Model;

use Manager\DTO\CanBo;
use Manager\DTO\NgachLuong;
use Zend\Db\Adapter\Adapter;

//exception
use Zend\Db\Adapter\Exception\InvalidQueryException;

class CanBoModel extends AbstractModel {

	public function huyGiaNhapBan($Ma_Ban, $lydo = null) {
		// Delete before
		$sql        = 'DELETE FROM `thong_tin_tham_gia_ban` WHERE  `Ma_Ban` = :Ma_Ban ;';
		$parameters = array(
			'Ma_Ban' => $Ma_Ban,
		);

		return $this->executeNonQuery($sql, $parameters);
	} 
	public function giaNhapBan($Ma_Can_Bo, $Ma_Ban_Den, $Ngay_Gia_Nhap, $ma_chuc_vu_moi, $lydo = null) {

		// FormatDate
		$Ngay_Gia_Nhap = $this->formatDateForDB($Ngay_Gia_Nhap);

		// Add
		$sql0 = 'SELECT *
		FROM thong_tin_tham_gia_ban
		WHERE (Ma_CB = ' . $Ma_Can_Bo . ') AND (La_Cong_Tac_Chinh = 1)';

		//query
		$data = $this->query($sql0, null);
		if (isset($data[0])) {
			$sql = 'Insert Into thong_tin_tham_gia_ban (Ma_CB, Ma_Ban, Ngay_Gia_Nhap, Ma_CV, Ly_Do_Chuyen_Đen)
                                             VALUE (:Ma_Can_Bo, :Ma_Ban_Den, :Ngay_Gia_Nhap, :Ma_Chuc_Vu, :Ly_Do );';
			$parameters = array(
				'Ma_Can_Bo'     => $Ma_Can_Bo,
				'Ma_Ban_Den'    => $Ma_Ban_Den,
				'Ngay_Gia_Nhap' => $Ngay_Gia_Nhap,
				'Ma_Chuc_Vu'    => $ma_chuc_vu_moi,
				'Ly_Do'         => $lydo,
			);
		} else {
			$sql = 'Insert Into thong_tin_tham_gia_ban (Ma_CB, Ma_Ban, Ngay_Gia_Nhap, Ma_CV, Ly_Do_Chuyen_Đen, La_Cong_Tac_Chinh)
                                             VALUE (:Ma_Can_Bo, :Ma_Ban_Den, :Ngay_Gia_Nhap, :Ma_Chuc_Vu, :Ly_Do, :Cong_Tac_Chinh );';

			$parameters = array(
				'Ma_Can_Bo'      => $Ma_Can_Bo,
				'Ma_Ban_Den'     => $Ma_Ban_Den,
				'Ngay_Gia_Nhap'  => $Ngay_Gia_Nhap,
				'Ma_Chuc_Vu'     => $ma_chuc_vu_moi,
				'Ly_Do'          => $lydo,
				'Cong_Tac_Chinh' => 1,
			);
		}

		return $this->executeNonQuery($sql, $parameters);
	}

	/**
	 * Can bộ rời khỏi một Ban ( cập nhật ngày rời khỏi)
	 * @param $Ma_Can_Bo
	 * @param $Ma_Ban
	 * @param $Ngay_Gia_Nhap
	 * @param $Ngay_Roi_Khoi
	 * @return mixed|null
	 */
	public function roiKhoiBan($Ma_Can_Bo, $Ma_Ban, $Ngay_Gia_Nhap,
		$Ngay_Roi_Khoi) {
		//
		$Ngay_Gia_Nhap = $this->formatDateForDB($Ngay_Gia_Nhap);
		$Ngay_Roi_Khoi = $this->formatDateForDB($Ngay_Roi_Khoi);

		//
		$sql = "UPDATE thong_tin_tham_gia_ban SET Ngay_Roi_Khoi= :Ngay_Roi_Khoi, Trang_Thai=0
                WHERE `Ma_CB`= :Ma_Can_Bo AND `Ma_Ban` = :Ma_Ban AND `Ngay_Gia_Nhap` = :Ngay_Gia_Nhap;";

		$parameters = array(
			'Ma_Can_Bo'     => $Ma_Can_Bo,
			'Ma_Ban'        => $Ma_Ban,
			'Ngay_Gia_Nhap' => $Ngay_Gia_Nhap,

			'Ngay_Roi_Khoi' => $Ngay_Roi_Khoi,
		);

		return $this->executeNonQuery($sql, $parameters);
	}

	public function luanchuyen($Ma_Can_Bo, $Ma_Ban_Den, $Ngay_GN_Ban_Den, $ma_chuc_vu_moi, $lydo = null,
		$Ma_Ban_Di = null, $Ngay_GN_Ban_Di = null
	) {
		//formatDate
		$Ngay_GN_Ban_Den = $this->formatDateForDB($Ngay_GN_Ban_Den);
		$Ngay_GN_Ban_Di  = $this->formatDateForDB($Ngay_GN_Ban_Di);

		//query
		$sql = "SELECT *
        		FROM thong_tin_tham_gia_ban
        		WHERE (Ma_CB = $Ma_Can_Bo) AND (La_Cong_Tac_Chinh = 1)";
		$data = $this->query($sql, null);
		if (isset($data[0])) {
			$sql1 = 'Insert Into thong_tin_tham_gia_ban (Ma_CB, Ma_Ban, Ngay_Gia_Nhap, Ma_CV, Ly_Do_Chuyen_Đen, Ma_Ban_Truoc_Đo, Ngay_GN_Ban_Truoc_Đo)
                                             VALUE (:Ma_Can_Bo, :Ma_Ban_Den, :Ngay_Gia_Nhap, :Ma_Chuc_Vu, :Ly_Do, :Ma_Ban_TD, :Ngay_GN_TD );';

			$parameters = array(
				'Ma_Can_Bo'     => $Ma_Can_Bo,
				'Ma_Ban_Den'    => $Ma_Ban_Den,
				'Ngay_Gia_Nhap' => $Ngay_GN_Ban_Den,
				'Ma_Chuc_Vu'    => $ma_chuc_vu_moi,
				'Ly_Do'         => $lydo,

				'Ma_Ban_TD'     => $Ma_Ban_Di,
				'Ngay_GN_TD'    => $Ngay_GN_Ban_Di,
			);

		} else {
			$sql1 = 'Insert Into thong_tin_tham_gia_ban (Ma_CB, Ma_Ban, Ngay_Gia_Nhap, Ma_CV, Ly_Do_Chuyen_Đen, Ma_Ban_Truoc_Đo, Ngay_GN_Ban_Truoc_Đo, La_Cong_Tac_Chinh)
                                             VALUE (:Ma_Can_Bo, :Ma_Ban_Den, :Ngay_Gia_Nhap, :Ma_Chuc_Vu, :Ly_Do, :Ma_Ban_TD, :Ngay_GN_TD, :Cong_Tac_Chinh );';
			$parameters = array(
				'Ma_Can_Bo'      => $Ma_Can_Bo,
				'Ma_Ban_Den'     => $Ma_Ban_Den,
				'Ngay_Gia_Nhap'  => $Ngay_GN_Ban_Den,
				'Ma_Chuc_Vu'     => $ma_chuc_vu_moi,
				'Ly_Do'          => $lydo,

				'Ma_Ban_TD'      => $Ma_Ban_Di,
				'Ngay_GN_TD'     => $Ngay_GN_Ban_Di,
				'Cong_Tac_Chinh' => 1,
			);

		}

		//rời khỏi ban cũ
		$sql2 = "UPDATE thong_tin_tham_gia_ban SET Ngay_Roi_Khoi= :Ngay_Roi_Khoi, Trang_Thai=0
                WHERE `Ma_CB`= :Ma_Can_Bo AND `Ma_Ban` = :Ma_Ban_Roi_Khoi AND `Ngay_Gia_Nhap` = :Ngay_GN_Ban_Di;";

		$parameters += array(
			'Ma_Can_Bo'       => $Ma_Can_Bo,
			'Ma_Ban_Roi_Khoi' => $Ma_Ban_Di,
			'Ngay_GN_Ban_Di'  => $Ngay_GN_Ban_Di,

			'Ngay_Roi_Khoi'   => $Ngay_GN_Ban_Den,
		);

		$sql = $sql1 . $sql2;
		//var_dump($parameters);exit;

		return $this->executeNonQuery($sql, $parameters);
	}

	/**
	 * lấy danh sách cán bộ cùng thông tin lý lịch vắn tắt của cán bộ.  Cán bộ đang hoạt động.
	 * @return array of Canbo object
	 */
	/*public function getAllWorkInfo(){
	//chú ý chuyển đồi format của ngày tháng khi lấy thông tin lên
	$sql = "CALL `sp_getCadreWorkInfo`();";
	$parameters = null;


	//execute
	$result = null;
	try{
	$sm = $this->adapter->createStatement();
	$sm->prepare($sql);
	$result = $sm->execute(null);
	} catch (Exception $exc){
	var_dump($exc);
	}

	//fetch data to object list
	$list = array();
	while(($result->valid())) {
	$row = $result->current();


	//a row to Object
	$canbo = new CanBo();
	$canbo->maCanBo($row['Ma_Can_Bo']);
	$canbo->tenCanBo($row['Ho_Ten_CB']);
	$canbo->ngaySinh($row['Ngay_Sinh']);
	$canbo->maChucvuChinh($row['Ten_Chuc_Vu']);

	//add to array Object
	$list[] = $canbo;

	//next row
	$result->next();
	};





	return $list;
	}*/

	/**
	 * lấy danh sách cán bộ cùng thông tin công tác vắn tắt của cán bộ. Cán bộ đang hoạt động.
	 */
	public function getAllBriefInfo() {
		//chú ý chuyển đồi format của ngày tháng khi lấy thông tin lên
		$sql = "SELECT can_bo.Ma_Can_Bo, Ho_Ten_CB, DATE_FORMAT(Ngay_Sinh,'%d/%m/%Y') AS Ngay_Sinh, SO_CMND AS So_CMND
                from can_bo left join ly_lich on( can_bo.Ma_Can_Bo = ly_lich.Ma_CB)
                WHERE (Ngay_Roi_Khoi IS NULL) AND can_bo.DangHoatDong = 1;";

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

	/*
	 * L?y danh sách cán b? cùng thông tin công tác, làm vi?c (t?i ??n v? nào, ban nào, ch?c v? gì)
	 */
	public function getAllWorkInfo() {
		$sql = 'SELECT Ten_Đon_Vi, Ten_Ban, Ma_Can_Bo, Ho_Ten_CB, DATE_FORMAT(Ngay_Sinh,"%d/%m/%Y") AS Ngay_Sinh, So_CMND, chuc_vu.Ten_Chuc_Vu
                FROM can_bo ca LEFT JOIN ly_lich ly ON (ca.Ma_Can_Bo = ly.Ma_CB)
                            LEFT JOIN thong_tin_tham_gia_ban ON (thong_tin_tham_gia_ban.Ma_CB = ca.Ma_Can_Bo
                                                                    AND (thong_tin_tham_gia_ban.Ngay_Roi_Khoi IS NULL)
                                                                    AND(thong_tin_tham_gia_ban.La_Cong_Tac_Chinh = 1)

                                                                )
                            LEFT JOIN chuc_vu ON (thong_tin_tham_gia_ban.Ma_CV = chuc_vu.Ma_Chuc_Vu)
                            LEFT JOIN ban ON (thong_tin_tham_gia_ban.Ma_Ban = ban.Ma_Ban)
                            LEFT JOIN đon_vi ON (ban.Ma_Đon_Vi =  đon_vi.Ma_ĐV)
                WHERE  (ca.Ngay_Roi_Khoi IS NULL OR ca.Ngay_Roi_Khoi = "1970-01-01") AND (ca.Trang_Thai = 1) AND ca.DangHoatDong = 1
                GROUP BY Ma_Can_Bo';

		//query
		$data = $this->query($sql, null);

		return $data;
	}

	/**
	 * l?y danh sách cán b? ?ã ng?ng công tác
	 * @return array|null
	 */
	public function getDSCanBoNgungCongTac() {
		//chú ý chuy?n ??i format c?a ngày tháng khi l?y thông tin lên
		$sql = "SELECT can_bo.Ma_Can_Bo, Ho_Ten_CB, DATE_FORMAT(Ngay_Sinh,'%d/%m/%Y') AS Ngay_Sinh, SO_CMND AS So_CMND, Ngay_Roi_Khoi, Tham_Gia_CLBTT, Trang_Thai
                from can_bo left join ly_lich on( can_bo.Ma_Can_Bo = ly_lich.Ma_CB)
                WHERE (Ngay_Roi_Khoi IS NOT NULL) OR (Trang_Thai <> 1)";

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
		$data = $this->query($sql, $parameters);

		return $data;
	}

	/**
	 * Nh?p thông tin c? b?n c?a cán b?
	 * @param $hoten
	 * @param $ngaysinh
	 * @param $gioitinh
	 * @param $ngaygianhap
	 * @param $ngaytuyendung
	 * @param $ngaybienche
	 * @param $thongTinLuong
	 */
	public function nhapThongTin(
		$hoten, $ngaysinh, $gioitinh, $ngaygianhap, $ngaytuyendung, $ngaybienche,
		$thongTinLuong
	) {
		//format date
		$ngaysinh      = $this->formatDateForDB($ngaysinh);
		$ngaygianhap   = $this->formatDateForDB($ngaygianhap);
		$ngaytuyendung = $this->formatDateForDB($ngaytuyendung);
		$ngaybienche   = $this->formatDateForDB($ngaybienche);
		///
		$thongTinLuong[0]['Thoi_Gian_Nang_Luong'] = $this->formatDateForDB($thongTinLuong[0]['Thoi_Gian_Nang_Luong']);

		//
		$sql = 'Insert Into can_bo (Ho_Ten_CB, Ngay_Gia_Nhap,  Ngay_Tuyen_Dung, Ngay_Bien_Che ) VALUE (:Ho_Ten, :Ngay_Gia_Nhap, :ngaytuyendung, :ngaybienche);
                SELECT LAST_INSERT_ID() into @ma_cb_moi;
                Insert Into Qua_Trinh_Luong (Ma_CB, Thoi_Gian_Nang_Luong, Ma_So_Ngach, Bac_Luong) VALUEs (@ma_cb_moi, :ngaynangluong1, :masongach1, :bacluong1);
                Insert Into ly_lich (Ma_CB, Ngay_Sinh, Gioi_Tinh) VALUE (@ma_cb_moi, :ngaysinh, :Gioi_Tinh);';

		//Các Quá Trình L??ng

		$sqlQTLuong = 'Insert Into Qua_Trinh_Luong (Ma_CB, Thoi_Gian_Nang_Luong, Ma_So_Ngach, Bac_Luong) VALUEs (@ma_cb_moi, :ngaynangluong1, :masongach1, :bacluong1);';

		$parameters = array(
			'Ho_Ten'         => $hoten,
			'Ngay_Gia_Nhap'  => $ngaygianhap,
			//L??ng
			'ngaytuyendung'  => $ngaytuyendung,
			'ngaybienche'    => $ngaybienche,
			//qua trinh STT
			'ngaynangluong1' => $thongTinLuong[0]['Thoi_Gian_Nang_Luong'],
			'masongach1'     => $thongTinLuong[0]['Ma_So_Ngach'],
			'bacluong1'      => $thongTinLuong[0]['Bac_Luong'],
			//Lý L?ch
			'Gioi_Tinh'      => $gioitinh,
			'ngaysinh'       => $ngaysinh,
		);

		//
		try {
			$sm = $this->adapter->createStatement();
			$sm->prepare($sql);

			$sm->execute($parameters);
		} catch (Exception $exc) {
			var_dump($exc);
		}

	}

	/**
	 * get brief info of a brief
	 * @param null $id id of cadre
	 * @return array
	 */
	public function getBriefInfo($id = null) {
		$sql        = "CALL `sp_layThongTinCanBo`(:Ma_CB)";
		$parameters = array(
			'Ma_CB' => $id,
		);

		$result = null;
		try {
			$sm = $this->adapter->createStatement();
			$sm->prepare($sql);
			$result = $sm->execute($parameters);
		} catch (Exception $exc) {
			var_dump($exc);
		}

		//get data to array
		$data = $result->current();

		return $data;
	}

	/**
	 * thêm m?t cán b? m?i (các thông tin c? b?n) và tr? v? mã c?a cán b? ?ó (dùng ?? nh?p các thông tin  khác ti?p theo)
	 * @param $thongtin m?ng các thông tin c?a cán b? m?i
	 * @return string mã c?a cán b? v?a thêm
	 */
	public function themCanBo(&$thongtin) {
		//convert datatime format
		$thongtin['ngaygianhap']   = $this->formatDateForDB($thongtin['ngaybienche']);
		$thongtin['ngaytuyendung'] = $this->formatDateForDB($thongtin['ngaybienche']);
		$thongtin['ngaybienche']   = $this->formatDateForDB($thongtin['ngaybienche']);
		$thongtin['ngaysinh']      = $this->formatDateForDB($thongtin['ngaysinh']);

		//l??ng
		$thongtin['ngaynangbac'] = $this->formatDateForDB($thongtin['ngaynangbac']);

		$thongtin['ngaycapCMND'] = $this->formatDateForDB($thongtin['ngaycapCMND']);

		$thongtin['ngaythamgiacachmang']  = $this->formatDateForDB($thongtin['ngaythamgiacachmang']);
		$thongtin['ngaynhapngu']          = $this->formatDateForDB($thongtin['ngaynhapngu']);
		$thongtin['ngayxuatngu']          = $this->formatDateForDB($thongtin['ngayxuatngu']);
		$thongtin['ngayvaodang']          = $this->formatDateForDB($thongtin['ngayvaodang']);
		$thongtin['ngayvaodangchinhthuc'] = $this->formatDateForDB($thongtin['ngayvaodangchinhthuc']);

		//tham s? t?m th?i ch?a dùng
		//unset($thongtin['chucvuchinh']);
		//unset($thongtin['nhap']);

		//set parameter
		$parameters = $thongtin;
		/*$parameters = array(
		'chucvuchinh' => $thongtin['chucvuchinh'],
		'hoten' => $thongtin['hoten'],
		'ngaygianhap' => $thongtin['ngaygianhap'],
		'ngaytuyendung' => $thongtin['ngaytuyendung'],
		'ngaybienche' => $thongtin['ngaybienche'],

		'ngaynangbac' => $thongtin['ngaynangbac'],
		'mangach' => $thongtin['mangach'],
		'bacluong' => $thongtin['bacluong'],
		'vuotbac' => $thongtin['vuotbac'],
		'hesoluong' => $thongtin['hesoluong'],
		'phucap' => $thongtin['phucap'],
		'mucluongkhoan' => $thongtin['mucluongkhoan'],

		'ngaysinh' => $thongtin['ngaysinh'],
		'gioitinh' => $thongtin['gioitinh'],
		'cmnd' => $thongtin['cmnd'],
		'ngaycapCMND' => $thongtin['ngaycapCMND'],
		'noicapCMND' => $thongtin['noicapCMND'],
		'tengoikhac' => $thongtin['tengoikhac'],
		'noisinh' => $thongtin['noisinh'],
		'quequan' => $thongtin['quequan'],
		'noiohiennay' => $thongtin['noiohiennay'],
		'dienthoai' => $thongtin['dienthoai'],
		'dantoc' => $thongtin['dantoc'],
		'tongiao' => $thongtin['tongiao'],
		'thanhphangiadinh' => $thongtin['thanhphangiadinh'],
		'capuyhientai' => $thongtin['capuyhientai'],
		'capuykiem' => $thongtin['capuykiem'],
		'nghenghieptruocdo' => $thongtin['nghenghieptruocdo'],
		'coquantuyendung' => $thongtin['coquantuyendung'],
		'ngayvaodang' => $thongtin['ngayvaodang'],
		'ngayvaodangchinhthuc' => $thongtin['ngayvaodangchinhthuc'],
		'chuyennganh' => $thongtin['chuyennganh'],
		'hochamhocvi' => $thongtin['hochamhocvi'],
		'lyluanchinhtri' => $thongtin['lyluanchinhtri'],
		'trinhdohocvan' => $thongtin['trinhdohocvan'],
		'ngoaingu' => $thongtin['ngoaingu'],

		);*/

		//sql
		$sql = 'INSERT into can_bo (Ho_Ten_CB, Ngay_Gia_Nhap, Ngay_Tuyen_Dung, Ngay_Bien_Che)
                            VALUE (:hoten, :ngaygianhap, :ngaytuyendung, :ngaybienche);
                select LAST_INSERT_ID() into @ma_cb_moi;

                insert Into qua_trinh_luong (Ma_CB, Thoi_Gian_Nang_Luong, Ma_So_Ngach, Bac_Luong, He_So_Luong, Phu_Cap_Vuot_Khung, He_So_Phu_Cap, Muc_Luong_Khoang)
                            VALUEs (@ma_cb_moi, :ngaynangbac, :mangach, :bacluong, :hesoluong, :vuotkhung, :phucap, :mucluongkhoan);

                Insert Into ly_lich (Ma_CB, So_Hieu_CB, Ho_Ten_Khai_Sinh, Ngay_Sinh, Gioi_Tinh, So_CMND, Ngay_Cap_CMND, Noi_Cap_CMND, Ten_Goi_Khac,
                                     Noi_Sinh, Que_Quan, Noi_O_Hien_Nay, Đien_Thoai, Dan_Toc, Ton_Giao, Thanh_Phan_Gia_Đinh_Xuat_Than,
                                     Cap_Uy_Hien_Tai, Cap_Uy_Kiem, Chuc_Danh, Nghe_Nghiep_Truoc_Đo, Co_Quan_Tuyen_Dung, Ngay_Tham_Gia_CM, Ngay_Vao_Đang, Ngay_Chinh_Thuc, Ngay_Tham_Gia_Cac_To_Chuc_Chinh_Tri_Xa_Hoi,
                                     Ngay_Nhap_Ngu, Ngay_Xuat_Ngu, Quan_Ham_Chuc_Vu_Cao_Nhat,
                                     Trinh_Đo_Hoc_Van, Chuyen_Nganh, Hoc_Ham, Cap_Đo_TĐCM, Cap_Đo_CTLL,  Ngoai_Ngu,
                                     Danh_Hieu_Đuoc_Phong, So_Truong_Cong_Tac, Cong_Viec_Lam_Lau_Nhat,
                                     Tinh_Trang_Suc_Khoe, Chieu_Cao, Can_Nang, Nhom_Mau,
                                     Loai_Thuong_Binh, Gia_Đinh_Liet_Sy,
                                     Đac_Điem_Lich_Su, Lam_Viec_Trong_Che_Đo_Cu, Co_Than_Nhan_Nuoc_Ngoai, Tham_Gia_Cac_To_Chuc_Nuoc_Ngoai,
                                     Luong_Thu_Nhap_Nam, Nguon_Thu_Khac, Loai_Nha_Đuoc_Cap, Dien_Tich_Nha_Đuoc_Cap, Loai_Nha_Tu_Xay, Dien_Tich_Nha_Tu_Xay,
                                     Dien_Tich_Đat_O_Đuoc_Cap, Dien_Tich_Đat_O_Tu_Mua, Dien_Tich_Đat_San_Xuat)
                              VALUEs (@ma_cb_moi,:sohieu, :hoten, :ngaysinh, :gioitinh, :cmnd, :ngaycapCMND, :noicapCMND, :tengoikhac,
                               :noisinh, :quequan, :noiohiennay, :dienthoai, :dantoc, :tongiao, :thanhphangiadinh,
                               :capuyhientai, :capuykiem, :chucdanh, :nghenghieptruocdo, :coquantuyendung, :ngaythamgiacachmang, :ngayvaodang, :ngayvaodangchinhthuc, :ngaythamgiactxh,
                               :ngaynhapngu, :ngayxuatngu, :quanhamchucvu,
                               :giaoducphothong, :chuyenmon, :hocham, :trinhdochuyenmon, :lyluanchinhtri,  :ngoaingu,
                               :danhhieu, :sotruong, :congvieclaunhat,
                               :tinhtrangsuckhoe, :chieucao, :cannang, :nhommau,
                               :thuongbinhloai, :giadinhlietsy,
                               :dacdiemlichsu, :lamviecchedocu, :quanhenuocngoai, :thannhannuocngoai,
                               :luongnam, :nguonthunhapkhac, :loainhaduoccap, :dientichnhaduoccap, :loainhatuxay, :dientichnhatuxay,
                               :dientichdatduoccap, :dientichdattumua, :dientichdatsx);
                ';

		//process database
		$canboID = $this->executeNonQuery1($sql, $parameters);

		return $canboID;

	}

	public function bosungThongTin($Ma_CB,
		$Ho_Ten_CB, $Ngay_Gia_Nhap, $Ngay_Tuyen_Dung, $Ngay_Bien_Che, $Ngay_Roi_Khoi, $Trang_Thai, $Tham_Gia_CLBTT,$So_The_HoiVien,
		$So_Hieu_CB, $Ho_Ten_Khai_Sinh, $Ten_Goi_Khac, $Gioi_Tinh, $Cap_Uy_Hien_Tai, $Cap_Uy_Kiem, $Chuc_Danh, $Phu_Cap_Chuc_Vu,
		$Ngay_Sinh, $Noi_Sinh, $So_CMND, $Ngay_Cap_CMND, $Noi_Cap_CMND,
		$Que_Quan, $Noi_O_Hien_Nay, $Dan_Toc = null, $Ton_Giao = null, $Đien_Thoai, $Ngay_Tham_Gia_CM,
		$Thanh_Phan_Gia_Đinh_Xuat_Than, $Nghe_Nghiep_Truoc_Đo, $Ngay_Đuoc_Tuyen_Dung, $Co_Quan_Tuyen_Dung, $Đia_Chi_Co_Quan_Tuyen_Dung,
		$Ngay_Vao_Đang, $Ngay_Chinh_Thuc, $Ngay_Tham_Gia_Cac_To_Chuc_Chinh_Tri_Xa_Hoi,
		$Ngay_Nhap_Ngu, $Ngay_Xuat_Ngu, $Quan_Ham_Chuc_Vu_Cao_Nhat,
		$Trinh_Đo_Hoc_Van, $Hoc_Ham, $Cap_Đo_CTLL = null, $Cap_Đo_TĐCM = null, $Chuyen_Nganh, $Ngoai_Ngu,
		$Đac_Điem_Lich_Su, $Lam_Viec_Trong_Che_Đo_Cu, $Co_Than_Nhan_Nuoc_Ngoai, $Tham_Gia_Cac_To_Chuc_Nuoc_Ngoai,
		$Cong_Tac_Chinh_Đang_Lam, $Danh_Hieu_Đuoc_Phong, $So_Truong_Cong_Tac, $Cong_Viec_Lam_Lau_Nhat,
		$Khen_Thuong, $Ky_Luat, $Tinh_Trang_Suc_Khoe,$Tien_Su_Benh, $Chieu_Cao, $Can_Nang, $Nhom_Mau,
		$Loai_Thuong_Binh, $Gia_Đinh_Liet_Sy,
		$Luong_Thu_Nhap_Nam, $Nguon_Thu_Khac, $Loai_Nha_Đuoc_Cap, $Dien_Tich_Nha_Đuoc_Cap, $Loai_Nha_Tu_Xay, $Dien_Tich_Nha_Tu_Xay,
		$Dien_Tich_Đat_O_Đuoc_Cap, $Dien_Tich_Đat_O_Tu_Mua, $Dien_Tich_Đat_San_Xuat
	) {

		//var_dump($Ngay_Roi_Khoi);
		//format date
		$Ngay_Gia_Nhap   = $this->formatDateForDB($Ngay_Gia_Nhap);
		$Ngay_Tuyen_Dung = $this->formatDateForDB($Ngay_Tuyen_Dung);
		$Ngay_Bien_Che   = $this->formatDateForDB($Ngay_Bien_Che);
		$Ngay_Roi_Khoi   = $this->formatDateForDB($Ngay_Roi_Khoi);

		$Ngay_Sinh             = $this->formatDateForDB($Ngay_Sinh);
		$Ngay_Cap_CMND         = $this->formatDateForDB($Ngay_Cap_CMND);
		$Ngay_Tham_Gia_CM      = $this->formatDateForDB($Ngay_Tham_Gia_CM);
		$Ngay_Đuoc_Tuyen_Dung = $this->formatDateForDB($Ngay_Đuoc_Tuyen_Dung);
		$Ngay_Vao_Đang        = $this->formatDateForDB($Ngay_Vao_Đang);
		$Ngay_Chinh_Thuc       = $this->formatDateForDB($Ngay_Chinh_Thuc);
		$Ngay_Nhap_Ngu         = $this->formatDateForDB($Ngay_Nhap_Ngu);
		$Tham_Gia_CLBTT         = $this->formatDateForDB($Tham_Gia_CLBTT);

		//can_bo
		$sql1 = 'UPDATE `can_bo` SET `Ho_Ten_CB`=:Ho_Ten_CB,`Ngay_Gia_Nhap`= :Ngay_Gia_Nhap,
                `Ngay_Tuyen_Dung`= :Ngay_Tuyen_Dung,`Ngay_Bien_Che`= :Ngay_Bien_Che, `Ngay_Roi_Khoi`= :Ngay_Roi_Khoi,
                `Trang_Thai`= :Trang_Thai,`Tham_Gia_CLBTT`=:Tham_Gia_CLBTT,`So_The_HoiVien`=:So_The_HoiVien
                 WHERE `Ma_Can_Bo`= :Ma_CB;';

		//ly_lich
		$sql2 = 'UPDATE `ly_lich` SET `So_Hieu_CB`= :So_Hieu_CB,`Ho_Ten_Khai_Sinh`= :Ho_Ten_Khai_Sinh,`Ten_Goi_Khac`=:Ten_Goi_Khac,`Gioi_Tinh`= :Gioi_Tinh,
                                      `Cap_Uy_Hien_Tai`=:Cap_Uy_Hien_Tai,`Cap_Uy_Kiem`=:Cap_Uy_Kiem,`Chuc_Danh`=:Chuc_Danh,`Phu_Cap_Chuc_Vu`=:Phu_Cap_Chuc_Vu,
                                      `Ngay_Sinh`=:Ngay_Sinh,`Noi_Sinh`=:Noi_Sinh,`So_CMND`=:So_CMND,`Ngay_Cap_CMND`=:Ngay_Cap_CMND,`Noi_Cap_CMND`=:Noi_Cap_CMND,
                                      `Que_Quan`= :Que_Quan,`Noi_O_Hien_Nay`=:Noi_O_Hien_Nay,`Dan_Toc`=:Dan_Toc,`Ton_Giao`=:Ton_Giao,`Đien_Thoai`=:Dien_Thoai,
                                      `Thanh_Phan_Gia_Đinh_Xuat_Than`=:Thanh_Phan_Gia_Dinh_Xuat_Than,`Ngay_Tham_Gia_CM`=:Ngay_Tham_Gia_CM,
                                      `Nghe_Nghiep_Truoc_Đo`=:Nghe_Nghiep_Truoc_Do,`Ngay_Đuoc_Tuyen_Dung`=:Ngay_Duoc_Tuyen_Dung,`Co_Quan_Tuyen_Dung`=:Co_Quan_Tuyen_Dung,`Đia_Chi_Co_Quan_Tuyen_Dung`=:Dia_Chi_Co_Quan_Tuyen_Dung,
                                      `Ngay_Vao_Đang`=:Ngay_Vao_Dang,`Ngay_Chinh_Thuc`=:Ngay_Chinh_Thuc,`Ngay_Tham_Gia_Cac_To_Chuc_Chinh_Tri_Xa_Hoi`=:Ngay_Tham_Gia_Cac_To_Chuc_Chinh_Tri_Xa_Hoi,
                                      `Ngay_Nhap_Ngu`=:Ngay_Nhap_Ngu,`Ngay_Xuat_Ngu`=:Ngay_Xuat_Ngu,`Quan_Ham_Chuc_Vu_Cao_Nhat`=:Quan_Ham_Chuc_Vu_Cao_Nhat,
                                      `Trinh_Đo_Hoc_Van`=:Trinh_Do_Hoc_Van,`Hoc_Ham`=:Hoc_Ham,`Cap_Đo_CTLL`= :Cap_Do_CTLL,`Cap_Đo_TĐCM`=:Cap_Do_TDCM,`Chuyen_Nganh`=:Chuyen_Nganh,`Ngoai_Ngu`=:Ngoai_Ngu,
                                      `Đac_Điem_Lich_Su`=:Dac_Diem_Lich_Su,`Lam_Viec_Trong_Che_Đo_Cu`=:Lam_Viec_Trong_Che_Do_Cu,`Co_Than_Nhan_Nuoc_Ngoai`=:Co_Than_Nhan_Nuoc_Ngoai,`Tham_Gia_Cac_To_Chuc_Nuoc_Ngoai`=:Tham_Gia_Cac_To_Chuc_Nuoc_Ngoai,
                                      `Cong_Tac_Chinh_Đang_Lam`= :Cong_Tac_Chinh_Dang_Lam,`Danh_Hieu_Đuoc_Phong`=:Danh_Hieu_Duoc_Phong,`So_Truong_Cong_Tac`=:So_Truong_Cong_Tac,`Cong_Viec_Lam_Lau_Nhat`=:Cong_Viec_Lam_Lau_Nhat,
                                      `Khen_Thuong`=:Khen_Thuong,`Ky_Luat`=:Ky_Luat,`Tinh_Trang_Suc_Khoe`=:Tinh_Trang_Suc_Khoe,`Tien_Su_Benh`=:Tien_Su_Benh,`Chieu_Cao`=:Chieu_Cao,`Can_Nang`=:Can_Nang,`Nhom_Mau`=:Nhom_Mau,
                                      `Loai_Thuong_Binh`=:Loai_Thuong_Binh,`Gia_Đinh_Liet_Sy`=:Gia_Dinh_Liet_Sy,
                                      `Luong_Thu_Nhap_Nam`=:Luong_Thu_Nhap_Nam,`Nguon_Thu_Khac`=:Nguon_Thu_Khac,`Loai_Nha_Đuoc_Cap`=:Loai_Nha_Duoc_Cap,`Dien_Tich_Nha_Đuoc_Cap`=:Dien_Tich_Nha_Duoc_Cap,`Loai_Nha_Tu_Xay`=:Loai_Nha_Tu_Xay,`Dien_Tich_Nha_Tu_Xay`=:Dien_Tich_Nha_Tu_Xay,
                                      `Dien_Tich_Đat_O_Đuoc_Cap`= :Dien_Tich_Dat_O_Duoc_Cap,`Dien_Tich_Đat_O_Tu_Mua`= :Dien_Tich_Dat_O_Tu_Mua,`Dien_Tich_Đat_San_Xuat`= :Dien_Tich_Dat_San_Xuat
                 WHERE `Ma_CB`= :Ma_CB;';

		$parameters = array(
			//can bo
			'Ma_CB'                                      => $Ma_CB,
			'Ho_Ten_CB'                                  => $Ho_Ten_CB,
			'Ngay_Gia_Nhap'                              => $Ngay_Gia_Nhap,
			'Ngay_Tuyen_Dung'                            => $Ngay_Tuyen_Dung,
			'Ngay_Bien_Che'                              => $Ngay_Bien_Che,
			'Ngay_Roi_Khoi'                              => $Ngay_Roi_Khoi,
			'Trang_Thai'                                 => $Trang_Thai,
			'Tham_Gia_CLBTT'                             => $Tham_Gia_CLBTT,
			'So_The_HoiVien'                             => $So_The_HoiVien,

			//ly lich
			'So_Hieu_CB'                                 => $So_Hieu_CB,
			'Ho_Ten_Khai_Sinh'                           => $Ho_Ten_Khai_Sinh,
			'Ten_Goi_Khac'                               => $Ten_Goi_Khac,
			'Gioi_Tinh'                                  => $Gioi_Tinh,
			'Cap_Uy_Hien_Tai'                            => $Cap_Uy_Hien_Tai,
			'Cap_Uy_Kiem'                                => $Cap_Uy_Kiem,
			'Chuc_Danh'                                  => $Chuc_Danh,
			'Phu_Cap_Chuc_Vu'                            => $Phu_Cap_Chuc_Vu,
			'Ngay_Sinh'                                  => $Ngay_Sinh,
			'Noi_Sinh'                                   => $Noi_Sinh,
			'So_CMND'                                    => $So_CMND,
			'Ngay_Cap_CMND'                              => $Ngay_Cap_CMND,
			'Noi_Cap_CMND'                               => $Noi_Cap_CMND,
			'Que_Quan'                                   => $Que_Quan,
			'Noi_O_Hien_Nay'                             => $Noi_O_Hien_Nay,
			'Dan_Toc'                                    => $Dan_Toc,
			'Ton_Giao'                                   => $Ton_Giao,
			'Dien_Thoai'                                 => $Đien_Thoai,
			'Thanh_Phan_Gia_Dinh_Xuat_Than'              => $Thanh_Phan_Gia_Đinh_Xuat_Than,
			'Ngay_Tham_Gia_CM'                           => $Ngay_Tham_Gia_CM,
			'Nghe_Nghiep_Truoc_Do'                       => $Nghe_Nghiep_Truoc_Đo,
			'Ngay_Duoc_Tuyen_Dung'                       => $Ngay_Đuoc_Tuyen_Dung,
			'Co_Quan_Tuyen_Dung'                         => $Co_Quan_Tuyen_Dung,
			'Dia_Chi_Co_Quan_Tuyen_Dung'                 => $Đia_Chi_Co_Quan_Tuyen_Dung,
			'Ngay_Vao_Dang'                              => $Ngay_Vao_Đang,
			'Ngay_Chinh_Thuc'                            => $Ngay_Chinh_Thuc,
			'Ngay_Tham_Gia_Cac_To_Chuc_Chinh_Tri_Xa_Hoi' => $Ngay_Tham_Gia_Cac_To_Chuc_Chinh_Tri_Xa_Hoi,
			'Ngay_Nhap_Ngu'                              => $Ngay_Nhap_Ngu,
			'Ngay_Xuat_Ngu'                              => $Ngay_Xuat_Ngu,
			'Quan_Ham_Chuc_Vu_Cao_Nhat'                  => $Quan_Ham_Chuc_Vu_Cao_Nhat,
			'Trinh_Do_Hoc_Van'                           => $Trinh_Đo_Hoc_Van,
			'Hoc_Ham'                                    => $Hoc_Ham,
			'Cap_Do_CTLL'                                => $Cap_Đo_CTLL,
			'Cap_Do_TDCM'                                => $Cap_Đo_TĐCM,
			'Chuyen_Nganh'                               => $Chuyen_Nganh,
			'Ngoai_Ngu'                                  => $Ngoai_Ngu,
			'Dac_Diem_Lich_Su'                           => $Đac_Điem_Lich_Su,
			'Lam_Viec_Trong_Che_Do_Cu'                   => $Lam_Viec_Trong_Che_Đo_Cu,
			'Co_Than_Nhan_Nuoc_Ngoai'                    => $Co_Than_Nhan_Nuoc_Ngoai,
			'Tham_Gia_Cac_To_Chuc_Nuoc_Ngoai'            => $Tham_Gia_Cac_To_Chuc_Nuoc_Ngoai,
			'Cong_Tac_Chinh_Dang_Lam'                    => $Cong_Tac_Chinh_Đang_Lam,
			'Danh_Hieu_Duoc_Phong'                       => $Danh_Hieu_Đuoc_Phong,
			'So_Truong_Cong_Tac'                         => $So_Truong_Cong_Tac,
			'Cong_Viec_Lam_Lau_Nhat'                     => $Cong_Viec_Lam_Lau_Nhat,
			'Khen_Thuong'                                => $Khen_Thuong,
			'Ky_Luat'                                    => $Ky_Luat,
			'Tinh_Trang_Suc_Khoe'                        => $Tinh_Trang_Suc_Khoe,
			'Tien_Su_Benh'                        => $Tien_Su_Benh,
			'Chieu_Cao'                                  => $Chieu_Cao,
			'Can_Nang'                                   => $Can_Nang,
			'Nhom_Mau'                                   => $Nhom_Mau,
			'Loai_Thuong_Binh'                           => $Loai_Thuong_Binh,
			'Gia_Dinh_Liet_Sy'                           => $Gia_Đinh_Liet_Sy,
			'Luong_Thu_Nhap_Nam'                         => $Luong_Thu_Nhap_Nam,
			'Nguon_Thu_Khac'                             => $Nguon_Thu_Khac,
			'Loai_Nha_Duoc_Cap'                          => $Loai_Nha_Đuoc_Cap,
			'Dien_Tich_Nha_Duoc_Cap'                     => $Dien_Tich_Nha_Đuoc_Cap,
			'Loai_Nha_Tu_Xay'                            => $Loai_Nha_Tu_Xay,
			'Dien_Tich_Nha_Tu_Xay'                       => $Dien_Tich_Nha_Tu_Xay,
			'Dien_Tich_Dat_O_Duoc_Cap'                   => $Dien_Tich_Đat_O_Đuoc_Cap,
			'Dien_Tich_Dat_O_Tu_Mua'                     => $Dien_Tich_Đat_O_Tu_Mua,
			'Dien_Tich_Dat_San_Xuat'                     => $Dien_Tich_Đat_San_Xuat,
		);

		$sql = $sql1 . $sql2;
		//var_dump($Ngay_Roi_Khoi);
		//var_dump($parameters);exit;
		//echo ($sql);exit;

		$result = $this->executeNonQuery($sql, $parameters);

		return $result;
	}

	public function layMaCanBoMoiNhat() {
		//init
		$sql = 'SELECT Ma_Can_Bo FROM can_bo ORDER BY Ma_Can_Bo DESC limit 1';

		//process database
		$result = null;
		try {
			$sm = $this->adapter->createStatement();
			$sm->prepare($sql);
			$result = $sm->execute();
		} catch (Exception $exc) {
			var_dump($exc);
		}

		//data to array
		return $result->current();
	}

	/** L?y toàn b? lo?i ch?c v? hi?n có
	 * @return array
	 */
	public function getChucVuList() {
		$sql    = 'select Ma_Chuc_Vu, Ten_Chuc_Vu from chuc_vu';
		$result = null;

		//
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

	/**
	 * l?y danh sách ng?ch l??ng
	 * @return array
	 */
	public function getNgachList() {
		//init
		$sql       = 'select Ma_So_Ngach, Ten_Ngach from Ngach_Luong';
		$parameter = null;

		//run
		$result = null;
		try {
			$sm = $this->adapter->createStatement();
			$sm->prepare($sql);
			$result = $sm->execute($parameter);
		} catch (Exception $exc) {
			var_dump($exc);
		}

		//fetch data to object list
		$listObject = array();
		while (($result->valid())) {
			$row = $result->current();

			//a row to Object
			$object = new NgachLuong();
			$object->maSoNgach($row['Ma_So_Ngach']);
			$object->tenNgach($row['Ten_Ngach']);

			//add to array Object
			$listObject[] = $object;

			//next row
			$result->next();
		};

		return $listObject;
	}

	/**
	 * l?y danh sách các tôn giáo
	 * @return array|null
	 */
	public function getTonGiaoList() {
		$sql = 'SELECT Ma_Ton_Giao, Ten_Ton_Giao FROM ton_giao';

		//get data to array
		$data = $this->query($sql, null);

		return $data;
	}

	/**
	 * l?y danh sách các dân t?c
	 * @return array|null
	 */
	public function getDanTocList() {
		$sql = 'SELECT Ma_Dan_Toc, Ten_Dan_Toc FROM dan_toc';

		//get data to array
		$data = $this->query($sql, null);

		return $data;
	}

	public function getTrinhDoChuyenMonList() {
		$sql = 'SELECT `Cap_Đo_TĐCM`,`Ten_TĐCM`,`Ten_TĐCM` FROM `trinh_đo_chuyen_mon`;';

		//get data to array
		$data = $this->query($sql, null);

		return $data;
	}

	public function getTrinhDoLLCTList() {
		$sql = 'SELECT * FROM `trinh_đo_ly_luan_chinh_tri`;';

		//get data to array
		$data = $this->query($sql, null);

		return $data;
	}

	/**
	 * L?y các thông tin c? b?n v? lý l?ch 2C c?a cán b? (các thông tin nhi?u record không l?y ? ?ây)
	 * @param $maCanBo mã cán b? c?n l?y lý l?ch
	 * @return mixed
	 */
	public function getLyLichCanBo($maCanBo) {
		// (backup code)
		$sql = 'SELECT can_bo.Ma_Can_Bo, đon_vi.Ten_Đon_Vi, can_bo.Ho_Ten_CB,can_bo.Ma_Quan_Ly,can_bo.So_The_HoiVien, ly_lich.So_Hieu_CB, can_bo.Ngay_Tuyen_Dung, can_bo.Ngay_Bien_Che, can_bo.Ngay_Roi_Khoi, can_bo.Trang_Thai, can_bo.Tham_Gia_CLBTT,
                       ly_lich.Ho_Ten_Khai_Sinh, Ten_Goi_Khac, Gioi_Tinh,Cap_Uy_Hien_Tai, Cap_Uy_Kiem, cvchinh.Ten_Chuc_Vu as Chuc_Vu_Chinh, Chuc_Danh as Chuc_Danh, Phu_Cap_Chuc_Vu,
                       Ngay_Sinh, Noi_Sinh, Que_Quan,Noi_O_Hien_Nay, Đien_Thoai,
                       ly_lich.Ton_Giao AS Ma_Ton_Giao, ton_giao.Ten_Ton_Giao as Ton_Giao, ly_lich.Dan_Toc AS Ma_Dan_Toc, dan_toc.Ten_Dan_Toc as Dan_Toc,Thanh_Phan_Gia_Đinh_Xuat_Than,
                       Nghe_Nghiep_Truoc_Đo, Co_Quan_Tuyen_Dung, Đia_Chi_Co_Quan_Tuyen_Dung, Ngay_Đuoc_Tuyen_Dung,
                       can_bo.Ngay_Gia_Nhap, Ngay_Tham_Gia_CM,
                       Ngay_Vao_Đang, Ngay_Chinh_Thuc,
                       Ngay_Tham_Gia_Cac_To_Chuc_Chinh_Tri_Xa_Hoi,
                       Ngay_Nhap_Ngu, Ngay_Xuat_Ngu, Quan_Ham_Chuc_Vu_Cao_Nhat,
                       Trinh_Đo_Hoc_Van, ly_lich.Cap_Đo_TĐCM, trinh_đo_chuyen_mon.Ten_TĐCM as Trinh_Đo_Chuyen_Mon, Chuyen_Nganh, Hoc_Ham, ly_lich.Cap_Đo_CTLL, trinh_đo_ly_luan_chinh_tri.Ten_CTLL, Ngoai_Ngu,
                       Cong_Tac_Chinh_Đang_Lam, qua_trinh_luong.Thoi_Gian_Nang_Luong, qua_trinh_luong.Ma_So_Ngach, qua_trinh_luong.Bac_Luong, qua_trinh_luong.He_So_Luong, qua_trinh_luong.He_So_Phu_Cap, qua_trinh_luong.Muc_Luong_Khoang, qua_trinh_luong.Phu_Cap_Vuot_Khung,
                       Danh_Hieu_Đuoc_Phong, So_Truong_Cong_Tac, Cong_Viec_Lam_Lau_Nhat, Khen_Thuong, Ky_Luat, Khen_Thuong,
                       Tinh_Trang_Suc_Khoe, Tien_Su_Benh, Chieu_Cao, Can_Nang, Nhom_Mau, So_CMND , Ngay_Cap_CMND, Noi_Cap_CMND,
                       Loai_Thuong_Binh, Gia_Đinh_Liet_Sy, Đac_Điem_Lich_Su, Lam_Viec_Trong_Che_Đo_Cu,
                       Tham_Gia_Cac_To_Chuc_Nuoc_Ngoai, Co_Than_Nhan_Nuoc_Ngoai,
                       Luong_Thu_Nhap_Nam, Nguon_Thu_Khac,
                       Loai_Nha_Đuoc_Cap, Dien_Tich_Nha_Đuoc_Cap, Loai_Nha_Tu_Xay, Dien_Tich_Nha_Tu_Xay,
                       Dien_Tich_Đat_O_Đuoc_Cap, Dien_Tich_Đat_O_Tu_Mua, Dien_Tich_Đat_San_Xuat
                FROM can_bo LEFT JOIN ly_lich ON (can_bo.Ma_Can_Bo = ly_lich.Ma_CB)
                            LEFT JOIN chuc_vu as cvchinh ON (can_bo.Ma_CV_Chinh = cvchinh.Ma_Chuc_Vu)
                            LEFT JOIN dan_toc ON (ly_lich.Dan_Toc = dan_toc.Ma_Dan_Toc)
                            LEFT JOIN ton_giao ON (ly_lich.Ton_Giao = ton_giao.Ma_Ton_Giao)
                            LEFT JOIN trinh_đo_chuyen_mon ON (ly_lich.Cap_Đo_TĐCM = trinh_đo_chuyen_mon.Cap_Đo_TĐCM)
                            LEFT JOIN trinh_đo_ly_luan_chinh_tri ON (ly_lich.Cap_Đo_CTLL = trinh_đo_ly_luan_chinh_tri.Cap_Đo_LLCT)
                            LEFT JOIN qua_trinh_luong ON (can_bo.Ma_Can_Bo = qua_trinh_luong.Ma_CB AND qua_trinh_luong.thoi_gian_nang_luong >= (SELECT MAX(qtl2.Thoi_Gian_Nang_Luong)
                                                                                                                                                FROM qua_trinh_luong qtl2
                                                                                                                                                WHERE qtl2.Ma_CB = qua_trinh_luong.Ma_CB
                                                                                                                                               )
                                                           )
                            LEFT JOIN đon_vi ON (can_bo.Ma_ĐVCT_Chinh = đon_vi.Ma_ĐV)
                WHERE (can_bo.Ma_Can_Bo = :macb)

                LIMIT 1;';

		//sql (use procedure)
		//$sql = 'CALL sp_layThongTinLyLich(:macb)';

		//parameter
		$parameters = array(
			'macb' => $maCanBo,
		);

		//process database
		$result = null;
		try {
			$sm = $this->adapter->createStatement();
			$sm->prepare($sql);
			$result = $sm->execute($parameters);
		} catch (Exception $exc) {
			var_dump($exc);
		}

		//data to array
		return $result->current();
	}

	/**
	 * L?y các thông tin quá trình ?ào t?o b?i d??ng c?a cán b?
	 * @param $maCanBo
	 * @return array
	 */
	public function getDaoTaoBoiDuong($maCanBo) {
		//sql
		$sql = 'SELECT Ma_ĐTBD, Ten_Truong, Nganh_Hoc, Thoi_Gian_Hoc, TG_Ket_Thuc,Hinh_Thuc_Hoc, Van_Bang_Chung_Chi
                FROM đao_tao_boi_duong WHERE ma_cb = :macb;';

		//parameter
		$parameters = array(
			'macb' => $maCanBo,
		);

		//execute query
		$result = null;
		try {
			$sm = $this->adapter->createStatement();
			$sm->prepare($sql);
			$result = $sm->execute($parameters);
		} catch (Exception $exc) {
			var_dump($exc);
		}

		//if there are no row
		if (0 == $result->count()) {
			return null;
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

	/**
	 * L?y các thông tin ??c ?i?m l?ch s? c?a cán b?
	 * @param $maCanBo
	 * @return array
	 */
	public function getDacDiemLichSu($maCanBo) {
		//sql
		$sql = 'SELECT *
                FROM đac_điem_lich_su WHERE ma_cb = :macb;';

		//parameter
		$parameters = array(
			'macb' => $maCanBo,
		);

		//execute query
		$result = null;
		try {
			$sm = $this->adapter->createStatement();
			$sm->prepare($sql);
			$result = $sm->execute($parameters);
		} catch (Exception $exc) {
			var_dump($exc);
		}

		//if there are no row
		if (0 == $result->count()) {
			return null;
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

	/**
	 * L?y các thông tin quá trình công tác c?a cán b?
	 * @param $maCanBo
	 * @return array
	 */
	public function getQuaTrinhCongTac($maCanBo) {
		//sql
		/*$sql = 'SELECT Ma_QTCT, `Tu_Ngay`,`Đen_Ngay`,`So_Luoc`
		FROM qua_trinh_cong_tac WHERE ma_cb = :macb;';*/
		$sql = 'SELECT Ma_CB as Ma_QTCT, `Ngay_Gia_Nhap` as Tu_Ngay, `Ngay_Roi_Khoi` as Đen_Ngay, t2.Ten_Chuc_Vu as `So_Luoc`, t3.Ten_Ban as `Ban_Den`, t4.Ten_Ban as `Ban_Di`
        FROM thong_tin_tham_gia_ban as t1
        LEFT JOIN `chuc_vu` as t2 ON (t1.Ma_CV = t2.Ma_Chuc_Vu)
        LEFT JOIN `ban` as t3 ON (t1.Ma_Ban = t3.Ma_Ban)
        LEFT JOIN `ban` as t4 ON (t1.Ma_Ban_Truoc_Đo = t4.Ma_Ban)
        where t1.ma_cb = :macb;';
		//parameter
		$parameters = array(
			'macb' => $maCanBo,
		);

		//execute query
		$result = null;
		try {
			$sm = $this->adapter->createStatement();
			$sm->prepare($sql);
			$result = $sm->execute($parameters);
		} catch (Exception $exc) {
			var_dump($exc);
		}

		//if there are no row
		if (0 == $result->count()) {
			return null;
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

	public function deleteQuaTrinhCongTac($maCanbo, $stt) {
		//
		$sql = 'DELETE FROM `qua_trinh_cong_tac`
                    WHERE `Ma_CB`=:maCanbo AND `Ma_QTCT`=:stt;';

		$parameters = array(
			'maCanbo' => $maCanbo,
			'stt'     => $stt,
		);

		$result = $this->executeNonQuery($sql, $parameters);

		return $result;
	}

	public function deleteCongTacNuocNgoai($maCanbo, $stt) {
		//
		$sql = 'DELETE FROM `cong_tac_nuoc_ngoai`
                    WHERE `Ma_CB`=:maCanbo AND `Ma_CTNN`=:stt;';

		$parameters = array(
			'maCanbo' => $maCanbo,
			'stt'     => $stt,
		);

		$result = $this->executeNonQuery($sql, $parameters);

		return $result;
	}

	/**
	 * L?y các thông tin quá trình công tác c?a cán b?
	 * @param $maCanBo
	 * @param $benvo l?y danh sách bên v? hay b?n thân: 1-bên v?, 0-b?n thân
	 * @return array
	 */
	public function getQuanHeGiaDinh($maCanBo, $benvo) {
		//sql (CRAZY)
		$sql = 'SELECT Ma_Quan_He, Quan_He, `Ho_Ten`, `Nam_Sinh`, `Thong_Tin_So_Luoc`
                FROM thanh_vien_gia_đinh
                WHERE ma_cb = :macb
                 AND  Ben_Vo = :benvo ; ';

		//parameter
		$parameters = array(
			'macb'  => $maCanBo,
			'benvo' => $benvo,
		);

		//execute query
		$result = null;
		try {
			$sm = $this->adapter->createStatement();
			$sm->prepare($sql);
			$result = $sm->execute($parameters);
		} catch (Exception $exc) {
			var_dump($exc);
		}

		//if there are no row
		if (0 == $result->count()) {
			return null;
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

	public function deleteQuanHeGiaDinh($maCanbo, $stt) {
		//
		$sql = 'DELETE FROM `thanh_vien_gia_đinh`
                WHERE `Ma_CB`= :maCanbo AND `Ma_Quan_He`=:stt;';

		$parameters = array(
			'maCanbo' => $maCanbo,
			'stt'     => $stt,
		);

		//var_dump($parameters);exit;

		$result = $this->executeNonQuery($sql, $parameters);

		return $result;
	}

	/**
	 * L?y danh sách quá trình thay ??i l??ng c?a m?t các b?n
	 * @param $maCanBo
	 * @return array|null
	 */
	public function getQuaTrinhLuong($maCanBo) {
		//sql (CRAZY)
		$sql = 'SELECT *
                FROM qua_trinh_luong WHERE ma_cb = :macb
                ORDER BY Thoi_Gian_Nang_Luong;';

		//parameter
		$parameters = array(
			'macb' => $maCanBo,
		);

		//execute query
		$result = null;
		try {
			$sm = $this->adapter->createStatement();
			$sm->prepare($sql);
			$result = $sm->execute($parameters);
		} catch (Exception $exc) {
			var_dump($exc);
		}

		//if there are no row
		if (0 == $result->count()) {
			return null;
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

	public function deleteQuaTrinhLuong($maCanbo, $ngaynangluong) {

		//
		$ngaynangluong = $this->formatDateForDB($ngaynangluong);

		//
		$sql = 'DELETE FROM `qua_trinh_luong`
                WHERE `Ma_CB`= :maCanbo AND `Thoi_Gian_Nang_Luong` =:ngaynangluong;';

		$parameters = array(
			'maCanbo'       => $maCanbo,
			'ngaynangluong' => $ngaynangluong,
		);

		$result = $this->executeNonQuery($sql, $parameters);

		return $result;
	}

	/**
	 * l?y toàn b? danh danh sách v? các công tác n??c ngoài c?a cán b?
	 * @param $maCanBo mã c?a cán b? c?n l?y danh sách
	 * @return array|null
	 */
	public function getCongTacNuocNgoai($maCanBo) {
		//sql (CRAZY)
		$sql = 'SELECT *
                FROM cong_tac_nuoc_ngoai
                WHERE ma_cb = :macb
                ORDER BY Tu_Ngay;';

		//parameter
		$parameters = array(
			'macb' => $maCanBo,
		);

		//execute query
		$result = null;
		try {
			$sm = $this->adapter->createStatement();
			$sm->prepare($sql);
			$result = $sm->execute($parameters);
		} catch (Exception $exc) {
			var_dump($exc);
		}

		//if there are no row
		if (0 == $result->count()) {
			return null;
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

	public function themCongTacNuocNgoai($canboID,
		$ngaydi, $ngayve, $diadiem, $noidung, $capcudi, $kinhphi) {
		//
		//
		//
		//format date
		$ngaydi = $this->formatDateForDB($ngaydi);
		$ngayve = $this->formatDateForDB($ngayve);

		//query
		$sql = 'INSERT INTO cong_tac_nuoc_ngoai (`Ma_CB`, `Tu_Ngay`, `Đen_Ngay`, `Đia_Điem`, `Noi_Dung`, `Cap_Cu_Đi`, `Kinh_Phi`)
                                      VALUES (:maCB, :ngaydi, :ngayve, :diaiem, :noidung, :capcudi, :kinhphi);';

		//parameter
		$parameters = array(
			'maCB'    => $canboID,
			'ngaydi'  => $ngaydi,
			'ngayve'  => $ngayve,
			'diaiem'  => $diadiem,
			'noidung' => $noidung,
			'capcudi' => $capcudi,
			'kinhphi' => $kinhphi,
		);

		//execute query
		$result = $this->executeNonQuery($sql, $parameters);

		return $result;

	}

	//L?y thông tin trong b?ng can_bo
	public function getCanBo($maCanBo) {

	}

	/**
	 * tìm cán b? theo tên
	 * @param $ten tên cán b? c?n tìm
	 * @return array
	 */
	public function timTheoTen($ten) {
		$sql        = 'select * from ly_lich where Ho_Ten_Khai_Sinh LIKE :ten';
		$parameters = array(
			'ten' => '%' . $ten . '%',
		);
		//process database
		$result = null;
		try {
			$sm = $this->adapter->createStatement();
			$sm->prepare($sql);
			$result = $sm->execute($parameters);
		} catch (Exception $exc) {
			var_dump($exc);
		}

		$list = array();
		while (($result->valid())) {
			$row = $result->current();
			//a row to Object
			$list[] = $row;
			//next row
			$result->next();
		};
		return $list;

	}

	/**
	 * truy v?n theo l?nh sql ???c ??nh tr??c.
	 * C?N TH?N KHI DÙNG HÀM NÀY
	 * @param $sql câu l?nh truy v?n
	 * @return array
	 */
	public function truyvan($sql) {
		//process database
		$result = null;
		try {
			$sm = $this->adapter->createStatement();
			$sm->prepare($sql);
			$result = $sm->execute();
		} catch (Exception $exc) {
			var_dump($exc);
		}

		$list = array();
		while (($result->valid())) {
			$row = $result->current();
			//a row to Object
			$list[] = $row;
			//next row
			$result->next();
		};
		return $list;
	}

	/**
	 * l?y danh sách các m?c ?? hoàn thành
	 */
	public function getAllMucDoHoanThanh() {
		//chú ý chuy?n ??i format c?a ngày tháng khi l?y thông tin lên
		$sql = "select *
                from muc_đo_hoan_thanh;";
		$parameters = null;

		$result = null;
		try {
			$sm = $this->adapter->createStatement();
			$sm->prepare($sql);
			$result = $sm->execute($parameters);
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

	/**
	 * l?y danh sách các chiêu h??ng phát tri?n
	 */
	public function getAllChieuHuongPhatTrien() {
		//chú ý chuy?n ??i format c?a ngày tháng khi l?y thông tin lên
		$sql = "select *
                from chieu_huong_phat_trien;";
		$parameters = null;

		$result = null;
		try {
			$sm = $this->adapter->createStatement();
			$sm->prepare($sql);
			$result = $sm->execute($parameters);
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

	/**
	 * l?y danh sách thông tin l??ng c?a các cán b? hi?n t?i
	 */
	public function getAllSalaryInfo() {
		$sql = 'select Ma_CB, Thoi_Gian_Nang_Luong, He_So_Luong
                from qua_trinh_luong q1
                where Thoi_Gian_Nang_Luong >= (select max(thoi_gian_nang_luong)
                                               from qua_trinh_luong q2
                                               where q1.ma_cb=q2.ma_cb)

                                               ;';

		$parameters = null;

		$result = null;
		try {
			$sm = $this->adapter->createStatement();
			$sm->prepare($sql);
			$result = $sm->execute($parameters);
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

	/**
	 * l?y danh sách các cán b? (?ang ho?t ??ng)c?n nâng l??ng vào m?t th?i ?i?m
	 * @param $ngay th?i ?i?m c?n l?p danh sách
	 * @return array|null (Ho_Ten_CB, Ngay_Sinh, So_CMND) (Thoi_Gian_Nang_Luong, Ngach_Luong)
	 */
	public function getDSCanNangLuong($ngay = null, $sonam = 3) {
		//defauft $ngay is current date
		$ngay = (null == $ngay) ? date('Y-m-d') : $this->formatDateForDB($ngay);

		//query
		$sql = 'SELECT can_bo.Ma_Can_Bo AS Ma_CB, can_bo.Ho_Ten_CB, ly_lich.Ngay_Sinh, ly_lich.So_CMND,
                      Thoi_Gian_Nang_Luong, Ma_So_Ngach, Bac_Luong, He_So_Luong, Phu_Cap_Vuot_Khung, He_So_Phu_Cap, Muc_Luong_Khoang
                FROM can_bo LEFT JOIN ly_lich ON (can_bo.Ma_Can_Bo = ly_lich.Ma_CB)
                		  	LEFT JOIN qua_trinh_luong q1 ON (q1.Ma_CB = can_bo.Ma_Can_Bo
                                                             AND thoi_gian_nang_luong >= (select max(thoi_gian_nang_luong) from qua_trinh_luong q2 where q1.ma_cb=q2.ma_cb))
                WHERE can_bo.Trang_Thai = 1
                	AND (   (q1.thoi_gian_nang_luong <= DATE_ADD(:ngay_xet, INTERVAL -(:sonam) YEAR))
                          OR(NOT EXISTS(SELECT * FROM qua_trinh_luong q3 WHERE q3.Ma_CB = can_bo.Ma_Can_Bo))
                        )

                ORDER BY thoi_gian_nang_luong DESC';

		$parameters = array(
			'ngay_xet' => $ngay,
			'sonam'    => $sonam,
		);

		$data = $this->query($sql, $parameters);

		//var_dump($data);

		return $data;
	}

	public function nangLuong($canboID, $ngaynangluong, $mangach, $bacluong, $hesoluong,
		$vuotkhung, $hesophucap, $mucluongkhoan) {
		//
		//defauft $ngay is current date
		$ngaynangluong = $this->formatDateForDB($ngaynangluong);

		//query
		$sql = 'INSERT INTO qua_trinh_luong (`Ma_CB`, `Thoi_Gian_Nang_Luong`, `Ma_So_Ngach`, `Bac_Luong`, `He_So_Luong`, `Phu_Cap_Vuot_Khung`, `He_So_Phu_Cap`, `Muc_Luong_Khoang`)
                                      VALUES (:Ma_CB, :Thoi_Gian_Nang_Luong, :Ma_So_Ngach, :Bac_Luong, :He_So_Luong, :Phu_Cap_Vuot_Khung,:He_So_Phu_Cap, :Muc_Luong_Khoang);';

		//parameter
		$parameters = array(
			'Ma_CB'                => $canboID,
			'Thoi_Gian_Nang_Luong' => $ngaynangluong,
			'Ma_So_Ngach'          => $mangach,
			'Bac_Luong'            => $bacluong,
			'He_So_Luong'          => $hesoluong,
			'Phu_Cap_Vuot_Khung'   => $vuotkhung,
			'He_So_Phu_Cap'        => $hesophucap,
			'Muc_Luong_Khoang'     => $mucluongkhoan,
		);

		//var_dump($parameters);exit;

		//execute query
		$result = $this->executeNonQuery($sql, $parameters);

		return $result;
	}

	/**
	 * thêm m?t Tóm t?t quá trình công tác c?a cán b?
	 * @param $canboID
	 * @param $tungay
	 * @param $denngay
	 * @param $soluoc
	 * @return mixed|null
	 */
	public function themQuaTrinhCongTac($canboID, $tungay, $denngay, $soluoc) {

		//format date to database
		$tungay  = $this->formatDateForDB($tungay);
		$denngay = $this->formatDateForDB($denngay);

		//query
		$sql = 'INSERT INTO `qua_trinh_cong_tac`(`Ma_CB`, `Tu_Ngay`, `Đen_Ngay`, `So_Luoc`)
                                         VALUES (:maCB,:tungay,:denngay,:soluoc);';

		//parameter
		$parameters = array(
			'maCB'    => $canboID,
			'tungay'  => $tungay,
			'denngay' => $denngay,
			'soluoc'  => $soluoc,
		);

		//execute query
		$result = $this->executeNonQuery($sql, $parameters);

		return $result;
	}

	/**
	 * @param $canboID
	 * @param $tentruong
	 * @param $nganhhoc
	 * @param $tungay
	 * @param $denngay
	 * @param $hinhthuc
	 * @param $vanbang
	 * @return mixed|null
	 */
	public function themDaoTaoBoiDuong($canboID, $tentruong, $nganhhoc, $tungay, $denngay, $hinhthuc, $vanbang) {

		//format date to database
		//query
		$sql = 'INSERT INTO `đao_tao_boi_duong`(`Ma_CB`, `Ten_Truong`, `Nganh_Hoc`, `Thoi_Gian_Hoc`, `TG_Ket_Thuc`, `Hinh_Thuc_Hoc`, `Van_Bang_Chung_Chi`)
                                        VALUES (:maCB, :tentruong, :nganhhoc, :tungay, :denngay, :hinhthuc, :vanbang);';

		//parameter
		$parameters = array(
			'maCB'      => $canboID,
			'tentruong' => $tentruong,
			'nganhhoc'  => $nganhhoc,
			'tungay'    => $tungay,
			'denngay'   => $denngay,
			'hinhthuc'  => $hinhthuc,
			'vanbang'   => $vanbang,
		);

		//execute query
		$result = $this->executeNonQuery($sql, $parameters);

		return $result;
	}

	public function deleteDaoTaoBoiDuong($maCanbo, $madtbd) {
		//
		$sql = 'DELETE FROM `đao_tao_boi_duong`
                    WHERE `Ma_CB`=:maCanbo AND `Ma_ĐTBD`=:madtbd;';

		$parameters = array(
			'maCanbo' => $maCanbo,
			'madtbd'  => $madtbd,
		);

		$result = $this->executeNonQuery($sql, $parameters);

		return $result;
	}

	public function themKyLuat($canboID, $ngayQD, $hinhthuc, $noiraQD, $lydo) {
		//format date to database
		$ngayQD = $this->formatDateForDB($ngayQD);

		//query
		$sql = 'INSERT INTO `ky_luat`(`Ma_CB`, `Ngay_Quyet_Đinh`, `Hinh_Thuc`, `Cap_Ra_Quyet_Đinh`, `Ly_Do_Ky_Luat`)
                              VALUES (:maCB, :ngayQD, :hinhthuc, :noiraQD, :lydo);';

		//parameter
		$parameters = array(
			'maCB'     => $canboID,
			'ngayQD'   => $ngayQD,
			'noiraQD'  => $noiraQD,
			'hinhthuc' => $hinhthuc,
			'lydo'     => $lydo,
		);

		//execute query
		$result = $this->executeNonQuery($sql, $parameters);

		return $result;
	}

	public function themKhenThuong($canboID, $ngayQD, $hinhthuc, $noiraQD, $lydo) {
		//format date to database
		$ngayQD = $this->formatDateForDB($ngayQD);

		//query
		$sql = 'INSERT INTO `khen_thuong`(`Ma_CB`, `Ngay_Quyet_Đinh`, `Hinh_Thuc`, `Cap_Ra_Quyet_Đinh`, `Ly_Do`)
                              VALUES (:maCB, :ngayQD, :hinhthuc, :noiraQD, :lydo);';

		//parameter
		$parameters = array(
			'maCB'     => $canboID,
			'ngayQD'   => $ngayQD,
			'noiraQD'  => $noiraQD,
			'hinhthuc' => $hinhthuc,
			'lydo'     => $lydo,
		);

		//execute query
		$result = $this->executeNonQuery($sql, $parameters);

		return $result;
	}

	public function themQuanHeGiaDinh($canboID, $benvo, $quanhe, $hoten, $ngaysinh, $thongtin) {
		//format date to database
		$ngaysinh = $this->formatDateForDB($ngaysinh);

		//query
		$sql = 'INSERT INTO `thanh_vien_gia_đinh`(`Ma_CB`, `Ben_Vo`, `Quan_He`, `Ho_Ten`, `Nam_Sinh`, `Thong_Tin_So_Luoc`)
                                        VALUES (:maCB, :benvo, :quanhe, :hoten, :ngaysinh, :thongtin);';

		//parameter
		$parameters = array(
			'maCB'     => $canboID,
			'benvo'    => $benvo,
			'quanhe'   => $quanhe,
			'hoten'    => $hoten,
			'ngaysinh' => $ngaysinh,
			'thongtin' => $thongtin,
		);

		//execute query
		$result = $this->executeNonQuery($sql, $parameters);

		return $result;
	}

	/**
	 * thêm m?t cán b? m?i
	 * @param $thongtin m?ng các thông tin c?a cán b? m?i
	 */
	public function themDanhGia($canbo_id,$dot_danh_gia, $noi_dung_danh_gia, $ma_xep_loai_finish, $luu_y,$manager_id) {
		//parameter
		$parameters = array(
			'canbo_id'                  => $canbo_id,
			'dot_danh_gia'                  => $dot_danh_gia,
			'noi_dung_danh_gia'         => $noi_dung_danh_gia,
			'ma_xep_loai_finish'        => $ma_xep_loai_finish,
			'luu_y'        				=> $luu_y,
			'manager_id'        				=> $manager_id,
		);
		$sqlCheckExist = "SELECT * FROM `đanh_gia_can_bo` WHERE canbo_id = $canbo_id and dot_danh_gia = $dot_danh_gia ";

		$checkExist = $this->query($sqlCheckExist);
		if($checkExist[0]){
			//parameter
			$parameters = array(
				'noi_dung_danh_gia'         => $noi_dung_danh_gia,
				'ma_xep_loai_finish'        => $ma_xep_loai_finish,
				'luu_y'        				=> $luu_y,
				'manager_id'        				=> $manager_id,
			);
			$sql = "UPDATE `đanh_gia_can_bo` SET `noi_dung_danh_gia` = :noi_dung_danh_gia, `luu_y` = :luu_y,`ma_xep_loai_finish` = :ma_xep_loai_finish, `manager_id` = :manager_id WHERE `canbo_id` = $canbo_id and `dot_danh_gia`	 = $dot_danh_gia;";
		}else{
			//parameter
			$parameters = array(
				'canbo_id'                  => $canbo_id,
				'dot_danh_gia'                  => $dot_danh_gia,
				'noi_dung_danh_gia'         => $noi_dung_danh_gia,
				'ma_xep_loai_finish'        => $ma_xep_loai_finish,
				'luu_y'        				=> $luu_y,
				'manager_id'        				=> $manager_id,
			);
			$sql = 'INSERT INTO `đanh_gia_can_bo` (`canbo_id`,`dot_danh_gia`, `noi_dung_danh_gia`, `ma_xep_loai_finish`, `luu_y`,`manager_id`) VALUES (:canbo_id,:dot_danh_gia,:noi_dung_danh_gia,:ma_xep_loai_finish, :luu_y,:manager_id)';
		}
		//sql
		

		//execute query
		$result = $this->executeNonQuery($sql, $parameters);
		return $result;

	}

	// get danh gia ton tai
	public function getDanhGia($canbo_id,$dot_danh_gia) {
		$sql = "SELECT * FROM `đanh_gia_can_bo` WHERE canbo_id = $canbo_id and dot_danh_gia = $dot_danh_gia ";
		$result = $this->query($sql);
		return $result;

	}

	/**
	 * l?y danh sách cán b? Ban Th??ng Tr?c Thành ?oàn
	 * @return array
	 */
	public function getDSCanBoThuongTrucThanhDoan() {
		//init
		$sql = 'SELECT Ma_CB
                FROM thong_tin_tham_gia_ban
                WHERE Ma_Ban IN (SELECT Ma_Ban_Chap_Hanh FROM đon_vi WHERE Ma_ĐV = 0)
                     AND Ngay_Roi_Khoi IS NULL;';

		$parameters = null;

		//process database
		$result = $this->query($sql, $parameters);

		//data to array
		return $result;
	}

	/**
	 * l?y danh sách cán b? c?p d??i tr?c thu?c (c?a Tr??ng/Phó Ban)
	 * @param $maCB
	 * @return array|null danh sách cán b? c?p d??i c?a cán b? hi?n t?i (t?i t?t c? các b? ph?n công tác)
	 */
	public function getDSCanBoTrucThuoc($maCB) {
		//init
		$sql = 'SELECT t1.Ma_CB AS Ma_Can_Bo, Ho_Ten_CB, Ngay_Sinh, SO_CMND AS So_CMND
                FROM thong_tin_tham_gia_ban t1 LEFT JOIN can_bo ON (t1.Ma_CB = can_bo.Ma_Can_Bo)
                                               LEFT JOIN ly_lich ON (can_bo.Ma_Can_Bo = ly_lich.Ma_CB)
                WHERE t1.Ngay_Roi_Khoi IS NULL
                      AND Ma_Ban  = (SELECT t2.Ma_Ban
                                     FROM thong_tin_tham_gia_ban t2
                                     WHERE t2.Ma_CB = :maCB
                                         AND t2.Ngay_Roi_Khoi IS NULL
                                         AND t2.Ma_CV IN (SELECT Ma_Chuc_Vu FROM chuc_vu WHERE Ma_Cap IN (1,2,6,7))
                                    );';

		$parameters = array(
			'maCB' => $maCB,
		);

		//process database
		$result = $this->query($sql, $parameters);

		//data to array
		return $result;
	}

	/**
	 * ki?m tra cán b? có ph?i là cán b? Ban Ch?p Hành Chuyên Trách Thành ?oàn
	 * @param $maCanBo
	 * @return true|false
	 */
	public function isCBThuongTrucTD($maCanBo) {

		//sql (CRAZY)
		$sql = 'SELECT `f_isCBThuongVuTD`(:macb) AS Result';

		//parameter
		$parameters = array(
			'macb' => $maCanBo,
		);

		//execute query
		$result = $this->query($sql, $parameters);

		//get data (first line)
		$data = $result[0]['Result'];

		//to boolean
		$data = ($data == 1) ? true : false;

		return $data;
	}

	/**
	 * Ki?m tra cán b? có là tr??ng/phó ban
	 * @param $maCanBo
	 * @return bool
	 */
	public function isTruongPhoBan($maCanBo) {

		//sql (CRAZY)
		$sql = 'SELECT `f_isTruongPhoBan`(:macb) AS Result';

		//parameter
		$parameters = array(
			'macb' => $maCanBo,
		);

		//execute query
		//process database
		$result = null;
		try {
			$sm = $this->adapter->createStatement();
			$sm->prepare($sql);
			$result = $sm->execute($parameters);
		} catch (Exception $exc) {
			var_dump($exc);
		}

		//get data (first line)
		$data = $result->current()['Result'];

		//to boolean
		$data = ($data == 1) ? true : false;

		return $data;
	}

	/**
	 * l?y danh sách toàn b? quá trình luân chuy?n c?a cán b?
	 * @param $maCanBo
	 * @return array|null
	 */
	public function getQuaTrinhLuanChuyen($maCanBo) {
		//sql (CRAZY)
		$sql = 'SELECT thong_tin_tham_gia_ban.Ngay_Gia_Nhap, ban.Ma_Ban, ban.Ten_Ban, đon_vi.Ten_Đon_Vi, chuc_vu.Ten_Chuc_Vu, thong_tin_tham_gia_ban.La_Cong_Tac_Chinh, thong_tin_tham_gia_ban.Ngay_Roi_Khoi,
                      ban_truocdo.Ma_Ban AS Ma_Ban_TD, ban_truocdo.Ten_Ban AS Ten_Ban_TD, dv_truocdo.Ten_Đon_Vi AS Ten_Don_Vi_TD, cv_truocdo.Ten_Chuc_Vu AS Ten_Chuc_Vu_TD
                FROM thong_tin_tham_gia_ban LEFT JOIN ban ON (thong_tin_tham_gia_ban.Ma_Ban = ban.Ma_Ban)
                    LEFT JOIN đon_vi ON (ban.Ma_Đon_Vi = đon_vi.Ma_ĐV)
                    LEFT JOIN chuc_vu ON (thong_tin_tham_gia_ban.Ma_CV = chuc_vu.Ma_Chuc_Vu)

                    LEFT JOIN thong_tin_tham_gia_ban tttgb_truocdo ON (thong_tin_tham_gia_ban.Ma_CB = tttgb_truocdo.Ma_CB
                                                                        AND thong_tin_tham_gia_ban.Ma_Ban_Truoc_Đo = tttgb_truocdo.Ma_Ban
                                                                        AND thong_tin_tham_gia_ban.Ngay_GN_Ban_Truoc_Đo =  tttgb_truocdo.Ngay_Gia_Nhap)
                    LEFT JOIN ban ban_truocdo ON (tttgb_truocdo.Ma_Ban = ban_truocdo.Ma_Ban)
                    LEFT JOIN đon_vi dv_truocdo ON (ban_truocdo.Ma_Đon_Vi = dv_truocdo.Ma_ĐV)
                    LEFT JOIN chuc_vu cv_truocdo ON (tttgb_truocdo.Ma_CV = cv_truocdo.Ma_Chuc_Vu)

                WHERE thong_tin_tham_gia_ban.`Ma_CB` = :macb
                      AND thong_tin_tham_gia_ban.Trang_Thai = 0;;';

		//parameter
		$parameters = array(
			'macb' => $maCanBo,
		);

		//execute query
		$data = $this->query($sql, $parameters);

		//process when "no row"
		if (!$data[0]) {
			$data = null;
		}

		return $data;
	}

	/**
	 * lấy danh sách toàn bộ công tác của cán bộ
	 * @param $maCanBo
	 * @return array|null
	 */
	public function getCongTacHienTai($maCanBo) {
		//sql (CRAZY)
		$sql = 'SELECT thong_tin_tham_gia_ban.Ngay_Gia_Nhap, ban.Ma_Ban, ban.Ten_Ban, đon_vi.Ten_Đon_Vi, chuc_vu.Ten_Chuc_Vu, thong_tin_tham_gia_ban.La_Cong_Tac_Chinh, thong_tin_tham_gia_ban.Ngay_Roi_Khoi,
                      ban_truocdo.Ma_Ban AS Ma_Ban_TD, ban_truocdo.Ten_Ban AS Ten_Ban_TD, dv_truocdo.Ten_Đon_Vi AS Ten_Don_Vi_TD, cv_truocdo.Ten_Chuc_Vu AS Ten_Chuc_Vu_TD
                FROM thong_tin_tham_gia_ban LEFT JOIN ban ON (thong_tin_tham_gia_ban.Ma_Ban = ban.Ma_Ban)
                    LEFT JOIN đon_vi ON (ban.Ma_Đon_Vi = đon_vi.Ma_ĐV)
                    LEFT JOIN chuc_vu ON (thong_tin_tham_gia_ban.Ma_CV = chuc_vu.Ma_Chuc_Vu)

                    LEFT JOIN thong_tin_tham_gia_ban tttgb_truocdo ON (thong_tin_tham_gia_ban.Ma_CB = tttgb_truocdo.Ma_CB
                                                                        AND thong_tin_tham_gia_ban.Ma_Ban_Truoc_Đo = tttgb_truocdo.Ma_Ban
                                                                        AND thong_tin_tham_gia_ban.Ngay_GN_Ban_Truoc_Đo =  tttgb_truocdo.Ngay_Gia_Nhap)
                    LEFT JOIN ban ban_truocdo ON (tttgb_truocdo.Ma_Ban = ban_truocdo.Ma_Ban)
                    LEFT JOIN đon_vi dv_truocdo ON (ban_truocdo.Ma_Đon_Vi = dv_truocdo.Ma_ĐV)
                    LEFT JOIN chuc_vu cv_truocdo ON (tttgb_truocdo.Ma_CV = cv_truocdo.Ma_Chuc_Vu)

                WHERE thong_tin_tham_gia_ban.`Ma_CB` = :macb
                      AND thong_tin_tham_gia_ban.Trang_Thai = 1;';

		//parameter
		$parameters = array(
			'macb' => $maCanBo,
		);

		//execute query
		$data = $this->query($sql, $parameters);

		//process when "no row"
		if (!$data[0]) {
			$data = null;
		}

		return $data;
	}

	public function deleteQuaTrinhLuanChuyen($maCanBo, $maBan, $ngayChuyen) {
		//
		$ngayChuyen = $this->formatDateForDB($ngayChuyen);

		//
		$sql = "DELETE FROM `qlcbd`.`thong_tin_tham_gia_ban`
                WHERE `Ma_CB` = :Ma_CB AND `Ma_Ban` = :Ma_Ban AND `Ngay_Gia_Nhap` = :Ngay_Gia_Nhap";

		$parameters = array(
			'Ma_CB'         => $maCanBo,
			'Ma_Ban'        => $maBan,
			'Ngay_Gia_Nhap' => $ngayChuyen,
		);

		//var_dump($parameters);exit;

		$result = $this->executeNonQuery($sql, $parameters);

		return $result;
	}

	public function getDSKienNghi($trangthai) {
		//sql (CRAZY)
		$sql = 'SELECT `Thoi_Gian`, Ma_Can_Bo, `Ho_Ten_CB` AS Ten_CB_Kien_Nghi , So_CMND, `Ten_Kien_Nghi`, `Noi_Dung`, `File_URL`, `kien_nghi`.`Trang_Thai`
                FROM `kien_nghi` LEFT JOIN can_bo ON (kien_nghi.Ma_CB_Kien_Nghi = can_bo.Ma_Can_Bo)
                                 LEFT JOIN ly_lich ON(can_bo.Ma_Can_Bo = ly_lich.Ma_CB)
                WHERE `kien_nghi`.`Trang_Thai` = :trangthai';

		//parameter
		$parameters = array(
			'trangthai' => $trangthai,
		);

		//execute query
		$data = $this->query($sql, $parameters);

		//var_dump($data);exit;

		return $data;
	}

	public function getDSKienNghiTheoNgay($begin = null, $end = null, $trangthai = 1) {

		$sql = 'SELECT `Thoi_Gian`, Ma_Can_Bo, `Ho_Ten_CB` AS Ten_CB_Kien_Nghi , So_CMND, `Ten_Kien_Nghi`, `Noi_Dung`, `File_URL`, `kien_nghi`.`Trang_Thai`
                FROM `kien_nghi` LEFT JOIN can_bo ON (kien_nghi.Ma_CB_Kien_Nghi = can_bo.Ma_Can_Bo)
                                 LEFT JOIN ly_lich ON(can_bo.Ma_Can_Bo = ly_lich.Ma_CB)
                WHERE `kien_nghi`.`Trang_Thai` = :trangthai AND date(Thoi_Gian) >= :begin and date(Thoi_Gian) <= :end';

		if ($begin == null || $end == null) {
			//parameter
			$parameters = array(
				'trangthai' => $trangthai,
				'begin'     => date('Y-m-d'),
				'end'       => date('Y-m-d'),
			);
		} else {
			//parameter
			$parameters = array(
				'trangthai' => $trangthai,
				'begin'     => $begin,
				'end'       => $end,
			);
		}

		//execute query
		$data = $this->query($sql, $parameters);

		//var_dump($data);exit;

		return $data;
	}

	public function getKienNghi($maCB, $time) {
		$sql        = 'SELECT * FROM `kien_nghi` WHERE Thoi_Gian = :thoigian AND Ma_CB_Kien_Nghi = :macb';
		$parameters = array(
			'thoigian' => $time,
			'macb'     => $maCB,
		);
		//execute query
		$data = $this->query($sql, $parameters);

		//var_dump($data);exit;

		return $data;
	}

	/**
	 * l?y s? l??ng ki?n nghi ch?a ???c gi?i quy?t
	 * @return string s? l??ng ki?n ngh?
	 */
	public function getSoLuongKienNghi() {
		$sql = 'SELECT COUNT(*) AS counter FROM `kien_nghi` WHERE `Trang_Thai`=1;';

		$data = $this->query($sql);

		$value = $data;

		return $value;
	}

	public function guiKienNghi($canboID, $curr_time = null, $tuade, $noidung, $file) {
		//get current time
		$curr_time = (null == $curr_time) ? date('Y-m-d H:i:s') : $curr_time;

		//var_dump($curr_time);exit;

		//query
		$sql = 'INSERT INTO kien_nghi (Thoi_Gian, `Ma_CB_Kien_Nghi`, `Ten_Kien_nghi`, `Noi_Dung`, `File_URL`)
                                      VALUES (:curr_time, :maCB, :tuade, :noidung, :file);';

		//parameter
		$parameters = array(
			'maCB'      => $canboID,
			'curr_time' => $curr_time,
			'tuade'     => $tuade,
			'noidung'   => $noidung,
			'file'      => $file,
		);

		//execute query
		try {
			$result = $this->executeNonQuery($sql, $parameters);
		} catch (InvalidQueryException $exc) {
			throw $exc;
		}

		return $result;
	}

	public function giaiquyetKienNghi($Ma_Can_Bo, $Thoi_Gian,
		$Trang_Thai = 1) {
		$sql = "UPDATE `kien_nghi` SET `Trang_Thai`= :Trang_Thai
                WHERE `Thoi_Gian` = :Thoi_Gian
                    AND`Ma_CB_Kien_Nghi` = :Ma_Can_Bo;";

		$parameters = array(
			'Ma_Can_Bo'  => $Ma_Can_Bo,
			'Thoi_Gian'  => $Thoi_Gian,

			'Trang_Thai' => $Trang_Thai,
		);

		$result = $this->executeNonQuery($sql, $parameters);

		return $result;
	}

	public function deleteKienNghi($Ma_Can_Bo, $Thoi_Gian) {
		$sql = "DELETE FROM `qlcbd`.`kien_nghi`
                WHERE `kien_nghi`.`Thoi_Gian` = :Thoi_Gian AND `kien_nghi`.`Ma_CB_Kien_Nghi` = :Ma_Can_Bo";

		$parameters = array(
			'Ma_Can_Bo' => $Ma_Can_Bo,
			'Thoi_Gian' => $Thoi_Gian,
		);

		//var_dump($parameters);exit;

		$result = $this->executeNonQuery($sql, $parameters);

		return $result;
	}

	/**
	 * lấy url của file đính kèm Kiến Nghị
	 * @param $Ma_Can_Bo
	 * @param $Thoi_Gian
	 *
	 * @return string
	 */
	public function getfileKienNghi($Thoi_Gian, $Ma_Can_Bo) {
		$sql = "SELECT `File_URL` FROM `kien_nghi`
                WHERE `Thoi_Gian` = :Thoi_Gian AND `Ma_CB_Kien_Nghi` = :Ma_Can_Bo
                LIMIT 1";

		$parameters = array(
			'Ma_Can_Bo' => $Ma_Can_Bo,
			'Thoi_Gian' => $Thoi_Gian,
		);

		//get data to array
		$data = $this->query($sql, $parameters);

		$value = $data[0]['File_URL'];

		return $value;
	}

	public function deleteCanbo($userID) {
		$sql = "SELECT `Identifier_Info` FROM `user`
                WHERE `UserID` = :User_ID
                LIMIT 1";

		$parameters = array(
			'User_ID' => $userID,
		);

		$data = $this->query($sql, $parameters);

		if (isset($data[0]['Identifier_Info'])) {
			$sql = "UPDATE `can_bo` SET `DangHoatDong` = 0
                    WHERE `Ma_Can_Bo` = :Ma_Can_Bo ";

			$parametersa = array(
				'Ma_Can_Bo' => $data[0]['Identifier_Info'],
			);

			$this->executeNonQuery($sql, $parametersa);

			return true;
		}

		return false;
	}
}