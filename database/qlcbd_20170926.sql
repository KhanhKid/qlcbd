-- phpMyAdmin SQL Dump
-- version 4.0.10.12
-- http://www.phpmyadmin.net
--
-- Host: 127.4.144.130:3306
-- Generation Time: Sep 26, 2017 at 02:32 AM
-- Server version: 5.5.52
-- PHP Version: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `qlcbd`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getCadreWorkInfo`()
    NO SQL
select can_bo.Ma_Can_Bo, Ho_Ten_CB, DATE_FORMAT(Ngay_Sinh,'%d/%m/%Y') AS Ngay_Sinh, So_CMND,
                       chuc_vu.Ten_Chuc_Vu
                from can_bo left join ly_lich on( can_bo.Ma_Can_Bo = ly_lich.Ma_CB)
                            left join chuc_vu on (can_bo.Ma_CV_Chinh = chuc_vu.Ma_Chuc_Vu)
                WHERE Ngay_Roi_Khoi IS NULL$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_layDSCanBoTrucThuoc`(IN `maCB` INT)
    NO SQL
SELECT Ma_CB AS Ma_Can_Bo, Ho_Ten_CB
                FROM thong_tin_tham_gia_ban t1 LEFT JOIN can_bo ON (t1.Ma_CB = can_bo.Ma_Can_Bo)
                WHERE t1.Ngay_Roi_Khoi IS NULL
                      AND Ma_Ban  = (SELECT t2.Ma_Ban
                                     FROM thong_tin_tham_gia_ban t2
                                     WHERE t2.Ma_CB = maCB
                                         AND t2.Ngay_Roi_Khoi IS NULL
                                         AND t2.Ma_CV IN (SELECT Ma_Chuc_Vu FROM chuc_vu WHERE Ma_Cap IN (1,2,6,7))
                                    )$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_layDSCanBoVoiThongTinVanTat`()
    NO SQL
SELECT can_bo.Ma_Can_Bo, Ho_Ten_CB, DATE_FORMAT(Ngay_Sinh,'%d/%m/%Y') AS Ngay_Sinh, SO_CMND
                from can_bo left join Ly_Lich on( can_bo.Ma_Can_Bo = ly_lich.Ma_CB)
                WHERE (Ngay_Roi_Khoi IS NULL)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_layThongTinBCH`(IN `maDonVi` VARCHAR(32))
    NO SQL
SELECT Ma_Ban AS Ma_BCH, Ten_Ban, loai_hinh_ban.Ten_Loai_Hinh_Ban AS Loai_Hinh
                FROM ban LEFT JOIN loai_hinh_ban ON (ban.Ma_Loai_Ban =loai_hinh_ban.Ma_Loai_Ban)
                WHERE   (Ma_Ban IN (SELECT Ma_Ban_Chap_Hanh FROM đon_vi WHERE Ma_Đon_Vi = maDonVi) )
                    AND (Trang_Thai=1)
                LIMIT 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_layThongTinCanBo`(IN `maCanBo` INT)
    NO SQL
    COMMENT 'lấy thông tin của một cán bộ từ mã cán bộ'
SELECT Ho_Ten_CB, DATE_FORMAT(Ngay_Sinh,'%d/%m/%Y') AS Ngay_Sinh, So_CMND,
                       DATE_FORMAT(Ngay_Tuyen_Dung,'%d/%m/%Y') AS Ngay_Tuyen_Dung, DATE_FORMAT(Ngay_Bien_Che,'%d/%m/%Y') AS Ngay_Bien_Che
                FROM can_bo LEFT JOIN ly_lich on( can_bo.Ma_Can_Bo = ly_lich.Ma_CB)
                WHERE Ma_CB = maCanBo
                LIMIT 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_layThongTinLyLich`(IN `macb` INT)
    NO SQL
SELECT can_bo.Ma_Can_Bo, đon_vi.Ten_Đon_Vi, can_bo.Ho_Ten_CB,can_bo.Ma_Quan_Ly, ly_lich.So_Hieu_CB, can_bo.Ngay_Tuyen_Dung, can_bo.Ngay_Bien_Che, can_bo.Ngay_Roi_Khoi, can_bo.Trang_Thai, can_bo.Tham_Gia_CLBTT,
                       Ten_Goi_Khac, Gioi_Tinh,Cap_Uy_Hien_Tai, Cap_Uy_Kiem, cvchinh.Ten_Chuc_Vu as Chuc_Vu_Chinh, Chuc_Danh as Chuc_Danh, Phu_Cap_Chuc_Vu,
                       Ngay_Sinh, Noi_Sinh, Que_Quan,Noi_O_Hien_Nay, Đien_Thoai,
                       ton_giao.Ten_Ton_Giao as Ton_Giao, dan_toc.Ten_Dan_Toc as Dan_Toc,Thanh_Phan_Gia_Đinh_Xuat_Than,
                       Nghe_Nghiep_Truoc_Đo, Co_Quan_Tuyen_Dung, Đia_Chi_Co_Quan_Tuyen_Dung, Ngay_Đuoc_Tuyen_Dung,
                       can_bo.Ngay_Gia_Nhap, Ngay_Tham_Gia_CM,
                       Ngay_Vao_Đang, Ngay_Chinh_Thuc,
                       Ngay_Tham_Gia_Cac_To_Chuc_Chinh_Tri_Xa_Hoi,
                       Ngay_Nhap_Ngu, Ngay_Xuat_Ngu, Quan_Ham_Chuc_Vu_Cao_Nhat,
                       Trinh_Đo_Hoc_Van, trinh_đo_chuyen_mon.Ten_TĐCM as Trinh_Đo_Chuyen_Mon, Chuyen_Nganh, Hoc_Ham, trinh_đo_ly_luan_chinh_tri.Ten_CTLL, Ngoai_Ngu,
                       Cong_Tac_Chinh_Đang_Lam, qua_trinh_luong.Thoi_Gian_Nang_Luong, qua_trinh_luong.Ma_So_Ngach, qua_trinh_luong.Bac_Luong, qua_trinh_luong.He_So_Luong, qua_trinh_luong.He_So_Phu_Cap, qua_trinh_luong.Muc_Luong_Khoang, qua_trinh_luong.Phu_Cap_Vuot_Khung,
                       Danh_Hieu_Đuoc_Phong, So_Truong_Cong_Tac, Cong_Viec_Lam_Lau_Nhat, Khen_Thuong, Ky_Luat, Khen_Thuong,
                       Tinh_Trang_Suc_Khoe, Chieu_Cao, Can_Nang, Nhom_Mau, So_CMND , Ngay_Cap_CMND, Noi_Cap_CMND,
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
                            LEFT JOIN qua_trinh_luong ON (can_bo.Ma_Can_Bo = qua_trinh_luong.Ma_CB AND qua_trinh_luong.thoi_gian_nang_luong >= (SELECT MAX(qua_trinh_luong.Thoi_Gian_Nang_Luong)
                                                                                                                                                FROM qua_trinh_luong qtl2
                                                                                                                                                WHERE qtl2.Ma_CB = qua_trinh_luong.Ma_CB
                                                                                                                                               )
                                                           )
                            LEFT JOIN đon_vi ON (can_bo.Ma_ĐVCT_Chinh = đon_vi.Ma_ĐV)
                WHERE (can_bo.Ma_Can_Bo = macb)

                LIMIT 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_themDanhGia`(IN `maCB_TDG` INT, IN `ngayDanhGia` DATE, IN `noidung_TDG` TEXT CHARSET utf8, IN `maMDHT_TDG` TINYINT, IN `maDonViMuonDen` TINYINT, IN `maBanMuonDen` INT, IN `tgMuonChuyen` DATE, IN `nguyenvongDaoTao` TEXT CHARSET utf8, IN `maCB_DG` INT, IN `noidung_DG` TEXT CHARSET utf8, IN `maMDHT_DG` TINYINT, IN `maCHPT` TINYINT, IN `dinhhuong` TEXT)
    NO SQL
INSERT INTO `đanh_gia_can_bo`(`Ma_CB_Tu_Đanh_Gia`, `Ngay_Đanh_Gia`, `Noi_Dung_Tu_Đanh_Gia`, `Ma_MĐHT_Tu_Đanh_Gia`, `Ma_ĐV_Muon_Đen`, `Ma_Ban_Muon_Đen`, `Thoi_Gian_Muon_Chuyen`, `Nguyen_Vong_Đao_Tao`, `Ma_CB_Đanh_Gia`, `Noi_Dung_Đanh_Gia`, `Ma_MĐHT`, `Ma_CHPT`, `Đinh_Huong`) 
VALUES (maCB_TDG,ngayDanhGia,noidung_TDG, maMDHT_TDG,maDonViMuonDen,maBanMuonDen, tgMuonChuyen,nguyenvongDaoTao, maCB_DG,noidung_DG,maMDHT_DG,maCHPT,dinhhuong)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_themLoaiHinhBan`(IN `tenLoaiHinh` VARCHAR(62) CHARSET utf8, IN `maKieu` TINYINT, IN `mota` VARCHAR(254) CHARSET utf8)
    NO SQL
    DETERMINISTIC
    COMMENT 'thêm Loại Hình Ban mới'
BEGIN
    INSERT INTO `loai_hinh_ban`( `Ten_Loai_Hinh_Ban`, `Ma_Kieu_Loai_Hinh` , `Mo_Ta`) VALUES (tenLoaiHinh, maKieu , mota);
    SELECT LAST_INSERT_ID() AS New_ID;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `f_isCBThuongVuTD`(`maCB` INT) RETURNS tinyint(1)
    NO SQL
RETURN (EXISTS(SELECT Ma_CB
 FROM thong_tin_tham_gia_ban
 WHERE Ma_Ban IN (SELECT Ma_Ban_Chap_Hanh FROM đon_vi WHERE Ma_ĐV = 0)
 	AND Ngay_Roi_Khoi IS NULL
    AND Ma_CB = maCB))$$

CREATE DEFINER=`root`@`localhost` FUNCTION `f_isTruongPhoBan`(`maCB` INT) RETURNS tinyint(1)
    NO SQL
Return EXISTS(SELECT Ma_CB FROM thong_tin_tham_gia_ban WHERE (Ngay_Roi_Khoi IS NULL) AND (Ma_CV IN (SELECT Ma_Chuc_Vu FROM chuc_vu WHERE Ma_Cap IN (1,2,6,7))                                                                                         AND (Ma_CB = maCB))
                                                                                         )$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ban`
--

CREATE TABLE IF NOT EXISTS `ban` (
  `Ma_Ban` int(11) NOT NULL AUTO_INCREMENT,
  `Ma_Loai_Ban` tinyint(4) DEFAULT NULL,
  `Ma_Đon_Vi` smallint(6) unsigned DEFAULT NULL,
  `Ten_Ban` varchar(254) DEFAULT NULL,
  `Ngay_Thanh_Lap` date DEFAULT NULL,
  `Ngay_Man_Nhiem` date DEFAULT NULL,
  `Mo_Ta` varchar(254) DEFAULT NULL,
  `Trang_Thai` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`Ma_Ban`),
  KEY `FK_loai_cua_ban` (`Ma_Loai_Ban`),
  KEY `FK_thuoc_DV` (`Ma_Đon_Vi`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=59 ;

--
-- Dumping data for table `ban`
--

INSERT INTO `ban` (`Ma_Ban`, `Ma_Loai_Ban`, `Ma_Đon_Vi`, `Ten_Ban`, `Ngay_Thanh_Lap`, `Ngay_Man_Nhiem`, `Mo_Ta`, `Trang_Thai`) VALUES
(1, 10, 0, 'Văn Phòng', '2017-01-01', NULL, NULL, 1),
(2, 11, 0, 'Văn Phòng Đảng Ủy', '2014-04-01', NULL, '<p>yes</p>\r\n', 1),
(3, 12, 0, 'Ban Quốc Tế', '2014-04-01', NULL, '', 1),
(4, 13, 0, 'Ban Tuyên Giáo', '2014-04-01', NULL, '', 1),
(5, 14, 0, 'Ban Thiếu Nhi', '2014-04-01', NULL, '', 1),
(6, 15, 0, 'Ban Tổ Chức', '2014-04-01', NULL, '', 1),
(7, 16, 0, 'Ban Kiểm Tra', '2014-04-01', NULL, '', 1),
(8, 3, 0, 'Ban Thanh Niên Trường Học', '2014-04-01', NULL, '', 1),
(9, 17, 0, 'Ban Công Nhân Lao Động', '2014-04-01', NULL, '', 1),
(40, 17, 0, 'Ban mặt trân an ninh quốc phòng địa bàn dân cư', '2014-04-01', NULL, NULL, 1),
(42, 13, 0, 'Khác ', '2014-04-01', NULL, NULL, 1),
(43, 0, 1, 'Báo tuổi trẻ', NULL, NULL, NULL, 1),
(44, 0, 1, 'Báo khăn quằng đỏ', NULL, NULL, NULL, 1),
(45, 0, 1, 'Nhà văn hóa thanh niên', NULL, NULL, NULL, 1),
(46, 0, 1, 'Nhà văn hóa sinh viên', NULL, NULL, NULL, 1),
(47, 0, 1, 'Nhà thiếu nhi thành phố', NULL, NULL, NULL, 1),
(48, 0, 1, 'Trung tâm hỗ trợ học sinh sinh viên TP', NULL, NULL, NULL, 1),
(49, 0, 1, 'Trung tâm hỗ trợ thanh niên công nhân', NULL, NULL, NULL, 1),
(50, 0, 1, 'Trung tâm hỗ trợ thanh niên khởi nghiệp', NULL, NULL, NULL, 1),
(51, 0, 1, 'Trung tâm phát triển khoa học và công nghệ trẻ', NULL, NULL, NULL, 1),
(52, 0, 1, 'Trung tâm CTXH thanh niên thành phố', NULL, NULL, NULL, 1),
(53, 0, 1, 'Trung tâm dịch vụ việc làm thanh niên TPHCM', NULL, NULL, NULL, 1),
(54, 0, 1, 'Trung tâm sinh hoạt dã ngoại thanh thiếu nhi TP', NULL, NULL, NULL, 1),
(55, 0, 1, 'Hãng phim trẻ', NULL, NULL, NULL, 1),
(56, 0, 1, 'Trường Đoàn Lý tự Trọng', NULL, NULL, NULL, 1),
(57, 0, 1, 'Công ty TNHH 1 thành viên nhà xuất bản trẻ', NULL, NULL, NULL, 1),
(58, 0, 1, 'Công ty TNHH 1 thành viên lê quang lộc', NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `can_bo`
--

CREATE TABLE IF NOT EXISTS `can_bo` (
  `Ma_Can_Bo` int(11) NOT NULL AUTO_INCREMENT,
  `Ma_Quan_Ly` varchar(254) DEFAULT NULL COMMENT 'MaDoiTuong, MaKhuVuc, STT',
  `Ma_CV_Chinh` smallint(5) unsigned DEFAULT NULL COMMENT 'tham chiếu chức vụ chính của cán bộ',
  `Ho_Ten_CB` varchar(254) DEFAULT '(chưa biết)',
  `Ngay_Gia_Nhap` date DEFAULT NULL,
  `Ngay_Tuyen_Dung` date DEFAULT NULL,
  `Ngay_Bien_Che` date DEFAULT NULL,
  `Ma_ĐVCT_Chinh` smallint(5) unsigned DEFAULT NULL,
  `Ngay_Roi_Khoi` date DEFAULT NULL,
  `Trang_Thai` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 - Đang công tác, 0 - Đã rời khỏi, (-1) - đã mất',
  `DangHoatDong` tinyint(1) NOT NULL DEFAULT '1',
  `Tham_Gia_CLBTT` date NOT NULL,
  `So_The_HoiVien` varchar(100) DEFAULT '0',
  `So_Quyet_Dinh_Cong_Chuc` varchar(100) DEFAULT '',
  `So_Hop_Dong` varchar(100) DEFAULT '',
  `So_Bao_Hiem_Xa_Hoi` varchar(50) NOT NULL,
  `So_The_Can_Bo` varchar(50) NOT NULL,
  PRIMARY KEY (`Ma_Can_Bo`),
  KEY `FK_co_Chuc_Vu_Chinh` (`Ma_CV_Chinh`),
  KEY `FK_co_ĐVCT_Chinh` (`Ma_ĐVCT_Chinh`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=120 ;

--
-- Dumping data for table `can_bo`
--

INSERT INTO `can_bo` (`Ma_Can_Bo`, `Ma_Quan_Ly`, `Ma_CV_Chinh`, `Ho_Ten_CB`, `Ngay_Gia_Nhap`, `Ngay_Tuyen_Dung`, `Ngay_Bien_Che`, `Ma_ĐVCT_Chinh`, `Ngay_Roi_Khoi`, `Trang_Thai`, `DangHoatDong`, `Tham_Gia_CLBTT`, `So_The_HoiVien`, `So_Quyet_Dinh_Cong_Chuc`, `So_Hop_Dong`, `So_Bao_Hiem_Xa_Hoi`, `So_The_Can_Bo`) VALUES
(107, NULL, NULL, 'Vũ Thị Minh Phương', '1970-01-01', '1970-01-01', '1970-01-01', NULL, NULL, 1, 0, '0000-00-00', '0', '', '', '', ''),
(109, NULL, NULL, 'Ngô Minh Hải', '1970-01-01', '1970-01-01', '1970-01-01', NULL, NULL, 1, 1, '0000-00-00', '0', '', '', '', ''),
(110, NULL, NULL, 'Vũ Thị Minh Phương', '1970-01-01', '1970-01-01', '1970-01-01', NULL, NULL, 1, 1, '0000-00-00', '0', '', '', '', ''),
(111, NULL, NULL, 'Nguyễn Thị Danh', '1970-01-01', '1970-01-01', '1970-01-01', NULL, NULL, 1, 1, '0000-00-00', '0', '', '', '', ''),
(112, NULL, NULL, 'Mai Kim Tuyền', '1970-01-01', '1970-01-01', '1970-01-01', NULL, NULL, 1, 1, '0000-00-00', '0', '', '', '', ''),
(113, NULL, NULL, 'Phạm Thị Thảo Linh', '1970-01-01', '1970-01-01', '1970-01-01', NULL, NULL, 1, 1, '0000-00-00', '0', '', '', '', ''),
(114, NULL, NULL, 'Trần Thanh Trà', '1970-01-01', '1970-01-01', '1970-01-01', NULL, NULL, 1, 1, '0000-00-00', '0', '', '', '', ''),
(115, NULL, NULL, 'Nguyễn Nam Hải', '1970-01-01', '1970-01-01', '1970-01-01', NULL, NULL, 1, 0, '0000-00-00', '0', '', '', '', ''),
(116, NULL, NULL, 'Nguyễn Đăng Khoa', '1970-01-01', '1970-01-01', '1970-01-01', NULL, NULL, 1, 1, '0000-00-00', '0', '', '', '', ''),
(117, NULL, NULL, 'Nguyễn Nam Hải', '1970-01-01', '1970-01-01', '1970-01-01', NULL, '1970-01-01', 1, 1, '1970-01-01', '0', NULL, NULL, '', ''),
(119, NULL, NULL, 'Nguyễn Phương Thảo', '1970-01-01', '1970-01-01', '1970-01-01', NULL, NULL, 1, 1, '0000-00-00', '0', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `cap_chuc_vu`
--

CREATE TABLE IF NOT EXISTS `cap_chuc_vu` (
  `Ma_Cap` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Ten_Cap_CV` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_Cap`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `cap_chuc_vu`
--

INSERT INTO `cap_chuc_vu` (`Ma_Cap`, `Ten_Cap_CV`) VALUES
(0, 'Khác'),
(1, 'Trưởng Đơn Vị'),
(2, 'Phó Đơn Vị'),
(3, 'Thường Trực'),
(4, 'Thường Vụ/ Thành Viên Hội Đồng'),
(5, 'Ủy Viên'),
(6, 'Trưởng Phòng/Ban'),
(7, 'Phó Phòng/Ban'),
(8, 'Cán Bộ/Nhân Viên');

-- --------------------------------------------------------

--
-- Table structure for table `chieu_huong_phat_trien`
--

CREATE TABLE IF NOT EXISTS `chieu_huong_phat_trien` (
  `Ma_CHPT` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Ten_CHPT` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_CHPT`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `chieu_huong_phat_trien`
--

INSERT INTO `chieu_huong_phat_trien` (`Ma_CHPT`, `Ten_CHPT`) VALUES
(1, 'Tốt hơn'),
(2, 'Giứ mức'),
(3, 'Giảm');

-- --------------------------------------------------------

--
-- Table structure for table `chuc_vu`
--

CREATE TABLE IF NOT EXISTS `chuc_vu` (
  `Ma_Chuc_Vu` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `Ma_Cap` tinyint(4) DEFAULT NULL,
  `Ten_Chuc_Vu` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_Chuc_Vu`),
  KEY `FK_cap_đo_cua_CV` (`Ma_Cap`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `chuc_vu`
--

INSERT INTO `chuc_vu` (`Ma_Chuc_Vu`, `Ma_Cap`, `Ten_Chuc_Vu`) VALUES
(0, NULL, '(Chức Vụ Khác)'),
(1, 1, 'Bí Thư'),
(2, 1, 'Giám Đốc'),
(3, 2, 'Phó Bí Thư'),
(4, 2, 'Phó Giám Đốc'),
(5, 3, 'Thường Trực'),
(6, 4, 'Thường Vụ'),
(7, 4, 'Thành Viên Hội Đồng'),
(8, 5, 'Ủy Viên BCH'),
(9, 6, 'Trưởng Ban'),
(10, 6, 'Kế Toán Trưởng'),
(11, 6, 'Trưởng Phòng'),
(12, 7, 'Phó Ban'),
(13, 7, 'Kế Toán Phó'),
(14, 7, 'Phó Phòng'),
(15, 8, 'Cán Bộ'),
(16, 8, 'Nhân Viên');

-- --------------------------------------------------------

--
-- Table structure for table `cong_tac_nuoc_ngoai`
--

CREATE TABLE IF NOT EXISTS `cong_tac_nuoc_ngoai` (
  `Ma_CB` int(10) unsigned NOT NULL,
  `Ma_CTNN` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Tu_Ngay` date NOT NULL DEFAULT '0000-00-00',
  `Đen_Ngay` date NOT NULL DEFAULT '0000-00-00',
  `Đia_Điem` varchar(254) DEFAULT NULL,
  `Noi_Dung` varchar(254) DEFAULT NULL,
  `Cap_Cu_Đi` varchar(254) DEFAULT NULL,
  `Kinh_Phi` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_CTNN`,`Ma_CB`),
  KEY `Ma_CTNN` (`Ma_CTNN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cong_tac_vien`
--

CREATE TABLE IF NOT EXISTS `cong_tac_vien` (
  `Ma_CTV` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Ho_Ten_CTV` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_CTV`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `controller`
--

CREATE TABLE IF NOT EXISTS `controller` (
  `Controller_Name` varchar(32) NOT NULL,
  `Module_Name` varchar(32) DEFAULT NULL,
  `Controller_Display_Name` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Controller_Name`),
  KEY `FK_of_module` (`Module_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `danh_muc_ho_so`
--

CREATE TABLE IF NOT EXISTS `danh_muc_ho_so` (
  `Ma_CB` int(11) NOT NULL,
  `bangtotnghiep` int(1) DEFAULT '0',
  `hopdonglaodong` int(1) DEFAULT '0',
  `quyetdinhtuyendung` int(1) DEFAULT '0',
  `bangchuyenmon` text COLLATE utf8_unicode_ci,
  `bangngoaingu` text COLLATE utf8_unicode_ci,
  `bangtinhoc` text COLLATE utf8_unicode_ci,
  `vanbangkhac` text COLLATE utf8_unicode_ci,
  `bonhiemngach` text COLLATE utf8_unicode_ci,
  `congtaccanbo` text COLLATE utf8_unicode_ci,
  `hinhthuongkhenhtuong` text COLLATE utf8_unicode_ci,
  `kyluat` text COLLATE utf8_unicode_ci,
  `dinuocngoai` text COLLATE utf8_unicode_ci,
  `canbodihoc` text COLLATE utf8_unicode_ci,
  `thoitraluong` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`Ma_CB`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `danh_muc_ho_so`
--

INSERT INTO `danh_muc_ho_so` (`Ma_CB`, `bangtotnghiep`, `hopdonglaodong`, `quyetdinhtuyendung`, `bangchuyenmon`, `bangngoaingu`, `bangtinhoc`, `vanbangkhac`, `bonhiemngach`, `congtaccanbo`, `hinhthuongkhenhtuong`, `kyluat`, `dinuocngoai`, `canbodihoc`, `thoitraluong`) VALUES
(117, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `danh_sach_ban`
--

CREATE TABLE IF NOT EXISTS `danh_sach_ban` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `ten_ban` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `danh_sach_ban`
--

INSERT INTO `danh_sach_ban` (`id`, `ten_ban`) VALUES
(1, 'Ban tổ chức'),
(2, 'Ban kiểm tra'),
(3, 'Văn phòng'),
(4, 'Ban Tuyên Giáo'),
(5, 'Ban công nhân lao động'),
(6, 'Ban thiếu nhi'),
(7, 'Ban thanh niên trường học'),
(8, 'Ban quốc tế'),
(9, 'Ban mặt trân an ninh quốc phòng địa bàn dân cư'),
(10, 'Văn phòng Đảng Ủy'),
(11, 'Khác ');

-- --------------------------------------------------------

--
-- Table structure for table `dan_toc`
--

CREATE TABLE IF NOT EXISTS `dan_toc` (
  `Ma_Dan_Toc` smallint(6) NOT NULL AUTO_INCREMENT,
  `Ten_Dan_Toc` varchar(63) DEFAULT NULL,
  PRIMARY KEY (`Ma_Dan_Toc`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `dan_toc`
--

INSERT INTO `dan_toc` (`Ma_Dan_Toc`, `Ten_Dan_Toc`) VALUES
(0, '(Khác)'),
(1, 'Kinh'),
(2, 'Chăm'),
(3, 'Hoa'),
(4, 'Thái'),
(5, 'Tày'),
(6, 'Nùng');

-- --------------------------------------------------------

--
-- Table structure for table `dien_khen_thuong`
--

CREATE TABLE IF NOT EXISTS `dien_khen_thuong` (
  `Ma_Dien` smallint(6) NOT NULL AUTO_INCREMENT,
  `Ten_Dien` varchar(254) DEFAULT NULL,
  `Muc_Thuong_Khung` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`Ma_Dien`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dot_danh_gia`
--

CREATE TABLE IF NOT EXISTS `dot_danh_gia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_unicode_ci NOT NULL,
  `ngay_bat_dau` date NOT NULL,
  `ngay_ket_thuc` date NOT NULL,
  `note` text COLLATE utf8_unicode_ci,
  `owner_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `dot_danh_gia`
--

INSERT INTO `dot_danh_gia` (`id`, `name`, `ngay_bat_dau`, `ngay_ket_thuc`, `note`, `owner_id`, `status`) VALUES
(1, 'Năm 2017', '2017-03-01', '2017-03-11', '', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `hop_đong_cong_tac`
--

CREATE TABLE IF NOT EXISTS `hop_đong_cong_tac` (
  `Ma_Ban` int(11) NOT NULL,
  `Ma_CTV` int(10) unsigned NOT NULL,
  `Ngay_Bat_Đau` datetime NOT NULL,
  `Ngay_Ket_Thuc` date DEFAULT NULL,
  `Nhiem_Vu` varchar(254) DEFAULT NULL,
  `Tien_Luong_Khoan` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`Ma_Ban`,`Ma_CTV`,`Ngay_Bat_Đau`),
  KEY `FK_CTV_Hop_Đong` (`Ma_CTV`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `khen_thuong`
--

CREATE TABLE IF NOT EXISTS `khen_thuong` (
  `Ma_CB` int(11) NOT NULL,
  `Ma_Khen_Thuong` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Ngay_Quyet_Đinh` date DEFAULT NULL,
  `Hinh_Thuc` varchar(62) DEFAULT NULL,
  `Cap_Ra_Quyet_Đinh` varchar(62) DEFAULT NULL,
  `Ly_Do` varchar(254) DEFAULT NULL,
  `Ma_DS_Khen_Thuong` int(10) unsigned DEFAULT NULL,
  `Ma_Dien` smallint(6) DEFAULT NULL,
  `He_So_Thuong` float DEFAULT '1',
  PRIMARY KEY (`Ma_Khen_Thuong`,`Ma_CB`),
  KEY `FK_thuoc_dien` (`Ma_Dien`),
  KEY `FK_thuoc_kq_xet` (`Ma_DS_Khen_Thuong`),
  KEY `Ma_CB` (`Ma_CB`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `khoi`
--

CREATE TABLE IF NOT EXISTS `khoi` (
  `Ma_Khoi` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `Ten_Khoi` varchar(254) DEFAULT NULL,
  `Mo_Ta` varchar(254) DEFAULT NULL,
  `Ma_Khoi_Cap_Tren` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`Ma_Khoi`),
  KEY `FK_khoi_cap_tren_truc_thuoc` (`Ma_Khoi_Cap_Tren`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `khoi`
--

INSERT INTO `khoi` (`Ma_Khoi`, `Ten_Khoi`, `Mo_Ta`, `Ma_Khoi_Cap_Tren`) VALUES
(1, 'Thành Đoàn', NULL, NULL),
(2, 'Khối cơ quan chuyên trách Thành Đoàn', NULL, 1),
(3, 'Khối các đơn vị sự nghiệp - doanh nghiệp', NULL, 1),
(4, 'Cơ Sở Đoàn', NULL, 1),
(5, 'Cơ Quan Chính Đảng', NULL, NULL),
(6, 'Khác', 'Các khối khác', 1);

-- --------------------------------------------------------

--
-- Table structure for table `kien_nghi`
--

CREATE TABLE IF NOT EXISTS `kien_nghi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Thoi_Gian` datetime NOT NULL,
  `Ma_CB_Kien_Nghi` int(11) NOT NULL,
  `Ten_Kien_Nghi` varchar(254) DEFAULT NULL,
  `Noi_Dung` text,
  `File_URL` varchar(254) DEFAULT NULL,
  `Trang_Thai` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `FK_đuoc_can_bo_yeu_cau` (`Ma_CB_Kien_Nghi`),
  KEY `Trang_Thai` (`Trang_Thai`),
  KEY `Thoi_Gian` (`Thoi_Gian`),
  KEY `FK_co_CB_kiennghi` (`Ma_CB_Kien_Nghi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `kieu_loai_hinh_ban`
--

CREATE TABLE IF NOT EXISTS `kieu_loai_hinh_ban` (
  `Ma_KLHB` tinyint(4) NOT NULL,
  `Ten_KLHB` varchar(62) DEFAULT NULL,
  `Mo_Ta` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_KLHB`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `kieu_loai_hinh_ban`
--

INSERT INTO `kieu_loai_hinh_ban` (`Ma_KLHB`, `Ten_KLHB`, `Mo_Ta`) VALUES
(0, 'Phòng/Ban chức năng', NULL),
(1, 'Ban Lãnh Đạo', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kq_xet_thi_đua`
--

CREATE TABLE IF NOT EXISTS `kq_xet_thi_đua` (
  `Ma_DS_Khen_Thuong` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Nam_Đanh_Gia` smallint(6) NOT NULL,
  `Quy_Đanh_Gia` smallint(6) NOT NULL,
  `Thoi_Điem_Xet` date DEFAULT NULL,
  PRIMARY KEY (`Ma_DS_Khen_Thuong`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ky_luat`
--

CREATE TABLE IF NOT EXISTS `ky_luat` (
  `Ma_CB` int(11) NOT NULL,
  `Ma_Ky_Luat` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Ngay_Quyet_Đinh` date DEFAULT NULL,
  `Hinh_Thuc` varchar(62) DEFAULT NULL,
  `Cap_Ra_Quyet_Đinh` varchar(62) DEFAULT NULL,
  `Ly_Do_Ky_Luat` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_Ky_Luat`,`Ma_CB`),
  KEY `FK_ky_luat_cua_can_bo` (`Ma_CB`),
  KEY `Ma_CB` (`Ma_CB`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `loai_hinh_ban`
--

CREATE TABLE IF NOT EXISTS `loai_hinh_ban` (
  `Ma_Loai_Ban` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Ten_Loai_Hinh_Ban` varchar(62) DEFAULT NULL,
  `Mo_Ta` varchar(254) DEFAULT NULL,
  `Ma_Kieu_Loai_Hinh` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`Ma_Loai_Ban`),
  KEY `FK_co_KieuLHB` (`Ma_Kieu_Loai_Hinh`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `loai_hinh_ban`
--

INSERT INTO `loai_hinh_ban` (`Ma_Loai_Ban`, `Ten_Loai_Hinh_Ban`, `Mo_Ta`, `Ma_Kieu_Loai_Hinh`) VALUES
(1, 'Ban Chấp Hành', '<p>Ban chấp h&igrave;nh c&aacute;c cơ quan. Ch&uacute; &yacute;: Ban Thường Vụ l&agrave; c&aacute;c C&aacute;n bộ giữ chức Thường Vụ tại Ban Chấp H&agrave;nh cơ quan đ&oacute; 4s</p>\r\n', 1),
(2, 'Hội Đồng Thành Viên', 'Hội đồng thành viên, giành cho các cơ quan doanh nghiệp', 1),
(3, 'Phòng Kế Toán', NULL, 0),
(4, 'Phòng Hành Chính', NULL, 0),
(5, 'Ban Biên Tập', NULL, 0),
(6, 'Ban Giám Hiệu', 'đối với các cơ quan Trường học', 1),
(7, 'Ban Giám Đốc', NULL, 1),
(8, 'Phòng Kinh Doanh', NULL, 0),
(9, 'Ban Phát Thanh Truyền Hình', '', 0),
(10, 'Phòng Bảo Vệ', '', 0),
(11, 'Văn Phòng Đảng Ủy', '', 0),
(12, 'Ban Quốc Tế', '', 0),
(13, 'Ban Tuyên Giáo', '', 0),
(14, 'Ban Thiếu Nhi', '', 0),
(15, 'Ban Tổ Chức', '', 0),
(16, 'Ban Kiểm Tra', '', 0),
(17, 'Ban Công Nhân Lao Động', '', 0),
(18, 'Ban Thanh Niên Trường Học', '', 0),
(19, 'Ban Mặt Trận - An Ninh Quốc Phòng - Địa Bàn Dân Cư', '', 0),
(20, 'Ban Kiểm Tra', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ly_lich`
--

CREATE TABLE IF NOT EXISTS `ly_lich` (
  `Ma_CB` int(11) NOT NULL,
  `So_Hieu_CB` varchar(30) DEFAULT NULL COMMENT 'dùng để quản lý hồ sơ lý lịch (MaDoiTuong, MaKhuVuc, STT)',
  `Ho_Ten_Khai_Sinh` varchar(254) DEFAULT NULL,
  `Ten_Goi_Khac` varchar(254) DEFAULT NULL,
  `Gioi_Tinh` tinyint(1) DEFAULT NULL,
  `Cap_Uy_Hien_Tai` varchar(254) DEFAULT NULL,
  `Cap_Uy_Kiem` varchar(254) DEFAULT NULL,
  `Chuc_Danh` varchar(62) DEFAULT NULL COMMENT 'chức vụ, chức danh tại cấp uy',
  `Phu_Cap_Chuc_Vu` float DEFAULT NULL,
  `Ngay_Sinh` date DEFAULT NULL,
  `Noi_Sinh` varchar(254) DEFAULT NULL,
  `So_CMND` varchar(254) DEFAULT NULL,
  `Ngay_Cap_CMND` date DEFAULT NULL,
  `Noi_Cap_CMND` varchar(254) DEFAULT NULL,
  `Que_Quan` varchar(254) DEFAULT NULL,
  `Noi_O_Hien_Nay` varchar(254) DEFAULT NULL,
  `Dan_Toc` smallint(6) DEFAULT NULL,
  `Ton_Giao` smallint(6) DEFAULT NULL,
  `Đien_Thoai` varchar(254) DEFAULT NULL,
  `Thanh_Phan_Gia_Đinh_Xuat_Than` varchar(254) DEFAULT NULL,
  `Ngay_Tham_Gia_CM` date DEFAULT NULL,
  `Nghe_Nghiep_Truoc_Đo` varchar(254) DEFAULT NULL,
  `Ngay_Đuoc_Tuyen_Dung` date DEFAULT NULL,
  `Co_Quan_Tuyen_Dung` varchar(254) DEFAULT NULL,
  `Đia_Chi_Co_Quan_Tuyen_Dung` varchar(254) DEFAULT NULL,
  `Ngay_Vao_Đang` date DEFAULT NULL,
  `Ngay_Chinh_Thuc` date DEFAULT NULL,
  `Ngay_Tham_Gia_Cac_To_Chuc_Chinh_Tri_Xa_Hoi` varchar(254) DEFAULT NULL,
  `Ngay_Nhap_Ngu` date DEFAULT NULL,
  `Ngay_Xuat_Ngu` date DEFAULT NULL,
  `Quan_Ham_Chuc_Vu_Cao_Nhat` varchar(254) DEFAULT NULL,
  `Trinh_Đo_Hoc_Van` varchar(254) DEFAULT '12/12',
  `Hoc_Ham` varchar(254) DEFAULT NULL,
  `Cap_Đo_CTLL` decimal(4,2) DEFAULT NULL,
  `Cap_Đo_TĐCM` decimal(4,2) DEFAULT NULL,
  `Chuyen_Nganh` varchar(62) DEFAULT NULL,
  `Ngoai_Ngu` varchar(254) DEFAULT NULL,
  `Đac_Điem_Lich_Su` text,
  `Lam_Viec_Trong_Che_Đo_Cu` varchar(254) DEFAULT NULL,
  `Co_Than_Nhan_Nuoc_Ngoai` varchar(254) DEFAULT NULL,
  `Tham_Gia_Cac_To_Chuc_Nuoc_Ngoai` varchar(254) DEFAULT NULL,
  `Cong_Tac_Chinh_Đang_Lam` varchar(254) DEFAULT NULL,
  `Danh_Hieu_Đuoc_Phong` varchar(254) DEFAULT NULL,
  `So_Truong_Cong_Tac` varchar(254) DEFAULT NULL,
  `Cong_Viec_Lam_Lau_Nhat` varchar(254) DEFAULT NULL,
  `Khen_Thuong` varchar(254) DEFAULT NULL,
  `Ky_Luat` varchar(254) DEFAULT NULL,
  `Tinh_Trang_Suc_Khoe` varchar(254) DEFAULT NULL,
  `Chieu_Cao` float DEFAULT NULL,
  `Can_Nang` float DEFAULT NULL,
  `Nhom_Mau` varchar(30) DEFAULT NULL,
  `Loai_Thuong_Binh` varchar(254) DEFAULT NULL,
  `Gia_Đinh_Liet_Sy` tinyint(1) DEFAULT NULL,
  `Luong_Thu_Nhap_Nam` bigint(20) unsigned DEFAULT '0',
  `Nguon_Thu_Khac` varchar(254) DEFAULT NULL,
  `Loai_Nha_Đuoc_Cap` varchar(62) DEFAULT NULL,
  `Dien_Tich_Nha_Đuoc_Cap` int(10) unsigned DEFAULT '0',
  `Loai_Nha_Tu_Xay` varchar(62) DEFAULT NULL,
  `Dien_Tich_Nha_Tu_Xay` int(10) unsigned DEFAULT '0',
  `Dien_Tich_Đat_O_Đuoc_Cap` int(10) unsigned DEFAULT '0',
  `Dien_Tich_Đat_O_Tu_Mua` int(10) unsigned DEFAULT '0',
  `Dien_Tich_Đat_San_Xuat` int(10) unsigned DEFAULT '0',
  `Tien_Su_Benh` text,
  PRIMARY KEY (`Ma_CB`),
  KEY `FK_co_TĐ_HV` (`Cap_Đo_TĐCM`),
  KEY `FK_co_TĐ_LLCT` (`Cap_Đo_CTLL`),
  KEY `FK_co_Dan_Toc` (`Dan_Toc`),
  KEY `FK_co_Ton_Giao` (`Ton_Giao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ly_lich`
--

INSERT INTO `ly_lich` (`Ma_CB`, `So_Hieu_CB`, `Ho_Ten_Khai_Sinh`, `Ten_Goi_Khac`, `Gioi_Tinh`, `Cap_Uy_Hien_Tai`, `Cap_Uy_Kiem`, `Chuc_Danh`, `Phu_Cap_Chuc_Vu`, `Ngay_Sinh`, `Noi_Sinh`, `So_CMND`, `Ngay_Cap_CMND`, `Noi_Cap_CMND`, `Que_Quan`, `Noi_O_Hien_Nay`, `Dan_Toc`, `Ton_Giao`, `Đien_Thoai`, `Thanh_Phan_Gia_Đinh_Xuat_Than`, `Ngay_Tham_Gia_CM`, `Nghe_Nghiep_Truoc_Đo`, `Ngay_Đuoc_Tuyen_Dung`, `Co_Quan_Tuyen_Dung`, `Đia_Chi_Co_Quan_Tuyen_Dung`, `Ngay_Vao_Đang`, `Ngay_Chinh_Thuc`, `Ngay_Tham_Gia_Cac_To_Chuc_Chinh_Tri_Xa_Hoi`, `Ngay_Nhap_Ngu`, `Ngay_Xuat_Ngu`, `Quan_Ham_Chuc_Vu_Cao_Nhat`, `Trinh_Đo_Hoc_Van`, `Hoc_Ham`, `Cap_Đo_CTLL`, `Cap_Đo_TĐCM`, `Chuyen_Nganh`, `Ngoai_Ngu`, `Đac_Điem_Lich_Su`, `Lam_Viec_Trong_Che_Đo_Cu`, `Co_Than_Nhan_Nuoc_Ngoai`, `Tham_Gia_Cac_To_Chuc_Nuoc_Ngoai`, `Cong_Tac_Chinh_Đang_Lam`, `Danh_Hieu_Đuoc_Phong`, `So_Truong_Cong_Tac`, `Cong_Viec_Lam_Lau_Nhat`, `Khen_Thuong`, `Ky_Luat`, `Tinh_Trang_Suc_Khoe`, `Chieu_Cao`, `Can_Nang`, `Nhom_Mau`, `Loai_Thuong_Binh`, `Gia_Đinh_Liet_Sy`, `Luong_Thu_Nhap_Nam`, `Nguon_Thu_Khac`, `Loai_Nha_Đuoc_Cap`, `Dien_Tich_Nha_Đuoc_Cap`, `Loai_Nha_Tu_Xay`, `Dien_Tich_Nha_Tu_Xay`, `Dien_Tich_Đat_O_Đuoc_Cap`, `Dien_Tich_Đat_O_Tu_Mua`, `Dien_Tich_Đat_San_Xuat`, `Tien_Su_Benh`) VALUES
(107, 'BTC010', 'Vũ Thị Minh Phương', '', 0, '', '', '', NULL, '1985-03-08', '', '024750860', '1970-01-01', '', '', '', 0, 0, '', '', '1970-01-01', '', NULL, 'Thành Đoàn TPHCM', NULL, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '12/12', 'Không', '0.00', '12.00', '', '', '', '', '', '', NULL, '', '', '', NULL, NULL, '', 0, 0, 'O', 'Không', 0, 0, '', '', 0, '', 0, 0, 0, 0, NULL),
(109, 'A002', 'Ngô Minh Hải', '', 0, '', '', '', NULL, '1987-06-02', '', '897654321', '1970-01-01', '', '', '', 0, 0, '', '', '1970-01-01', '', NULL, 'Thành Đoàn TPHCM', NULL, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '12/12', 'Không', '0.00', '12.00', '', '', '', '', '', '', NULL, '', '', '', NULL, NULL, '', 0, 0, 'O', 'Không', 0, 0, '', '', 0, '', 0, 0, 0, 0, NULL),
(110, 'A003', 'Vũ Thị Minh Phương', '', 0, '', '', '', NULL, '1985-03-08', '', '798654321', '1970-01-01', '', '', '', 0, 0, '', '', '1970-01-01', '', NULL, 'Thành Đoàn TPHCM', NULL, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '12/12', 'Không', '0.00', '12.00', '', '', '', '', '', '', NULL, '', '', '', NULL, NULL, '', 0, 0, 'O', 'Không', 0, 0, '', '', 0, '', 0, 0, 0, 0, NULL),
(111, 'A004', 'Nguyễn Thị Danh', '', 0, '', '', '', NULL, '1977-08-31', '', '698754321', '1970-01-01', '', '', '', 0, 0, '', '', '1970-01-01', '', NULL, 'Thành Đoàn TPHCM', NULL, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '12/12', 'Không', '0.00', '12.00', '', '', '', '', '', '', NULL, '', '', '', NULL, NULL, '', 0, 0, 'O', 'Không', 0, 0, '', '', 0, '', 0, 0, 0, 0, NULL),
(112, 'A005', 'Mai Kim Tuyền', '', 0, '', '', '', NULL, '1988-08-21', '', '598764321', '1970-01-01', '', '', '', 0, 0, '', '', '1970-01-01', '', NULL, 'Thành Đoàn TPHCM', NULL, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '12/12', 'Không', '0.00', '12.00', '', '', '', '', '', '', NULL, '', '', '', NULL, NULL, '', 0, 0, 'O', 'Không', 0, 0, '', '', 0, '', 0, 0, 0, 0, NULL),
(113, 'A006', 'Phạm Thị Thảo Linh', '', 0, '', '', '', NULL, '1990-10-09', '', '4987654321', '1970-01-01', '', '', '', 0, 0, '', '', '1970-01-01', '', NULL, 'Thành Đoàn TPHCM', NULL, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '12/12', 'Không', '0.00', '12.00', '', '', '', '', '', '', NULL, '', '', '', NULL, NULL, '', 0, 0, 'O', 'Không', 0, 0, '', '', 0, '', 0, 0, 0, 0, NULL),
(114, 'A008', 'Trần Thanh Trà', '', 0, '', '', '', NULL, '1991-11-28', '', '298765431', '1970-01-01', '', '', '', 0, 0, '', '', '1970-01-01', '', NULL, 'Thành Đoàn TPHCM', NULL, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '12/12', 'Không', '0.00', '12.00', '', '', '', '', '', '', NULL, '', '', '', NULL, NULL, '', 0, 0, 'O', 'Không', 0, 0, '', '', 0, '', 0, 0, 0, 0, NULL),
(116, 'A007', 'Nguyễn Đăng Khoa', '', 0, '', '', '', NULL, '1991-09-12', '', '398765421', '1970-01-01', '', '', '', 0, 0, '', '', '1970-01-01', '', NULL, 'Thành Đoàn TPHCM', NULL, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '12/12', 'Không', '0.00', '12.00', '', '', '', '', '', '', NULL, '', '', '', NULL, NULL, '', 0, 0, 'O', 'Không', 0, 0, '', '', 0, '', 0, 0, 0, 0, NULL),
(117, 'A009', 'Nguyễn Nam Hải', '', 0, '', '', '', 0, '1993-06-13', '', '198765432', '1970-01-01', '', '', '', 0, 0, '', '', '1970-01-01', '', '1970-01-01', 'Thành Đoàn TPHCM', NULL, '1970-01-01', '1970-01-01', '', '1970-01-01', NULL, '', '12/12', '', '0.00', '12.00', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0, 'O', 'Không', 0, 0, '', '', 0, '', 0, 0, 0, 0, ''),
(119, 'A001', 'Nguyễn Phương Thảo', '', 0, '', '', '', NULL, '1984-05-18', '', '987654321', '1970-01-01', '', '', '', 0, 0, '', '', '1970-01-01', '', NULL, 'Thành Đoàn TPHCM', NULL, '1970-01-01', '1970-01-01', '', '1970-01-01', '1970-01-01', '', '12/12', 'Không', '0.00', '12.00', '', '', '', '', '', '', NULL, '', '', '', NULL, NULL, '', 0, 0, 'O', 'Không', 0, 0, '', '', 0, '', 0, 0, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE IF NOT EXISTS `module` (
  `Module_Name` varchar(32) NOT NULL,
  `Module_Display_Name` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Module_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `muc_sua_đoi`
--

CREATE TABLE IF NOT EXISTS `muc_sua_đoi` (
  `Ma_Yeu_Cau` bigint(20) unsigned NOT NULL,
  `Ten_Cot_Thay_Đoi` varchar(254) NOT NULL,
  `Gia_Tri_Thay_Đoi` varchar(254) DEFAULT NULL,
  `Ten_Hien_Thi` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_Yeu_Cau`,`Ten_Cot_Thay_Đoi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `muc_thuong_theo_dien`
--

CREATE TABLE IF NOT EXISTS `muc_thuong_theo_dien` (
  `Ma_Dien` smallint(6) NOT NULL,
  `Ma_DS_Khen_Thuong` int(10) unsigned NOT NULL,
  `Muc_Thuong` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`Ma_Dien`,`Ma_DS_Khen_Thuong`),
  KEY `FK_kq_xet` (`Ma_DS_Khen_Thuong`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `muc_đo_hoan_thanh`
--

CREATE TABLE IF NOT EXISTS `muc_đo_hoan_thanh` (
  `Ma_MĐHT` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Ten_MĐHT` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_MĐHT`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `muc_đo_hoan_thanh`
--

INSERT INTO `muc_đo_hoan_thanh` (`Ma_MĐHT`, `Ten_MĐHT`) VALUES
(1, 'Hoàn thành xuất sắc nhiệm vụ'),
(2, 'Hoàn thành tốt nhiệm vụ'),
(3, 'Hoàn thành nhiệm vụ, nhưng còn hạn chế về năng lực'),
(4, 'Không hoàn thành nhiệm vụ');

-- --------------------------------------------------------

--
-- Table structure for table `ngach_luong`
--

CREATE TABLE IF NOT EXISTS `ngach_luong` (
  `Ma_So_Ngach` varchar(32) NOT NULL,
  `Ky_Hieu_Ngach` varchar(32) DEFAULT NULL,
  `Ten_Ngach` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_So_Ngach`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ngach_luong`
--

INSERT INTO `ngach_luong` (`Ma_So_Ngach`, `Ky_Hieu_Ngach`, `Ten_Ngach`) VALUES
('00000', NULL, '(không hưởng lương)'),
('01.001', '', 'Chuyên viên cao cấp'),
('01.002', '', 'Chuyên viên chính'),
('01.003', '', 'Chuyên viên'),
('01.004', '', 'Cán sự'),
('01.005', '', 'Kỹ thuật viên đánh máy'),
('01.006', '', 'Nhân viên đánh máy'),
('01.007', '', 'Nhân viên kỹ thuật'),
('01.008', '', 'Nhân viên văn thư'),
('01.009', '', 'Nhân viên phục vụ'),
('01.010', '', 'Lái xe cơ quan'),
('01.011', '', 'Nhân viên bảo vệ'),
('01.015', '', 'Kỹ thuật viên trung cấp'),
('02.012', '', 'Lưu trữ viên cao cấp'),
('02.013', '', 'Lưu trữ viên chính'),
('02.014', '', 'Lưu trữ viên'),
('02.016', '', 'Kỹ thuật viên lưu trữ'),
('03.017', '', 'Chấp hành viên tỉnh, thành phố trực thuộc Trung ương'),
('03.018', '', 'Chấp hành viên quận, huyện, thị xã, thành phố thuộc tỉnh'),
('03.019', '', 'Công chứng viên'),
('04.023', '', 'Thanh tra viên cao cấp'),
('04.024', '', 'Thanh tra viên chính'),
('04.025', '', 'Thanh tra viên'),
('06.029', '', 'Kế toán viên cao cấp'),
('06.030', '', 'Kế toán viên chính'),
('06.031', '', 'Kế toán viên'),
('06.032', '', 'Kế toán viên trung cấp'),
('06.033', '', 'Kế toán viên sơ cấp'),
('06.034', '', 'Thủ quỹ kho bạc, ngân hàng'),
('06.035', '', 'Thủ quỹ cơ quan đơn vị'),
('06.036', '', 'Kiểm soát viên cao cấp thuế'),
('06.037', '', 'Kiểm soát viên chính thuế'),
('06.038', '', 'Kiểm soát viên thuế'),
('06.039', '', 'Kiểm thu viên thuế'),
('06.040', '', 'Nhân viên thuế');

-- --------------------------------------------------------

--
-- Table structure for table `ngoai_ngu`
--

CREATE TABLE IF NOT EXISTS `ngoai_ngu` (
  `Ma_Ngoai_Ngu` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `Ten_Ngoai_Ngu` varchar(63) DEFAULT NULL,
  PRIMARY KEY (`Ma_Ngoai_Ngu`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `ngoai_ngu`
--

INSERT INTO `ngoai_ngu` (`Ma_Ngoai_Ngu`, `Ten_Ngoai_Ngu`) VALUES
(0, '(không)'),
(1, 'Anh'),
(2, 'Pháp'),
(3, 'Nhật'),
(4, 'Hàn Quốc'),
(5, 'Hoa'),
(6, 'Thái');

-- --------------------------------------------------------

--
-- Table structure for table `privilege`
--

CREATE TABLE IF NOT EXISTS `privilege` (
  `Privilege_Name` varchar(32) NOT NULL,
  `Controller_Name` varchar(32) DEFAULT NULL,
  `Privilege_Display_Name` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Privilege_Name`),
  KEY `FK_of_controller` (`Controller_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE IF NOT EXISTS `profile` (
  `UserID` int(11) NOT NULL,
  `Avatar_URL` varchar(254) DEFAULT NULL,
  `Surname` varchar(254) DEFAULT NULL,
  `Firstname` varchar(254) DEFAULT NULL,
  `Email` varchar(254) DEFAULT NULL,
  `Description` varchar(254) DEFAULT NULL,
  `Phone` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `qua_trinh_cong_tac`
--

CREATE TABLE IF NOT EXISTS `qua_trinh_cong_tac` (
  `Ma_CB` int(11) NOT NULL,
  `Ma_QTCT` bigint(20) NOT NULL AUTO_INCREMENT,
  `Tu_Ngay` date DEFAULT NULL,
  `Đen_Ngay` date DEFAULT NULL,
  `So_Luoc` varchar(254) DEFAULT NULL,
  `Chuc_Danh` varchar(62) DEFAULT NULL,
  `Chuc_Vu` varchar(62) DEFAULT NULL,
  `Đon_Vi_Cong_Tac` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_QTCT`,`Ma_CB`),
  KEY `Ma_CB` (`Ma_CB`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `qua_trinh_luong`
--

CREATE TABLE IF NOT EXISTS `qua_trinh_luong` (
  `Ma_CB` int(11) NOT NULL,
  `Thoi_Gian_Nang_Luong` date NOT NULL DEFAULT '0001-01-01',
  `Ma_So_Ngach` varchar(254) DEFAULT NULL,
  `Bac_Luong` varchar(32) DEFAULT NULL,
  `He_So_Luong` float DEFAULT NULL,
  `Phu_Cap_Vuot_Khung` float DEFAULT NULL,
  `He_So_Phu_Cap` float DEFAULT NULL,
  `Muc_Luong_Khoang` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`Ma_CB`,`Thoi_Gian_Nang_Luong`),
  KEY `FK_co_Ngach` (`Ma_So_Ngach`),
  KEY `INDEX_NgayNangLuong` (`Thoi_Gian_Nang_Luong`),
  KEY `INDEX_MaCanBO` (`Ma_CB`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `qua_trinh_luong`
--

INSERT INTO `qua_trinh_luong` (`Ma_CB`, `Thoi_Gian_Nang_Luong`, `Ma_So_Ngach`, `Bac_Luong`, `He_So_Luong`, `Phu_Cap_Vuot_Khung`, `He_So_Phu_Cap`, `Muc_Luong_Khoang`) VALUES
(107, '2017-04-21', '00000', '', 0, 0, 0, 0),
(109, '2017-05-08', '00000', '', 0, 0, 0, 0),
(110, '2017-05-08', '00000', '', 0, 0, 0, 0),
(111, '2017-05-08', '00000', '', 0, 0, 0, 0),
(112, '2017-05-08', '00000', '', 0, 0, 0, 0),
(113, '2017-05-08', '00000', '', 0, 0, 0, 0),
(114, '2017-05-08', '00000', '', 0, 0, 0, 0),
(115, '2017-05-08', '00000', '', 0, 0, 0, 0),
(116, '2017-05-08', '00000', '', 0, 0, 0, 0),
(117, '2017-05-08', '00000', '', 0, 0, 0, 0),
(119, '2017-05-09', '00000', '', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `Role_Name` varchar(32) NOT NULL,
  `Role_Display_Name` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Role_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`Role_Name`, `Role_Display_Name`) VALUES
('admin', 'Quản trị cao cấp'),
('cadre', 'Cán bộ'),
('manager', 'Cán Bộ Quản lý');

-- --------------------------------------------------------

--
-- Table structure for table `role_privilege_relation`
--

CREATE TABLE IF NOT EXISTS `role_privilege_relation` (
  `Role_Name` varchar(32) NOT NULL,
  `Privilege_Name` varchar(32) NOT NULL,
  `isAllowed` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`Role_Name`,`Privilege_Name`),
  KEY `FK_for_role` (`Privilege_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `Status_Code` tinyint(4) NOT NULL,
  `Status_Name` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Status_Code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`Status_Code`, `Status_Name`) VALUES
(-1, 'blocked'),
(0, 'unconfirmed'),
(1, 'actived');

-- --------------------------------------------------------

--
-- Table structure for table `thanh_vien_gia_đinh`
--

CREATE TABLE IF NOT EXISTS `thanh_vien_gia_đinh` (
  `Ma_Quan_He` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Ma_CB` int(11) NOT NULL,
  `Ben_Vo` tinyint(1) NOT NULL,
  `Quan_He` varchar(62) NOT NULL,
  `Ho_Ten` varchar(62) DEFAULT NULL,
  `Nam_Sinh` date DEFAULT NULL,
  `Thong_Tin_So_Luoc` text,
  `Que_Quan` varchar(254) DEFAULT NULL,
  `Nghe_Nghiep` varchar(254) DEFAULT NULL,
  `Chuc_Danh` varchar(254) DEFAULT NULL,
  `Chuc_Vu` varchar(254) DEFAULT NULL,
  `Đon_Vi_Cong_Tac` varchar(254) DEFAULT NULL,
  `Hoc_Tap` varchar(254) DEFAULT NULL,
  `Noi_O` varchar(254) DEFAULT NULL,
  `Thanh_Vien_Cac_To_Chuc` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_Quan_He`,`Ma_CB`),
  KEY `Ben_Vo` (`Ben_Vo`),
  KEY `Ma_CB` (`Ma_CB`),
  KEY `Ma_Quan_He` (`Ma_Quan_He`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `thong_tin_tham_gia_ban`
--

CREATE TABLE IF NOT EXISTS `thong_tin_tham_gia_ban` (
  `Ma_CB` int(11) NOT NULL,
  `Ma_Ban` int(11) NOT NULL,
  `Ngay_Gia_Nhap` date NOT NULL,
  `Ngay_Roi_Khoi` date DEFAULT NULL,
  `Ly_Do_Chuyen_Đen` varchar(254) DEFAULT NULL,
  `Ma_CV` smallint(5) unsigned DEFAULT NULL,
  `La_Cong_Tac_Chinh` tinyint(4) NOT NULL DEFAULT '1',
  `STT_To` tinyint(4) DEFAULT NULL,
  `Ma_Ban_Truoc_Đo` int(11) DEFAULT NULL,
  `Ngay_GN_Ban_Truoc_Đo` date DEFAULT NULL,
  `Trang_Thai` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1-Đang công tác, 0-đã chuyển đi',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Ma_CB`,`Ma_Ban`,`Ngay_Gia_Nhap`),
  KEY `FK_cv_tai_ban` (`Ma_CV`),
  KEY `FK_to_cong_tac_cua_CB_tai_ban` (`Ma_Ban`,`STT_To`),
  KEY `La_Cong_Tac_Chinh` (`La_Cong_Tac_Chinh`),
  KEY `Ngay_Roi_Khoi` (`Ngay_Roi_Khoi`),
  KEY `Ma_Ban` (`Ma_Ban`),
  KEY `Ngay_Gia_Nhap` (`Ngay_Gia_Nhap`),
  KEY `Ma_Ban__NgayRK` (`Ma_Ban`,`Ngay_Roi_Khoi`),
  KEY `Ma_CB___NgayRK__Ma_CV` (`Ma_CB`,`Ngay_Roi_Khoi`,`Ma_CV`),
  KEY `Trang_Thai` (`Trang_Thai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `thong_tin_tham_gia_ban_bk`
--

CREATE TABLE IF NOT EXISTS `thong_tin_tham_gia_ban_bk` (
  `Ma_CB` int(11) NOT NULL,
  `Ma_Ban` int(11) NOT NULL,
  `Ngay_Gia_Nhap` date NOT NULL,
  `Ngay_Roi_Khoi` date DEFAULT NULL,
  `Ly_Do_Chuyen_Đen` varchar(254) DEFAULT NULL,
  `Ma_CV` smallint(5) unsigned DEFAULT NULL,
  `La_Cong_Tac_Chinh` tinyint(4) NOT NULL DEFAULT '1',
  `STT_To` tinyint(4) DEFAULT NULL,
  `Ma_Ban_Truoc_Đo` int(11) DEFAULT NULL,
  `Ngay_GN_Ban_Truoc_Đo` date DEFAULT NULL,
  `Trang_Thai` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1-Đang công tác, 0-đã chuyển đi',
  PRIMARY KEY (`Ma_CB`,`Ma_Ban`,`Ngay_Gia_Nhap`),
  KEY `FK_cv_tai_ban` (`Ma_CV`),
  KEY `FK_to_cong_tac_cua_CB_tai_ban` (`Ma_Ban`,`STT_To`),
  KEY `La_Cong_Tac_Chinh` (`La_Cong_Tac_Chinh`),
  KEY `Ngay_Roi_Khoi` (`Ngay_Roi_Khoi`),
  KEY `Ma_Ban` (`Ma_Ban`),
  KEY `Ngay_Gia_Nhap` (`Ngay_Gia_Nhap`),
  KEY `Ma_Ban__NgayRK` (`Ma_Ban`,`Ngay_Roi_Khoi`),
  KEY `Ma_CB___NgayRK__Ma_CV` (`Ma_CB`,`Ngay_Roi_Khoi`,`Ma_CV`),
  KEY `Trang_Thai` (`Trang_Thai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `thong_tin_tham_gia_ban_bk`
--

INSERT INTO `thong_tin_tham_gia_ban_bk` (`Ma_CB`, `Ma_Ban`, `Ngay_Gia_Nhap`, `Ngay_Roi_Khoi`, `Ly_Do_Chuyen_Đen`, `Ma_CV`, `La_Cong_Tac_Chinh`, `STT_To`, `Ma_Ban_Truoc_Đo`, `Ngay_GN_Ban_Truoc_Đo`, `Trang_Thai`) VALUES
(1, 2, '2014-04-01', NULL, NULL, 3, 1, NULL, NULL, NULL, 1),
(1, 5, '2014-04-01', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(1, 9, '2014-05-24', NULL, ' ', 9, 1, NULL, 0, NULL, 1),
(1, 11, '2014-05-24', NULL, NULL, 8, 1, NULL, NULL, NULL, 1),
(1, 13, '2015-05-13', NULL, NULL, 11, 1, NULL, NULL, NULL, 1),
(1, 14, '2015-05-22', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(1, 15, '2015-05-22', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(1, 16, '2015-05-22', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(1, 17, '2015-05-22', NULL, NULL, 2, 1, NULL, NULL, NULL, 1),
(1, 18, '2015-05-22', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(1, 19, '2015-05-22', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(1, 20, '2015-05-22', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(1, 21, '2015-05-22', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(1, 22, '2015-06-04', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(1, 23, '2015-06-04', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(1, 24, '2015-06-04', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 25, '2015-06-04', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 26, '2015-06-04', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 27, '2015-06-04', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 28, '2015-06-04', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 29, '2015-06-04', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 30, '2015-06-04', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 31, '2015-06-04', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 32, '2015-06-29', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 33, '2015-06-30', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 34, '2015-06-29', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 35, '2015-06-23', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 36, '2015-06-16', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 37, '2015-06-16', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 38, '2015-06-16', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(2, 2, '2014-04-01', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(2, 5, '2014-04-01', NULL, NULL, 2, 1, NULL, NULL, NULL, 1),
(2, 7, '2014-05-24', NULL, ' ', 0, 1, NULL, 0, NULL, 1),
(2, 11, '2014-05-24', NULL, NULL, 8, 1, NULL, NULL, NULL, 1),
(2, 13, '2015-05-13', NULL, NULL, 7, 1, NULL, NULL, NULL, 1),
(2, 15, '2015-05-22', NULL, NULL, 2, 1, NULL, NULL, NULL, 1),
(2, 18, '2015-05-22', NULL, NULL, 2, 1, NULL, NULL, NULL, 1),
(2, 22, '2015-06-04', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(2, 36, '2015-06-16', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(2, 37, '2015-06-16', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(2, 38, '2015-06-16', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(3, 8, '2014-05-24', NULL, ' ', 9, 1, NULL, 0, NULL, 1),
(3, 11, '2014-05-24', NULL, NULL, 6, 1, NULL, NULL, NULL, 1),
(3, 13, '2015-05-13', NULL, NULL, 2, 1, NULL, NULL, NULL, 1),
(3, 18, '2015-05-22', NULL, NULL, 3, 1, NULL, NULL, NULL, 1),
(3, 22, '2015-06-04', NULL, NULL, 3, 1, NULL, NULL, NULL, 1),
(3, 23, '2015-06-04', NULL, NULL, 2, 1, NULL, NULL, NULL, 1),
(4, 1, '2014-04-01', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(4, 13, '2015-05-13', NULL, NULL, 3, 1, NULL, NULL, NULL, 1),
(4, 23, '2015-06-04', NULL, NULL, 3, 1, NULL, NULL, NULL, 1),
(4, 39, '2017-03-15', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(4, 43, '1970-01-01', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(4, 44, '2017-03-15', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(4, 45, '2017-03-15', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(5, 6, '2014-05-24', NULL, ' ', 9, 1, NULL, 0, NULL, 1),
(6, 4, '2014-05-24', NULL, ' ', 0, 1, NULL, 0, NULL, 1),
(6, 11, '2014-05-24', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(6, 13, '2015-05-13', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(7, 5, '2015-06-24', '2015-06-24', ' ', 1, 1, NULL, 11, '2014-05-24', 0),
(7, 10, '2015-06-25', NULL, ' ', 2, 1, NULL, 18, '2015-06-24', 1),
(7, 11, '2014-05-24', '2015-06-24', NULL, 3, 1, NULL, NULL, NULL, 0),
(7, 18, '2015-06-24', '2015-06-25', ' ', 5, 1, NULL, 5, '2015-06-24', 0),
(9, 12, '2015-03-15', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(9, 13, '2015-05-13', NULL, NULL, 16, 1, NULL, NULL, NULL, 1),
(11, 8, '2015-06-04', '2015-06-25', ' phu hop', 0, 1, NULL, 23, '2015-06-04', 0),
(11, 23, '2015-06-04', '2015-06-04', NULL, 7, 1, NULL, NULL, NULL, 0),
(13, 45, '2017-03-15', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(94, 21, '2015-06-25', NULL, '', 3, 1, NULL, 0, '1970-01-01', 1),
(95, 4, '2017-03-15', '1970-01-01', ' ', 0, 1, NULL, 0, '1970-01-01', 0),
(96, 23, '2015-06-04', NULL, NULL, 15, 1, NULL, NULL, NULL, 1),
(99, 7, '2015-06-25', NULL, ' ', 4, 1, NULL, 0, '1970-01-01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ton_giao`
--

CREATE TABLE IF NOT EXISTS `ton_giao` (
  `Ma_Ton_Giao` smallint(6) NOT NULL AUTO_INCREMENT,
  `Ten_Ton_Giao` varchar(62) DEFAULT NULL,
  PRIMARY KEY (`Ma_Ton_Giao`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `ton_giao`
--

INSERT INTO `ton_giao` (`Ma_Ton_Giao`, `Ten_Ton_Giao`) VALUES
(0, '(không)'),
(1, '(khác)'),
(2, 'Phật Giáo'),
(3, 'Cao Đài'),
(4, 'Hòa Hảo'),
(5, 'Thiên Chúa'),
(6, 'Ấn Độ Giáo'),
(7, 'Hiếu Nghĩa');

-- --------------------------------------------------------

--
-- Table structure for table `to_cong_tac`
--

CREATE TABLE IF NOT EXISTS `to_cong_tac` (
  `Ma_Ban` int(11) NOT NULL,
  `STT_To` tinyint(4) NOT NULL,
  `Ten_To` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_Ban`,`STT_To`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `trinh_đo_chuyen_mon`
--

CREATE TABLE IF NOT EXISTS `trinh_đo_chuyen_mon` (
  `Cap_Đo_TĐCM` decimal(4,2) NOT NULL DEFAULT '0.00',
  `Ten_TĐCM` varchar(254) DEFAULT NULL,
  `Viet_Tat_TĐCM` varchar(8) NOT NULL,
  PRIMARY KEY (`Cap_Đo_TĐCM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `trinh_đo_chuyen_mon`
--

INSERT INTO `trinh_đo_chuyen_mon` (`Cap_Đo_TĐCM`, `Ten_TĐCM`, `Viet_Tat_TĐCM`) VALUES
('12.00', '(chưa có bằng CM)', ''),
('14.00', 'Trung cấp', 'TC'),
('14.50', 'TC Chuyên Nghiệp', 'TCCN'),
('15.00', 'Cao đẳng', 'CĐ'),
('16.00', 'Cử nhân', 'CN ĐH'),
('16.50', 'Kỹ sư', 'KS ĐH'),
('18.00', 'Thạc Sỹ', 'ThS'),
('20.00', 'Tiến Sỹ', 'TS'),
('22.00', 'Tiến Sỹ Khoa Học', 'TSKH');

-- --------------------------------------------------------

--
-- Table structure for table `trinh_đo_ly_luan_chinh_tri`
--

CREATE TABLE IF NOT EXISTS `trinh_đo_ly_luan_chinh_tri` (
  `Cap_Đo_LLCT` decimal(4,2) NOT NULL DEFAULT '0.00',
  `Ten_CTLL` varchar(254) DEFAULT NULL,
  `Viet_Tat_CTLL` varchar(32) NOT NULL,
  PRIMARY KEY (`Cap_Đo_LLCT`),
  KEY `cap_do_lltt` (`Cap_Đo_LLCT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `trinh_đo_ly_luan_chinh_tri`
--

INSERT INTO `trinh_đo_ly_luan_chinh_tri` (`Cap_Đo_LLCT`, `Ten_CTLL`, `Viet_Tat_CTLL`) VALUES
('0.00', '(chưa có)', ''),
('1.00', 'Sơ cấp', 'SC'),
('2.00', 'Trung cấp', 'TC'),
('3.00', 'Cao cấp', 'CC');

-- --------------------------------------------------------

--
-- Table structure for table `tt_tai_đon_vi`
--

CREATE TABLE IF NOT EXISTS `tt_tai_đon_vi` (
  `NgayDen` datetime DEFAULT NULL,
  `LidoChuyenĐen` varchar(254) DEFAULT NULL,
  `HoSoXacNhan` varchar(254) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(254) NOT NULL,
  `Password` varchar(254) DEFAULT NULL,
  `Identifier_Info` int(11) DEFAULT NULL,
  `Status_Code` tinyint(4) DEFAULT '1',
  `Role_Name` varchar(32) DEFAULT NULL,
  `Password_Key` varchar(254) NOT NULL DEFAULT 'dk',
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Username` (`Username`),
  KEY `FK_have_role` (`Role_Name`),
  KEY `FK_have_status` (`Status_Code`),
  KEY `Identifier_Info` (`Identifier_Info`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=138 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Username`, `Password`, `Identifier_Info`, `Status_Code`, `Role_Name`, `Password_Key`) VALUES
(1, 'admin', '66579284dd25f62cde52b036108c4bd6', NULL, 1, 'admin', 'dk'),
(124, 'minhphuong', 'fca527673673988b426bc9639a03fada', 107, 1, 'cadre', '21/04/2017 07:04:33:0433'),
(126, 'nmhai_btc', '5db67526d3ec440f3c713f491a58a2b0', 109, 1, 'manager', '08/05/2017 12:05:08:0508'),
(127, 'vtmphuong_btc', 'e249aa5016ece0f97b1bdc6f3944d4cf', 110, 1, 'cadre', '08/05/2017 12:05:02:0502'),
(128, 'ntdanh_btc', '7dd962dddcd9afd443a0d988d337032c', 111, 1, 'cadre', '08/05/2017 12:05:50:0550'),
(129, 'mktuyen_btc', 'b6a16ab74cae3b67437b3c50b4116652', 112, 1, 'cadre', '09/05/2017 07:05:08:0508'),
(130, 'pttlinh_btc', 'e9472d652d334b776f91008c9f93edd7', 113, 1, 'cadre', '08/05/2017 12:05:56:0556'),
(131, 'tttra_btc', '45e00def8ba059099903b396b21b9755', 114, 1, 'cadre', '08/05/2017 12:05:44:0544'),
(133, 'ndkhoa_btc', '298a1ad68de4e85dd7104309a5d4d74c', 116, 1, 'cadre', '08/05/2017 12:05:39:0539'),
(134, 'test', '00000be3d6700ef91a73c3bc32a56950', 115, 1, NULL, '08/05/2017 11:05:21:0521'),
(135, 'nnhai_btc', 'db63769b0ef925ff87be2a5c16a4872f', 117, 1, 'cadre', '08/05/2017 12:05:06:0506'),
(137, 'npthao_btc', '9bc67d5200e9c75418e0e28fd6f65adb', 119, 1, 'manager', '09/05/2017 07:05:18:0518');

-- --------------------------------------------------------

--
-- Table structure for table `user_log`
--

CREATE TABLE IF NOT EXISTS `user_log` (
  `Thoi_Gian` datetime NOT NULL,
  `Ma_User_Thuc_Hien` int(11) NOT NULL,
  `Ma_CB_Thuc_Hien` int(11) DEFAULT NULL,
  `Chuc_Nang` varchar(254) DEFAULT NULL,
  `Noi_Dung` text,
  PRIMARY KEY (`Thoi_Gian`,`Ma_User_Thuc_Hien`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_log`
--

INSERT INTO `user_log` (`Thoi_Gian`, `Ma_User_Thuc_Hien`, `Ma_CB_Thuc_Hien`, `Chuc_Nang`, `Noi_Dung`) VALUES
('2016-09-11 03:16:08', 109, NULL, 'Thêm công tác nước ngoài', 'Thêm công tác nước ngoài cho cán bộ: '),
('2016-09-11 03:19:04', 109, NULL, 'Thêm công tác nước ngoài', 'Thêm công tác nước ngoài cho cán bộ: '),
('2016-10-30 12:33:49', 109, NULL, 'Thêm công tác nước ngoài', 'Thêm công tác nước ngoài cho cán bộ: '),
('2016-10-30 12:34:03', 109, NULL, 'Thêm công tác nước ngoài', 'Thêm công tác nước ngoài cho cán bộ: '),
('2016-10-30 12:35:13', 109, NULL, 'Thêm công tác nước ngoài', 'Thêm công tác nước ngoài cho cán bộ: Nguyễn Tuấn Anh'),
('2016-10-30 12:35:31', 109, NULL, 'Thêm công tác nước ngoài', 'Thêm công tác nước ngoài cho cán bộ: ');

-- --------------------------------------------------------

--
-- Table structure for table `yeu_cau_thay_đoi_tt_cb`
--

CREATE TABLE IF NOT EXISTS `yeu_cau_thay_đoi_tt_cb` (
  `Ma_Yeu_Cau` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Ma_CB_Anh_Huong` int(11) NOT NULL,
  `Ma_CB_Yeu_Cau` int(11) NOT NULL,
  `Ten_Yeu_Cau` varchar(254) DEFAULT NULL,
  `Loi_Noi` varchar(254) DEFAULT NULL,
  `Trang_Thai` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`Ma_Yeu_Cau`),
  KEY `FK_Anh_Huong_Can_bo` (`Ma_CB_Anh_Huong`),
  KEY `FK_đuoc_can_bo_yeu_cau` (`Ma_CB_Yeu_Cau`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `_bkthong_tin_tham_gia_ban`
--

CREATE TABLE IF NOT EXISTS `_bkthong_tin_tham_gia_ban` (
  `Ma_CB` int(11) NOT NULL,
  `Ma_Ban` int(11) NOT NULL,
  `Ngay_Gia_Nhap` date NOT NULL,
  `Ngay_Roi_Khoi` date DEFAULT NULL,
  `Ly_Do_Chuyen_Đen` varchar(254) DEFAULT NULL,
  `Ma_CV` smallint(5) unsigned DEFAULT NULL,
  `La_Cong_Tac_Chinh` tinyint(4) NOT NULL DEFAULT '1',
  `STT_To` tinyint(4) DEFAULT NULL,
  `Ma_Ban_Truoc_Đo` int(11) DEFAULT NULL,
  `Ngay_GN_Ban_Truoc_Đo` date DEFAULT NULL,
  `Trang_Thai` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1-Đang công tác, 0-đã chuyển đi',
  PRIMARY KEY (`Ma_CB`,`Ma_Ban`,`Ngay_Gia_Nhap`),
  KEY `FK_cv_tai_ban` (`Ma_CV`),
  KEY `FK_to_cong_tac_cua_CB_tai_ban` (`Ma_Ban`,`STT_To`),
  KEY `La_Cong_Tac_Chinh` (`La_Cong_Tac_Chinh`),
  KEY `Ngay_Roi_Khoi` (`Ngay_Roi_Khoi`),
  KEY `Ma_Ban` (`Ma_Ban`),
  KEY `Ngay_Gia_Nhap` (`Ngay_Gia_Nhap`),
  KEY `Ma_Ban__NgayRK` (`Ma_Ban`,`Ngay_Roi_Khoi`),
  KEY `Ma_CB___NgayRK__Ma_CV` (`Ma_CB`,`Ngay_Roi_Khoi`,`Ma_CV`),
  KEY `Trang_Thai` (`Trang_Thai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `_bkthong_tin_tham_gia_ban`
--

INSERT INTO `_bkthong_tin_tham_gia_ban` (`Ma_CB`, `Ma_Ban`, `Ngay_Gia_Nhap`, `Ngay_Roi_Khoi`, `Ly_Do_Chuyen_Đen`, `Ma_CV`, `La_Cong_Tac_Chinh`, `STT_To`, `Ma_Ban_Truoc_Đo`, `Ngay_GN_Ban_Truoc_Đo`, `Trang_Thai`) VALUES
(1, 2, '2014-04-01', NULL, NULL, 3, 1, NULL, NULL, NULL, 1),
(1, 5, '2014-04-01', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(1, 9, '2014-05-24', NULL, ' ', 9, 1, NULL, 0, NULL, 1),
(1, 11, '2014-05-24', NULL, NULL, 8, 1, NULL, NULL, NULL, 1),
(1, 13, '2015-05-13', NULL, NULL, 11, 1, NULL, NULL, NULL, 1),
(1, 14, '2015-05-22', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(1, 15, '2015-05-22', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(1, 16, '2015-05-22', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(1, 17, '2015-05-22', NULL, NULL, 2, 1, NULL, NULL, NULL, 1),
(1, 18, '2015-05-22', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(1, 19, '2015-05-22', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(1, 20, '2015-05-22', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(1, 21, '2015-05-22', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(1, 22, '2015-06-04', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(1, 23, '2015-06-04', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(1, 24, '2015-06-04', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 25, '2015-06-04', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 26, '2015-06-04', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 27, '2015-06-04', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 28, '2015-06-04', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 29, '2015-06-04', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 30, '2015-06-04', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 31, '2015-06-04', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 32, '2015-06-29', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 33, '2015-06-30', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 34, '2015-06-29', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 35, '2015-06-23', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 36, '2015-06-16', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 37, '2015-06-16', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(1, 38, '2015-06-16', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(2, 2, '2014-04-01', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(2, 5, '2014-04-01', NULL, NULL, 2, 1, NULL, NULL, NULL, 1),
(2, 7, '2014-05-24', NULL, ' ', 0, 1, NULL, 0, NULL, 1),
(2, 11, '2014-05-24', NULL, NULL, 8, 1, NULL, NULL, NULL, 1),
(2, 13, '2015-05-13', NULL, NULL, 7, 1, NULL, NULL, NULL, 1),
(2, 15, '2015-05-22', NULL, NULL, 2, 1, NULL, NULL, NULL, 1),
(2, 18, '2015-05-22', NULL, NULL, 2, 1, NULL, NULL, NULL, 1),
(2, 22, '2015-06-04', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(2, 36, '2015-06-16', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(2, 37, '2015-06-16', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(2, 38, '2015-06-16', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(3, 8, '2014-05-24', NULL, ' ', 9, 1, NULL, 0, NULL, 1),
(3, 11, '2014-05-24', NULL, NULL, 6, 1, NULL, NULL, NULL, 1),
(3, 13, '2015-05-13', NULL, NULL, 2, 1, NULL, NULL, NULL, 1),
(3, 18, '2015-05-22', NULL, NULL, 3, 1, NULL, NULL, NULL, 1),
(3, 22, '2015-06-04', NULL, NULL, 3, 1, NULL, NULL, NULL, 1),
(3, 23, '2015-06-04', NULL, NULL, 2, 1, NULL, NULL, NULL, 1),
(4, 1, '2014-04-01', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(4, 13, '2015-05-13', NULL, NULL, 3, 1, NULL, NULL, NULL, 1),
(4, 23, '2015-06-04', NULL, NULL, 3, 1, NULL, NULL, NULL, 1),
(4, 39, '2017-03-15', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(4, 43, '1970-01-01', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(4, 44, '2017-03-15', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(4, 45, '2017-03-15', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(5, 6, '2014-05-24', NULL, ' ', 9, 1, NULL, 0, NULL, 1),
(6, 4, '2014-05-24', NULL, ' ', 0, 1, NULL, 0, NULL, 1),
(6, 11, '2014-05-24', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(6, 13, '2015-05-13', NULL, NULL, 1, 1, NULL, NULL, NULL, 1),
(7, 5, '2015-06-24', '2015-06-24', ' ', 1, 1, NULL, 11, '2014-05-24', 0),
(7, 10, '2015-06-25', NULL, ' ', 2, 1, NULL, 18, '2015-06-24', 1),
(7, 11, '2014-05-24', '2015-06-24', NULL, 3, 1, NULL, NULL, NULL, 0),
(7, 18, '2015-06-24', '2015-06-25', ' ', 5, 1, NULL, 5, '2015-06-24', 0),
(9, 12, '2015-03-15', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(9, 13, '2015-05-13', NULL, NULL, 16, 1, NULL, NULL, NULL, 1),
(11, 8, '2015-06-04', '2015-06-25', ' phu hop', 0, 1, NULL, 23, '2015-06-04', 0),
(11, 23, '2015-06-04', '2015-06-04', NULL, 7, 1, NULL, NULL, NULL, 0),
(13, 45, '2017-03-15', NULL, NULL, 0, 1, NULL, NULL, NULL, 1),
(94, 21, '2015-06-25', NULL, '', 3, 1, NULL, 0, '1970-01-01', 1),
(95, 4, '2017-03-15', '1970-01-01', ' ', 0, 1, NULL, 0, '1970-01-01', 0),
(96, 23, '2015-06-04', NULL, NULL, 15, 1, NULL, NULL, NULL, 1),
(99, 7, '2015-06-25', NULL, ' ', 4, 1, NULL, 0, '1970-01-01', 1);

--
-- Triggers `_bkthong_tin_tham_gia_ban`
--
DROP TRIGGER IF EXISTS `tg_ngayroikhoi`;
DELIMITER //
CREATE TRIGGER `tg_ngayroikhoi` BEFORE INSERT ON `_bkthong_tin_tham_gia_ban`
 FOR EACH ROW IF(NEW.Ngay_Gia_Nhap>NEW.Ngay_Roi_Khoi) THEN

	SET NEW.Ngay_Roi_Khoi = null;
END IF
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `đac_điem_lich_su`
--

CREATE TABLE IF NOT EXISTS `đac_điem_lich_su` (
  `Ma_CB` int(11) NOT NULL,
  `Ma_ĐĐLS` bigint(20) NOT NULL,
  `Su_Kien` varchar(254) DEFAULT NULL,
  `Tu_Thoi_Điem` date DEFAULT NULL,
  `Đen_Thoi_Điem` date DEFAULT NULL,
  `Nguoi_Nhan_Khai_Bao` varchar(254) DEFAULT NULL,
  `Noi_Dung` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_ĐĐLS`,`Ma_CB`),
  KEY `FK_cua_Can_Bo_1` (`Ma_CB`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `đanh_gia_can_bo`
--

CREATE TABLE IF NOT EXISTS `đanh_gia_can_bo` (
  `canbo_id` int(11) NOT NULL,
  `dot_danh_gia` int(11) NOT NULL,
  `noi_dung_danh_gia` text,
  `ma_tu_xep_loai` tinyint(4) DEFAULT NULL,
  `ma_xep_loai_finish` int(2) DEFAULT NULL COMMENT 'truong ban danh gia',
  `luu_y` text,
  `manager_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`canbo_id`,`dot_danh_gia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `đao_tao_boi_duong`
--

CREATE TABLE IF NOT EXISTS `đao_tao_boi_duong` (
  `Ma_CB` int(11) NOT NULL,
  `Ma_ĐTBD` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Ten_Truong` varchar(254) DEFAULT NULL,
  `Nganh_Hoc` varchar(254) DEFAULT NULL,
  `Thoi_Gian_Hoc` text,
  `TG_Ket_Thuc` text,
  `Hinh_Thuc_Hoc` varchar(254) DEFAULT NULL,
  `Van_Bang_Chung_Chi` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_ĐTBD`,`Ma_CB`),
  KEY `Ma_CB` (`Ma_CB`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `đon_vi`
--

CREATE TABLE IF NOT EXISTS `đon_vi` (
  `Ma_ĐV` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `Ky_Hieu_ĐV` varchar(32) NOT NULL,
  `Ten_Đon_Vi` varchar(254) DEFAULT NULL,
  `Ma_Khoi` tinyint(3) unsigned DEFAULT NULL,
  `Ma_Truong_ĐV` int(11) DEFAULT NULL,
  `Ma_Ban_Chap_Hanh` int(11) DEFAULT NULL,
  `Ngay_Thanh_Lap` date DEFAULT NULL,
  `Chuc_Nang_ĐV` varchar(254) DEFAULT NULL,
  `Đia_Chi` varchar(254) DEFAULT NULL,
  `Email` varchar(126) DEFAULT NULL,
  `So_Đien_Thoai` varchar(62) DEFAULT NULL,
  `Mo_Ta` varchar(254) DEFAULT NULL,
  `Trang_Thai` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`Ma_ĐV`),
  KEY `FK_Co_Ban_Chap_Hanh` (`Ma_Ban_Chap_Hanh`),
  KEY `FK_co_Truong_Đon_Vi` (`Ma_Truong_ĐV`),
  KEY `FK_khoi_truc_thuoc` (`Ma_Khoi`),
  KEY `Ma_Đon_Vi` (`Ky_Hieu_ĐV`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `đon_vi`
--

INSERT INTO `đon_vi` (`Ma_ĐV`, `Ky_Hieu_ĐV`, `Ten_Đon_Vi`, `Ma_Khoi`, `Ma_Truong_ĐV`, `Ma_Ban_Chap_Hanh`, `Ngay_Thanh_Lap`, `Chuc_Nang_ĐV`, `Đia_Chi`, `Email`, `So_Đien_Thoai`, `Mo_Ta`, `Trang_Thai`) VALUES
(0, 'CTTD', 'Cơ Quan Chuyên Trách Thành Đoàn', 4, NULL, 45, NULL, NULL, '', '', NULL, '', 1),
(1, 'SNTD', 'Đơn vị sự nghiệp trực thuộc thành đoàn', 4, NULL, 20, '2006-06-08', NULL, NULL, NULL, NULL, NULL, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
