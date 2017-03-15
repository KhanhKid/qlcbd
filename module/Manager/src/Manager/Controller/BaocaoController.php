<?php
//Khai bao namespace 
namespace Manager\Controller;

//Load lớp AbstractActionController vào CONTROLLER
use Zend\Mvc\Controller\AbstractActionController;
use Manager\Model\CanBoModel;

//Load lớp ViewModel vào CONTROLLER
use Zend\View\Model\ViewModel;

//auth
use Zend\Authentication\AuthenticationService;

class BaocaoController extends AbstractActionController
{
    private  $kieu = array(
        'bang'      => '=',
        'nhohon'    => '<',
        'lonhon'    => '>',
        'tuongtu'    => 'like',
    );
    private $thongtinhienthi = array(
        'sohieu'        => array(
                                    'field' => 'So_Hieu_CB',
                                    'text'  => 'Số hiệu cán bộ',
                                    'table' => 'ly_lich'
        ),

        'hoten'         => array(
                                    'field' => 'Ho_Ten_Khai_Sinh',
                                    'text'  => 'Họ tên',
                                    'table' => 'ly_lich'
        ),
        'tengoikhac'         => array(
                                    'field' => 'Ten_Goi_Khac',
                                    'text'  => 'Tên gọi khác',
                                    'table' => 'ly_lich'
        ),
        'gioitinh'          => array(
                                    'field' => 'Gioi_Tinh',
                                    'text'  => 'Giới tính',
                                    'table' => 'ly_lich'
        ),
        'cmnd'              => array(
                                    'field' => 'So_CMND',
                                    'text'  => 'CMND',
                                    'table' => 'ly_lich'
        ),
        'ngaycapcmnd'         => array(
                                    'field' => 'Ngay_Cap_CMND',
                                    'text'  => 'Ngày cấp CMND',
                                    'table' => 'ly_lich'
        ),
        'noicapcmnd'         => array(
                                    'field' => 'Noi_Cap_CMND',
                                    'text'  => 'Nơi cấp CMND',
                                    'table' => 'ly_lich'

        ),
        'ngaysinh'         => array(
                                    'field' => 'Ngay_Sinh',
                                    'text'  => 'Ngày sinh',
                                    'table' => 'ly_lich'
        ),
        'noisinh'         => array(
                                    'field' => 'Noi_Sinh',
                                    'text'  => 'Nơi sinh',
                                    'table' => 'ly_lich'
        ),
        'quequan'         => array(
                                    'field' => 'Que_Quan',
                                    'text'  => 'Quê quán',
                                    'table' => 'ly_lich'
        ),
        'noiohiennay'         => array(
                                    'field' => 'Noi_O_Hien_Nay',
                                    'text'  => 'Nơi ở hiện nay',
                                    'table' => 'ly_lich'
        ),
        'dantoc'         => array(
                                    'field' => 'Dan_Toc',
                                    'text'  => 'Dân tộc',
                                    'table' => 'ly_lich'
        ),
        'tongiao'         => array(
                                    'field' => 'Ton_Giao',
                                    'text'  => 'Tôn giáo',
                                    'table' => 'ly_lich'
        ),
        'nghenghiep'         => array(
                                    'field' => 'Nghe_Nghiep_Truoc_Đo',
                                    'text'  => 'Nghề nghiệp trước đó',
                                    'table' => 'ly_lich'
        ),
        'dienthoai'         => array(
                                    'field' => 'Đien_Thoai',
                                    'text'  => 'Điện thoại',
                                    'table' => 'ly_lich'
        ),
        'tpgiadinh'         => array(
                                    'field' => 'Thanh_Phan_Gia_Đinh_Xuat_Than',
                                    'text'  => 'Thành phần gia đình',
                                    'table' => 'ly_lich'
        ),
        'capuyhientai'      => array(
                                    'field' => 'Cap_Uy_Hien_Tai',
                                    'text'  => 'Cấp ủy hiện tại',
                                    'table' => 'ly_lich'
        ),
        'capuykiem'         => array(
                                    'field' => 'Cap_Uy_Kiem',
                                    'text'  => 'Cấp ủy kiêm',
                                    'table' => 'ly_lich'
        ),
        'ngayvaodang'       => array(
                                    'field' => 'Ngay_Vao_Đang',
                                    'text'  => 'Ngày vào Đảng',
                                    'table' => 'ly_lich'
        ),
        'ngaychinhthuc'      => array(
                                    'field' => 'Ngay_Chinh_Thuc',
                                    'text'  => 'Ngày vào Đảng chính thức',
                                    'table' => 'ly_lich'
        ),
        'chuyennganh'        => array(
                                    'field' => 'Chuyen_Nganh',
                                    'text'  => 'Chuyên ngành',
                                    'table' => 'ly_lich'
        ),
        'hocham'             => array(
                                    'field' => 'Hoc_Ham',
                                    'text'  => 'Học hàm',
                                    'table' => 'ly_lich'
        ),
        'lyluanchinhtri'     => array(
                                    'field' => 'Cap_Đo_CTLL',
                                    'text'  => 'Lý luận chính trị',
                                    'table' => 'ly_lich'
        ),
        'trinhdohocvan'      => array(
                                    'field' => 'Trinh_Đo_Hoc_Van',
                                    'text'  => 'Trình độ học vấn',
                                    'table' => 'ly_lich'
        ),

        'trinhdochuyenmon'    => array(
                                    'field' => 'Cap_Đo_TĐCM',
                                    'text'  => 'Trình độ chuyên môn',
                                    'table' => 'ly_lich'
        ),
        'danhhieuduocphong'    => array(
                                    'field' => 'Danh_Hieu_Đuoc_Phong',
                                    'text'  => 'Danh hiệu được phong',
                                    'table' => 'ly_lich'
        ),
        'tinhtrangsuckhoe'     => array(
                                    'field' => 'Tinh_Trang_Suc_Khoe',
                                    'text'  => 'Tình trạng sức khỏe',
                                    'table' => 'ly_lich'
        ),
        'chieucao'          => array(
                                    'field' => 'Chieu_Cao',
                                    'text'  => 'Chiều cao',
                                    'table' => 'ly_lich'
        ),
        'cannang'          => array(
                                    'field' => 'Can_Nang',
                                    'text'  => 'Cân nặng',
                                    'table' => 'ly_lich'
        ),
        'nhommau'          => array(
                                    'field' => 'Nhom_Mau',
                                    'text'  => 'Nhóm máu',
                                    'table' => 'ly_lich'
        ),
        'loaithuongbinh'          => array(
                                    'field' => 'Loai_Thuong_Binh',
                                    'text'  => 'Loại thương binh',
                                    'table' => 'ly_lich'
        ),
        'giadinhlietsy'          => array(
                                    'field' => 'Gia_Đinh_Liet_Sy',
                                    'text'  => 'Gia đình liệt sỹ',
                                    'table' => 'ly_lich'
        ),
        'luongthunhapnam'          => array(
                                    'field' => 'Luong_Thu_Nhap_Nam',
                                    'text'  => 'Thu nhập mỗi năm',
                                    'table' => 'ly_lich'
        )
    );

    private $thongtintruyvan = array(
        array(
                'name'     => 'gioitinh',
                'text'      => 'Giới tính',
                'type'      => 'select',
                'values'    => array(
                array(
                    'value'   => '0',
                    'text' => 'Nam'
                ),
                array(
                    'value'   => '1',
                    'text' => 'Nữ'
                ),

            ),
        ),
        array(
                'name'      => 'dangvien',
                'text'      => 'Đảng viên',
                'type'      => 'select',
                'values'    => array(
                    array(
                        'value'   => '1',
                        'text' => 'Có'
                    ),
                    array(
                        'value'   => '0',
                        'text' => 'Không'
                    )
                ),
            ),
        array(
            'name'  => 'lyluanchinhtri',
            'text'      => 'Lý luận chính trị',
            'type'      => 'select',
            'values'    => array(
                array(
                    'value'=> '0',
                    'text' => 'Không'
                ),
                array(
                    'value'=> '1',
                    'text' => 'Sơ cấp'
                ),
                array(
                    'value' => '2',
                    'text' => 'Trung cấp'
                ),
                array(
                    'value' => '3',
                    'text'  => 'Cao cấp'
                )
            ),
        ),
        array(
            'name'  => 'trinhdohocvan',
            'text'      => 'Trình độ học vấn',
            'type'      => 'select',
            'values'    => array(
                array(
                    'value'=> '9/12',
                    'text' => '9/12'
                ),
                array(
                    'value' => '12/12',
                    'text' => '12/12'
                )
            ),
        ),
        array(
            'name'      => 'sohieu',
            'text'      => 'Số hiệu cán bộ',
            'type'      => 'text'
        ),
        array(
            'name'      => 'ngaysinh',
            'text'      => 'Ngày sinh',
            'type'      => 'date'
        ),
        array(
            'name'      => 'noisinh',
            'text'      => 'Nơi sinh',
            'type'      => 'text'
        ),
        array(
            'name'      => 'quequan',
            'text'      => 'Quê quán',
            'type'      => 'text'
        ),
        array(
            'name'      => 'cmnd',
            'text'      => 'CMND',
            'type'      => 'text'
        ),
        array(
            'name'      => 'capuyhientai',
            'text'      => 'Cấp Ủy hiện tại',
            'type'      => 'text'
        ),
        array(
            'name'      => 'capuykiem',
            'text'      => 'Cấp Ủy kiêm',
            'type'      => 'text'
        ),
        array(
            'name'      => 'luongthunhapnam',
            'text'      => 'Thu nhập mỗi năm',
            'type'      => 'number'
        )
    );


    public $donviModel;

    public function indexAction()
    {

    }

    public function truyvanAction(){
        $this->layout('layout/home');
        $helper = $this->getServiceLocator()->get('viewhelpermanager');
        $headScript = $helper->get('headscript');
        $headScript->appendFile('/script/datatable/media/js/jquery.dataTables.js');
        $headScript->appendFile('/script/datatable/extras/TableTools/media/js/ZeroClipboard.js');
        $headScript->appendFile('/script/datatable/extras/TableTools/media/js/TableTools.js');
        $headScript->appendFile('/script/datatable/extras/ColReorder/media/js/ColReorder.js');
        $view = array();


        $canboModel = $this->getServiceLocator()->get('Manager\Model\CanBoModel');
        $dantoc = $canboModel->getDanTocList();
        $thongtin = array();
        foreach ($dantoc as $item){
            $thongtin[] = array('value' => $item['Ma_Dan_Toc'], 'text' => $item['Ten_Dan_Toc']);
        }
        $this->thongtintruyvan[] = array(
            'name'      => 'dantoc',
            'text'      => 'Dân tộc',
            'type'      => 'select',
            'values'    => $thongtin
        );

        $tongiao = $canboModel->getTonGiaoList();
        $thongtin = array();
        foreach ($tongiao as $item){
            $thongtin[] = array('value' => $item['Ma_Ton_Giao'], 'text' => $item['Ten_Ton_Giao']);
        }
        $this->thongtintruyvan[] = array(
            'name'      => 'tongiao',
            'text'      => 'Tôn giáo',
            'type'      => 'select',
            'values'    => $thongtin
        );

        /*$tongiao = $canboModel->getTonGiaoList();
        $thongtin = array();
        foreach ($tongiao as $item){
            $thongtin[] = array('value' => $item['Ma_Ton_Giao'], 'text' => $item['Ten_Ton_Giao']);
        }
        $this->thongtintruyvan[] = array(
            'name'      => 'tongiao',
            'text'      => 'Tôn giáo',
            'type'      => 'select',
            'values'    => $thongtin
        );*/


        $view['thongtinhienthi'] = $this->thongtinhienthi;
        //Lưu thông tin các giá trị trong form
        $view['thongtintruyvan'] = $this->thongtintruyvan;

        return new ViewModel($view);

    }


    public function gettruyvanAction(){
        if($this->getRequest()->isPost()){
            $parameters = $this->getRequest()->getPost();

            //to model
            $thongtin = $parameters->toArray();

            $table = array();

            $strHienThi = $thongtin['hienthi'];

            foreach ($strHienThi as $key => &$value){
                $table[] = $this->thongtinhienthi[$value]['table'];
                //echo array_search($this->thongtinhienthi[$value]['table'], $table);
                $value = $this->thongtinhienthi[$value]['field'];

            }
            $table = array_unique($table);
            foreach ($table as &$value){
                $value = '`' . $value . '`';
            }

            $tableSelect = implode(',', $table);
            $strHienThi = implode(', ', $strHienThi);


            if (isset($thongtin['truyvan'])){
                $strTruyVan = $thongtin['truyvan'];
                foreach ($strTruyVan as $key => &$value){
                    if ($value == 'dangvien')
                        $value = 'Ngay_Vao_Đang';
                    else
                        $value = $this->thongtinhienthi[$value]['field'];
                }


                $strKieuSoSanh = $thongtin['kieusosanh'];

                $strGiaTri = $thongtin['giatri'];

                $arrDieuKien = array();

                $size = count($strTruyVan);

                $final = array();
                for ($i = 0; $i < $size; $i++){
                    if ($strTruyVan[$i]=='Ngay_Vao_Đang'){
                        if ($strGiaTri[$i] == '1'){
                            $final[$strTruyVan[$i]][] = array('is not', 'null');
                        }
                        else{
                            $final[$strTruyVan[$i]][] = array('is', 'null');
                        }
                    }
                    else{
                        $final[$strTruyVan[$i]][] = array($this->kieu[$strKieuSoSanh[$i]], $strGiaTri[$i]);
                    }

                }

                foreach ($final as $key => $item){
                    $tmp = array();
                    foreach ($item as $value){
                        if ($value[0] == 'like')
                            $tmp[] = $key . ' '. $value[0] . ' "%' . $value[1] . '%"';
                        else
                        {
                            if ($value[1] == 'null'){
                                $tmp[] = $key . ' '. $value[0] . ' ' . $value[1] . '';
                            }
                            else{
                                $tmp[] = $key . ' '. $value[0] . ' "' . $value[1] . '"';
                            }

                        }

                    }
                    $arrDieuKien[] = '('. implode(' or ', $tmp) . ')';
                }

                $strDieuKien = implode(' and ', $arrDieuKien);
            }
            else{
                $strDieuKien = '1';
            }
            //echo $strDieuKien;

            $strSQL = 'select ' . $strHienThi . ' from ' . $tableSelect . ' where ' . $strDieuKien;
            file_put_contents('logsql.txt', $strSQL);
            //echo '<br/>', $strSQL; return $this->response;


            $canboModel = $this->getServiceLocator()->get('Manager\Model\CanBoModel');
            $ketqua = $canboModel->truyvan($strSQL);


            $dantoc = $canboModel->getDanTocList();
            $thongtin = array();
            foreach ($dantoc as $item){
                $thongtin[$item['Ma_Dan_Toc']] = $item['Ten_Dan_Toc'];
            }
            $dantoc = $thongtin;

            $tongiao = $canboModel->getTonGiaoList();
            $thongtin = array();
            foreach ($tongiao as $item){
                $thongtin[$item['Ma_Ton_Giao']] = $item['Ten_Ton_Giao'];
            }
            $tongiao = $thongtin;

            $LLCT = $canboModel->getTrinhDoLLCTList();
            $thongtin = array();
            foreach ($LLCT as $item){
                $thongtin[$item['Cap_Đo_LLCT']] = $item['Ten_CTLL'];
            }
            $LLCT = $thongtin;

            $chuyenmon = $canboModel->getTrinhDoChuyenMonList();
            $thongtin = array();
            foreach ($chuyenmon as $item){
                $thongtin[$item['Cap_Đo_TĐCM']] = $item['Ten_TĐCM'];
            }
            $chuyenmon = $thongtin;

            $swicher = array(
                'Gioi_Tinh' => array('Nam', 'Nữ'),
                'Cap_Đo_CTLL'=> $LLCT,
                'Dan_Toc' => $dantoc,
                'Ton_Giao' => $tongiao,
                'Cap_Đo_TĐCM'=> $chuyenmon
            );

            if (!empty($ketqua)){
                //Re-index to numberic
                $trave = array();

                foreach ($ketqua as $item){
                    $tmp = array();
                    foreach ($item as $key => $value){
                        //Chuẩn hóa dữ liệu
                        if (isset($swicher[$key][$value])){
                            $value = $swicher[$key][$value];
                        }
                        else{
                            switch ($key){
                                case 'Ngay_Cap_CMND':
                                case 'Ngay_Sinh':
                                case 'Ngay_Vao_Đang':
                                case 'Ngay_Chinh_Thuc':
                                    $value = date('d/m/Y', strtotime($value));
                                    break;
                                default:
                                    break;
                            }
                        }

                        $tmp[] = $value;
                    }
                    $trave[] = $tmp;
                }
                echo json_encode($trave);
            }
            else{
                echo 'null';
            }

        }
        return $this->response;
    }


    public function canboAction(){
        $this->layout('layout/home');
    }



    public function cannangluongAction(){
        //init view
        $this->layout('layout/home');

        //init model
        $canboModel = $this->getServiceLocator()->get('Manager\Model\CanBoModel');

        $ngayxet = date('Y-m-d');
        $sonam = 3;
        if($this->getRequest()->isPost()){
            $parameters = $this->getRequest()->getPost();
            $ngayxet = $parameters['ngayxet'];
            $sonam = $parameters['sonam'];
        }


        $view['dscannangluong'] = $canboModel->getDSCanNangLuong($ngayxet, $sonam);

        //var_dump($view);exit;

        return new ViewModel($view);
    }

    public function luongAction(){
        //init model
        $canboModel = $this->getServiceLocator()->get('Manager\Model\CanBoModel');

        //process
        if($this->getRequest()->isPost()){
            $parameters = $this->getRequest()->getPost();

        }

        //get parameter from request
        $id = $this->params('id');


        $view = null;
        $view['quatrinhluong'] = $canboModel->getQuaTrinhLuong($id);
        $view['canbo'] = $canboModel->getBriefInfo($id);

        return new ViewModel($view);
    }


    public  function lichsudonviAction(){
        //
        $madonvi = 0; //default
        $tungay = date('Y-m-d');
        $denngay = date('Y-m-d');


        $view['quatrinhluanchuyen'] = $this->donviModel->getLichSuLuanChuyen($madonvi, $tungay, $denngay);

        //process request
        if($this->getRequest()->isPost()){
            $parameters=$this->getRequest()->getPost();
            //var_dump($parameters);exit;
            //insert
            $madonvi = $parameters['donvi'];
            $tungay =  $parameters['tungay'];
            $denngay = $parameters['denngay'];

            $view['quatrinhluanchuyen'] = $this->donviModel->getLichSuLuanChuyen($madonvi, $tungay, $denngay);
        }


        //get data from model
        $view['dsDonVi'] = $this->donviModel->getBriefInfoList(); //load "danh sach Don Vi" from database




        //send data to view
        return new ViewModel($view);
    }
}

