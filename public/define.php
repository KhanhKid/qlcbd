<?php

//Đường dẫn đến thư mục chứa thư mục hiện thời
chdir(dirname(__DIR__));

//Hằng số lưu đường dẫn thư mục ứng dụng
//Hằng số lưu thông tin đường dẫn thư mục
define('APPLICATION_PATH', realpath(dirname(__DIR__)));

//Hằng số lưu đường dẫn thư mục chứa thư viên ZF2'
define('LIBRARY_PATH', realpath(APPLICATION_PATH . '/library/'));


define('PUBLIC_PATH', realpath(APPLICATION_PATH . '/public'));
define('TEMPLATE_PATH', realpath(PUBLIC_PATH . '/template/'));

define('LIBRARY', realpath(APPLICATION_PATH . '/vendor'));

//external library
define('PHPEXCEL', realpath(LIBRARY . '/phpexcel'));

//
define('QLCBD', realpath(LIBRARY . '/qlcbd'));

define('ROOT_PATH', '/qlcbd/');

define('ROOT_URL', 'http://localhost/qlcbd/');

//file minh chứng
define('PROOF_FILES_PATH'   , realpath(PUBLIC_PATH . '/files/proof_files'));
