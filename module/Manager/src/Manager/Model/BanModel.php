<?php
namespace Manager\Model;

use Zend\Db\Adapter\Adapter;

class BanModel extends  AbstractModel
{
    /**
     * lấy danh sách các ban đang hoạt động
     * @return array|null table(Ma_Ban, Ten_Ban)
     */
    public function getDSBanHoatDong(){
        //init
        $sql = "SELECT Ma_Ban, Ten_Ban FROM `ban` WHERE `Trang_Thai` = 1;";
        $parameters = null;

        //process database
        $data = $this->query($sql,$parameters);

        return $data;
    }


    // get so luong member by Ma_Ban
    public function getNumMemberBan($Ma_Ban){
        //init
        $sql = "SELECT count(*) as num FROM `thong_tin_tham_gia_ban` WHERE `Ngay_Roi_Khoi` is NULL AND Ma_Ban = $Ma_Ban";
        $parameters = null;

        //process database
        $data = $this->query($sql,$parameters);
        if($data)
            return $data[0]['num'];
        return 0;

    }

    /*
     * lấy danh sách các ban đang hoạt động thuộc đơn vị
     * 
     */


    /**
     * lấy danh sách cán bộ (đang công tác) của một Ban
     * @param $maBan
     * @return array|null
     */
    public function getDSCanBoThuocBan($maBan){
        //init
        $sql = 'SELECT thong_tin_tham_gia_ban.Ma_CB, Ho_Ten_CB
                FROM thong_tin_tham_gia_ban LEFT JOIN can_bo ON (thong_tin_tham_gia_ban.Ma_CB = can_bo.Ma_Can_Bo)
                                            LEFT JOIN ly_lich ON (thong_tin_tham_gia_ban.Ma_CB = ly_lich.Ma_CB)
                WHERE Ma_Ban = :maBan
                     AND thong_tin_tham_gia_ban.Ngay_Roi_Khoi IS NULL;';

        $parameters = array(
            'maBan'     =>  $maBan
        );

        //process database
        $result = $this->query($sql,$parameters);

        //data to array
        return $result;
    }


    /**
     * Thành lập Ban CH mới, đồng thời kết thúc nhiệm kì Ban CH cũ, cập nhật lại BCH mới ở Đơn Vị
     * @param $Ma_Loai_Ban
     * @param $Ma_Đon_Vi
     * @param $Ten_Ban
     * @param $Ngay_Thanh_Lap
     * @param $Mo_Ta
     * @return mixed|null mã của Ban mới
     */
    public function establish($Ma_Loai_Ban,$Ma_Đon_Vi, $Ten_Ban, $Ngay_Thanh_Lap, $Mo_Ta){
        //init query
        //format datatime to MySQL
        $Ngay_Thanh_Lap = date('Y-m-d', strtotime($Ngay_Thanh_Lap));


        //save new
        $sql1 = 'INSERT INTO `Ban` (`Ma_Loai_Ban`, `Ma_Đon_Vi`, `Ten_Ban`, `Ngay_Thanh_Lap`, `Ngay_Man_Nhiem`, `Mo_Ta`)
                           VALUES (:Ma_Loai_Ban,:Ma_Don_Vi, :Ten_Ban, :Ngay_Thanh_Lap, NULL, :Mo_Ta);
                  SELECT LAST_INSERT_ID() INTO @maBanMoi;
                  SELECT @maBanMoi AS Ma_Ban_Moi;
                           ';

        //end old-Ban (set EndTime)
        $sql2 = "Update Ban Set Ngay_Man_Nhiem = :Ngay_Thanh_Lap, Trang_Thai = 0
                    WHERE Ban.Ma_Ban = (Select Ma_Ban_Chap_Hanh From Đon_Vi where Ma_ĐV = :Ma_Don_Vi LIMIT 1);";

        //set Ban mới là BCH của Đơn Vị
        $sql3 = "Update Đon_Vi Set Ma_Ban_Chap_Hanh = @maBanMoi
                 WHERE Ma_ĐV = :Ma_Don_Vi;";

        //set parameters
        $parameters = array(
            'Ma_Loai_Ban'=> $Ma_Loai_Ban,
            'Ma_Don_Vi'=> $Ma_Đon_Vi,
            'Ten_Ban'=> $Ten_Ban,
            'Ngay_Thanh_Lap' => $Ngay_Thanh_Lap,
            'Mo_Ta' => $Mo_Ta,
        );


        //query
        $sql = $sql1.$sql2.$sql3;

        //process database
        $sm = $this->adapter->createStatement($sql,$parameters);
        $sm->prepare();


        $result = null;
        try{
            $result = $sm->execute();
        } catch (Exception $exc){
            var_dump($exc);
        }


        //new Ban ID
        $data  = $result->getGeneratedValue();

        return $data;
    }

    public function thanhlapBanchucnang($Ma_Ban=0, $Ma_Loai_Ban,$Ma_Đon_Vi, $Ten_Ban, $Ngay_Thanh_Lap, $Mo_Ta){
        //init query
        //format datatime to MySQL
        $Ngay_Thanh_Lap = date('Y-m-d', strtotime($Ngay_Thanh_Lap));
        // lets update begin
        $sql = 'UPDATE `Ban` SET `Ma_Loai_Ban` = :Ma_Loai_Ban, `Ma_Đon_Vi` = :Ma_Don_Vi, 
                                 `Ten_Ban` = :Ten_Ban, `Ngay_Thanh_Lap` = :Ngay_Thanh_Lap, 
                                 `Ngay_Man_Nhiem` = NULL, `Mo_Ta` = :Mo_Ta 
                WHERE `Ma_Ban` = :Ma_Ban; ';

        //set parameters
        $parameters = array(
            'Ma_Ban' => $Ma_Ban,
            'Ma_Loai_Ban'=> $Ma_Loai_Ban,
            'Ma_Don_Vi'=> $Ma_Đon_Vi,
            'Ten_Ban'=> $Ten_Ban,
            'Ngay_Thanh_Lap' => $Ngay_Thanh_Lap,
            'Mo_Ta' => $Mo_Ta,
        );    

        $this->executeNonQuery($sql,$parameters);
        $result = $Ma_Ban;
        return $result;
    }

    public function thanhlapBanchucnangBK($Ma_Ban=0, $Ma_Loai_Ban,$Ma_Đon_Vi, $Ten_Ban, $Ngay_Thanh_Lap, $Mo_Ta){
        //init query
        //format datatime to MySQL
        $Ngay_Thanh_Lap = date('Y-m-d', strtotime($Ngay_Thanh_Lap));


        if (empty($Ma_Ban)) {

            //save new
            $sql = 'INSERT INTO `Ban` (`Ma_Loai_Ban`, `Ma_Đon_Vi`, `Ten_Ban`, `Ngay_Thanh_Lap`, `Ngay_Man_Nhiem`, `Mo_Ta`)
                               VALUES (:Ma_Loai_Ban,:Ma_Don_Vi, :Ten_Ban, :Ngay_Thanh_Lap, NULL, :Mo_Ta);
                      SELECT LAST_INSERT_ID() INTO @maBanMoi;
                      SELECT @maBanMoi AS Ma_Ban_Moi;
                               ';


            //set parameters
            $parameters = array(
                'Ma_Loai_Ban'=> $Ma_Loai_Ban,
                'Ma_Don_Vi'=> $Ma_Đon_Vi,
                'Ten_Ban'=> $Ten_Ban,
                'Ngay_Thanh_Lap' => $Ngay_Thanh_Lap,
                'Mo_Ta' => $Mo_Ta,
            );

            //process database
            $result = $this->executeNonQuery($sql,$parameters);
        }
        // Update not create new
        else {

            // lets update begin
            $sql = 'UPDATE `Ban` SET `Ma_Loai_Ban` = :Ma_Loai_Ban, `Ma_Đon_Vi` = :Ma_Don_Vi, 
                                     `Ten_Ban` = :Ten_Ban, `Ngay_Thanh_Lap` = :Ngay_Thanh_Lap, 
                                     `Ngay_Man_Nhiem` = NULL, `Mo_Ta` = :Mo_Ta 
                    WHERE `Ma_Ban` = :Ma_Ban; ';

            //set parameters
            $parameters = array(
                'Ma_Ban' => $Ma_Ban,
                'Ma_Loai_Ban'=> $Ma_Loai_Ban,
                'Ma_Don_Vi'=> $Ma_Đon_Vi,
                'Ten_Ban'=> $Ten_Ban,
                'Ngay_Thanh_Lap' => $Ngay_Thanh_Lap,
                'Mo_Ta' => $Mo_Ta,
            );    

            $this->executeNonQuery($sql,$parameters);
            $result = $Ma_Ban;
        }

        //var_dump($result);exit;

        return $result;
    }

    /**
     * lấy thông tin vấn tắt danh sách Loại Hình Ban
     *
     * @param null $kieuID mã của Kiểu Loại Hình với 0: Ban Thường, 1: Ban Lãnh Đạo, null: lấy toàn bộ
     * @return array|null
     */
    public function getLoaiHinhBan_List_Brief($kieuID=null){
        //init
        $sql = null;

        //var_dump($kieuID);exit;
        $parameters=null;
        if(is_null($kieuID)){
            $sql = "SELECT `Ma_Loai_Ban`, `Ten_Loai_Hinh_Ban`
                FROM `loai_hinh_ban`";
        }else{
            //parameter
            $parameters = array(
                'kieuID'    => $kieuID
            );

            $sql = "SELECT `Ma_Loai_Ban`, `Ten_Loai_Hinh_Ban`
                FROM `loai_hinh_ban`
                WHERE `Ma_Kieu_Loai_Hinh` = :kieuID ";
        }


        //var_dump($sql);exit;


        //process database
        $data = $this->query($sql, $parameters);


        return $data;
    }

    /**
     * lấy thông tin đầy đủ danh sách Loại Hình Ban
     *
     * @param null $kieuID mã của Kiểu Loại Hình với 0: Ban Thường, 1: Ban Lãnh Đạo, null: lấy toàn bộ
     * @return array|null
     */
    public function getLoaiHinhBan_List($kieuID=null){
        //init
        $sql = null;
        $parameters=null;
        if(null==$kieuID){
            $sql = "SELECT `Ma_Loai_Ban`, `Ten_Loai_Hinh_Ban`, kieu_loai_hinh_ban.Ten_KLHB as Kieu_Loai_Hinh_Ban, loai_hinh_ban.Mo_Ta
                    FROM `loai_hinh_ban`
                    LEFT JOIN kieu_loai_hinh_ban ON (loai_hinh_ban.Ma_Kieu_Loai_Hinh = kieu_loai_hinh_ban.Ma_KLHB);";
        }else{
            //parameter
            $parameters = array(
                'kieuID'    => $kieuID
            );

            $sql = "SELECT `Ma_Loai_Ban`, `Ten_Loai_Hinh_Ban`, kieu_loai_hinh_ban.Ten_KLHB as Kieu_Loai_Hinh_Ban, loai_hinh_ban.Mo_Ta
                    FROM `loai_hinh_ban`
                    LEFT JOIN kieu_loai_hinh_ban ON (loai_hinh_ban.Ma_Kieu_Loai_Hinh = kieu_loai_hinh_ban.Ma_KLHB)
                    WHERE `Ma_Kieu_Loai_Hinh` = :kieuID ";
        }




        //process database
        $data = $this->query($sql, $parameters);


        return $data;
    }

    public function getBanDetailedInfo($banID) {
        // Init
        $sql = null;
        $parameters = null;
        $return = false;

        $sql = "SELECT * FROM `ban` WHERE `Ma_Ban` = $banID;";

        // process database
        $data = $this->query($sql, $parameters);
        if(!empty($data[0]))
            $return = $data[0];

        return $return;
    }

    public function getLoaiHinhBanDetailedInfo($loaiBanID) {
        // Init
        $sql = null;
        $parameters = null;
        $return = false;

        $sql = "SELECT `Ma_Loai_Ban`, `Ten_Loai_Hinh_Ban`, kieu_loai_hinh_ban.Ma_KLHB as Ma_KLHB,  kieu_loai_hinh_ban.Ten_KLHB as Kieu_Loai_Hinh_Ban, loai_hinh_ban.Mo_Ta
                    FROM `loai_hinh_ban`
                    LEFT JOIN kieu_loai_hinh_ban ON (loai_hinh_ban.Ma_Kieu_Loai_Hinh = kieu_loai_hinh_ban.Ma_KLHB)
                    WHERE `Ma_Loai_Ban` = $loaiBanID;";

        // process database
        $data = $this->query($sql, $parameters);
        if(!empty($data[0]))
            $return = $data[0];

        return $return;
    }

    /**
     * lấy danh sách các kiểu loại hình ban
     * @return array|null
     */
    public function getKieuLoaiHinhBan_List(){
        //init
        $sql = "SELECT `Ma_KLHB`, `Ten_KLHB`
                FROM `kieu_loai_hinh_ban`";
        $parameters = null;

        //process database
        $data = $this->query($sql,$parameters);

        return $data;
    }

    /**
     * tạo Loại Hình Ban mới
     * @param $tenLoaiHinh
     * @param $mota
     * @param $maKieuLHB
     * @return mixed|null mã của Loại Hình Ban mới. Kết quả null nếu không lưu thành công
     */
    public function insertLoaiHinhBan($tenLoaiHinh, $maKieuLHB, $mota){
        //init query
        $sql = ' CALL `sp_themLoaiHinhBan`(:teLoaiHinh, :maKieuLHB,:mota);';

        //set parameters
        $parameters = array(
            'teLoaiHinh'=> $tenLoaiHinh,
            'maKieuLHB'=> $maKieuLHB,
            'mota' => $mota,
        );


        //process database
        $result = $this->executeNonQuery($sql, $parameters);

        return $result;
    }

    public function updateLoaiHinhBan($id, $tenLoaiHinh, $maKieuLHB, $mota) {

        // Init query
        $sql = " UPDATE `loai_hinh_ban` SET `Ten_Loai_Hinh_Ban` = :tenLoaiHinh, `Ma_Kieu_Loai_Hinh` = :maKieuLHB, `Mo_Ta` = :mota WHERE `Ma_Loai_Ban` = :id; ";
        $parameters = array(
            'id'            => $id,
            'tenLoaiHinh'   => $tenLoaiHinh,
            'maKieuLHB'     => $maKieuLHB,
            'mota'          => $mota,
        );

        // process database
        $data = $this->executeNonQuery($sql, $parameters);
        return $data;
    }
}