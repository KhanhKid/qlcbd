-- phpMyAdmin SQL Dump
-- version 4.1.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 01, 2014 at 12:47 AM
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
select can_bo.Ma_CB, Ho_Ten_CB, DATE_FORMAT(Ngay_Sinh,'%d/%m/%Y') AS Ngay_Sinh, chuc_vu.Ten_Chuc_Vu
                from can_bo left join ly_lich on( can_bo.Ma_CB = ly_lich.Ma_CB)
                            left join chuc_vu on (can_bo.Ma_CV_Chinh = chuc_vu.Ma_Chuc_Vu)$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ban`
--

CREATE TABLE IF NOT EXISTS `ban` (
  `Ma_Ban` int(11) NOT NULL AUTO_INCREMENT,
  `Ma_Loai_Ban` tinyint(4) DEFAULT NULL,
  `Ma_Đon_Vi` varchar(32) DEFAULT NULL,
  `Ten_Ban` varchar(254) DEFAULT NULL,
  `Ngay_Thanh_Lap` date DEFAULT NULL,
  `Ngay_Man_Nhiem` date DEFAULT NULL,
  `Mo_Ta` varchar(254) DEFAULT NULL,
  `Trang_Thai` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`Ma_Ban`),
  KEY `FK_loai_cua_ban` (`Ma_Loai_Ban`),
  KEY `FK_truc_thuoc` (`Ma_Đon_Vi`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- Dumping data for table `ban`
--

INSERT INTO `ban` (`Ma_Ban`, `Ma_Loai_Ban`, `Ma_Đon_Vi`, `Ten_Ban`, `Ngay_Thanh_Lap`, `Ngay_Man_Nhiem`, `Mo_Ta`, `Trang_Thai`) VALUES
(25, 1, NULL, 'Ban Chấp Hành', '2013-12-29', NULL, '', 1),
(26, 1, 'BTT', 'Ban Chấp Hành', '2014-01-06', NULL, '', 1),
(27, 1, 'UIT', 'Ban Chấp Hành trường ĐH Công nghệ Thông tin', '2014-01-01', '2014-01-07', '<p>Mô tả cho BCH trường ĐH Công nghệ Thông tin</p>\r\n', 0),
(28, 1, 'UEL', 'Ban Chấp Hành trường ĐH Kinh Tế - Luật', '2014-01-02', NULL, '<p>Mô tả cho BCH trường ĐH Kinh Tế - Luật</p>\r\n', 1),
(29, 1, 'UIT', 'Ban Chấp Hành trường ĐH Công nghệ Thông tin', '2014-01-07', NULL, '<p>12345</p>\r\n', 1);

-- --------------------------------------------------------

--
-- Table structure for table `can_bo`
--

CREATE TABLE IF NOT EXISTS `can_bo` (
  `Ma_CB` int(11) NOT NULL AUTO_INCREMENT,
  `Ma_CV_Chinh` smallint(5) unsigned DEFAULT NULL,
  `Ho_Ten_CB` varchar(254) DEFAULT '(chưa biết)',
  `Ngay_Gia_Nhap` date DEFAULT NULL,
  `Ngay_Tuyen_Dung` date DEFAULT NULL,
  `Ngay_Bien_Che` date DEFAULT NULL,
  `Ma_ĐVCT_Chinh` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`Ma_CB`),
  KEY `FK_co_Chuc_Vu_Chinh` (`Ma_CV_Chinh`),
  KEY `FK_co_ĐVCT_Chinh` (`Ma_ĐVCT_Chinh`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `can_bo`
--

INSERT INTO `can_bo` (`Ma_CB`, `Ma_CV_Chinh`, `Ho_Ten_CB`, `Ngay_Gia_Nhap`, `Ngay_Tuyen_Dung`, `Ngay_Bien_Che`, `Ma_ĐVCT_Chinh`) VALUES
(1, 1, 'Nguyễn Trác Thức', '2006-10-10', NULL, NULL, 'UIT'),
(2, 3, 'Lê Đức Thịnh', NULL, NULL, NULL, 'UIT'),
(3, 5, 'Hoàng Anh Hùng', NULL, NULL, NULL, 'BTT'),
(4, 5, 'Trần Đình Thi', NULL, NULL, NULL, 'CQCTTĐ'),
(12, 1, 'Nguyễn Tuấn Anh', '2014-01-08', '2014-01-01', '2014-01-13', 'HDBC');

-- --------------------------------------------------------

--
-- Table structure for table `cap_chuc_vu`
--

CREATE TABLE IF NOT EXISTS `cap_chuc_vu` (
  `Ma_Cap` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Ten_Cap_CV` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_Cap`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `cap_chuc_vu`
--

INSERT INTO `cap_chuc_vu` (`Ma_Cap`, `Ten_Cap_CV`) VALUES
(1, 'Trưởng Đơn Vị'),
(2, 'Phó Đơn Vị'),
(3, 'Thường Vụ'),
(4, 'Ủy Viên'),
(5, 'Trưởng Phòng/Ban'),
(6, 'Phó Phòng/Ban'),
(7, 'Cán Bộ/Nhân Viên');

-- --------------------------------------------------------

--
-- Table structure for table `chieu_huong_phat_trien`
--

CREATE TABLE IF NOT EXISTS `chieu_huong_phat_trien` (
  `Ma_CHPT` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Ten_CHPT` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_CHPT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
(1, 1, 'Bí Thư'),
(2, 1, 'Giám Đốc'),
(3, 2, 'Phó Bí Thư'),
(4, 2, 'Phó Giám Đốc'),
(5, 3, 'Ủy Viên Thường Vụ'),
(6, 3, 'Thành Viên Hội Đồng'),
(7, 4, 'Ủy Viên BCH'),
(8, 5, 'Trưởng Ban'),
(9, 5, 'Kế Toán Trưởng'),
(10, 5, 'Trưởng Phòng'),
(11, 6, 'Phó Ban'),
(12, 6, 'Kế Toán Phó'),
(13, 6, 'Phó Phòng'),
(14, 7, 'Cán Bộ'),
(15, 7, 'Nhân Viên'),
(16, NULL, 'Khác');

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
(4, 'Khối Cơ Sở Đoàn', NULL, 1),
(5, 'Cơ Quan Chính Đảng', NULL, NULL);

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
-- Table structure for table `loai_ban`
--

CREATE TABLE IF NOT EXISTS `loai_ban` (
  `Ma_Loai_Ban` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Ten_Loai_Ban` varchar(254) DEFAULT NULL,
  `La_BCH` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`Ma_Loai_Ban`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `loai_ban`
--

INSERT INTO `loai_ban` (`Ma_Loai_Ban`, `Ten_Loai_Ban`, `La_BCH`) VALUES
(1, 'Ban Chấp Hành', 1),
(2, 'Hội Đồng Thành Viên', 1),
(3, 'Phòng Kế Toán', 0),
(4, 'Phòng', 0),
(5, 'Ban', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ly_lich`
--

CREATE TABLE IF NOT EXISTS `ly_lich` (
  `Ma_CB` int(11) NOT NULL,
  `So_Hieu_CB` varchar(32) DEFAULT NULL,
  `Ho_Ten_Khai_Sinh` varchar(254) DEFAULT NULL,
  `Ten_Goi_Khac` varchar(254) DEFAULT NULL,
  `Gioi_Tinh` tinyint(1) DEFAULT NULL,
  `Cap_Uy_Hien_Tai` varchar(254) DEFAULT NULL,
  `Cap_Uy_Kiem` varchar(254) DEFAULT NULL,
  `Chuc_Vu` smallint(5) unsigned DEFAULT NULL,
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
  `Chuyen_Nganh` varchar(254) DEFAULT NULL,
  `Hoc_Ham_Hoc_Vi_Cao_Nhat` varchar(254) DEFAULT NULL,
  `Cap_Đo_CTLL` decimal(4,2) DEFAULT NULL,
  `Cap_Đo_TĐHV` decimal(4,2) DEFAULT NULL,
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
  `Thu_Nhap_Chinh_Trong_Gia_Đinh` varchar(254) DEFAULT NULL,
  `Loai_Nha_O` varchar(254) DEFAULT NULL,
  `Dien_Tich_Nha` varchar(254) DEFAULT NULL,
  `Loai_Đat_O` varchar(254) DEFAULT NULL,
  `Dien_Tich_Đat_O` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_CB`),
  KEY `FK_co_TĐ_HV` (`Cap_Đo_TĐHV`),
  KEY `FK_co_TĐ_LLCT` (`Cap_Đo_CTLL`),
  KEY `FK_co_Chuc_Vu` (`Chuc_Vu`),
  KEY `FK_co_Dan_Toc` (`Dan_Toc`),
  KEY `FK_co_Ton_Giao` (`Ton_Giao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ly_lich`
--

INSERT INTO `ly_lich` (`Ma_CB`, `So_Hieu_CB`, `Ho_Ten_Khai_Sinh`, `Ten_Goi_Khac`, `Gioi_Tinh`, `Cap_Uy_Hien_Tai`, `Cap_Uy_Kiem`, `Chuc_Vu`, `Phu_Cap_Chuc_Vu`, `Ngay_Sinh`, `Noi_Sinh`, `So_CMND`, `Ngay_Cap_CMND`, `Noi_Cap_CMND`, `Que_Quan`, `Noi_O_Hien_Nay`, `Dan_Toc`, `Ton_Giao`, `Đien_Thoai`, `Thanh_Phan_Gia_Đinh_Xuat_Than`, `Ngay_Tham_Gia_CM`, `Nghe_Nghiep_Truoc_Đo`, `Ngay_Đuoc_Tuyen_Dung`, `Co_Quan_Tuyen_Dung`, `Đia_Chi_Co_Quan_Tuyen_Dung`, `Ngay_Vao_Đang`, `Ngay_Chinh_Thuc`, `Ngay_Tham_Gia_Cac_To_Chuc_Chinh_Tri_Xa_Hoi`, `Ngay_Nhap_Ngu`, `Ngay_Xuat_Ngu`, `Quan_Ham_Chuc_Vu_Cao_Nhat`, `Chuyen_Nganh`, `Hoc_Ham_Hoc_Vi_Cao_Nhat`, `Cap_Đo_CTLL`, `Cap_Đo_TĐHV`, `Ngoai_Ngu`, `Lam_Viec_Trong_Che_Đo_Cu`, `Co_Than_Nhan_Nuoc_Ngoai`, `Tham_Gia_Cac_To_Chuc_Nuoc_Ngoai`, `Cong_Tac_Chinh_Đang_Lam`, `Danh_Hieu_Đuoc_Phong`, `So_Truong_Cong_Tac`, `Cong_Viec_Lam_Lau_Nhat`, `Khen_Thuong`, `Ky_Luat`, `Tinh_Trang_Suc_Khoe`, `Chieu_Cao`, `Can_Nang`, `Nhom_Mau`, `Loai_Thuong_Binh`, `Gia_Đinh_Liet_Sy`, `Thu_Nhap_Chinh_Trong_Gia_Đinh`, `Loai_Nha_O`, `Dien_Tich_Nha`, `Loai_Đat_O`, `Dien_Tich_Đat_O`) VALUES
(1, NULL, 'Nguyễn Trác Thức', 'Không', 0, 'Đảng Ủy viên', 'Không', 1, 0, '1980-11-20', 'TP. Hồ Chí Minh', '352454537', '1995-12-20', 'TP. Hồ Chí Minh', 'TP. Hồ Chí Minh', 'Bình Chánh, TP. Hồ Chí Minh', 1, 0, '01234567890', 'Cán bộ', NULL, 'Giảng viên', '2013-12-02', 'Trường ĐH Khoa Học Tự Nhiên', 'Quận 1, TP. Hồ Chí Minh', '2005-12-02', '2006-12-02', '2008-01-01', NULL, NULL, NULL, 'Công nghệ Thông tin', 'Thạc sỹ', '3.00', '18.00', 'Anh, Pháp', 'Không', 'Không', 'Không', 'Giảng viên', 'Không', 'Tổ chức xây dựng Đoàn', 'Bí thư Đoàn trường', 'Bằng khen các cấp', 'Không', 'Tốt', 1.7, 80, 'O', 'Không', 0, NULL, NULL, NULL, NULL, NULL),
(2, NULL, 'Lê Đức Thịnh', 'Không', 0, 'Bí thư chi bộ Sinh viên', 'Không', NULL, NULL, '1989-01-01', 'Long An', '435676357', '2003-01-13', 'Long An', 'Long An', 'Quận Thủ Đức, TP. Hồ Chí Minh', 1, 0, '01234567890', 'Viên chức', NULL, 'Sinh viên', '2010-01-14', 'Trường ĐH Công nghệ Thông tin', 'Thủ Đức, TP. Hồ Chí Minh', '2005-01-21', '2006-01-21', NULL, NULL, NULL, NULL, NULL, NULL, '2.00', '16.00', 'Anh', 'Không', 'Không', 'Không', 'Giảng viên', 'Không', 'Công tác xây dựng Đoàn', 'Phó bí thư Đoàn trường', 'Giấy khen các cấp', 'Không', 'Tốt', 1.7, 60, 'A', 'không', 0, NULL, NULL, NULL, NULL, NULL),
(3, NULL, 'Hoàng Anh Hùng', 'Không', 0, 'Phó bí thư chi bộ', 'Không', NULL, NULL, '1923-02-03', 'Đồng Nai', '012345678', '2014-01-14', 'Đồng Nai', 'Thanh Hóa', 'Đồng Nai', 3, 0, '01234567890', 'Nông dân', NULL, 'Sinh viên', '2014-01-08', 'Trường ĐH Công nghệ Thông tin', 'Thủ Đức, TP. Hồ Chí Minh', '2014-01-08', '2014-01-29', NULL, NULL, NULL, NULL, 'Công nghệ Thông tin', NULL, '1.00', '12.00', 'Anh, Nhật', 'Không', 'Không', 'Không', 'UV BTV', 'Không', 'Tuyên giáo', 'UV BTV', 'Giấy khen', 'Không', 'Tốt', 1.7, 65, 'O', 'Không', 0, NULL, NULL, NULL, NULL, NULL),
(4, NULL, 'Trần Đình Thi', 'Không', 0, 'Không', 'Không', NULL, NULL, '1992-02-25', 'An Giang', '352039720', '2007-03-15', 'An Giang', 'An Giang', 'Dĩ An, Bình Dương', 2, 0, '01256745609', 'Viên chức', NULL, 'Sinh viên', '2014-01-21', 'Trường ĐH Công nghệ Thông tin', 'Thủ Đức, TP. Hồ Chí Minh', NULL, NULL, NULL, NULL, NULL, NULL, 'Công nghệ Thông tin', NULL, '1.00', '12.00', 'Anh', 'Không', 'Không', 'Không', 'Chủ tịch Hội Sinh viên', 'Không', 'Phong trào', 'Chủ tịch Hội Sinh viên', 'Bằng khen, Giấy khen', 'Không', 'Tốt', 1.67, 55, 'A', 'Không', 0, NULL, NULL, NULL, NULL, NULL),
(12, NULL, 'Nguyễn Tuấn Anh', 'Không', 0, 'Không', 'Không', NULL, NULL, '2014-01-08', 'Đồng Tháp', '123456789', '0000-00-00', 'Tây Ninh', 'Đồng Tháp', 'TP. Hồ Chí Minh', 1, 2, '0125674569', 'Nông dân', NULL, 'Sinh viên', NULL, 'UBND tỉnh Tây Ninh', 'Tây Ninh', NULL, NULL, NULL, NULL, NULL, NULL, 'Công nghệ Thông tin', 'Không', '1.00', '12.00', 'Anh', '(không làm)', '(không có)', '(không tham gia)', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1.66, NULL, NULL, 'không', 0, 'lương 200 triệu VNĐ/năm; cho thuê nhà 50 triệu/năm;', 'nhà tự mua', NULL, NULL, 'được cấp 100 m2, tự mua 100 m2');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
  KEY `FK_co_Ngach` (`Ma_So_Ngach`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `qua_trinh_luong`
--

INSERT INTO `qua_trinh_luong` (`Ma_CB`, `Thoi_Gian_Nang_Luong`, `Ma_So_Ngach`, `Bac_Luong`, `He_So_Luong`, `Phu_Cap_Vuot_Khung`, `He_So_Phu_Cap`, `Muc_Luong_Khoang`) VALUES
(1, '2003-03-02', '01001', '5', 5.1, 0, 0, 0),
(1, '2012-02-02', '01001', '6', 6.1, 0, 0, 0),
(12, '2001-00-00', '01001', '1', 1, 10, 1, 1300000),
(12, '2009-03-03', '01001', '4', 4.3, 0, 0, 0);

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
  `La_Cong_Tac_Chinh` tinyint(4) DEFAULT NULL,
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
(1, 25, '1970-01-01', NULL, NULL, 2, NULL, NULL, NULL, NULL),
(1, 26, '2014-01-07', '2014-01-07', NULL, 1, NULL, NULL, NULL, NULL),
(1, 27, '1970-01-01', NULL, NULL, 2, NULL, NULL, NULL, NULL),
(1, 29, '1970-01-01', NULL, NULL, 1, NULL, NULL, NULL, NULL),
(2, 25, '1970-01-01', NULL, NULL, 3, NULL, NULL, NULL, NULL),
(2, 26, '1970-01-01', NULL, NULL, 3, NULL, NULL, NULL, NULL),
(2, 27, '1970-01-01', NULL, NULL, 3, NULL, NULL, NULL, NULL),
(3, 27, '1970-01-01', NULL, NULL, 5, NULL, NULL, NULL, NULL),
(4, 28, '1970-01-01', NULL, NULL, 3, NULL, NULL, NULL, NULL);

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
-- Table structure for table `trinh_đo_hoc_van`
--

CREATE TABLE IF NOT EXISTS `trinh_đo_hoc_van` (
  `Cap_Đo_TĐHV` decimal(4,2) NOT NULL DEFAULT '0.00',
  `Ten_TĐHV` varchar(254) DEFAULT NULL,
  `Viet_Tat_TĐHV` varchar(8) NOT NULL,
  PRIMARY KEY (`Cap_Đo_TĐHV`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `trinh_đo_hoc_van`
--

INSERT INTO `trinh_đo_hoc_van` (`Cap_Đo_TĐHV`, `Ten_TĐHV`, `Viet_Tat_TĐHV`) VALUES
('12.00', '12/12 - Trung Học Phổ Thông', '12/12'),
('14.00', 'Trung cấp', 'TC'),
('15.00', 'Cao đẳng', 'CĐ'),
('16.00', 'Đại học', 'ĐH'),
('18.00', 'Cao Học', 'CH');

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
  `Ngay_Đanh_Gia` datetime NOT NULL,
  `Ma_MĐHT_Tu_Đanh_Gia` tinyint(4) DEFAULT NULL,
  `Noi_Dung_Tu_Đanh_Gia` text,
  `Ma_Ban_Muon_Đen` int(11) DEFAULT NULL,
  `Thoi_Gian_Muon_Chuyen` date DEFAULT NULL,
  `Nguyen_Vong_Đao_Tao` text,
  `Ma_CB_Đanh_Gia` int(11) DEFAULT NULL,
  `Noi_Dung_Đanh_Gia` text,
  `Ma_MĐHT` tinyint(4) DEFAULT NULL,
  `Ma_CHPT` tinyint(4) DEFAULT NULL,
  `Đinh_Huong_Quy_Hoach` varchar(254) DEFAULT NULL,
  `Đinh_Huong_Đao_Tao` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`Ma_CB_Tu_Đanh_Gia`,`Ngay_Đanh_Gia`),
  KEY `FK_Ban_muon_đen` (`Ma_Ban_Muon_Đen`),
  KEY `FK_Can_Bo_đanh_gia` (`Ma_CB_Đanh_Gia`),
  KEY `FK_chieu_huong_phat_trien_cua_CB` (`Ma_MĐHT`),
  KEY `FK_chieu_huong_phat_trien_cua_CB_tu_đanh_gia` (`Ma_CHPT`),
  KEY `FK_muc_đo_hoan_thanh_cua_CB` (`Ma_MĐHT_Tu_Đanh_Gia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(1, 1, 'Trường Đoàn Lý Tự Trọng', 'Trung Cấp Chính Trị', '2002-01-16', '0002-03-16', 'Chính quy', 'Trung Cấp Chính Trị'),
(1, 2, 'Trường Đoàn Lý Tự Trọng', 'Cao cấp chính trị', '2005-04-04', '2005-09-04', 'chính quy', 'Cao cấp chính trị'),
(12, 1, 'Đại học Chính Trị', 'Sơ Cấp Chính Trị', '2013-03-03', '2003-04-03', 'bổ túc', 'Sơ Cấp Chính Trị');

-- --------------------------------------------------------

--
-- Table structure for table `đon_vi`
--

CREATE TABLE IF NOT EXISTS `đon_vi` (
  `Ma_Đon_Vi` varchar(32) NOT NULL,
  `Ten_Đon_Vi` varchar(254) DEFAULT NULL,
  `Ma_Khoi` tinyint(3) unsigned DEFAULT NULL,
  `Ma_Truong_ĐV` int(11) DEFAULT NULL,
  `Ma_Ban_Chap_Hanh` int(11) DEFAULT NULL,
  `Mo_Ta` varchar(254) DEFAULT NULL,
  `Ngay_Thanh_Lap` date DEFAULT NULL,
  `Trang_Thai` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`Ma_Đon_Vi`),
  KEY `FK_Co_Ban_Chap_Hanh` (`Ma_Ban_Chap_Hanh`),
  KEY `FK_co_Truong_Đon_Vi` (`Ma_Truong_ĐV`),
  KEY `FK_khoi_truc_thuoc` (`Ma_Khoi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `đon_vi`
--

INSERT INTO `đon_vi` (`Ma_Đon_Vi`, `Ten_Đon_Vi`, `Ma_Khoi`, `Ma_Truong_ĐV`, `Ma_Ban_Chap_Hanh`, `Mo_Ta`, `Ngay_Thanh_Lap`, `Trang_Thai`) VALUES
('BTT', 'Báo Tuổi Trẻ', NULL, NULL, 26, '<p>M&ocirc; tả cho đơn vị B&aacute;o Tuổi trẻ</p>\r\n', '2014-01-25', 1),
('CQCTTĐ', 'Cơ Quan Chuyên Trách Thành Đoàn', 2, NULL, NULL, 'Cơ Quan Chuyên Trách Thành Đoàn quản lý các vấn đề thành đoàn TP HCM', NULL, 1),
('HDBC', 'Huyện Đoàn Bình Chánh', 1, NULL, NULL, 'Mô tả huyện Đoàn Bình Chánh', '1970-01-01', 1),
('HDCC', 'Huyện Đoàn Củ Chi', 1, NULL, NULL, 'Mô tả huyện Đoàn Củ Chi', '1970-01-01', 1),
('HDCG', 'Huyện Đoàn Cần Giờ', 1, NULL, NULL, 'Mô tả huyện Đoàn Cần Giờ', '1970-01-01', 1),
('UEL', 'Trường Đại Học Kinh Tế - Luật', 4, NULL, 28, '<p>M&ocirc; tả cho ĐH Kinh Tế - Luật</p>\r\n', '2010-01-21', 1),
('UIT', 'Trường Đại Học Công Nghệ Thông Tin', NULL, 3, 29, '<p>M&ocirc; tả cho trường ĐH C&ocirc;ng nghệ Th&ocirc;ng tin</p>\r\n', '2006-06-08', 1),
('UT', 'Trường ĐH Bách Khoa', 1, NULL, NULL, 'Mô tả cho trường ĐH Bách Khoa', '2014-01-01', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ban`
--
ALTER TABLE `ban`
  ADD CONSTRAINT `FK_loai_cua_ban` FOREIGN KEY (`Ma_Loai_Ban`) REFERENCES `loai_ban` (`Ma_Loai_Ban`),
  ADD CONSTRAINT `FK_truc_thuoc` FOREIGN KEY (`Ma_Đon_Vi`) REFERENCES `đon_vi` (`Ma_Đon_Vi`);

--
-- Constraints for table `can_bo`
--
ALTER TABLE `can_bo`
  ADD CONSTRAINT `FK_co_ĐVCT_Chinh` FOREIGN KEY (`Ma_ĐVCT_Chinh`) REFERENCES `đon_vi` (`Ma_Đon_Vi`),
  ADD CONSTRAINT `FK_co_Chuc_Vu_Chinh` FOREIGN KEY (`Ma_CV_Chinh`) REFERENCES `chuc_vu` (`Ma_Chuc_Vu`);

--
-- Constraints for table `chuc_vu`
--
ALTER TABLE `chuc_vu`
  ADD CONSTRAINT `FK_cap_đo_cua_CV` FOREIGN KEY (`Ma_Cap`) REFERENCES `cap_chuc_vu` (`Ma_Cap`);

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
  ADD CONSTRAINT `FK_CB_đuoc_khen_thuong` FOREIGN KEY (`Ma_CB`) REFERENCES `can_bo` (`Ma_CB`),
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
  ADD CONSTRAINT `FK_ky_luat_cua_can_bo` FOREIGN KEY (`Ma_CB`) REFERENCES `can_bo` (`Ma_CB`);

--
-- Constraints for table `ly_lich`
--
ALTER TABLE `ly_lich`
  ADD CONSTRAINT `FK_co_Ton_Giao` FOREIGN KEY (`Ton_Giao`) REFERENCES `ton_giao` (`Ma_Ton_Giao`),
  ADD CONSTRAINT `FK_co_Chuc_Vu` FOREIGN KEY (`Chuc_Vu`) REFERENCES `chuc_vu` (`Ma_Chuc_Vu`),
  ADD CONSTRAINT `FK_co_Dan_Toc` FOREIGN KEY (`Dan_Toc`) REFERENCES `dan_toc` (`Ma_Dan_Toc`),
  ADD CONSTRAINT `FK_co_Ly_Lich` FOREIGN KEY (`Ma_CB`) REFERENCES `can_bo` (`Ma_CB`),
  ADD CONSTRAINT `FK_co_TĐ_HV` FOREIGN KEY (`Cap_Đo_TĐHV`) REFERENCES `trinh_đo_hoc_van` (`Cap_Đo_TĐHV`),
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
-- Constraints for table `qua_trinh_cong_tac`
--
ALTER TABLE `qua_trinh_cong_tac`
  ADD CONSTRAINT `FK_QTCT_cua_can_bo` FOREIGN KEY (`Ma_CB`) REFERENCES `ly_lich` (`Ma_CB`);

--
-- Constraints for table `qua_trinh_luong`
--
ALTER TABLE `qua_trinh_luong`
  ADD CONSTRAINT `FK_co_Ngach` FOREIGN KEY (`Ma_So_Ngach`) REFERENCES `ngach_luong` (`Ma_So_Ngach`),
  ADD CONSTRAINT `FK_luong_cua_can_bo` FOREIGN KEY (`Ma_CB`) REFERENCES `can_bo` (`Ma_CB`);

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
  ADD CONSTRAINT `FK_Can_Bo_Tai_Ban` FOREIGN KEY (`Ma_CB`) REFERENCES `can_bo` (`Ma_CB`),
  ADD CONSTRAINT `FK_cv_tai_ban` FOREIGN KEY (`Ma_CV`) REFERENCES `chuc_vu` (`Ma_Chuc_Vu`),
  ADD CONSTRAINT `FK_to_cong_tac_cua_CB_tai_ban` FOREIGN KEY (`Ma_Ban`, `STT_To`) REFERENCES `to_cong_tac` (`Ma_Ban`, `STT_To`);

--
-- Constraints for table `to_cong_tac`
--
ALTER TABLE `to_cong_tac`
  ADD CONSTRAINT `FK_ban_truc_thuoc` FOREIGN KEY (`Ma_Ban`) REFERENCES `ban` (`Ma_Ban`);

--
-- Constraints for table `yeu_cau_thay_đoi_tt_cb`
--
ALTER TABLE `yeu_cau_thay_đoi_tt_cb`
  ADD CONSTRAINT `FK_Anh_Huong_Can_bo` FOREIGN KEY (`Ma_CB_Anh_Huong`) REFERENCES `can_bo` (`Ma_CB`),
  ADD CONSTRAINT `FK_đuoc_can_bo_yeu_cau` FOREIGN KEY (`Ma_CB_Yeu_Cau`) REFERENCES `can_bo` (`Ma_CB`);

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
  ADD CONSTRAINT `FK_Can_Bo_đanh_gia` FOREIGN KEY (`Ma_CB_Đanh_Gia`) REFERENCES `can_bo` (`Ma_CB`),
  ADD CONSTRAINT `FK_chieu_huong_phat_trien_cua_CB` FOREIGN KEY (`Ma_MĐHT`) REFERENCES `muc_đo_hoan_thanh` (`Ma_MĐHT`),
  ADD CONSTRAINT `FK_chieu_huong_phat_trien_cua_CB_tu_đanh_gia` FOREIGN KEY (`Ma_CHPT`) REFERENCES `chieu_huong_phat_trien` (`Ma_CHPT`),
  ADD CONSTRAINT `FK_co_KQKT` FOREIGN KEY (`Ma_CB_Tu_Đanh_Gia`) REFERENCES `can_bo` (`Ma_CB`),
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
  ADD CONSTRAINT `FK_co_Truong_Đon_Vi` FOREIGN KEY (`Ma_Truong_ĐV`) REFERENCES `can_bo` (`Ma_CB`),
  ADD CONSTRAINT `FK_khoi_truc_thuoc` FOREIGN KEY (`Ma_Khoi`) REFERENCES `khoi` (`Ma_Khoi`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
