<?php
namespace Manager\Model;

use Zend\Db\Adapter\Adapter;

class DonViModel  extends  AbstractModel
{
    /**
     * only get list of ID
     * @return array of Ids
     */
    public function getIDsList(){
        //init
        $sql = 'SELECT Ma_ĐV FROM đon_vi';
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
            $data[] = $row['Ma_ĐV']; //get ID only

            $result->next();
        };


        return $data;
    }

    /**
     * lấy thông tin vấn tắt của tất cả đơn vị như: Mã Đơn Vị, Tên Đơn Vị.
     * @return array
     */
    public function getBriefInfoList(){
        //init
        $sql = 'SELECT  Ma_ĐV, Ky_Hieu_ĐV, Ten_Đon_Vi, Ngay_Thanh_Lap, Mo_Ta
                FROM đon_vi';
        $parameters = null;

        //process database
        //data to array
        $data = $this->query($sql,$parameters);


        return $data;
    }

    /**
     * lấy thông tin vấn tắt của một đơn vị như: Mã Đơn Vị, Tên Đơn Vị.
     * @return array
     */
    public function getBriefInfo($maDonVi){
        //init
        $sql = 'SELECT  Ma_ĐV, Ten_Đon_Vi, Ngay_Thanh_Lap, Mo_Ta FROM đon_vi
                WHERE Ma_ĐV = :maDonVi
                LIMIT 1;';

        $parameters = array(
            'maDonVi' => $maDonVi,
        );

        //process database
        //data to array
        $data = $this->query($sql,$parameters);


        return $data;
    }

    /**
     * Lấy thông tin của 1 đơn vị (chi tiết)
     * @param $maDonVi
     * @return array
     */
    public function getDetailedInfo($maDonVi){
        //init
        $sql = "SELECT `Ma_ĐV`, `Ky_Hieu_ĐV`, `Ten_Đon_Vi`, `Ma_Khoi`, `Ma_Truong_ĐV`, `Ma_Ban_Chap_Hanh`, DATE_FORMAT(`Ngay_Thanh_Lap`,'%d/%m/%Y') AS Ngay_Thanh_Lap,
                       `Chuc_Nang_ĐV` , `Đia_Chi`, `Email`, `So_Đien_Thoai`, `Mo_Ta`, `Trang_Thai`
                FROM `đon_vi`
                WHERE `Ma_ĐV` = :maDonVi
                LIMIT 1;";
        file_put_contents('sqllog.txt', $sql);

        $parameters = array(
            'maDonVi' => $maDonVi,
        );

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
        return $result->current();
    }

    /**
     * Lấy mã của Ban chấp hành của một đơn vị
     * @param $maDonVi mã của đơn vị cần lấy mã BCH
     * @return mixed trả về array(Ma_BCH, Ten_Ban, Loai_Hinh)
     */
    public function getBCH_Info($maDonVi){
        //init
        $sql = 'SELECT Ma_Ban AS Ma_BCH, Ten_Ban, loai_hinh_ban.Ten_Loai_Hinh_Ban AS Loai_Hinh
                FROM ban LEFT JOIN loai_hinh_ban ON (ban.Ma_Loai_Ban =loai_hinh_ban.Ma_Loai_Ban)
                WHERE   (Ma_Ban IN (SELECT Ma_Ban_Chap_Hanh FROM đon_vi WHERE Ma_ĐV = :maDonVi) )
                    AND (Trang_Thai=1)
                LIMIT 1;';
        $parameters = array(
            'maDonVi' => $maDonVi,
        );

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
        $data = $result->current()['Ma_Ban_Chap_Hanh'];


        return $data;
    }



    /**
     * lấy danh sách các Ban (đang hoạt động) của đơn vị
     * @param $maDonVi mã đơn vị cần lấy danh sách ban
     * @return array|null table(Ma_Ban, Ten_Ban)
     */
    public function getDSBanThuoc($maDonVi){
        //init
        $sql = "SELECT Ma_Ban, Ten_Ban FROM `ban`
                WHERE `Ma_Đon_Vi`= :maDonVi
                       AND `Trang_Thai` = 1;";
        $parameters = array(
            'maDonVi'    => $maDonVi
        );

        //process database
        $data = $this->query($sql,$parameters);

        return $data;
    }


    /**
     * lấy danh sách các Cán Bộ thuộc BCH của một đơn vị
     * @param $maDonVi mã đơn vị cần lấy
     * @return array|null (Ma_CB)
     */
    public function getDSCanBoBCHCuaDonVi($maDonVi){
        //init
        $sql = 'SELECT Ma_CB
                FROM thong_tin_tham_gia_ban
                WHERE Ma_Ban IN (SELECT Ma_Ban_Chap_Hanh FROM đon_vi WHERE Ma_ĐV = :maDonVi)
                     AND Ngay_Roi_Khoi IS NULL;';

        $parameters = array(
            'maDonVi'     =>  $maDonVi
        );

        //process database
        $result = $this->query($sql,$parameters);

        //data to array
        return $result;
    }


    /**
     * Thành lập đơn vị mới
     * @param $id
     * @param $name
     * @param $khoiID
     * @param $establishmentDate
     * @param $description
     */
    public function establish($id, $dvname, $khoiID, $establishmentDate, $description){
        //init query
        //format datatime to MySQL
        $establishmentDate = date('Y-m-d', strtotime($establishmentDate));


        //save new
        $sql = "INSERT INTO đon_vi (Ky_Hieu_ĐV, Ten_Đon_Vi, Ma_Khoi, Ngay_Thanh_Lap, Mo_Ta)
                           VALUES (:id,:dvname, :khoiID, :establishmentDate, :description);";


        //echo $khoiID;
        //print_r($parameters);
        //exit;

        $parameters = array(
            'id'=> $id,
            'khoiID'=> $khoiID,
            'dvname'=> $dvname,
            'establishmentDate' => $establishmentDate,
            'description' => $description,

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



    /**
     * Cập nhật thông tin của 1 đơn vị
     * @param $data
     * @return bool
     */
    public function editInfo($id,
                             $tendonvi, $kyhieu, $loaihinh  = null, $ngaythanhlap,
                             $diachi, $email, $sdt,
                             $mota, $trangthai){

        //date type processing
        $ngaythanhlap = $this->formatDateForDB($ngaythanhlap);

        //query
        $sql = 'UPDATE đon_vi SET Ten_Đon_Vi = :tenDonVi,
                                  Ky_Hieu_ĐV = :kyhieuDonVi,
                                  Ngay_Thanh_Lap = :Ngay_Thanh_Lap,
                                  Ma_Khoi = :Ma_Khoi,
                                  Đia_Chi = :diachi,
                                  Email = :Email,
                                  So_Đien_Thoai = :sdt,
                                  Mo_Ta = :Mo_Ta,
                                  Trang_Thai = :trangthai

                WHERE Ma_ĐV = :maDonVi;';



        //parameter
        $parameters = array(
            'maDonVi'           => $id,
            'tenDonVi'          => $tendonvi,
            'kyhieuDonVi'          => $kyhieu,
            'Ma_Khoi'           => $loaihinh,
            'Ngay_Thanh_Lap'    => $ngaythanhlap,
            'diachi'            => $diachi,
            'Email'             => $email,
            'sdt'               => $sdt,
            'Mo_Ta'             => $mota,
            'trangthai'         => $trangthai
        );

        //var_dump($parameters);exit;

        //execute query
        try{
            $sm = $this->adapter->createStatement();
            $sm->prepare($sql);
            $sm->execute($parameters);

        } catch (Exception $exc){
            //throw exception
            var_dump($exc);
        }
    }


    public function getLichSuLuanChuyen($madonvi, $tungay, $denngay){
        //sql (CRAZY)
        $sql = 'SELECT Ngay_Gia_Nhap, Ten_Ban, Ten_Đon_Vi, Ten_Chuc_Vu, Ngay_Roi_Khoi
                FROM thong_tin_tham_gia_ban LEFT JOIN ban ON (thong_tin_tham_gia_ban.Ma_Ban = ban.Ma_Ban)
                    LEFT JOIN đon_vi ON (ban.Ma_Đon_Vi = đon_vi.Ma_ĐV)
                    LEFT JOIN chuc_vu ON (thong_tin_tham_gia_ban.Ma_CV = chuc_vu.Ma_Chuc_Vu)

                WHERE ban.`Ma_Đon_Vi` = :madonvi
                      AND (Ngay_Gia_Nhap>=:tungay AND Ngay_Gia_Nhap<=:denngay);';


        //date type processing
        $tungay = $this->formatDateForDB($tungay);
        $denngay = $this->formatDateForDB($denngay);

        //parameter
        $parameters = array(
            'madonvi'  =>  $madonvi,
            'tungay'  =>  $tungay,
            'denngay'  =>  $denngay,
        );

        //execute query
        $data = $this->query($sql, $parameters);



        return $data;
    }


}