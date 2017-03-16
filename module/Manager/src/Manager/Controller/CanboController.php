<?php
//Khai bao namespace
namespace Manager\Controller;

//Load lớp AbstractActionController vào CONTROLLER
use Manager\Model\CanBoModel;
use Zend\Authentication\AuthenticationService;
use Zend\Db\Adapter\Exception\InvalidQueryException;

//Load lớp ViewModel vào CONTROLLER
use Zend\Mvc\Controller\AbstractActionController;

//auth

//dump
use Zend\View\Model\ViewModel;

class CanboController extends AbstractActionController {
	public $canboModel;
	public $banModel;
	public $logModel;
	public $DotDanhGiaModel;

	public function __contructor() {
		//$this->canboModel = new CanBoModel();

	}

	public function indexAction() {

		$helper     = $this->getServiceLocator()->get('viewhelpermanager');
		$headScript = $helper->get('headscript');
		$headScript->appendFile('/template/js/combobox.js');

		//init view
		$this->layout('layout/home');
		$view                    = array(); //to view
		$view['soluongKienNghi'] = $this->canboModel->getSoLuongKienNghi()[0]['counter']; //load "danh sach Cán Bộ" from database, với thông tin công tác

		$view['dsCanBo']         = $this->canboModel->getAllBriefInfo(); //load "danh sach Cán Bộ" from database
		
		return new ViewModel($view);
	}

	public function thongtinAction() {
		$this->layout('layout/home');
		$helper = $this->getServiceLocator()->get('viewhelpermanager');

		$headScript = $helper->get('headscript');
		$headScript->appendFile('/script/datatable/media/js/jquery.dataTables.js');
		$headScript->appendFile('/script/datatable/extras/TableTools/media/js/ZeroClipboard.js');
		$headScript->appendFile('/script/datatable/extras/TableTools/media/js/TableTools.js');
		$headScript->appendFile('/script/datatable/extras/ColReorder/media/js/ColReorder.js');

		//to view
		$view['dsCanBo'] = $this->canboModel->getAllWorkInfo(); //load "danh sach Cán Bộ" from database, với thông tin công tác

		return new ViewModel($view);
	}

	public function danhgiaAction() {
		//init view
		$helper     = $this->getServiceLocator()->get('viewhelpermanager');
		$headScript = $helper->get('headscript');
		$headScript->appendFile('/script/ckeditor/ckeditor.js');

		//get current CanBoID
		$auth   = (new AuthenticationService());
		$idCBNX = $auth->getIdentity()->Identifier_Info;

		//get parameter from request
		$curId = $this->params('id');
		$curId = (!is_null($curId)) ? $curId : $idCBNX; //from GET or from Auth

		//get info from model
		$view['canbo_tdg'] = $this->canboModel->getBriefInfo($curId);
		$view['canbo_tdgID'] = $curId;
        $DotDanhGiaModel = $this->getServiceLocator()->get('Manager\Model\DotDanhGiaModel');
		$view['listDot'] = $DotDanhGiaModel->getAllDot();
		$view['message']   = ''; //nothing
	
		//process request: when save this "đánh giá"
		if ($this->getRequest()->isPost()) {
			$parameters = $this->getRequest()->getPost();
			//save "đánh giá"
			$maCB = ('' == $parameters['id']) ? $curId : $parameters['id'];
				if ('' == $parameters['id_nx']) {
					$parameters['id_nx'] = 0;
			}
			if ($maCB != null) {
				$this->canboModel->themDanhGia(
					$maCB,$parameters['dot_danh_gia'], $parameters['noi_dung_danh_gia'],NULL, $parameters['mdht_tdg'],  $parameters['luu_y'],$idCBNX);
				$view['message'] = 'Lưu đánh giá thành công';
				//log
				$logModel = $this->getServiceLocator()->get('Admin\Model\LogModel');
				$userID   = (new \Zend\Authentication\AuthenticationService)->getIdentity()->UserID;
				$cbID     = (new \Zend\Authentication\AuthenticationService)->getIdentity()->Identifier_Info;
				$noidung  = 'Thêm đánh giá cán bộ: ' . $view['canbo_tdg']['Ho_Ten_CB'];
				$logModel->createNew(date('Y-m-d H:i:s'), $userID, $cbID, 'Thêm đánh giá', $noidung);
			} else {
				$view['message'] = 'không xác định cán bộ tự đánh giá';
			}

		}
		//view-model
		// $view['canbo_nx']            = $this->canboModel->getBriefInfo($idCBNX);
		$view['mucdohoanthanh']      = $this->canboModel->getAllMucDoHoanThanh();
		// $view['chieuhuongphattrien'] = $this->canboModel->getAllChieuHuongPhatTrien();

		// $view['dsDonVi'] = $this->donviModel->getBriefInfoList(); //load "danh sach Don Vi" from database
		// $view['dsBan']   = $this->banModel->getDSBanHoatDong();
		//$view['chieuhuongphattrien']    = $this->banModel->getDSBanThuoc($maDonvi);

		return $view;
	}
	public function danhgianamAction() {
		//load from model
		$danhgia = $this->canboModel->getDanhGia((int)$_POST['canbo_tdgID'],(int)$_POST['dotdanhgia']);
		if(!is_null($danhgia[0]['ma_xep_loai_finish']) && $danhgia[0]['ma_xep_loai_finish'] > 0){
			$result = array("xeploai" => $danhgia[0]['ma_xep_loai_finish']);
		}else{
			$result = array("xeploai" => $danhgia[0]['ma_tu_xep_loai']);
		}
		//to View
		echo json_encode($result);

		//do not view
		return $this->response;
	}

	/**
	 * Thêm mới 1 cán bộ
	 */
	public function themAction() {
		//init model
		//$canboModel = $this->getServiceLocator()->get('Manager\Model\CanBoModel');
		$userModel = $this->getServiceLocator()->get('Admin\Model\UserModel');

		//init view
		$this->layout('layout/home'); //set layout

		$view['message'] = '';
		//Khi Có Yêu cầu nhập mới
		if ($this->getRequest()->isPost()) {
			$parameters = $this->getRequest()->getPost();

			//get data to model
			$thongtin = $parameters->toArray();
			$password = $parameters['ngaysinh'];

			//Checkbox "giadinhlietsy"
			$thongtin['giadinhlietsy'] = (isset($thongtin['giadinhlietsy'])) ? '1' : '0';

			//Tóm tắt quá trình công tác
			if (isset($thongtin['congtac-tu'])) {
				//////lấy giá trị
				$dsQuaTrinhCongTac['tungay']  = $thongtin['congtac-tu'];
				$dsQuaTrinhCongTac['denngay'] = $thongtin['congtac-den'];
				$dsQuaTrinhCongTac['soluoc']  = $thongtin['congtac-thongtin'];
				//////không dùng nữa
				unset($thongtin['congtac-tu']);
				unset($thongtin['congtac-den']);
				unset($thongtin['congtac-thongtin']);
			}
			//Đào tạo bồi dưỡng
			if (isset($thongtin['daotao-tentruong'])) {
				//////lấy giá trị
				$dsDaoTaoBoiDuong['tentruong'] = $thongtin['daotao-tentruong'];
				$dsDaoTaoBoiDuong['nganhhoc']  = $thongtin['daotao-nganhhoc'];
				$dsDaoTaoBoiDuong['tungay']    = $thongtin['daotao-thoigian'];
				$dsDaoTaoBoiDuong['denngay']   = $thongtin['daotao-tgketthuc'];
				$dsDaoTaoBoiDuong['hinhthuc']  = $thongtin['daotao-hinhthuc'];
				$dsDaoTaoBoiDuong['vanbang']   = $thongtin['daotao-vanbang'];
				//////không dùng nữa
				unset($thongtin['daotao-tentruong']);
				unset($thongtin['daotao-nganhhoc']);
				unset($thongtin['daotao-thoigian']);
				unset($thongtin['daotao-tgketthuc']);
				unset($thongtin['daotao-hinhthuc']);
				unset($thongtin['daotao-vanbang']);
			}
			//Kỹ luật
			if (isset($thongtin['kyluat-thoigian'])) {
				//////lấy giá trị
				$dsKyLuat['thoigian']     = $thongtin['kyluat-thoigian'];
				$dsKyLuat['hinhthuc']     = $thongtin['kyluat-hinhthuc'];
				$dsKyLuat['noiquyetdinh'] = $thongtin['kyluat-noiquyetdinh'];
				$dsKyLuat['lydo']         = $thongtin['kyluat-lydo'];
				//////không dùng nữa
				unset($thongtin['kyluat-thoigian']);
				unset($thongtin['kyluat-hinhthuc']);
				unset($thongtin['kyluat-noiquyetdinh']);
				unset($thongtin['kyluat-lydo']);
			}
			//Khen thưởng
			if (isset($thongtin['khenthuong-thoigian'])) {
				//////lấy giá trị
				$dsKhenThuong['thoigian']     = $thongtin['khenthuong-thoigian'];
				$dsKhenThuong['hinhthuc']     = $thongtin['khenthuong-hinhthuc'];
				$dsKhenThuong['noiquyetdinh'] = $thongtin['khenthuong-noiquyetdinh'];
				$dsKhenThuong['lydo']         = $thongtin['khenthuong-lydo'];
				//////không dùng nữa
				unset($thongtin['khenthuong-thoigian']);
				unset($thongtin['khenthuong-hinhthuc']);
				unset($thongtin['khenthuong-noiquyetdinh']);
				unset($thongtin['khenthuong-lydo']);
			}
			//Quan hệ gia đình
			if (isset($thongtin['qh-quanhe'])) {
				//////lấy giá trị
				$dsQuanHeGiaDinh['quanhe']   = $thongtin['qh-quanhe'];
				$dsQuanHeGiaDinh['hoten']    = $thongtin['qh-hoten'];
				$dsQuanHeGiaDinh['namsinh']  = $thongtin['qh-namsinh'];
				$dsQuanHeGiaDinh['thongtin'] = $thongtin['qh-thongtin'];
				//////không dùng nữa
				unset($thongtin['qh-quanhe']);
				unset($thongtin['qh-hoten']);
				unset($thongtin['qh-namsinh']);
				unset($thongtin['qh-thongtin']);
			}
			//Quan hệ gia đình bên vợ
			if (isset($thongtin['qhvo-quanhe'])) {
				//////lấy giá trị
				$dsQuanHeGiaDinhVo['quanhe']   = $thongtin['qhvo-quanhe'];
				$dsQuanHeGiaDinhVo['hoten']    = $thongtin['qhvo-hoten'];
				$dsQuanHeGiaDinhVo['namsinh']  = $thongtin['qhvo-namsinh'];
				$dsQuanHeGiaDinhVo['thongtin'] = $thongtin['qhvo-thongtin'];
				//////không dùng nữa
				unset($thongtin['qhvo-quanhe']);
				unset($thongtin['qhvo-hoten']);
				unset($thongtin['qhvo-namsinh']);
				unset($thongtin['qhvo-thongtin']);
			}
			//Quá trình lương
			if (isset($thongtin['qtluong-thoigian'])) {
				//////lấy giá trị
				$dsQuaTrinhLuong['thoigian']      = $thongtin['qtluong-thoigian'];
				$dsQuaTrinhLuong['ngach']         = $thongtin['qtluong-ngach'];
				$dsQuaTrinhLuong['bac']           = $thongtin['qtluong-bac'];
				$dsQuaTrinhLuong['hesoluong']     = $thongtin['qtluong-hesoluong'];
				$dsQuaTrinhLuong['phucap']        = $thongtin['qtluong-phucap'];
				$dsQuaTrinhLuong['vuotkhung']     = $thongtin['qtluong-vuotkhung'];
				$dsQuaTrinhLuong['mucluongkhoan'] = $thongtin['qtluong-mucluongkhoan'];
				//////không dùng nữa
				unset($thongtin['qtluong-thoigian']);
				unset($thongtin['qtluong-ngach']);
				unset($thongtin['qtluong-bac']);
				unset($thongtin['qtluong-hesoluong']);
				unset($thongtin['qtluong-phucap']);
				unset($thongtin['qtluong-vuotkhung']);
				unset($thongtin['qtluong-mucluongkhoan']);
			}

			//=====================================================
			//nhập các thông tin cơ bản
			try {
				$maCB            = $this->canboModel->themCanBo($thongtin);
				$view['message'] = 'Lưu thông chính thành công';

			} catch (InvalidQueryException $exc) {
				$view['message'] = 'Không lưu được thông tin chính';
			}

			//
			//var_dump($thongtin);
			//tạo tài khoản cho cán bộ (user = số cmnd, pass = ngày sinh, role= "cadre", mã cán bộ)
			try {
				$userModel->createNew(
					$thongtin['cmnd'],
					$password,
					'cadre',
					$maCB
				);
			} catch (InvalidQueryException $exc) {
				$view['message'] += ', không thể tạo user mới(kiểm tra trùng số CMND)';
			}

			//Nhập các thông tin nhiều dòng khác
			if (null != $maCB) {
				//đào tạo bồi dưỡng
				if (isset($dsDaoTaoBoiDuong)) {
					try {
						foreach ($dsDaoTaoBoiDuong['tentruong'] as $i => $tentruong) {
							$this->canboModel->themDaoTaoBoiDuong(
								$maCB,
								$tentruong,
								$dsDaoTaoBoiDuong['nganhhoc'][$i],
								$dsDaoTaoBoiDuong['tungay'][$i],
								$dsDaoTaoBoiDuong['denngay'][$i],
								$dsDaoTaoBoiDuong['hinhthuc'][$i],
								$dsDaoTaoBoiDuong['vanbang'][$i]
							);
						}
					} catch (InvalidQueryException $exc) {
						$view['message'] .= ', lưu đào tạo bồi dưỡng có sai sót';
					}

				}

				//quá trình công tác
				if (isset($dsQuaTrinhCongTac)) {
					try {
						foreach ($dsQuaTrinhCongTac['tungay'] as $i => $tungay) {
							$this->canboModel->themQuaTrinhCongTac(
								$maCB,
								$tungay,
								$dsQuaTrinhCongTac['denngay'][$i],
								$dsQuaTrinhCongTac['soluoc'][$i]
							);
						}
					} catch (InvalidQueryException $exc) {
						$view['message'] .= ', lưu quá trình công tác có sai sót';
					}
				}

				//kỷ luật
				if (isset($dsKyLuat)) {
					foreach ($dsKyLuat['thoigian'] as $i => $thoigian) {
						$this->canboModel->themKyLuat(
							$maCB,
							$thoigian,
							$dsKyLuat['hinhthuc'][$i],
							$dsKyLuat['noiquyetdinh'][$i],
							$dsKyLuat['lydo'][$i]
						);
					}
				}

				//Khen thưởng
				if (isset($dsKhenThuong)) {
					foreach ($dsKhenThuong['thoigian'] as $i => $thoigian) {
						$this->canboModel->themKhenThuong(
							$maCB,
							$thoigian,
							$dsKhenThuong['hinhthuc'][$i],
							$dsKhenThuong['noiquyetdinh'][$i],
							$dsKhenThuong['lydo'][$i]
						);
					}
				}

				//Quan hệ gia đình
				if (isset($dsQuanHeGiaDinh)) {
					foreach ($dsQuanHeGiaDinh['quanhe'] as $i => $quanhe) {
						$this->canboModel->themQuanHeGiaDinh(
							$maCB,
							0, // bản thân
							$quanhe,
							$dsQuanHeGiaDinh['hoten'][$i],
							$dsQuanHeGiaDinh['namsinh'][$i],
							$dsQuanHeGiaDinh['thongtin'][$i]
						);
					}
				}

				//Quan hệ gia đình vợ
				if (isset($dsQuanHeGiaDinhVo)) {
					foreach ($dsQuanHeGiaDinhVo['quanhe'] as $i => $quanhe) {
						$this->canboModel->themQuanHeGiaDinh(
							$maCB,
							1, // bên vợ
							$quanhe,
							$dsQuanHeGiaDinhVo['hoten'][$i],
							$dsQuanHeGiaDinhVo['namsinh'][$i],
							$dsQuanHeGiaDinhVo['thongtin'][$i]
						);
					}
				}

				//Quá trình lương
				if (isset($dsQuaTrinhLuong)) {
					//var_dump($dsQuaTrinhLuong);exit;
					foreach ($dsQuaTrinhLuong['thoigian'] as $i => $ngayNangluong) {
						$this->canboModel->nangluong(
							$maCB,
							$ngayNangluong,
							$dsQuaTrinhLuong['ngach'][$i],
							$dsQuaTrinhLuong['bac'][$i],
							$dsQuaTrinhLuong['hesoluong'][$i],
							$dsQuaTrinhLuong['vuotkhung'][$i],
							$dsQuaTrinhLuong['phucap'][$i],
							$dsQuaTrinhLuong['mucluongkhoan'][$i]
						);
					}
				}
				//
				//
			}

		}

		//show to view
		$view['dsNgachLuong'] = $this->canboModel->getNgachList();
		$view['dsChucVu']     = $this->canboModel->getChucVuList();
		$view['dsDanToc']     = $this->canboModel->getDanTocList();
		$view['dsTonGiao']    = $this->canboModel->getTonGiaoList();
		$view['dsTDCM']       = $this->canboModel->getTrinhDoChuyenMonList();
		$view['dsTDLLCT']     = $this->canboModel->getTrinhDoLLCTList();
		//var_dump( $view['dsTDLLCT']);exit;

		return new ViewModel($view);
	}

	public function getlylichfromfileAction() {
		/**
		 * @var $request Request
		 */
		$request = $this->getRequest();
		$arr     = $request->getFiles()->toArray();
		$file    = $arr['filename'];
		//Kiểm tra file gửi lên có phải là file excel không

		if ($file['type'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $file['type'] == 'application/vnd.ms-excel') {
			error_reporting(E_ALL);
			set_time_limit(0);
			date_default_timezone_set('Europe/London');
			set_include_path(PHPEXCEL);
			include PHPEXCEL . '\PHPExcel\IOFactory.php';

			$inputFileName = $file['tmp_name'];

			if ($file['type'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
				$inputFileType = 'Excel2007';
			} else {
				$inputFileType = 'Excel5';
			}

			$objReader   = \PHPExcel_IOFactory::createReader($inputFileType);
			$objPHPExcel = $objReader->load($inputFileName);

			$data = array();

			$objPHPExcel->setActiveSheetIndex(1);
			$arr    = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			$arr    = array_values($arr);
			$data[] = $arr;

			$objPHPExcel->setActiveSheetIndex(2);
			$arr    = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			$arr    = array_values($arr);
			$data[] = $arr;

			$objPHPExcel->setActiveSheetIndex(4);
			$arr    = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			$arr    = array_values($arr);
			$data[] = $arr;

			$objPHPExcel->setActiveSheetIndex(5);
			$arr    = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			$arr    = array_values($arr);
			$data[] = $arr;

			$objPHPExcel->setActiveSheetIndex(8);
			$arr    = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			$arr    = array_values($arr);
			$data[] = $arr;

			$objPHPExcel->setActiveSheetIndex(9);
			$arr    = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			$arr    = array_values($arr);
			$data[] = $arr;

			$objPHPExcel->setActiveSheetIndex(6);
			$arr    = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			$arr    = array_values($arr);
			$data[] = $arr;

			$objPHPExcel->setActiveSheetIndex(7);
			$arr    = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			$arr    = array_values($arr);
			$data[] = $arr;

			$objPHPExcel->setActiveSheetIndex(3);
			$arr    = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
			$arr    = array_values($arr);
			$data[] = $arr;

			echo json_encode($data);
		} else {
			echo 'Error';
		}

		//Không render ra giao diện
		return $this->response;
	}

	/**
	 * Hiện thông tin lý lịch 2c
	 * @return ViewModel
	 */
	public function lylichAction() {
		//init model

		//init view

		//get parameter from request
		$id = $this->params('id');

		//get data from model
		$view['lylich']            = $this->canboModel->getLyLichCanBo($id);
		$view['dao_tao_boi_duong'] = $this->canboModel->getDaoTaoBoiDuong($id);
		$view['dacdiemlichsu']     = $this->canboModel->getDacDiemLichSu($id);
		$view['quatrinhcongtac']   = $this->canboModel->getQuaTrinhCongTac($id);
		$view['quanhegiadinh']     = $this->canboModel->getQuanHeGiaDinh($id, 0); //của bản thân
		$view['quanhegiadinhvo']   = $this->canboModel->getQuanHeGiaDinh($id, 1); //của nhà vợ
		$view['quatrinhluong']     = $this->canboModel->getQuaTrinhLuong($id);
		$view['congtacnuocngoai']  = $this->canboModel->getCongTacNuocNgoai($id);

		//var_dump($view['quatrinhcongtac']);exit;
		//$a = $this->canboModel->getSoLuongKienNghi();

		//var_dump($a);exit;

		//send data to view
		return new ViewModel($view);
	}

	/**
	 * bổ sung thông tin cán bộ
	 * @return ViewModel
	 */
	public function bosungAction() {

		$time = $this->getRequest()->getQuery('time');

		$canboModel = $this->getServiceLocator()->get('Manager\Model\CanBoModel');

		$view['message'] = '';

		$curId = $this->params('id');

		$kiennghi = null;
		if (isset($time)) {
			$kiennghi = $canboModel->getKienNghi($curId, $time);
		}

		//process request:  when save edited info
		if ($this->getRequest()->isPost()) {

			if (null != $curId) {
				$userID = (new \Zend\Authentication\AuthenticationService)->getIdentity()->UserID;
				$cbID   = (new \Zend\Authentication\AuthenticationService)->getIdentity()->Identifier_Info;

				$canboInfo = $this->canboModel->getBriefInfo($curId);

				//log
				$logModel = $this->getServiceLocator()->get('Admin\Model\LogModel');
				$noidung  = 'Sửa thông tin của cán bộ: ' . $canboInfo['Ho_Ten_CB'];
				try {
					$logModel->createNew(date('Y-m-d H:i:s'), $userID, $curId, 'Sửa thông tin lý lịch', $noidung);
				} catch (\InvalidQueryException $exc) {
					$view['message'] = ', không lưu dược log';
				}

				$maCB       = $curId;
				$parameters = $this->getRequest()->getPost();

				//special value, null value
				$parameters['ngaygianhap']          = ('' == $parameters['ngaygianhap']) ? null : $parameters['ngaygianhap'];
				$parameters['ngaysinh']             = ('' == $parameters['ngaysinh']) ? null : $parameters['ngaysinh'];
				$parameters['ngaytuyendung']        = ('' == $parameters['ngaytuyendung']) ? null : $parameters['ngaytuyendung'];
				$parameters['ngaybienche']          = ('' == $parameters['ngaybienche']) ? null : $parameters['ngaybienche'];
				$parameters['ngaycapCMND']          = ('' == $parameters['ngaycapCMND']) ? null : $parameters['ngaycapCMND'];
				$parameters['ngayvaodang']          = ('' == $parameters['ngayvaodang']) ? null : $parameters['ngayvaodang'];
				$parameters['ngayvaodangchinhthuc'] = ('' == $parameters['ngayvaodangchinhthuc']) ? null : $parameters['ngayvaodangchinhthuc'];
				$parameters['ngaynhapngu']          = ('' == $parameters['ngaynhapngu']) ? null : $parameters['ngaynhapngu'];
				$parameters['ngayxuatngu']          = ('' == $parameters['ngayxuatngu']) ? null : $parameters['ngayxuatngu'];
				$parameters['ngayroikhoi']          = ('' == $parameters['ngayroikhoi']) ? null : $parameters['ngayroikhoi'];
				$parameters['ngayroikhoi']          = ('' == $parameters['ngayroikhoi']) ? null : $parameters['ngayroikhoi'];
				$parameters['ngayroikhoi']          = ('' == $parameters['ngayroikhoi']) ? null : $parameters['ngayroikhoi'];
				$parameters['ngayroikhoi']          = ('' == $parameters['ngayroikhoi']) ? null : $parameters['ngayroikhoi'];
				$parameters['dantoc']               = ('' == $parameters['dantoc']) ? null : $parameters['dantoc'];
				$parameters['tongiao']              = ('' == $parameters['tongiao']) ? null : $parameters['tongiao'];
				$parameters['Cap_Do_CTLL']          = ('' == $parameters['Cap_Do_CTLL']) ? null : $parameters['Cap_Do_CTLL'];
				$parameters['Cap_Do_TDCM']          = ('' == $parameters['Cap_Do_TDCM']) ? null : $parameters['Cap_Do_TDCM'];
				$parameters['giadinhlietsy']        = (isset($parameters['giadinhlietsy'])) ? '1' : '0'; //Checkbox "giadinhlietsy"
				$parameters['thamgia_clbtt']        = ('' == $parameters['thamgia_clbtt']) ? null : $parameters['thamgia_clbtt']; 
				$parameters['sothehoivien']        = ('' == $parameters['sothehoivien']) ? null : $parameters['sothehoivien']; 
				$parameters['soquyetdinhcongchuc']        = ('' == $parameters['soquyetdinhcongchuc']) ? null : $parameters['soquyetdinhcongchuc']; 
				$parameters['sohopdong']        = ('' == $parameters['sohopdong']) ? null : $parameters['sohopdong']; 
				//var_dump($parameters);exit;

				//to model
				try {
					$canboModel->bosungThongTin($curId,
						$parameters['hoten'], $parameters['ngaygianhap'], $parameters['ngaytuyendung'], $parameters['ngaybienche'], $parameters['ngayroikhoi'], $parameters['trangthai'], $parameters['thamgia_clbtt'], $parameters['sothehoivien'],
						$parameters['sohieu'], $parameters['hotenkhaisinh'], $parameters['tengoikhac'], $parameters['gioitinh'], $parameters['capuyhientai'], $parameters['capuykiem'], $parameters['chucdanh'], $parameters['phucapchucvu'],
						$parameters['ngaysinh'], $parameters['noisinh'], $parameters['cmnd'], $parameters['ngaycapCMND'], $parameters['noicapCMND'],
						$parameters['quequan'], $parameters['noiohiennay'], $parameters['dantoc'], $parameters['tongiao'], $parameters['dienthoai'], $parameters['ngaythamgiacm'],
						$parameters['thanhphangiadinh'], $parameters['nghenghieptruocdo'], $parameters['ngaytuyendung_cqnc'], $parameters['coquantuyendung'], null,
						$parameters['ngayvaodang'], $parameters['ngayvaodangchinhthuc'], $parameters['thamgiatcccxh'],
						$parameters['ngaynhapngu'], $parameters['ngayxuatngu'], $parameters['quanhamcaonhat'],
						$parameters['trinhdohocvan'], $parameters['hocham'], $parameters['lyluanchinhtri'], $parameters['trinhdochuyenmon'], $parameters['chuyennganh'], $parameters['ngoaingu'],
						$parameters['dacdiemlichsu'], $parameters['lamviecchedocu'], $parameters['thannhannuocngoai'], $parameters['quanhenuocngoai'],
						$parameters['congtacchinh'], $parameters['danhhieu'], $parameters['sotruong'], $parameters['congvieclaunhat'],
						$parameters['khenthuong'], $parameters['kyluat'], $parameters['tinhtrangsuckhoe'], $parameters['tiensubenh'], $parameters['chieucao'], $parameters['cannang'], $parameters['nhommau'],
						$parameters['thuongbinhloai'], $parameters['giadinhlietsy'],
						$parameters['luongnam'], $parameters['nguonthunhapkhac'], $parameters['loainhaduoccap'], $parameters['dientichnhaduoccap'], $parameters['loainhatuxay'], $parameters['dientichnhatuxay'],
						$parameters['dientichdatduoccap'], $parameters['dientichdattumua'], $parameters['dientichdatsx'], $parameters['soquyetdinhcongchuc'], $parameters['sohopdong']
					);
					$view['message'] = 'Đã sửa đổi, bổ sung thông tin';
				} catch (\Exception $exc) {
					//throw $exc;
					$view['message'] = 'Không lưu được thông tin [lỗi xử lý dữ liệu]';
				}

				$thongtin = $parameters->toArray();
				//
				//Đào tạo bồi dưỡng

				if (isset($thongtin['daotao-tentruong'])) {
					//////lấy giá trị
					$dsDaoTaoBoiDuong['tentruong'] = $thongtin['daotao-tentruong'];
					$dsDaoTaoBoiDuong['nganhhoc']  = $thongtin['daotao-nganhhoc'];
					$dsDaoTaoBoiDuong['tungay']    = $thongtin['daotao-thoigian'];
					$dsDaoTaoBoiDuong['denngay']   = $thongtin['daotao-tgketthuc'];
					$dsDaoTaoBoiDuong['hinhthuc']  = $thongtin['daotao-hinhthuc'];
					$dsDaoTaoBoiDuong['vanbang']   = $thongtin['daotao-vanbang'];
				}
				if (isset($dsDaoTaoBoiDuong)) {
					try {
						foreach ($dsDaoTaoBoiDuong['tentruong'] as $i => $tentruong) {
							$this->canboModel->themDaoTaoBoiDuong(
								$maCB,
								$tentruong,
								$dsDaoTaoBoiDuong['nganhhoc'][$i],
								$dsDaoTaoBoiDuong['tungay'][$i],
								$dsDaoTaoBoiDuong['denngay'][$i],
								$dsDaoTaoBoiDuong['hinhthuc'][$i],
								$dsDaoTaoBoiDuong['vanbang'][$i]
							);
						}
					} catch (InvalidQueryException $exc) {
						$view['message'] .= ', lưu đào tạo bồi dưỡng có sai sót';
					}

				}

				//Tóm tắt quá trình công tác
				if (isset($thongtin['congtac-tu'])) {
					//////lấy giá trị
					$dsQuaTrinhCongTac['tungay']  = $thongtin['congtac-tu'];
					$dsQuaTrinhCongTac['denngay'] = $thongtin['congtac-den'];
					$dsQuaTrinhCongTac['soluoc']  = $thongtin['congtac-thongtin'];
				}
				if (isset($dsQuaTrinhCongTac)) {
					try {
						foreach ($dsQuaTrinhCongTac['tungay'] as $i => $tungay) {
							$this->canboModel->themQuaTrinhCongTac(
								$maCB,
								$tungay,
								$dsQuaTrinhCongTac['denngay'][$i],
								$dsQuaTrinhCongTac['soluoc'][$i]
							);
						}
					} catch (InvalidQueryException $exc) {
						$view['message'] .= ', lưu quá trình công tác có sai sót';
					}

				}

				//Quá trình lương
				if (isset($thongtin['qtluong-thoigian'])) {
					//////lấy giá trị
					$dsQuaTrinhLuong['thoigian']      = $thongtin['qtluong-thoigian'];
					$dsQuaTrinhLuong['ngach']         = $thongtin['qtluong-ngach'];
					$dsQuaTrinhLuong['bac']           = $thongtin['qtluong-bac'];
					$dsQuaTrinhLuong['hesoluong']     = $thongtin['qtluong-hesoluong'];
					$dsQuaTrinhLuong['phucap']        = $thongtin['qtluong-phucap'];
					$dsQuaTrinhLuong['vuotkhung']     = $thongtin['qtluong-vuotkhung'];
					$dsQuaTrinhLuong['mucluongkhoan'] = $thongtin['qtluong-mucluongkhoan'];
				}
				if (isset($dsQuaTrinhLuong)) {
					try {
						foreach ($dsQuaTrinhLuong['thoigian'] as $i => $ngayNangluong) {
							$this->canboModel->nangluong(
								$maCB,
								$ngayNangluong,
								$dsQuaTrinhLuong['ngach'][$i],
								$dsQuaTrinhLuong['bac'][$i],
								$dsQuaTrinhLuong['hesoluong'][$i],
								$dsQuaTrinhLuong['vuotkhung'][$i],
								$dsQuaTrinhLuong['phucap'][$i],
								$dsQuaTrinhLuong['mucluongkhoan'][$i]
							);
						}
					} catch (InvalidQueryException $exc) {
						$view['message'] .= ', lưu quá trình lương có sai sót (mã ngạch chưa đúng, cán bộ được nâng lương hơn 1 lần/ngày,...)';
					}
				}

				//Quan hệ gia đình
				if (isset($thongtin['qh-quanhe'])) {

					$dsQuanHeGiaDinh['quanhe']   = $thongtin['qh-quanhe'];
					$dsQuanHeGiaDinh['hoten']    = $thongtin['qh-hoten'];
					$dsQuanHeGiaDinh['namsinh']  = $thongtin['qh-namsinh'];
					$dsQuanHeGiaDinh['thongtin'] = $thongtin['qh-thongtin'];
				}

				if (isset($dsQuanHeGiaDinh)) {
					foreach ($dsQuanHeGiaDinh['quanhe'] as $i => $quanhe) {
						$this->canboModel->themQuanHeGiaDinh(
							$maCB,
							0, // bản thân
							$quanhe,
							$dsQuanHeGiaDinh['hoten'][$i],
							$dsQuanHeGiaDinh['namsinh'][$i],
							$dsQuanHeGiaDinh['thongtin'][$i]
						);
					}
				}

				//Quan hệ gia đình bên vợ
				if (isset($thongtin['qhvo-quanhe'])) {
					$dsQuanHeGiaDinhVo['quanhe']   = $thongtin['qhvo-quanhe'];
					$dsQuanHeGiaDinhVo['hoten']    = $thongtin['qhvo-hoten'];
					$dsQuanHeGiaDinhVo['namsinh']  = $thongtin['qhvo-namsinh'];
					$dsQuanHeGiaDinhVo['thongtin'] = $thongtin['qhvo-thongtin'];
				}
				if (isset($dsQuanHeGiaDinhVo)) {
					foreach ($dsQuanHeGiaDinhVo['quanhe'] as $i => $quanhe) {
						$this->canboModel->themQuanHeGiaDinh(
							$maCB,
							1, // bên vợ
							$quanhe,
							$dsQuanHeGiaDinhVo['hoten'][$i],
							$dsQuanHeGiaDinhVo['namsinh'][$i],
							$dsQuanHeGiaDinhVo['thongtin'][$i]
						);
					}
				}
				if (isset($time)) {
					$canboModel->giaiquyetKienNghi($curId, $time, 0);
				}

			}
		}

		//ViewModel
		$view['lylich']    = $canboModel->getLyLichCanBo($curId);
		$view['dsDanToc']  = $this->canboModel->getDanTocList();
		$view['dsTonGiao'] = $this->canboModel->getTonGiaoList();
		$view['dsTDCM']    = $this->canboModel->getTrinhDoChuyenMonList();
		$view['dsTDLLCT']  = $this->canboModel->getTrinhDoLLCTList();

		$view['dsChucVu'] = $canboModel->getChucVuList();

		$view['kiennghi']          = $kiennghi;
		$view['dao_tao_boi_duong'] = $this->canboModel->getDaoTaoBoiDuong($curId);
		//$view['dacdiemlichsu'] = $this->canboModel->getDacDiemLichSu($id);
		$view['quatrinhcongtac'] = $this->canboModel->getQuaTrinhCongTac($curId);
		$view['quanhegiadinh']   = $this->canboModel->getQuanHeGiaDinh($curId, 0); //của bản thân
		$view['quanhegiadinhvo'] = $this->canboModel->getQuanHeGiaDinh($curId, 1); //của nhà vợ
		$view['quatrinhluong']   = $this->canboModel->getQuaTrinhLuong($curId);
		//$view['congtacnuocngoai'] = $this->canboModel->getCongTacNuocNgoai($id);

		return new ViewModel($view);
	}

	/**
	 * quản lý tiền lương cán bộ
	 * @return ViewModel
	 */
	public function luongAction() {
		//init
		$ngayxet = date('d/m/Y');
		$sonam   = 2;

		//init model
		$canboModel = $this->getServiceLocator()->get('Manager\Model\CanBoModel');

		//get parameter from request (GET)
		$id = $this->params('id');

		//set id = current uset id (if there is no value)
		$id = (null != $id) ? $id : ((new AuthenticationService())->getIdentity()->Identifier_Info);

		$view['message'] = '';
		//process
		if ($this->getRequest()->isPost()) {
			$parameters = $this->getRequest()->getPost();

			//var_dump($this->getRequest());exit;
			//set "ngayxet" to view "danh sach den han"
			$ngayxet = $parameters['ngayxet'];

			//process null value
			$parameters['mangach'] = ('' == $parameters['mangach']) ? null : $parameters['mangach'];

			//nâng lương
			try {
				$canboModel->nangLuong(
					$id,
					$parameters['ngaynangluong'],
					$parameters['mangach'],
					$parameters['bacluong'],
					$parameters['hesoluong'],
					$parameters['phucapvuotkhung'],
					$parameters['hesophucap'],
					$parameters['mucluongkhoan']
				);

				$view['message'] = 'Nâng lương thành công';

				$lylich   = $canboModel->getLyLichCanBo($id);
				$userID   = (new \Zend\Authentication\AuthenticationService)->getIdentity()->UserID;
				$cbID     = (new \Zend\Authentication\AuthenticationService)->getIdentity()->Identifier_Info;
				$logModel = $this->getServiceLocator()->get('Admin\Model\LogModel');
				$noidung  = 'Nâng lương cán bộ: ' . $lylich['Ho_Ten_CB'];
				$logModel->createNew(date('Y-m-d H:i:s'), $userID, $cbID, 'Nâng lương', $noidung);

			} catch (InvalidQueryException $exc) {
				$view['message'] = 'Không thể lưu dữ liệu (Chú ý: trong một ngày mỗi cán bộ chỉ nâng lương 1 lần, chưa xác định cán bộ,...) ';
			}

		}

		$view['dsNgachLuong']   = $canboModel->getNgachList();
		$view['dscannangluong'] = $canboModel->getDSCanNangLuong($ngayxet, $sonam);
		$view['quatrinhluong']  = $canboModel->getQuaTrinhLuong($id);
		$view['canbo']          = $canboModel->getBriefInfo($id);

		$view['id'] = $id; //back to View

		return new ViewModel($view);
	}

	/**
	 * support Ajax
	 * POST
	 * @return ViewModel
	 */
	public function getDsCannangluongAction() {
		$ngayxet = date('Y-m-d');
		$sonam   = 3;

		if ($this->getRequest()->isPost()) {
			$parameters = $this->getRequest()->getPost();
			$ngayxet    = $parameters['ngayxet'];
			$sonam      = $parameters['sonam'];

			$data = $this->canboModel->getDSCanNangLuong($ngayxet, $sonam);

			//send to client (Json)
			echo json_encode($data);
		}

		return $this->getResponse();
	}

	/**
	 * support Ajax
	 * @return \Zend\Stdlib\ResponseInterface
	 */
	public function xoaLuongAction() {
		//get parameter from GET request
		//$paras = explode('-',$this->params('id'));
		//$id = $paras[0];
		//$date = date('Y-m-d', $paras[1]);

		//$paras = $this->getRequest()->getQuery();
		//$id = $paras['id'];
		//$date = $paras['date'];
		//var_dump($paras);exit;
		//var_dump($id,$date);exit;

		$paras = $this->getRequest()->getPost();
		$id    = $paras['id'];
		$date  = $paras['date'];

		//process request (GET)
		if ($this->getRequest()) {

			//delete data
			try {
				$this->canboModel->deleteQuaTrinhLuong(
					$id,
					$date
				);
			} catch (InvalidQueryException $exc) {

			}

		}

		//var_dump($this->getRequest()->getHeader('Referer')->getUri());
		//var_dump($this->getRequest()->getRequestUri());exit;

		//$basePath = $this->getRequest()->getBasePath();
		//$lastUrl = $this->getRequest()->getHeader('Referer')->getUri();
		//$this->redirect()->toUrl($lastUrl);

		return $this->getResponse();
	}

	public function xoaDaotaoboiduongAction() {
		//get parameter from GET request
		$paras  = explode('-', $this->params('id'));
		$id     = $paras[0];
		$dtbdID = $paras[1];

		//var_dump($id,' ',$dtbdID);exit;

		//process request (GET)
		if ($this->getRequest()) {

			//delete data
			$this->canboModel->deleteDaoTaoBoiDuong(
				$id,
				$dtbdID
			);
		}

		$lastUrl = $this->getRequest()->getHeader('Referer')->getUri();
		$this->redirect()->toUrl($lastUrl);

	}

	public function xoaQuatrinhcongtacAction() {
		//get parameter from GET request
		$paras = explode('-', $this->params('id'));
		$id    = $paras[0];
		$stt   = $paras[1];

		//var_dump($id,' ',$dtbdID);exit;

		//process request (GET)
		if ($this->getRequest()) {

			//delete data
			$this->canboModel->deleteQuaTrinhCongTac(
				$id,
				$stt
			);
		}

		$lastUrl = $this->getRequest()->getHeader('Referer')->getUri();
		$this->redirect()->toUrl($lastUrl);

	}

	public function xoaQuanhegiadinhAction() {
		//get parameter from GET request
		$paras = explode('-', $this->params('id'));
		$id    = $paras[0];
		$stt   = $paras[1];

		//process request (GET)
		if ($this->getRequest()) {
			//delete data
			$this->canboModel->deleteQuanHeGiaDinh(
				$id,
				$stt
			);
		}

		$lastUrl = $this->getRequest()->getHeader('Referer')->getUri();
		$this->redirect()->toUrl($lastUrl);

	}

	public function congtacnuocngoaiAction() {
		//init model

		//init view

		//get parameter from GET request
		$id = $this->params('id');

		//set id = current uset id (if there is no value)
		$id = (null != $id) ? $id : ((new AuthenticationService())->getIdentity()->Identifier_Info);

		//process request
		if ($this->getRequest()->isPost()) {
			$parameters = $this->getRequest()->getPost();

			//insert
			try {
				$this->canboModel->themCongTacNuocNgoai(
					$id,
					$parameters['ngaydi'],
					$parameters['ngayve'],
					$parameters['điaiem'],
					$parameters['noidung'],
					$parameters['capcudi'],
					$parameters['kinhphi']
				);
				$view['message'] = 'Lưu được dữ liệu thành công';
			} catch (InvalidQueryException $exc) {
				$view['message'] = 'Không lưu được dữ liệu (chưa xác định cán bộ,...)';
			}

			//log
			$lylich   = $this->canboModel->getLyLichCanBo($id);
			$userID   = (new \Zend\Authentication\AuthenticationService)->getIdentity()->UserID;
			$cbID     = (new \Zend\Authentication\AuthenticationService)->getIdentity()->Identifier_Info;
			$logModel = $this->getServiceLocator()->get('Admin\Model\LogModel');
			$noidung  = 'Thêm công tác nước ngoài cho cán bộ: ' . $lylich['Ho_Ten_CB'];
			$logModel->createNew(date('Y-m-d H:i:s'), $userID, $cbID, 'Thêm công tác nước ngoài', $noidung);
		}

		//get data from model
		$view['congtacnuocngoai'] = $this->canboModel->getCongTacNuocNgoai($id);
		$view['canbo']            = $this->canboModel->getBriefInfo($id);
		$view['id']               = $id; // to View

		//send data to view
		return new ViewModel($view);
	}

	/**
	 * Ajax
	 * @return \Zend\Stdlib\ResponseInterface
	 */
	public function xoaCongtacnuocngoaiAction() {
		//get parameter from GET request
		//$paras = explode('-',$this->params('id'));
		//$id = $paras[0];
		//$stt = $paras[1];

		//get parameter from GET request
		$paras = $this->getRequest()->getQuery();
		$id    = $paras['id'];
		$stt   = $paras['stt'];

		//process request (GET)
		if ($this->getRequest()) {

			//delete data
			try {
				$this->canboModel->deleteCongTacNuocNgoai(
					$id,
					$stt
				);
			} catch (InvalidQueryException $exc) {

			}
		}

		//$lastUrl = $this->getRequest()->getHeader('Referer')->getUri();
		//$this->redirect()->toUrl($lastUrl);

		return $this->getResponse();

	}

	public function luanchuyenAction() {
		//init View
		$helper     = $this->getServiceLocator()->get('viewhelpermanager');
		$headScript = $helper->get('headscript');
		$headScript->appendFile('/template/js/combobox.js');

		//get parameter from GET request
		$id = $this->params('id');

		//set id = current uset id (if there is no value)
		$id = (null != $id) ? $id : ((new AuthenticationService())->getIdentity()->Identifier_Info);

		//process request
		if ($this->getRequest()->isPost()) {
			$parameters = $this->getRequest()->getPost();
			// if move to out donvi
			//            if(isset($parameters['donvi']) && $parameters['donvi'] == '0') {
			//                $parameters['banden'] = '0';
			//            }
			//            var_dump($parameters); die();

			// make '' to null
			$parameters['banden'] = ('' == $parameters['banden']) ? null : $parameters['banden'];

			$thongtindi = explode(' ', $parameters['thongtindi'], 2);
			$mabandi    = isset($thongtindi[0]) ? $thongtindi[0] : null;
			$tgbandi    = isset($thongtindi[1]) ? $thongtindi[1] : null;

			//insert
			try {
				$this->canboModel->luanchuyen(
					$id,
					$parameters['banden'],
					$parameters['ngaydi'],
					$parameters['chucvu'],
					$parameters['lydo'],

					$mabandi,
					$tgbandi
				);
				$view['message'] = 'Luân chuyển thành công';

				//log
				$lylich   = $this->canboModel->getBriefInfo($id);
				$authInfo = (new \Zend\Authentication\AuthenticationService)->getIdentity();
				$userID   = $authInfo->UserID;
				$cbID     = $authInfo->Identifier_Info;
				$noidung  = 'Cập nhật luân chuyển cán bộ: ' . $lylich['Ho_Ten_CB'];

//                if(isset($parameters['donvi']) && $parameters['donvi'] == '0')
				//                    $this->logModel->createNew(date('Y-m-d H:i:s'), $userID, $cbID, 'Luan chuyen ra ngoai', $noidung);
				//                else
				$this->logModel->createNew(date('Y-m-d H:i:s'), $userID, $cbID, 'Luân chuyển', $noidung);

			} catch (InvalidQueryException $exc) {
				$view['message'] = 'Không lưu được thông tin (có thể do chưa xác định cán bộ, chưa rõ nơi đến, luân chuyển cùng bộ phận nhiều lần/ngày,...)';
			}

		}

		//get data from model
		$view['quatrinhluanchuyen'] = $this->canboModel->getQuaTrinhLuanChuyen($id);
		$view['congtachientai']     = $this->canboModel->getCongTacHienTai($id);
		//var_dump( $view['congtachientai']);
		$view['canbo']               = $this->canboModel->getBriefInfo($id);
		$view['dsDonvi']             = $this->donviModel->getBriefInfoList();
		$view['dsBan']               = $this->banModel->getDSBanHoatDong();
		$view['dsBanThuocThanhDoan'] = $this->donviModel->getDSBanthuoc(0);
		$view['dsChucvu']            = $this->canboModel->getChucVuList();
		$view['id']                  = $id;

		//send data to view
		return new ViewModel($view);
	}

	public function roikhoiBanAction() {
		//process request (POST)
		if ($this->getRequest()) {
			//get parameter from request
			$paras       = $this->getRequest()->getPost();
			$canboID     = $paras['canbo_id'];
			$banID       = $paras['ban_id'];
			$joiningDate = $paras['joining_date'];
			$leavingDate = $paras['leaving_date'];

			//delete data
			try {
				$this->canboModel->roiKhoiBan(
					$canboID, $banID, $joiningDate,
					$leavingDate
				);
			} catch (InvalidQueryException $exc) {

			}

		}

		return $this->getResponse();
	}

	/**
	 * support Ajax
	 * POST
	 * @return \Zend\Stdlib\ResponseInterface
	 */
	public function xoaLuanchuyenAction() {
		//process request (POST)
		if ($this->getRequest()) {
			//get parameter from request
			$paras   = $this->getRequest()->getPost();
			$canboID = $paras['canbo_id'];
			$banID   = $paras['ban_id'];
			$date    = $paras['date'];

			//delete data
			try {
				$this->canboModel->deleteQuaTrinhLuanChuyen(
					$canboID,
					$banID,
					$date
				);
			} catch (InvalidQueryException $exc) {

			}
		}

		//$lastUrl = $this->getRequest()->getHeader('Referer')->getUri();
		//$this->redirect()->toUrl($lastUrl);

		return $this->getResponse();
	}

	/**
	 * giải quyết các kiến nghị của cán bộ
	 */
	public function giaiquyetkiennghiAction() {
		$this->layout('layout/home');
		$helper = $this->getServiceLocator()->get('viewhelpermanager');

		$headScript = $helper->get('headscript');
		$headScript->appendFile('/script/datatable/media/js/jquery.dataTables.js');
		$headScript->appendFile('/script/datatable/extras/TableTools/media/js/ZeroClipboard.js');
		$headScript->appendFile('/script/datatable/extras/TableTools/media/js/TableTools.js');
		$headScript->appendFile('/script/datatable/extras/ColReorder/media/js/ColReorder.js');

		//get data
		$view['dsKienNghi'] = $this->canboModel->getDSKienNghi(1); //danh sách kiến nghị chưa giải quyết

		$view['dsKienNghiGQ']  = $this->canboModel->getDSKienNghi(0); //danh sách kiến nghị đã giải quyết
		$view['dsKienNghiKGQ'] = $this->canboModel->getDSKienNghi(-1); //danh sách kiến nghị không giải quyết

		//send data to view
		return new ViewModel($view);
	}

	public function chapnhanKiennghiAction() {
		//get parameter from GET request
		$paras = explode('-', $this->params('id'));
		$id    = $paras[0];
		$date  = date('Y-m-d H:i:s', $paras[1]);

		//process request
		if ($this->getRequest()) {
			$parameters = $this->getRequest()->getPost();
			//var_dump($parameters);exit;
			//insert
			$this->canboModel->giaiquyetKienNghi(
				$id,
				$date,
				0
			);
		}

		//redirect
		$basePath = $this->getRequest()->getBasePath();
		$this->redirect()->toUrl($basePath . '/manager/canbo/giaiquyetkiennghi');
	}

	public function kiennghitheongayAction() {
		$begin = $this->getRequest()->getQuery('begin');
		$end   = $this->getRequest()->getQuery('end');
		$all   = $this->getRequest()->getQuery('all');
		if (isset($all)) {
			$data = $this->canboModel->getDSKienNghi(1);
		} else {
			if (isset($begin)) {
				$data = $this->canboModel->getDSKienNghiTheoNgay($begin, $end, 1);
			} else {
				$data = $this->canboModel->getDSKienNghiTheoNgay();
			}
		}

		echo json_encode($data);
		return $this->response;
	}

	public function boquaKiennghiAction() {
		//get parameter from GET request
		//$paras = explode('-',$this->params('id'));
		$id = $this->params('id');
		//$date = date('Y-m-d H:i:s', $paras[1]);
		$date = $this->getRequest()->getQuery('time');

		//process request (GET)
		if ($this->getRequest()) {
			$parameters = $this->getRequest()->getPost();
			//var_dump($parameters);exit;
			//insert
			$this->canboModel->giaiquyetKienNghi(
				$id,
				$date,
				-1
			);
		}

		//redirect
		/*$this->redirect()->toRoute('manager/default', array(
		'controller'    => 'canbo',
		'action'    => 'giaiquyetkiennghi',
		));
		 */

		$basePath = $this->getRequest()->getBasePath();
		$this->redirect()->toUrl($basePath . '/manager/canbo/giaiquyetkiennghi');

	}

	public function xoaKiennghiAction() {
		//get parameter from GET request
		$paras = explode('-', $this->params('id'));
		$id    = $paras[0];
		$date  = date('Y-m-d H:i:s', $paras[1]);

		//process request (GET)
		if ($this->getRequest()) {

			//delete file
			$file_name = $this->canboModel->getfileKienNghi($date, $id);
			$file_path = PROOF_FILES_PATH . '/' . $file_name;
			//var_dump(file_exists($file_path)); var_dump($file_path);exit;
			if ($file_name != null && file_exists($file_path)) {
				unlink($file_path);
			}
			//check and delete file

			//delete data
			$this->canboModel->deleteKienNghi(
				$id,
				$date
			);
		}

		$basePath = $this->getRequest()->getBasePath();
		$this->redirect()->toUrl($basePath . '/manager/canbo/giaiquyetkiennghi');

	}

	public function taifileKiennghiAction() {
		//base url
		$basePath  = $this->getRequest()->getBasePath();
		$file_name = $this->params('id') . '.zip';
		//if file exist, do nothing
		if (null == $file_name) {
			$this->redirect()->toUrl($basePath . '/manager/canbo/giaiquyetkiennghi');
		}

		//go on
		$file_path = PROOF_FILES_PATH . '/' . $file_name; //var_dump($file_url);exit;
		//$file_url = '/QLCBD_UIT/public/files/proof_files/'.$file_name; //var_dump($file_url);exit;

		//var_dump(file_exists($file_path)); var_dump($file_path);exit;

		//download
		/*
		header('Content-type: application/zip');
		//header("Content-type: application/save");
		header('Content-disposition: attachment; filename="'.$file_url.'"');
		header("Content-Transfer-Encoding: binary");
		@readfile($file_url);
		 */

		header('Content-type: application/zip');
		header('Content-Disposition: attachment; filename="' . $file_name . '"');
		readfile($file_path);

		//redirect
		//$this->redirect()->toUrl( $basePath.'/manager/canbo/giaiquyetkiennghi');

		return null;
	}

	/**
	 * hỗ trợ Ajax xem lý lịch vắn tắt của cán bộ
	 * @return \Zend\Stdlib\ResponseInterface
	 */
	public function xemlylichcanboAction() {
		//load from Model
		$canbo = $this->canboModel->getLyLichCanBo($this->params('id'));

		//to view
		echo json_encode($canbo);

		//do not view
		return $this->response;
	}

	/**
	 * hỗ trợ Ajax xem thông tin vắn tắt của cán bộ
	 * @return \Zend\Stdlib\ResponseInterface
	 */
	public function xemthongtincanboAction() {
		//load from model
		$canbo = $this->canboModel->getBriefInfo($this->params('id'));

		//to View
		echo json_encode($canbo);

		//do not view
		return $this->response;
	}

	public function uploadavatarAction() {
		$cmnd = $this->params('id');

		$request = $this->getRequest();
		$arr     = $request->getFiles()->toArray();

		$flag = move_uploaded_file($arr['filename']['tmp_name'], 'public/pictures/portraits/' . $cmnd . '.jpg');
		if ($flag) {
			echo 'true';
		} else {
			echo 'false';
		}
		return $this->response;
	}

	public function portraitUploaderAction() {
		$cmnd       = $this->params('id');
		$view['id'] = $cmnd;
		return new ViewModel($view);
	}

	public function portraitMultiuploaderAction() {
		$flag = '';
		if ($this->getRequest()->isPost()) {
			$arr   = $this->request->getFiles()->toArray();
			$files = $arr['filename'];
			foreach ($files as $file) {
				$flag = move_uploaded_file($file['tmp_name'], 'public/pictures/portraits/' . $file['name']);
			}
			$flag = ($flag) ? 'true' : 'false';
		}

		$view['flag'] = $flag;
		return new ViewModel($view);

	}

	/**
	 * quản lý cựu cán bộ
	 * @return ViewModel
	 */
	public function canbocuAction() {
		$this->layout('layout/home');
		$helper = $this->getServiceLocator()->get('viewhelpermanager');

		$headScript = $helper->get('headscript');
		$headScript->appendFile('/script/datatable/media/js/jquery.dataTables.js');
		$headScript->appendFile('/script/datatable/extras/TableTools/media/js/ZeroClipboard.js');
		$headScript->appendFile('/script/datatable/extras/TableTools/media/js/TableTools.js');
		$headScript->appendFile('/script/datatable/extras/ColReorder/media/js/ColReorder.js');

		//to view
		$view['dsCanBo'] = $this->canboModel->getDSCanBoNgungCongTac(); //load "danh sach Cán Bộ" from database, với thông tin công tác
		//var_dump($view['dsCanBo']);exit;

		return new ViewModel($view);
	}

	//TOOL=====================================================================

}
