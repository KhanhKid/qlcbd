-- phpMyAdmin SQL Dump
-- version 4.1.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 14, 2014 at 08:25 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.12

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
                       Chuc_Vu.Ten_Chuc_Vu
                from Can_Bo left join Ly_Lich on( Can_Bo.Ma_Can_Bo = Ly_Lich.Ma_CB)
                            left join Chuc_Vu on (Can_Bo.Ma_CV_Chinh = Chuc_Vu.Ma_Chuc_Vu)
                WHERE Ngay_Roi_Khoi IS NULL$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_layDSCanBoVoiThongTinVanTat`()
    NO SQL
SELECT can_bo.Ma_Can_Bo, Ho_Ten_CB, DATE_FORMAT(Ngay_Sinh,'%d/%m/%Y') AS Ngay_Sinh, SO_CMND
                from Can_Bo left join Ly_Lich on( can_bo.Ma_Can_Bo = Ly_Lich.Ma_CB)
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
                FROM Can_Bo LEFT JOIN Ly_Lich on( can_bo.Ma_Can_Bo = Ly_Lich.Ma_CB)
                WHERE Ma_CB = maCanBo
                LIMIT 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_layThongTinLyLich`(IN `macb` INT)
    NO SQL
SELECT can_bo.Ma_Can_Bo,đon_vi.Ten_Đon_Vi,can_bo.Ho_Ten_CB,can_bo.Ma_Quan_Ly, ly_lich.So_Hieu_CB, can_bo.Ngay_Roi_Khoi, can_bo.Con_Song, can_bo.Tham_Gia_CLBTT,
                       Ten_Goi_Khac, Gioi_Tinh,Cap_Uy_Hien_Tai, Cap_Uy_Kiem, cvchinh.Ten_Chuc_Vu as Chuc_Vu_Chinh, Chuc_Danh as Chuc_Danh, Phu_Cap_Chuc_Vu,
                       Ngay_Sinh, Noi_Sinh, Que_Quan,Noi_O_Hien_Nay, Đien_Thoai,
                       ton_giao.Ten_Ton_Giao as Ton_Giao, dan_toc.Ten_Dan_Toc as Dan_Toc,Thanh_Phan_Gia_Đinh_Xuat_Than,
                       Nghe_Nghiep_Truoc_Đo, Co_Quan_Tuyen_Dung, Đia_Chi_Co_Quan_Tuyen_Dung, Ngay_Đuoc_Tuyen_Dung,
                       can_bo.Ngay_Gia_Nhap, Ngay_Tham_Gia_CM,
                       Ngay_Vao_Đang, Ngay_Chinh_Thuc,
                       Ngay_Tham_Gia_Cac_To_Chuc_Chinh_Tri_Xa_Hoi,
                       Ngay_Nhap_Ngu, Ngay_Xuat_Ngu, Quan_Ham_Chuc_Vu_Cao_Nhat,
                       Trinh_Đo_Hoc_Van, trinh_đo_chuyen_mon.Ten_TĐCM as Trinh_Đo_Chuyen_Mon, Chuyen_Nganh, Hoc_Ham_Hoc_Vi_Cao_Nhat, trinh_đo_ly_luan_chinh_tri.Ten_CTLL, Ngoai_Ngu,
                       Cong_Tac_Chinh_Đang_Lam, qua_trinh_luong.Ma_So_Ngach, qua_trinh_luong.Bac_Luong, qua_trinh_luong.He_So_Luong,
                       Danh_Hieu_Đuoc_Phong, So_Truong_Cong_Tac, Cong_Viec_Lam_Lau_Nhat, Khen_Thuong, Ky_Luat, Khen_Thuong,
                       Tinh_Trang_Suc_Khoe, Chieu_Cao, Can_Nang, Nhom_Mau, So_CMND , Ngay_Cap_CMND, Noi_Cap_CMND,
                       Loai_Thuong_Binh, Gia_Đinh_Liet_Sy, Lam_Viec_Trong_Che_Đo_Cu,
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
                            LEFT JOIN qua_trinh_luong ON (can_bo.Ma_Can_Bo = qua_trinh_luong.Ma_CB AND qua_trinh_luong.thoi_gian_nang_luong = (SELECT MAX(qua_trinh_luong.Thoi_Gian_Nang_Luong)
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=56 ;

--
-- Dumping data for table `ban`
--

INSERT INTO `ban` (`Ma_Ban`, `Ma_Loai_Ban`, `Ma_Đon_Vi`, `Ten_Ban`, `Ngay_Thanh_Lap`, `Ngay_Man_Nhiem`, `Mo_Ta`, `Trang_Thai`) VALUES
(25, 1, 4, 'Ban Chấp Hành', '2013-12-29', NULL, '', 1),
(26, 1, 0, 'Ban Chấp Hành', '2014-01-06', '2014-12-03', '', 0),
(27, 1, 6, 'Ban Chấp Hành trường ĐH Công nghệ Thông tin', '2014-01-01', '2014-01-07', '<p>Mô tả cho BCH trường ĐH Công nghệ Thông tin</p>\r\n', 0),
(28, 1, 0, 'Ban Chấp Hành trường ĐH Kinh Tế - Luật', '2014-01-02', NULL, '<p>Mô tả cho BCH trường ĐH Kinh Tế - Luật</p>\r\n', 1),
(29, 1, 6, 'Ban Chấp Hành trường ĐH Công nghệ Thông tin', '2014-01-07', NULL, '<p>12345</p>\r\n', 1),
(30, 1, 0, 'Ban Chấp Hành Trường ĐH Bách Khoa - nhiệm kỳ 2013', '2013-12-25', '2013-12-25', '', 0),
(31, 1, 0, 'Ban Chấp Hành Trường ĐH Bách Khoa - nhiệm kỳ 2013', '2013-12-25', '2013-12-25', '', 0),
(32, 1, 7, 'Ban Chấp Hành Trường ĐH Bách Khoa - nhiệm kỳ 2013', '2013-12-25', NULL, '', 1),
(33, 1, 0, 'Ban Chấp Hành Trường ĐH Bách Khoa - nhiệm kỳ 2013', '2013-12-25', '2013-12-25', '', 0),
(34, 1, 0, 'Ban Chấp Hành Trường ĐH Bách Khoa - nhiệm kỳ 2013', '2013-12-25', '2013-12-25', '', 0),
(35, 1, 0, 'Ban Chấp Hành Trường ĐH Bách Khoa - nhiệm kỳ 2013', '2013-12-25', '2013-12-25', '', 0),
(36, 1, 0, 'Ban Chấp Hành Trường ĐH Bách Khoa - nhiệm kỳ 2013', '2013-12-25', '2013-12-25', '', 0),
(37, 1, 0, 'Ban Chấp Hành Trường ĐH Bách Khoa - nhiệm kỳ 2013', '2013-12-25', '2013-12-25', '', 0),
(38, 1, 0, 'Ban Chấp Hành Trường ĐH Bách Khoa - nhiệm kỳ 2013', '2013-12-25', '2013-12-25', '', 0),
(39, 1, 0, 'Ban Chấp Hành Trường ĐH Bách Khoa - nhiệm kỳ 2013', '2013-12-25', '2013-12-25', '', 0),
(40, 1, 7, 'Ban Chấp Hành Trường ĐH Bách Khoa - nhiệm kỳ 2013', '2013-12-25', '2013-12-25', '', 0),
(41, 1, 0, 'Ban Chấp Hành Trường ĐH Bách Khoa - nhiệm kỳ 2013', '2013-12-25', '2013-12-25', '', 0),
(42, 1, 0, 'Ban Chấp Hành Trường ĐH Bách Khoa - nhiệm kỳ 2013', '2013-12-25', '2013-12-25', '', 0),
(43, 1, 7, 'Ban Chấp Hành Trường ĐH Bách Khoa - nhiệm kỳ 2013', '2013-12-25', '2014-03-12', '', 0),
(44, 1, 0, 'Ban Chấp Hành Báo Tuổi Trẻ - nhiệm kỳ 2014', '2014-10-03', '2014-10-03', '', 0),
(45, 1, 0, 'Ban Chấp Hành Báo Tuổi Trẻ - nhiệm kỳ 2014', '2014-10-03', '2014-10-03', '', 0),
(46, 1, 1, 'Ban Chấp Hành Báo Tuổi Trẻ - nhiệm kỳ 2014', '2014-10-03', NULL, '', 1),
(47, 1, 0, '', '2014-12-03', '2014-12-03', '', 0),
(48, 1, 0, '', '2014-12-03', '2014-12-03', '', 0),
(49, 1, 0, '', '2014-12-03', '2014-12-03', '', 0),
(50, 1, 0, '', '2014-12-03', '2014-12-03', '', 0),
(51, 1, 0, '', '2014-12-03', '2014-12-03', '', 0),
(52, 1, 0, 'Ban Chấp Hành Báo Tuổi Trẻ - nhiệm kỳ 2014', '2014-12-03', '2014-10-03', '', 0),
(53, 1, 0, 'Ban Chấp Hành Báo Tuổi Trẻ - nhiệm kỳ 2014', '2014-10-03', '1970-01-01', '', 0),
(54, 1, 1, 'Ban Chấp Hành Báo Tuổi Trẻ - nhiệm kỳ NaN', '1970-01-01', '2014-01-01', '', 0),
(55, 1, 3, 'Ban Chấp Hành  - nhiệm kỳ 2016', '2016-03-10', NULL, '', 1);

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
  `Con_Song` bit(1) NOT NULL DEFAULT b'1',
  `Tham_Gia_CLBTT` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`Ma_Can_Bo`),
  KEY `FK_co_Chuc_Vu_Chinh` (`Ma_CV_Chinh`),
  KEY `FK_co_ĐVCT_Chinh` (`Ma_ĐVCT_Chinh`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `can_bo`
--

INSERT INTO `can_bo` (`Ma_Can_Bo`, `Ma_Quan_Ly`, `Ma_CV_Chinh`, `Ho_Ten_CB`, `Ngay_Gia_Nhap`, `Ngay_Tuyen_Dung`, `Ngay_Bien_Che`, `Ma_ĐVCT_Chinh`, `Ngay_Roi_Khoi`, `Con_Song`, `Tham_Gia_CLBTT`) VALUES
(1, NULL, 1, 'Nguyễn Trác Thức', '2006-10-10', NULL, NULL, 0, NULL, b'1', b'0'),
(2, NULL, 3, 'Lê Đức Thịnh', '2007-07-06', NULL, NULL, 0, NULL, b'1', b'0'),
(3, NULL, 5, 'Hoàng Anh Hùng', '2009-03-04', NULL, NULL, 0, NULL, b'1', b'0'),
(4, NULL, 5, 'Trần Đình Thi', '2011-02-03', NULL, NULL, 0, NULL, b'1', b'1'),
(12, '01-56-11', 1, 'Nguyễn Tuấn Anh', '2014-01-08', '2014-01-01', '2014-01-13', 0, NULL, b'1', b'0');

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
(0, NULL, 'Khác'),
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
  `Tu_Ngay` date NOT NULL DEFAULT '0000-00-00',
  `Đen_Ngay` date NOT NULL DEFAULT '0000-00-00',
  `Đia_Điem` varchar(254) DEFAULT NULL,
  `Noi_Dung` varchar(254) DEFAULT NULL,
  `Cap_Cu_Đi` varchar(254) DEFAULT NULL,
  `Kinh_Phi` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_CB`,`Tu_Ngay`,`Đen_Ngay`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cong_tac_nuoc_ngoai`
--

INSERT INTO `cong_tac_nuoc_ngoai` (`Ma_CB`, `Tu_Ngay`, `Đen_Ngay`, `Đia_Điem`, `Noi_Dung`, `Cap_Cu_Đi`, `Kinh_Phi`) VALUES
(1, '2014-02-04', '2014-03-04', 'Hoa Kỳ', 'giao lưu giảng viên đại học', 'cấp quốc gia', '- đài thọ: 50.000.000 VNĐ'),
(1, '2014-03-13', '2014-03-17', 'Nga', 'hội nghị nghiên cứu khoa học', 'cấp thành', '- đài thọ 30.000.000 VNĐ\r\n- tự túc ăn uống'),
(4, '2014-03-01', '2014-03-08', 'Nhật Bản', 'giao lưu văn hóa sinh viên Việt - Nhật', 'cấp thành phố', 'đài thọ hoàn toàn (khoảng 10 triệu VNĐ), 10 triệu cho phí phát sinh'),
(12, '2013-08-04', '2013-09-25', 'Singapo', 'ngắm cảnh', 'cấp nhà', '4.000.000 VNĐ'),
(12, '2013-10-01', '2014-03-20', 'Hàn Quốc', 'giao lưu các trường đại học châu Á', 'cấp trường', 'đài thọ hoàn toàn (cao nhất 20 triệu)'),
(12, '2014-02-05', '2014-02-13', 'Hồng Công', 'đi chơi', 'cấp nhà', 'tự túc 2.000.000 VNĐ');

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
  `Ma_DS_Khen_Thuong` int(10) unsigned NOT NULL,
  `Ma_Dien` smallint(6) DEFAULT NULL,
  `He_So_Thuong` float DEFAULT '1',
  PRIMARY KEY (`Ma_CB`,`Ma_DS_Khen_Thuong`),
  KEY `FK_danh_sach_khen_thuong_CB` (`Ma_DS_Khen_Thuong`),
  KEY `FK_thuoc_dien` (`Ma_Dien`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `khoi`
--

INSERT INTO `khoi` (`Ma_Khoi`, `Ten_Khoi`, `Mo_Ta`, `Ma_Khoi_Cap_Tren`) VALUES
(1, 'Thành Đoàn', NULL, NULL),
(2, 'Khối cơ quan chuyên trách Thành Đoàn', NULL, 1),
(3, 'Khối các đơn vị sự nghiệp - doanh nghiệp', NULL, 1),
(4, 'Cơ Sở Đoàn', NULL, 1),
(5, 'Cơ Quan Chính Đảng', NULL, NULL);

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
  `STT_Ky_Luat` int(11) NOT NULL,
  `Ngay_Quyet_Đinh` date DEFAULT NULL,
  `Ly_Do_Ky_Luat` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`STT_Ky_Luat`,`Ma_CB`),
  KEY `FK_ky_luat_cua_can_bo` (`Ma_CB`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `loai_hinh_ban`
--

INSERT INTO `loai_hinh_ban` (`Ma_Loai_Ban`, `Ten_Loai_Hinh_Ban`, `Mo_Ta`, `Ma_Kieu_Loai_Hinh`) VALUES
(1, 'Ban Chấp Hành', 'Ban chấp hình các cơ quan. Chú ý: Ban Thường Vụ là các Cán bộ giữ chức Thường Vụ tại Ban Chấp Hành cơ quan đó ', 1),
(2, 'Hội Đồng Thành Viên', 'Hội đồng thành viên, giành cho các cơ quan doanh nghiệp', 1),
(3, 'Phòng Kế Toán', NULL, 0),
(4, 'Phòng Hành Chính', NULL, 0),
(5, 'Ban Biên Tập', NULL, 0),
(6, 'Ban Giám Hiệu', 'đối với các cơ quan Trường học', 1),
(7, 'Ban Giám Đốc', NULL, 1),
(8, 'Phòng Kinh Doanh', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `ly_lich`
--

CREATE TABLE IF NOT EXISTS `ly_lich` (
  `Ma_CB` int(11) NOT NULL,
  `So_Hieu_CB` varchar(30) DEFAULT NULL COMMENT 'dùng để quản lý hồ sơ lý lịch',
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
  `Ngay_Tham_Gia_Cac_To_Chuc_Chinh_Tri_Xa_Hoi` date DEFAULT NULL,
  `Ngay_Nhap_Ngu` date DEFAULT NULL,
  `Ngay_Xuat_Ngu` date DEFAULT NULL,
  `Quan_Ham_Chuc_Vu_Cao_Nhat` varchar(254) DEFAULT NULL,
  `Trinh_Đo_Hoc_Van` varchar(254) DEFAULT '12/12',
  `Hoc_Ham_Hoc_Vi_Cao_Nhat` varchar(254) DEFAULT NULL,
  `Cap_Đo_CTLL` decimal(4,2) DEFAULT NULL,
  `Cap_Đo_TĐCM` decimal(4,2) DEFAULT NULL,
  `Chuyen_Nganh` varchar(62) DEFAULT NULL,
  `Ngoai_Ngu` varchar(254) DEFAULT NULL,
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
  `Nhom_Mau` varchar(254) DEFAULT NULL,
  `Loai_Thuong_Binh` varchar(254) DEFAULT NULL,
  `Gia_Đinh_Liet_Sy` tinyint(1) DEFAULT NULL,
  `Luong_Thu_Nhap_Nam` bigint(20) unsigned DEFAULT '0',
  `Nguon_Thu_Khac` varchar(254) DEFAULT NULL,
  `Loai_Nha_Đuoc_Cap` varchar(62) DEFAULT NULL,
  `Dien_Tich_Nha_Đuoc_Cap` int(10) unsigned DEFAULT '0',
  `Loai_Nha_Tu_Xay` varchar(62) DEFAULT NULL,
  `Dien_Tich_Nha_Tu_Xay` int(10) unsigned DEFAULT '0',
  `Dien_Tich_Đat_O_Tu_Mua` int(10) unsigned DEFAULT '0',
  `Dien_Tich_Đat_O_Đuoc_Cap` int(10) unsigned DEFAULT '0',
  `Dien_Tich_Đat_San_Xuat` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`Ma_CB`),
  KEY `FK_co_TĐ_HV` (`Cap_Đo_TĐCM`),
  KEY `FK_co_TĐ_LLCT` (`Cap_Đo_CTLL`),
  KEY `FK_co_Dan_Toc` (`Dan_Toc`),
  KEY `FK_co_Ton_Giao` (`Ton_Giao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ly_lich`
--

INSERT INTO `ly_lich` (`Ma_CB`, `So_Hieu_CB`, `Ho_Ten_Khai_Sinh`, `Ten_Goi_Khac`, `Gioi_Tinh`, `Cap_Uy_Hien_Tai`, `Cap_Uy_Kiem`, `Chuc_Danh`, `Phu_Cap_Chuc_Vu`, `Ngay_Sinh`, `Noi_Sinh`, `So_CMND`, `Ngay_Cap_CMND`, `Noi_Cap_CMND`, `Que_Quan`, `Noi_O_Hien_Nay`, `Dan_Toc`, `Ton_Giao`, `Đien_Thoai`, `Thanh_Phan_Gia_Đinh_Xuat_Than`, `Ngay_Tham_Gia_CM`, `Nghe_Nghiep_Truoc_Đo`, `Ngay_Đuoc_Tuyen_Dung`, `Co_Quan_Tuyen_Dung`, `Đia_Chi_Co_Quan_Tuyen_Dung`, `Ngay_Vao_Đang`, `Ngay_Chinh_Thuc`, `Ngay_Tham_Gia_Cac_To_Chuc_Chinh_Tri_Xa_Hoi`, `Ngay_Nhap_Ngu`, `Ngay_Xuat_Ngu`, `Quan_Ham_Chuc_Vu_Cao_Nhat`, `Trinh_Đo_Hoc_Van`, `Hoc_Ham_Hoc_Vi_Cao_Nhat`, `Cap_Đo_CTLL`, `Cap_Đo_TĐCM`, `Chuyen_Nganh`, `Ngoai_Ngu`, `Lam_Viec_Trong_Che_Đo_Cu`, `Co_Than_Nhan_Nuoc_Ngoai`, `Tham_Gia_Cac_To_Chuc_Nuoc_Ngoai`, `Cong_Tac_Chinh_Đang_Lam`, `Danh_Hieu_Đuoc_Phong`, `So_Truong_Cong_Tac`, `Cong_Viec_Lam_Lau_Nhat`, `Khen_Thuong`, `Ky_Luat`, `Tinh_Trang_Suc_Khoe`, `Chieu_Cao`, `Can_Nang`, `Nhom_Mau`, `Loai_Thuong_Binh`, `Gia_Đinh_Liet_Sy`, `Luong_Thu_Nhap_Nam`, `Nguon_Thu_Khac`, `Loai_Nha_Đuoc_Cap`, `Dien_Tich_Nha_Đuoc_Cap`, `Loai_Nha_Tu_Xay`, `Dien_Tich_Nha_Tu_Xay`, `Dien_Tich_Đat_O_Tu_Mua`, `Dien_Tich_Đat_O_Đuoc_Cap`, `Dien_Tich_Đat_San_Xuat`) VALUES
(1, 'NTA-4311', 'Nguyễn Trác Thức', 'Không', 0, 'Đảng bộ Trường ĐH CNTT', '', 'Đảng Ủy viên', 1.5, '1980-11-20', 'TP. Hồ Chí Minh', '352454537', '1995-12-20', 'TP. Hồ Chí Minh', 'TP. Hồ Chí Minh', 'Bình Chánh, TP. Hồ Chí Minh', 1, 0, '01234567890', 'Cán bộ', NULL, 'Giảng viên', '2013-12-02', 'Trường ĐH Khoa Học Tự Nhiên', 'Quận 1, TP. Hồ Chí Minh', '2005-12-02', '2006-12-02', '2008-01-01', NULL, NULL, NULL, '12/12', 'Phó Giáo Sư, Tiến sĩ', '3.00', '20.00', NULL, 'Anh, Pháp', 'Không', 'Không', 'Không', 'Giảng viên', 'Không', 'Tổ chức xây dựng Đoàn', 'Bí thư Đoàn trường', 'Bằng khen các cấp', 'Không', 'Tốt', 1.7, 80, 'O', 'Không', 0, 0, NULL, NULL, 0, NULL, 0, 0, 0, 0),
(2, 'NTA-1129', 'Lê Đức Thịnh', 'Không', 0, 'Bí thư chi bộ Sinh viên', 'Không', '3', NULL, '1989-01-01', 'Long An', '435676357', '2003-01-13', 'Long An', 'Long An', 'Quận Thủ Đức, TP. Hồ Chí Minh', 1, 0, '01234567890', 'Viên chức', NULL, 'Sinh viên', '2010-01-14', 'Trường ĐH Công nghệ Thông tin', 'Thủ Đức, TP. Hồ Chí Minh', '2005-01-21', '2006-01-21', NULL, NULL, NULL, NULL, '12/12', NULL, '2.00', '16.00', NULL, 'Anh', 'Không', 'Không', 'Không', 'Giảng viên', 'Không', 'Công tác xây dựng Đoàn', 'Phó bí thư Đoàn trường', 'Giấy khen các cấp', 'Không', 'Tốt', 1.7, 60, 'A', 'không', 0, 0, NULL, NULL, 0, NULL, 0, 0, 0, 0),
(3, 'NTA-1322', 'Hoàng Anh Hùng', 'Không', 0, 'Phó bí thư chi bộ', 'Không', NULL, NULL, '1923-02-03', 'Đồng Nai', '012345678', '2014-01-14', 'Đồng Nai', 'Thanh Hóa', 'Đồng Nai', 3, 0, '01234567890', 'Nông dân', NULL, 'Sinh viên', '2014-01-08', 'Trường ĐH Công nghệ Thông tin', 'Thủ Đức, TP. Hồ Chí Minh', '2014-01-08', '2014-01-29', NULL, NULL, NULL, NULL, '12/12', NULL, '1.00', '12.00', NULL, 'Anh, Nhật', 'Không', 'Không', 'Không', 'UV BTV', 'Không', 'Tuyên giáo', 'UV BTV', 'Giấy khen', 'Không', 'Tốt', 1.7, 65, 'O', 'Không', 0, 0, NULL, NULL, 0, NULL, 0, 0, 0, 0),
(4, 'NTA-1122', 'Trần Đình Thi', 'Không', 0, 'Không', 'Không', NULL, NULL, '1992-02-25', 'An Giang', '352039720', '2007-03-15', 'An Giang', 'An Giang', 'Dĩ An, Bình Dương', 2, 0, '01256745609', 'Viên chức', NULL, 'Sinh viên', '2014-01-21', 'Trường ĐH Công nghệ Thông tin', 'Thủ Đức, TP. Hồ Chí Minh', NULL, NULL, NULL, NULL, NULL, NULL, '12/12', NULL, '1.00', '12.00', NULL, 'Anh', 'Không', 'Không', 'Không', 'Chủ tịch Hội Sinh viên', 'Không', 'Phong trào', 'Chủ tịch Hội Sinh viên', 'Bằng khen, Giấy khen', 'Không', 'Tốt', 1.67, 55, 'A', 'Không', 0, 0, NULL, NULL, 0, NULL, 0, 0, 0, 0),
(12, 'NTA-1122', NULL, 'Tí Em', 0, 'Không', 'Không', '10', NULL, '1992-01-05', 'Đồng Tháp', '123456789', NULL, 'Tây Ninh', 'Đồng Tháp', 'TP. Hồ Chí Minh', 1, 2, '0125674569', 'Nông dân', NULL, 'Sinh viên', '2014-02-04', 'UBND tỉnh Tây Ninh', 'Tây Ninh', NULL, NULL, NULL, NULL, NULL, NULL, '12/12', 'Không', '1.00', '12.00', NULL, 'Anh', '(không làm)', '(không có)', '(không tham gia)', 'Ủy viên', 'không', 'CNTT', NULL, 'Cán bộ đoàn giỏi cấp trường', '(không)', 'bình thường', 1.66, NULL, NULL, '1/5', 1, 200000000, 'trồng cao su: 100 triệu VNĐ/năm', 'nhà lá', 50, 'Biệt thự', 100, 200, 0, 500);

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
(1, 'Hoàn thành xuất sắc chức trách, nhiệm vụ'),
(2, 'Hoàn thành tốt chức trách, nhiệm vụ'),
(3, 'Hoàn thành chức trách, nhiệm vụ'),
(4, 'Chưa hoàn thành chức trách, nhiệm vụ');

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
('01001', '', 'Chuyên Viên Cao Cấp'),
('01003', NULL, 'Chuyên Viên'),
('01004', '', 'Cán Sự');

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
  `So_Thu_Tu` int(11) NOT NULL,
  `Tu_Ngay` date DEFAULT NULL,
  `Đen_Ngay` date DEFAULT NULL,
  `Chuc_Danh` varchar(254) DEFAULT NULL,
  `Chuc_Vu` varchar(254) DEFAULT NULL,
  `Đon_Vi_Cong_Tac` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_CB`,`So_Thu_Tu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `qua_trinh_cong_tac`
--

INSERT INTO `qua_trinh_cong_tac` (`Ma_CB`, `So_Thu_Tu`, `Tu_Ngay`, `Đen_Ngay`, `Chuc_Danh`, `Chuc_Vu`, `Đon_Vi_Cong_Tac`) VALUES
(12, 1, '2014-01-01', '2014-01-03', 'không', 'Ủy Viên', 'ĐH CNTT');

-- --------------------------------------------------------

--
-- Table structure for table `qua_trinh_luong`
--

CREATE TABLE IF NOT EXISTS `qua_trinh_luong` (
  `Ma_CB` int(11) NOT NULL,
  `Thoi_Gian_Nang_Luong` date NOT NULL DEFAULT '0000-00-00',
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
(1, '2003-03-02', '01001', '5', 5.1, 0, 0, 0),
(1, '2009-02-02', '01001', '6', 6.1, 0, 0, 0),
(2, '2014-03-11', '01003', '4', 4.3, 0, 0, 0),
(12, '2013-03-11', '01001', '1', 1.1, 10, 1, 1300000),
(12, '2014-03-11', '01001', '4', 4.3, 0, 0, 0),
(12, '2014-03-12', '01004', '4', 4.9, 0, 0, 0),
(12, '2014-03-13', '01001', '', 0, 0, 0, 0);

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
('admin', 'Admin'),
('cadre', 'Cán Bộ'),
('manager', 'Quản lý'),
('organizer_cadre', 'Cán Bộ Ban Tổ Chức'),
('permanent_cadre', 'Thường Trực');

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
  `Ma_CB` int(11) NOT NULL,
  `Quan_He` varchar(254) NOT NULL,
  `Ho_Ten` varchar(254) DEFAULT NULL,
  `Nam_Sinh` date DEFAULT NULL,
  `Que_Quan` varchar(254) DEFAULT NULL,
  `Nghe_Nghiep` varchar(254) DEFAULT NULL,
  `Chuc_Danh` varchar(254) DEFAULT NULL,
  `Chuc_Vu` varchar(254) DEFAULT NULL,
  `Đon_Vi_Cong_Tac` varchar(254) DEFAULT NULL,
  `Hoc_Tap` varchar(254) DEFAULT NULL,
  `Noi_O` varchar(254) DEFAULT NULL,
  `Thanh_Vien_Cac_To_Chuc` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_CB`,`Quan_He`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `thanh_vien_gia_đinh`
--

INSERT INTO `thanh_vien_gia_đinh` (`Ma_CB`, `Quan_He`, `Ho_Ten`, `Nam_Sinh`, `Que_Quan`, `Nghe_Nghiep`, `Chuc_Danh`, `Chuc_Vu`, `Đon_Vi_Cong_Tac`, `Hoc_Tap`, `Noi_O`, `Thanh_Vien_Cac_To_Chuc`) VALUES
(12, 'Cha', 'Nguuyễn Anh Tuấn', '1988-02-03', 'Tây Ninh', 'Thương Gia', 'không', 'không', 'nhà vợ\r\n', 'cao học', 'Tây Ninh', 'không');

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
  `La_Cong_Tac_Chinh` tinyint(4) NOT NULL DEFAULT '0',
  `STT_To` tinyint(4) DEFAULT NULL,
  `Ma_Ban_Truoc_Đo` int(11) DEFAULT NULL,
  `Ngay_GN_Ban_Truoc_Đo` date DEFAULT NULL,
  PRIMARY KEY (`Ma_CB`,`Ma_Ban`,`Ngay_Gia_Nhap`),
  KEY `FK_Ban_Đi` (`Ma_CB`,`Ma_Ban_Truoc_Đo`,`Ngay_GN_Ban_Truoc_Đo`),
  KEY `FK_cv_tai_ban` (`Ma_CV`),
  KEY `FK_to_cong_tac_cua_CB_tai_ban` (`Ma_Ban`,`STT_To`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `thong_tin_tham_gia_ban`
--

INSERT INTO `thong_tin_tham_gia_ban` (`Ma_CB`, `Ma_Ban`, `Ngay_Gia_Nhap`, `Ngay_Roi_Khoi`, `Ly_Do_Chuyen_Đen`, `Ma_CV`, `La_Cong_Tac_Chinh`, `STT_To`, `Ma_Ban_Truoc_Đo`, `Ngay_GN_Ban_Truoc_Đo`) VALUES
(1, 25, '1970-01-01', NULL, NULL, 2, 0, NULL, NULL, NULL),
(1, 26, '2014-01-07', '2014-01-07', NULL, 1, 0, NULL, NULL, NULL),
(1, 27, '1970-01-01', NULL, NULL, 2, 0, NULL, NULL, NULL),
(1, 29, '1970-01-01', NULL, NULL, 1, 0, NULL, NULL, NULL),
(1, 49, '1970-01-01', NULL, NULL, NULL, 0, NULL, NULL, NULL),
(1, 51, '2014-12-03', NULL, NULL, 1, 0, NULL, NULL, NULL),
(1, 52, '2014-12-03', NULL, NULL, 1, 0, NULL, NULL, NULL),
(1, 53, '2014-10-03', NULL, NULL, 1, 0, NULL, NULL, NULL),
(1, 54, '1970-01-01', NULL, NULL, 1, 0, NULL, NULL, NULL),
(1, 55, '2016-03-10', NULL, NULL, 0, 0, NULL, NULL, NULL),
(2, 25, '1970-01-01', NULL, NULL, 3, 0, NULL, NULL, NULL),
(2, 26, '1970-01-01', NULL, NULL, 3, 0, NULL, NULL, NULL),
(2, 27, '1970-01-01', NULL, NULL, 3, 0, NULL, NULL, NULL),
(2, 52, '2014-12-03', NULL, NULL, 1, 0, NULL, NULL, NULL),
(2, 54, '1970-01-01', NULL, NULL, 1, 0, NULL, NULL, NULL),
(3, 27, '1970-01-01', NULL, NULL, 5, 0, NULL, NULL, NULL),
(4, 28, '1970-01-01', NULL, NULL, 3, 0, NULL, NULL, NULL);

--
-- Triggers `thong_tin_tham_gia_ban`
--
DROP TRIGGER IF EXISTS `tg_ngayroikhoi`;
DELIMITER //
CREATE TRIGGER `tg_ngayroikhoi` BEFORE INSERT ON `thong_tin_tham_gia_ban`
 FOR EACH ROW IF(NEW.Ngay_Roi_Khoi<NEW.Ngay_Gia_Nhap) THEN

	SET NEW.Ngay_Roi_Khoi = null;
END IF
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ton_giao`
--

CREATE TABLE IF NOT EXISTS `ton_giao` (
  `Ma_Ton_Giao` smallint(6) NOT NULL AUTO_INCREMENT,
  `Ten_Ton_Giao` varchar(63) DEFAULT NULL,
  PRIMARY KEY (`Ma_Ton_Giao`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=7 ;

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
(6, 'Ấn Độ Giáo');

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
('12.00', '(chưa có bằng chuyên môn)', ''),
('14.00', 'Trung cấp', 'TC'),
('14.50', 'Trung Cấp Chuyên Nghiệp', 'TCCN'),
('15.00', 'Cao đẳng', 'CĐ'),
('16.00', 'Đại học', 'ĐH'),
('18.00', 'Thạc Sĩ', 'ThS'),
('20.00', 'Tiến Sĩ', 'TS'),
('22.00', 'Tiến Sĩ Khoa Học', 'TSKH');

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
  `Identifier_Info` varchar(254) DEFAULT NULL,
  `Status_Code` tinyint(4) DEFAULT '1',
  `Role_Name` varchar(32) DEFAULT NULL,
  `Password_Key` varchar(254) NOT NULL DEFAULT 'dk',
  PRIMARY KEY (`UserID`),
  UNIQUE KEY `Username` (`Username`),
  KEY `FK_have_role` (`Role_Name`),
  KEY `FK_have_status` (`Status_Code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Username`, `Password`, `Identifier_Info`, `Status_Code`, `Role_Name`, `Password_Key`) VALUES
(1, 'admin', '66579284dd25f62cde52b036108c4bd6', '1', 1, 'admin', 'dk'),
(10, 'manager', '5d03757598faa106b9d0d6c2a08eea43', '3', 1, 'manager', '28/12/2013 09:12:21:1221'),
(15, 'dangkhoa', '337ab2818447d7027e59d462317c3105', '', 1, 'manager', '31/12/2013 10:12:16:1216'),
(16, 'phobithu', '8880d22d7f12eeabe27802c41532315e', '2', 1, 'admin', '31/12/2013 10:12:03:1203'),
(17, 'dinhthi', '1000c73b2aaf80ac21bda4784150e1a7', '4', 1, 'cadre', '12/03/2014 10:03:34:0334'),
(18, 'nguyentuananh', 'a73b90b4555d62137a448d0a66743116', '12', 1, 'permanent_cadre', '31/12/2013 10:12:47:1247'),
(19, 'xuanthu', 'ec0619b0129a36e676e70a5fb117e58f', NULL, 1, 'organizer_cadre', '13/01/2014 12:01:44:0144'),
(20, 'hoanganhhung', '0031755bcdd0a3a7a89fad4e30a134b0', '3', 1, 'cadre', '12/03/2014 07:03:37:0337');

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
-- Table structure for table `đac_điem_lich_su`
--

CREATE TABLE IF NOT EXISTS `đac_điem_lich_su` (
  `Ma_CB` int(11) NOT NULL,
  `So_Thu_Tu` int(11) NOT NULL,
  `Su_Kien` varchar(254) DEFAULT NULL,
  `Tu_Thoi_Điem` date DEFAULT NULL,
  `Đen_Thoi_Điem` date DEFAULT NULL,
  `Nguoi_Nhan_Khai_Bao` varchar(254) DEFAULT NULL,
  `Noi_Dung` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_CB`,`So_Thu_Tu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `đac_điem_lich_su`
--

INSERT INTO `đac_điem_lich_su` (`Ma_CB`, `So_Thu_Tu`, `Su_Kien`, `Tu_Thoi_Điem`, `Đen_Thoi_Điem`, `Nguoi_Nhan_Khai_Bao`, `Noi_Dung`) VALUES
(12, 1, 'học tại ĐH CNTT', '2010-09-09', '2015-03-03', 'Dương Anh Đức', 'nhận bằng tốt nghiệp');

-- --------------------------------------------------------

--
-- Table structure for table `đanh_gia_can_bo`
--

CREATE TABLE IF NOT EXISTS `đanh_gia_can_bo` (
  `Ma_CB_Tu_Đanh_Gia` int(11) NOT NULL,
  `Ngay_Đanh_Gia` date NOT NULL,
  `Noi_Dung_Tu_Đanh_Gia` text,
  `Ma_MĐHT_Tu_Đanh_Gia` tinyint(4) DEFAULT NULL,
  `Ma_ĐV_Muon_Đen` smallint(5) unsigned DEFAULT NULL,
  `Ma_Ban_Muon_Đen` int(11) DEFAULT NULL,
  `Thoi_Gian_Muon_Chuyen` date DEFAULT NULL,
  `Nguyen_Vong_Đao_Tao` text,
  `Ma_CB_Đanh_Gia` int(11) DEFAULT NULL,
  `Noi_Dung_Đanh_Gia` text,
  `Ma_MĐHT` tinyint(4) DEFAULT NULL,
  `Ma_CHPT` tinyint(4) DEFAULT NULL,
  `Đinh_Huong` text,
  PRIMARY KEY (`Ma_CB_Tu_Đanh_Gia`,`Ngay_Đanh_Gia`),
  KEY `FK_Ban_muon_đen` (`Ma_Ban_Muon_Đen`),
  KEY `FK_Can_Bo_đanh_gia` (`Ma_CB_Đanh_Gia`),
  KEY `FK_chieu_huong_phat_trien_cua_CB` (`Ma_MĐHT`),
  KEY `FK_chieu_huong_phat_trien_cua_CB_tu_đanh_gia` (`Ma_CHPT`),
  KEY `FK_muc_đo_hoan_thanh_cua_CB` (`Ma_MĐHT_Tu_Đanh_Gia`),
  KEY `FK_ĐV_MuonDen` (`Ma_ĐV_Muon_Đen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `đanh_gia_can_bo`
--

INSERT INTO `đanh_gia_can_bo` (`Ma_CB_Tu_Đanh_Gia`, `Ngay_Đanh_Gia`, `Noi_Dung_Tu_Đanh_Gia`, `Ma_MĐHT_Tu_Đanh_Gia`, `Ma_ĐV_Muon_Đen`, `Ma_Ban_Muon_Đen`, `Thoi_Gian_Muon_Chuyen`, `Nguyen_Vong_Đao_Tao`, `Ma_CB_Đanh_Gia`, `Noi_Dung_Đanh_Gia`, `Ma_MĐHT`, `Ma_CHPT`, `Đinh_Huong`) VALUES
(2, '1970-01-01', 'a. Ưu điểm:.....\r\n                ', 4, NULL, NULL, '1970-01-01', '+ Chuyên môn nghiệp vụ:......\r\n                ', NULL, '1. Mặt mạnh:.....\r\n                ', 2, 3, '+ Quy hoạch:......\r\n                '),
(2, '2014-03-14', 'a. Ưu điểm:.....\r\n                ', 3, NULL, NULL, '1970-01-01', '+ Chuyên môn nghiệp vụ:......\r\n                ', NULL, '1. Mặt mạnh:.....\r\n                ', 3, 2, '+ Quy hoạch:......\r\n                '),
(3, '2014-03-14', 'a. Ưu điểm:.....\r\n                ', 2, NULL, NULL, '2014-03-14', '+ Chuyên môn nghiệp vụ:......\r\n                ', NULL, '1. Mặt mạnh:.....\r\n                ', 3, 2, '+ Quy hoạch:......\r\n                '),
(4, '2014-03-05', '', 1, 1, 28, '0000-00-00', '', 1, '', 1, 1, ''),
(12, '2014-02-11', 'tốt', 1, 1, 28, '2014-03-05', 'fdsa', 1, '1', 1, 1, 'dfsf'),
(12, '2014-03-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, '2014-03-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, '2014-03-14', 'Ưu điểm: Tốt', 1, 1, 28, '2014-03-22', 'đào tạo cao cấp chính trị', 1, 'tương đối tốt', 1, 1, 'cở sở đoàn');

-- --------------------------------------------------------

--
-- Table structure for table `đao_tao_boi_duong`
--

CREATE TABLE IF NOT EXISTS `đao_tao_boi_duong` (
  `Ma_CB` int(11) NOT NULL,
  `So_Thu_Tu` int(11) NOT NULL,
  `Ten_Truong` varchar(254) DEFAULT NULL,
  `Nganh_Hoc` varchar(254) DEFAULT NULL,
  `Thoi_Gian_Hoc` date DEFAULT NULL,
  `TG_Ket_Thuc` date DEFAULT NULL,
  `Hinh_Thuc_Hoc` varchar(254) DEFAULT NULL,
  `Van_Bang_Chung_Chi` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_CB`,`So_Thu_Tu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `đao_tao_boi_duong`
--

INSERT INTO `đao_tao_boi_duong` (`Ma_CB`, `So_Thu_Tu`, `Ten_Truong`, `Nganh_Hoc`, `Thoi_Gian_Hoc`, `TG_Ket_Thuc`, `Hinh_Thuc_Hoc`, `Van_Bang_Chung_Chi`) VALUES
(1, 1, 'Trường Đoàn Lý Tự Trọng', 'Trung Cấp Chính Trị', '2002-01-16', '2002-03-16', 'Chính quy', 'Trung Cấp Chính Trị'),
(1, 2, 'Trường Đoàn Lý Tự Trọng', 'Cao cấp chính trị', '2005-04-04', '2005-09-04', 'chính quy', 'Cao cấp chính trị'),
(12, 1, 'Đại học Chính Trị', 'Sơ Cấp Chính Trị', '2013-03-03', '2003-04-03', 'bổ túc', 'Sơ Cấp Chính Trị');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `đon_vi`
--

INSERT INTO `đon_vi` (`Ma_ĐV`, `Ky_Hieu_ĐV`, `Ten_Đon_Vi`, `Ma_Khoi`, `Ma_Truong_ĐV`, `Ma_Ban_Chap_Hanh`, `Ngay_Thanh_Lap`, `Chuc_Nang_ĐV`, `Đia_Chi`, `Email`, `So_Đien_Thoai`, `Mo_Ta`, `Trang_Thai`) VALUES
(0, 'UEL', 'Trường Đại Học Kinh Tế - Luật', 4, NULL, 28, '2010-01-21', NULL, '', '', '', '<p>ĐH Kinh Tế - Luật&nbsp;<br />\r\n&nbsp;</p>\r\n', 1),
(1, 'BTT1', 'Báo Tuổi Trẻ', NULL, NULL, 54, '2014-06-03', 'thông tin liên lạc', '12 Nguyễn Thị Minh Khai, Phường 4, Quận 3, TP.HCM', 'toasoan@baotuoitre.vn', '+8423253543', '<p>M&ocirc; tả cho đơn vị B&aacute;o Tuổi trẻ</p>\r\n', 1),
(2, 'CQCTTĐ', 'Cơ Quan Chuyên Trách Thành Đoàn', 2, NULL, NULL, NULL, NULL, NULL, NULL, '', 'Cơ Quan Chuyên Trách Thành Đoàn quản lý các vấn đề thành đoàn TP HCM', 1),
(3, 'HDBC', 'Huyện Đoàn Bình Chánh', 4, NULL, 43, '1999-01-10', NULL, '', '', '', '<p>- Chức năng nhiệm vụ</p>\r\n', 1),
(4, 'HDCC', 'Huyện Đoàn Củ Chi', 1, NULL, NULL, '1970-01-01', NULL, NULL, NULL, '', 'Mô tả huyện Đoàn Củ Chi', 1),
(5, 'HDCG', 'Huyện Đoàn Cần Giờ', 4, NULL, 46, '1994-01-01', NULL, '', '', '', '<p>M&ocirc; tả huyện Đo&agrave;n Cần Giờ</p>\r\n', 1),
(6, 'UIT', 'Trường Đại Học Công Nghệ Thông Tin', 4, 3, 29, '2006-08-06', NULL, 'Linh Chung', 'admin@uit.edu.vn', '(08) 372 52002', '<p>M&ocirc; tả cho trường ĐH C&ocirc;ng nghệ Th&ocirc;ng tin - Đại học Quốc Gia TPHCM:</p>\r\n\r\n<p>- Chức năng, nhiệm vụ:</p>\r\n', 1),
(7, 'UT', 'Trường ĐH Bách Khoa', 4, NULL, 32, '2014-01-01', NULL, NULL, NULL, '', '<p><strong>M&ocirc; tả cho trường ĐH B&aacute;ch Khoa</strong></p>\r\n', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ban`
--
ALTER TABLE `ban`
  ADD CONSTRAINT `FK_thuoc_Don_Vi` FOREIGN KEY (`Ma_Đon_Vi`) REFERENCES `đon_vi` (`Ma_ĐV`),
  ADD CONSTRAINT `FK_loai_cua_ban` FOREIGN KEY (`Ma_Loai_Ban`) REFERENCES `loai_hinh_ban` (`Ma_Loai_Ban`);

--
-- Constraints for table `can_bo`
--
ALTER TABLE `can_bo`
  ADD CONSTRAINT `FK_thuoc_DonViChinh` FOREIGN KEY (`Ma_ĐVCT_Chinh`) REFERENCES `đon_vi` (`Ma_ĐV`),
  ADD CONSTRAINT `FK_co_Chuc_Vu_Chinh` FOREIGN KEY (`Ma_CV_Chinh`) REFERENCES `chuc_vu` (`Ma_Chuc_Vu`);

--
-- Constraints for table `chuc_vu`
--
ALTER TABLE `chuc_vu`
  ADD CONSTRAINT `FK_cap_đo_cua_CV` FOREIGN KEY (`Ma_Cap`) REFERENCES `cap_chuc_vu` (`Ma_Cap`);

--
-- Constraints for table `controller`
--
ALTER TABLE `controller`
  ADD CONSTRAINT `FK_of_module` FOREIGN KEY (`Module_Name`) REFERENCES `module` (`Module_Name`);

--
-- Constraints for table `hop_đong_cong_tac`
--
ALTER TABLE `hop_đong_cong_tac`
  ADD CONSTRAINT `FK_Ban_Hop_Đong` FOREIGN KEY (`Ma_Ban`) REFERENCES `ban` (`Ma_Ban`),
  ADD CONSTRAINT `FK_CTV_Hop_Đong` FOREIGN KEY (`Ma_CTV`) REFERENCES `cong_tac_vien` (`Ma_CTV`);

--
-- Constraints for table `khen_thuong`
--
ALTER TABLE `khen_thuong`
  ADD CONSTRAINT `FK_CB_đuoc_khen_thuong` FOREIGN KEY (`Ma_CB`) REFERENCES `can_bo` (`Ma_Can_Bo`),
  ADD CONSTRAINT `FK_danh_sach_khen_thuong_CB` FOREIGN KEY (`Ma_DS_Khen_Thuong`) REFERENCES `kq_xet_thi_đua` (`Ma_DS_Khen_Thuong`),
  ADD CONSTRAINT `FK_thuoc_dien` FOREIGN KEY (`Ma_Dien`) REFERENCES `dien_khen_thuong` (`Ma_Dien`);

--
-- Constraints for table `khoi`
--
ALTER TABLE `khoi`
  ADD CONSTRAINT `FK_khoi_cap_tren_truc_thuoc` FOREIGN KEY (`Ma_Khoi_Cap_Tren`) REFERENCES `khoi` (`Ma_Khoi`);

--
-- Constraints for table `ky_luat`
--
ALTER TABLE `ky_luat`
  ADD CONSTRAINT `FK_ky_luat_cua_can_bo` FOREIGN KEY (`Ma_CB`) REFERENCES `can_bo` (`Ma_Can_Bo`);

--
-- Constraints for table `loai_hinh_ban`
--
ALTER TABLE `loai_hinh_ban`
  ADD CONSTRAINT `FK_co_KieuLHB` FOREIGN KEY (`Ma_Kieu_Loai_Hinh`) REFERENCES `kieu_loai_hinh_ban` (`Ma_KLHB`);

--
-- Constraints for table `ly_lich`
--
ALTER TABLE `ly_lich`
  ADD CONSTRAINT `FK_co_Dan_Toc` FOREIGN KEY (`Dan_Toc`) REFERENCES `dan_toc` (`Ma_Dan_Toc`),
  ADD CONSTRAINT `FK_co_Ly_Lich` FOREIGN KEY (`Ma_CB`) REFERENCES `can_bo` (`Ma_Can_Bo`),
  ADD CONSTRAINT `FK_co_Ton_Giao` FOREIGN KEY (`Ton_Giao`) REFERENCES `ton_giao` (`Ma_Ton_Giao`),
  ADD CONSTRAINT `FK_co_TĐ_HV` FOREIGN KEY (`Cap_Đo_TĐCM`) REFERENCES `trinh_đo_chuyen_mon` (`Cap_Đo_TĐCM`),
  ADD CONSTRAINT `FK_co_TĐ_LLCT` FOREIGN KEY (`Cap_Đo_CTLL`) REFERENCES `trinh_đo_ly_luan_chinh_tri` (`Cap_Đo_LLCT`);

--
-- Constraints for table `muc_sua_đoi`
--
ALTER TABLE `muc_sua_đoi`
  ADD CONSTRAINT `FK_muc_thay_đoi_cua_yeu_cau` FOREIGN KEY (`Ma_Yeu_Cau`) REFERENCES `yeu_cau_thay_đoi_tt_cb` (`Ma_Yeu_Cau`);

--
-- Constraints for table `muc_thuong_theo_dien`
--
ALTER TABLE `muc_thuong_theo_dien`
  ADD CONSTRAINT `FK_dien_nhan_thuong` FOREIGN KEY (`Ma_Dien`) REFERENCES `dien_khen_thuong` (`Ma_Dien`),
  ADD CONSTRAINT `FK_kq_xet` FOREIGN KEY (`Ma_DS_Khen_Thuong`) REFERENCES `kq_xet_thi_đua` (`Ma_DS_Khen_Thuong`);

--
-- Constraints for table `privilege`
--
ALTER TABLE `privilege`
  ADD CONSTRAINT `FK_of_controller` FOREIGN KEY (`Controller_Name`) REFERENCES `controller` (`Controller_Name`);

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `FK_of_user` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);

--
-- Constraints for table `qua_trinh_cong_tac`
--
ALTER TABLE `qua_trinh_cong_tac`
  ADD CONSTRAINT `FK_QTCT_cua_can_bo` FOREIGN KEY (`Ma_CB`) REFERENCES `ly_lich` (`Ma_CB`);

--
-- Constraints for table `qua_trinh_luong`
--
ALTER TABLE `qua_trinh_luong`
  ADD CONSTRAINT `FK_co_Ngach` FOREIGN KEY (`Ma_So_Ngach`) REFERENCES `ngach_luong` (`Ma_So_Ngach`),
  ADD CONSTRAINT `FK_luong_cua_can_bo` FOREIGN KEY (`Ma_CB`) REFERENCES `can_bo` (`Ma_Can_Bo`);

--
-- Constraints for table `role_privilege_relation`
--
ALTER TABLE `role_privilege_relation`
  ADD CONSTRAINT `FK_for_role` FOREIGN KEY (`Privilege_Name`) REFERENCES `privilege` (`Privilege_Name`),
  ADD CONSTRAINT `FK_have_privilege` FOREIGN KEY (`Role_Name`) REFERENCES `role` (`Role_Name`);

--
-- Constraints for table `thanh_vien_gia_đinh`
--
ALTER TABLE `thanh_vien_gia_đinh`
  ADD CONSTRAINT `FK_cua_can_bo` FOREIGN KEY (`Ma_CB`) REFERENCES `ly_lich` (`Ma_CB`);

--
-- Constraints for table `thong_tin_tham_gia_ban`
--
ALTER TABLE `thong_tin_tham_gia_ban`
  ADD CONSTRAINT `FK_Ban_Tham_Gia` FOREIGN KEY (`Ma_Ban`) REFERENCES `ban` (`Ma_Ban`),
  ADD CONSTRAINT `FK_Ban_Đi` FOREIGN KEY (`Ma_CB`, `Ma_Ban_Truoc_Đo`, `Ngay_GN_Ban_Truoc_Đo`) REFERENCES `thong_tin_tham_gia_ban` (`Ma_CB`, `Ma_Ban`, `Ngay_Gia_Nhap`),
  ADD CONSTRAINT `FK_Can_Bo_Tai_Ban` FOREIGN KEY (`Ma_CB`) REFERENCES `can_bo` (`Ma_Can_Bo`),
  ADD CONSTRAINT `FK_cv_tai_ban` FOREIGN KEY (`Ma_CV`) REFERENCES `chuc_vu` (`Ma_Chuc_Vu`),
  ADD CONSTRAINT `FK_to_cong_tac_cua_CB_tai_ban` FOREIGN KEY (`Ma_Ban`, `STT_To`) REFERENCES `to_cong_tac` (`Ma_Ban`, `STT_To`);

--
-- Constraints for table `to_cong_tac`
--
ALTER TABLE `to_cong_tac`
  ADD CONSTRAINT `FK_ban_truc_thuoc` FOREIGN KEY (`Ma_Ban`) REFERENCES `ban` (`Ma_Ban`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_have_role` FOREIGN KEY (`Role_Name`) REFERENCES `role` (`Role_Name`),
  ADD CONSTRAINT `FK_have_status` FOREIGN KEY (`Status_Code`) REFERENCES `status` (`Status_Code`);

--
-- Constraints for table `yeu_cau_thay_đoi_tt_cb`
--
ALTER TABLE `yeu_cau_thay_đoi_tt_cb`
  ADD CONSTRAINT `FK_Anh_Huong_Can_bo` FOREIGN KEY (`Ma_CB_Anh_Huong`) REFERENCES `can_bo` (`Ma_Can_Bo`),
  ADD CONSTRAINT `FK_đuoc_can_bo_yeu_cau` FOREIGN KEY (`Ma_CB_Yeu_Cau`) REFERENCES `can_bo` (`Ma_Can_Bo`);

--
-- Constraints for table `đac_điem_lich_su`
--
ALTER TABLE `đac_điem_lich_su`
  ADD CONSTRAINT `FK_cua_Can_Bo_1` FOREIGN KEY (`Ma_CB`) REFERENCES `ly_lich` (`Ma_CB`);

--
-- Constraints for table `đanh_gia_can_bo`
--
ALTER TABLE `đanh_gia_can_bo`
  ADD CONSTRAINT `FK_Ban_muon_đen` FOREIGN KEY (`Ma_Ban_Muon_Đen`) REFERENCES `ban` (`Ma_Ban`),
  ADD CONSTRAINT `FK_Can_Bo_đanh_gia` FOREIGN KEY (`Ma_CB_Đanh_Gia`) REFERENCES `can_bo` (`Ma_Can_Bo`),
  ADD CONSTRAINT `FK_chieu_huong_phat_trien_cua_CB` FOREIGN KEY (`Ma_MĐHT`) REFERENCES `muc_đo_hoan_thanh` (`Ma_MĐHT`),
  ADD CONSTRAINT `FK_chieu_huong_phat_trien_cua_CB_tu_đanh_gia` FOREIGN KEY (`Ma_CHPT`) REFERENCES `chieu_huong_phat_trien` (`Ma_CHPT`),
  ADD CONSTRAINT `FK_co_KQKT` FOREIGN KEY (`Ma_CB_Tu_Đanh_Gia`) REFERENCES `can_bo` (`Ma_Can_Bo`),
  ADD CONSTRAINT `FK_donvi_muonden` FOREIGN KEY (`Ma_ĐV_Muon_Đen`) REFERENCES `đon_vi` (`Ma_ĐV`),
  ADD CONSTRAINT `FK_muc_đo_hoan_thanh_cua_CB` FOREIGN KEY (`Ma_MĐHT_Tu_Đanh_Gia`) REFERENCES `muc_đo_hoan_thanh` (`Ma_MĐHT`);

--
-- Constraints for table `đao_tao_boi_duong`
--
ALTER TABLE `đao_tao_boi_duong`
  ADD CONSTRAINT `FK_ĐTBD_cua_Can_Bo` FOREIGN KEY (`Ma_CB`) REFERENCES `ly_lich` (`Ma_CB`);

--
-- Constraints for table `đon_vi`
--
ALTER TABLE `đon_vi`
  ADD CONSTRAINT `FK_Co_Ban_Chap_Hanh` FOREIGN KEY (`Ma_Ban_Chap_Hanh`) REFERENCES `ban` (`Ma_Ban`),
  ADD CONSTRAINT `FK_co_Truong_Đon_Vi` FOREIGN KEY (`Ma_Truong_ĐV`) REFERENCES `can_bo` (`Ma_Can_Bo`),
  ADD CONSTRAINT `FK_khoi_truc_thuoc` FOREIGN KEY (`Ma_Khoi`) REFERENCES `khoi` (`Ma_Khoi`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
