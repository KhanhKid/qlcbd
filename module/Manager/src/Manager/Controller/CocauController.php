<?php
//Khai bao namespace
namespace Manager\Controller;
use Manager\DTO\DonVi;
use Zend\Http\Request;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;

//input validator
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Validator;

///
use Zend\View\Model\ViewModel;
use Zend\View\View;

//use QLCBDService

class CocauController extends AbstractActionController {
	public $donviModel;
	public $banModel;
	public $canboModel;
	public $khoiModel;
	//=================================================================================

	public function indexAction() {
		$this->layout('layout/home');
	}

	/**
	 * thành lập đơn vị mới
	 * @return ViewModel
	 */
	public function thanhlapdonviAction() {
		//init model

		//check submit
		if ($this->getRequest()->isPost()) {
			//get parameter
			$parameters = $this->getRequest()->getPost();

			$len = count($parameters['madonvi']); //number of Don Vi

			for ($i = 0; $i < $len; $i++) {
				$this->donviModel->establish(
					$parameters['madonvi'][$i],
					$parameters['tendonvi'][$i],
					$parameters['khoitructhuoc'][$i],
					$parameters['ngaythanhlap'][$i],
					$parameters['mota'][$i]
				);
			}

		}

		//init view
		$helper     = $this->getServiceLocator()->get('viewhelpermanager');
		$headScript = $helper->get('headscript');

		$headScript->appendFile(ROOT_PATH . 'public/script/ckeditor/ckeditor.js');
		$headScript->appendFile(ROOT_PATH . 'public/template/js/combobox.js');

		$this->layout('layout/home');

		//get data
		$listMaDonVi  = array('QĐ1', 'QĐ1-1', 'QĐ1-2', 'QĐ2');
		$listMaDonVi  = $this->donviModel->getIDsList();
		$listThongTin = $this->donviModel->getBriefInfoList();
		$listCapKhoi  = $this->khoiModel->getBriefInfo();

		//to view
		$view['listMaDonVi']  = $listMaDonVi;
		$view['dsCapKhoi']    = $listCapKhoi; //Danh sách Khối trực thuộc
		$view['listThongTin'] = $listThongTin;

		return new ViewModel($view);
	}

	/**
	 * Thành lập Ban lãnh đạo mới cho đơn vị
	 * @return ViewModel
	 */
	public function thanhlapbchAction() {
		//Khi Có Yêu cầu thành lập
		if ($this->getRequest()->isPost()) {
			//parameters
			$parameters = $this->getRequest()->getPost();

			//tạo ban mới
			$BanID = $this->banModel->establish(
				$parameters['loaiban'],
				$parameters['donvi'], //get Ma_Don_Vi
				$parameters['tengoiban'],
				$parameters['ngaythanhlap'],
				$parameters['mota']
			);

			//thêm cán bộ vào Ban mới thành lập (nếu không có lỗi lúc thành lập)
			if (null != $BanID && $parameters['hoten']) {
				//từng cán bộ gia nhập ban
				foreach ($parameters['hoten'] as $i => $macb) {
					$this->canboModel->giaNhapBan(
						$macb,
						$BanID, //ID of BCH (sau khi lấy được sau khi Thành lập ban mới)
						$parameters['ngaythanhlap'], //Ngày Cán bộ gia nhập cũng là ngày thành lập BCH
						$parameters['chucvu'][$i]
					);
				}
			}

		}

		//init View
		$helper     = $this->getServiceLocator()->get('viewhelpermanager');
		$headScript = $helper->get('headscript');

		$headScript->appendFile(ROOT_PATH . 'public/script/ckeditor/ckeditor.js');
		$headScript->appendFile(ROOT_PATH . 'public/template/js/combobox.js');
		$this->layout('layout/home');

		//to view
		$view['dsDonVi']     = $this->donviModel->getBriefInfoList(); //load "danh sach Don Vi" from database
		$view['dsLoaiBanLD'] = $this->banModel->getLoaiHinhBan_List_Brief(1); //load "danh sach Lọai Ban Lãnh Đạo" from database
		$view['dsChucVu']    = $this->canboModel->getChucVuList(); //load "danh sach Chuc Vu" from database
		$view['dsCanBo']     = $this->canboModel->getAllBriefInfo(); //load "danh sach Cán Bộ" from database

		return new ViewModel($view);
	}

	/**
	 * thành lập Phòng/Ban chức năng mới
	 * @return ViewModel
	 */
	public function thanhlapphongbanAction() {
		//Khi Có Yêu cầu thành lập
		if ($this->getRequest()->isPost()) {
			//parameters
			$parameters = $this->getRequest()->getPost();

			//var_dump($parameters);exit;

			//tạo ban mới
			$BanID = $this->banModel->thanhlapBanchucnang(
				$parameters['maban'],
				$parameters['loaiban'],
				$parameters['donvitructhuoc'], //get Ma_Don_Vi
				$parameters['tengoiban'],
				$parameters['ngaythanhlap'],
				$parameters['mota']
			);

			//thêm cán bộ vào Ban mới thành lập (nếu không có lỗi lúc thành lập)
			if (null != $BanID && $parameters['hoten']) {
				//từng cán bộ gia nhập ban
				$this->canboModel->huyGiaNhapBan($BanID);
				foreach ($parameters['hoten'] as $i => $macb) {

					$this->canboModel->giaNhapBan(
						$macb,
						$BanID, //ID of Ban (sau khi lấy được sau khi Thành lập ban mới)
						$parameters['ngaythanhlap'], //Ngày Cán bộ gia nhập cũng là ngày thành lập Ban
						$parameters['chucvu'][$i]
					);
				}
			}
		}

		// Show the tree
		$dsDonVi    = $this->donviModel->getBriefInfoList(); //load "danh sach Don Vi" from database
		$helper     = $this->getServiceLocator()->get('viewhelpermanager');
		$headScript = $helper->get('headscript');
		$headScript->appendFile(ROOT_PATH . 'public/script/jstree.min.js');
		$result           = $this->donviModel->getDSBanThuoc(0);
		$view['danhsach'] = $result;
		$jsTreeData       = array();
		// JS tree data
		foreach ($result as $ban) {
			$id       = $ban['Ma_Ban'];
			$canboban = $this->banModel->getDSCanBoThuocBan($id);

			if ($canboban[0] != '') {
				$danhsach = array();
				foreach ($canboban as $canbo) {
					$danhsach[] = array(
						'text' => $canbo['Ho_Ten_CB'],
					);
				}
				$jsTreeData[] = array(
					'id'       => $id,
					'text'     => $ban['Ten_Ban'],
					'children' => $danhsach,
				);
			} else {
				$jsTreeData[] = array(
					'id'   => $id,
					'text' => $ban['Ten_Ban'],
				);
			}
		}
		$view['jsTreeData'] = $jsTreeData;

		//init View
		$helper     = $this->getServiceLocator()->get('viewhelpermanager');
		$headScript = $helper->get('headscript');

		$headScript->appendFile(ROOT_PATH . 'public/script/ckeditor/ckeditor.js');
		$headScript->appendFile(ROOT_PATH . 'public/template/js/combobox.js');
		$this->layout('layout/home');

		//to View
		$view['dsDonVi']    = $dsDonVi; //load "danh sach Don Vi" from database
		$view['dsLoaiBan']  = $this->banModel->getLoaiHinhBan_List_Brief(0); //load "danh sach LoaiBan" from database
		$view['dsChucVu']   = $this->canboModel->getChucVuList(); //load "danh sach Chuc Vu" from database
		$view['dsCanBo']    = $this->canboModel->getAllBriefInfo(); //load "danh sach Cán Bộ" from database
		$view['dsPhongBan'] = $this->banModel->getDSBanHoatDong(); //load "danh sach phong ban" from database

		return new ViewModel($view);
	}

	public function loaihinhbanAction() {
		//init view
		$helper     = $this->getServiceLocator()->get('viewhelpermanager');
		$headScript = $helper->get('headscript');

		$headScript->appendFile(ROOT_PATH . 'public/script/ckeditor/ckeditor.js');
		$this->layout('layout/home');

		//Khi Có Yêu cầu tạo
		if ($this->getRequest()->isPost()) {
			//parameters
			$parameters = $this->getRequest()->getPost();

			//tạo ban mới
			$keuloaihinh = ($parameters['kieuloaihinh'] == '') ? null : $parameters['kieuloaihinh']; //process null value

			$LoaiHinhID = $this->banModel->insertLoaiHinhBan(
				$parameters['tenloaihinh'],
				$keuloaihinh,
				$parameters['mota']
			);

		}

		//load "danh sach Loai Hinh Ban" from database
		$view['dsKieuLoaiHinhBan'] = $this->banModel->getKieuLoaiHinhBan_List();
		$view['dsLoaiHinhBan']     = $this->banModel->getLoaiHinhBan_List();

		return new ViewModel($view);
	}

	/**
	 * view detail information of a "loại hình ban"
	 *
	 */
	public function thongtinbanAction() {
		//init view
		$id = $this->params('id');

		$banDetail             = $this->banModel->getBanDetailedInfo($id);
		$banDetail['canboban'] = array();
		$canboban              = $this->banModel->getDSCanBoThuocBan($id);
		if ($canboban[0] != '') {
			$banDetail['canboban'] = $canboban;
		}

		$json = json_encode($banDetail);

		$this->response->setContent($json);
		return $this->response;
	}

	/**
	 * view detail information of a "loại hình ban"
	 *
	 */
	public function thongtinloaihinhbanAction() {
		//init view
		$this->layout('layout/home');

		$id = $this->params('id');

		$view['data'] = $this->banModel->getLoaiHinhBanDetailedInfo($id);

		return new ViewModel($view);
	}

	/**
	 * Edit detail information of a "loại hình ban"
	 *
	 */
	public function chinhsualoaihinhbanAction() {
		//init view
		$this->layout('layout/home');
		$helper     = $this->getServiceLocator()->get('viewhelpermanager');
		$headScript = $helper->get('headscript');

		$headScript->appendFile(ROOT_PATH . 'public/script/ckeditor/ckeditor.js');
		$headScript->appendFile(ROOT_PATH . 'public/template/js/combobox.js');

		//process request
		if ($this->getRequest()->isPost()) {
			//get parameter
			$parameters = $this->getRequest()->getPost();
			$id         = $parameters['maloaihinhban'];
			$name       = $parameters['tenloaihinhban'];
			$type       = $parameters['kieuloaihinhban'];
			$meta       = $parameters['mota'];

			$LoaiHinhID = $this->banModel->updateLoaiHinhBan($id,
				$name,
				$type,
				$meta
			);
		}

		//to view
		$id                        = $this->params('id');
		$view['dsKieuLoaiHinhBan'] = $this->banModel->getKieuLoaiHinhBan_List();
		$view['data']              = $this->banModel->getLoaiHinhBanDetailedInfo($id);

		return new ViewModel($view);
	}

	public function tochuccocauAction() {
		$this->layout('layout/home');
		$dsDonVi    = $this->donviModel->getBriefInfoList(); //load "danh sach Don Vi" from database
		$helper     = $this->getServiceLocator()->get('viewhelpermanager');
		$headScript = $helper->get('headscript');
		$headScript->appendFile(ROOT_PATH . 'public/script/tree.js');
		$headScript->appendFile(ROOT_PATH . 'public/script/jstree.min.js');

		/*$result = array();
		foreach($dsDonVi as $donvi){
		$arr['MaDV']    = $donvi['Ma_ĐV'];
		$arr['TenDV']   = $donvi['Ten_Đon_Vi'];

		$ban = $this->donviModel->getDSBanThuoc($donvi['Ma_ĐV']);

		$arr['DanhSachBan'] = $ban;
		$result[] = $arr;
		}*/
		$result           = $this->donviModel->getDSBanThuoc(0);
		$view['danhsach'] = $result;
		$countResult      = sizeof($result);

		$data = array();
		foreach ($result as $ban) {
			$id       = $ban['Ma_Ban'];
			$canboban = $this->banModel->getDSCanBoThuocBan($id);
			$countResult += sizeof($canboban);

			if ($canboban[0] != '') {
				$danhsach = array();
				foreach ($canboban as $canbo) {
					$danhsach[] = array(
						'name' => $canbo['Ho_Ten_CB'],
					);
				}
				$data[] = array(
					'id'       => $id,
					'name'     => $ban['Ten_Ban'],
					'children' => $danhsach,
				);
			} else {
				$data[] = array(
					'name' => $ban['Ten_Ban'],
				);
			}

		}

		$view['data'] = $data;

		$jsTreeData = array();
		// JS tree data
		foreach ($result as $ban) {
			$id       = $ban['Ma_Ban'];
			$canboban = $this->banModel->getDSCanBoThuocBan($id);

			if ($canboban[0] != '') {
				$danhsach = array();
				foreach ($canboban as $canbo) {
					$danhsach[] = array(
						'text' => $canbo['Ho_Ten_CB'],
					);
				}
				$jsTreeData[] = array(
					'text'     => $ban['Ten_Ban'],
					'children' => $danhsach,
				);
			} else {
				$jsTreeData[] = array(
					'text' => $ban['Ten_Ban'],
				);
			}
		}
		$view['jsTreeData']  = $jsTreeData;
		$view['countResult'] = $countResult;

		return new ViewModel($view);
	}

	/**
	 * @description support Ajax
	 * @return \Zend\Stdlib\ResponseInterface
	 */
	public function getCanBoThuocBanAction() {
		$id     = $this->params('id');
		$result = $this->banModel->getDSCanBoThuocBan($id);

		//to View
		echo json_encode($result);

		return $this->response;
	}

	/**
	 * @description support Ajax, GET
	 * @return \Zend\Stdlib\ResponseInterface
	 */
	public function getBanThuocDonviAction() {
		//get parameter GET
		$id = $this->params('id');

		//load from Model
		$data = $this->donviModel->getDSBanThuoc($id);

		//to View
		echo json_encode($data);

		return $this->response; // do not render
	}

	/**
	 * view detail information of a "đơn vị"
	 *
	 */
	public function thongtindonviAction() {
		//init view
		$this->layout('layout/home');

		$id = $this->params('id');

		$view['data'] = $this->donviModel->getDetailedInfo($id);

		return new ViewModel($view);
	}

	/**
	 * @description: sử dụng cho load ajax trong tạo đơn vị
	 */
	public function danhsachdonviAction() {
		/**
		 * @var $request Request
		 */
		$request = $this->getRequest();
		$arr     = $request->getFiles()->toArray();
		$arr     = $arr['file'];
		//Kiểm tra file gửi lên có phải là file excel không
		if ($arr['type'] != 'application/vnd.ms-excel' && $arr['type'] != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
			echo 'Error';
		} else {
			error_reporting(E_ALL);
			set_time_limit(0);
			date_default_timezone_set('Europe/London');
			set_include_path(PHPEXCEL);
			include PHPEXCEL . '\PHPExcel\IOFactory.php';

			$inputFileName = $arr['tmp_name'];
			$objPHPExcel   = \PHPExcel_IOFactory::load($inputFileName);
			$sheetData     = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

			$first = 1;
			if ($arr['type'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
				$first = 0;
			}

			$arr = array();
			foreach ($sheetData as $key => $row) {
				if ($key != $first) {
					$arr[] = $row;
				}
			}
			echo json_encode($arr);
		}
		//Không render ra giao diện
		return $this->response;
	}

	public function xuatdonviAction() {
		//init model

		//check submit
		$listThongTin = $this->donviModel->getBriefInfoList();
		if ($this->getRequest()->isPost()) {
			$view['thongtin'] = $listThongTin;

			error_reporting(E_ALL);
			set_time_limit(0);
			date_default_timezone_set('Europe/London');
			set_include_path(PHPEXCEL);
			include PHPEXCEL . '\PHPExcel.php';

			$objPHPExcel = new \PHPExcel();

			// Set document properties
			$objPHPExcel->getProperties()->setCreator("UIT")
			            ->setLastModifiedBy("UIT")
			            ->setTitle("Office 2007 XLSX Test Document")
			            ->setSubject("Office 2007 XLSX Test Document")
			            ->setDescription("Quan ly can bo")
			            ->setKeywords("office 2007 openxml php")
			            ->setCategory("Quan ly can bo");

			// Add some data
			$objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue('A1', 'Hello')
			            ->setCellValue('B2', 'world!')
			            ->setCellValue('C1', 'Hello')
			            ->setCellValue('D2', 'world!');

			// Miscellaneous glyphs, UTF-8
			$objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue('A4', 'Miscellaneous glyphs');

			// Rename worksheet
			$objPHPExcel->getActiveSheet()->setTitle('Simple');

			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);

			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="01simple.xls"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			$objWriter->save('php://output');
			exit;
		}

		return $this->response;
	}

	/**
	 * @return ViewModel
	 */
	public function chinhsuadonviAction() {
		//init view
		$this->layout('layout/home');
		$helper     = $this->getServiceLocator()->get('viewhelpermanager');
		$headScript = $helper->get('headscript');

		$headScript->appendFile(ROOT_PATH . 'public/script/ckeditor/ckeditor.js');
		$headScript->appendFile(ROOT_PATH . 'public/template/js/combobox.js');

		//process request
		if ($this->getRequest()->isPost()) {
			//get parameter
			$parameters = $this->getRequest()->getPost();
			$id         = $parameters['madonvi'];

			//input filter
			////email
			$email = new Input('email');
			$email->getValidatorChain()
			      ->attach(new Validator\EmailAddress());

			$inputFilter = new InputFilter();
			$inputFilter->add($email)
			            ->setData($_POST);

			//objecting
			$donvi = new DonVi();
			$donvi->id($parameters['madonvi']); //id of what be edited
			$donvi->name($parameters['tendonvi']);

			//process "null value"
			$makhoi = ('' == $parameters['makhoi']) ? null : $parameters['makhoi'];

			//model process
			$this->donviModel->editInfo($id,
				$parameters['tendonvi'],
				$parameters['kyhieudonvi'],
				$makhoi, //"loại hình" is "Mã Khối"
				$parameters['ngaythanhlap'],
				$parameters['diachi'],
				$parameters['email'],
				$parameters['sodienthoai'],
				$parameters['mota'],
				$parameters['trangthai']
			); //process
		}

		//to view
		$id             = $this->params('id');
		$view['dsKhoi'] = $this->khoiModel->getBriefInfo();
		$view['data']   = $this->donviModel->getDetailedInfo($id);

		return new ViewModel($view);
	}

	public function thongtinbldAction() {
		/**
		 * @var $request Request
		 */
		$madv = $this->params('id');

		$result = $this->donviModel->getBCH_info($madv);

		print_r($result);
		//echo json_encode($result);

		return $this->response;
	}

}
