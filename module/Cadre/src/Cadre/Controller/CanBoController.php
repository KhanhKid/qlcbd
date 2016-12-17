<?php
//Khai bao namespace 
namespace Cadre\Controller;

//Load lớp AbstractActionController vào CONTROLLER
use Manager\DTO\CanBo;
use Zend\Mvc\Controller\AbstractActionController;

//Load lớp ViewModel vào CONTROLLER
use Zend\View\Model\ViewModel;

//auth
use Zend\Authentication\AuthenticationService;

//file upload
//use Zend\File\;
use Zend\Validator\File\UploadFile;
use Zend\Filter\File\RenameUpload;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\FileInput;

//exption
use Zend\Db\Adapter\Exception\InvalidQueryException;

class CanboController extends AbstractActionController
{
    public function indexAction()
    {

    }


    public function danhgiaAction()
    {

        $helper = $this->getServiceLocator()->get('viewhelpermanager');
        $headScript = $helper->get('headscript');

        $headScript->appendFile(ROOT_PATH . 'public/script/ckeditor/ckeditor.js');
    }


    public function lylichAction(){
        //init model
        $canboModel = $this->getServiceLocator()->get('Manager\Model\CanBoModel');
        //init view


        //get CanBoID
        $auth = (new AuthenticationService());
        $id = $auth->getIdentity()->Identifier_Info;
        //var_dump( $id); exit;



        //get data from model
        $view['lylich'] = $canboModel->getLyLichCanBo($id);
        $view['dao_tao_boi_duong'] = $canboModel->getDaoTaoBoiDuong($id);
        $view['dacdiemlichsu'] = $canboModel->getDacDiemLichSu($id);
        $view['quatrinhcongtac'] = $canboModel->getQuaTrinhCongTac($id);
        //var_dump($view['quatrinhcongtac']);exit;

        //send data to view
        return new ViewModel($view);
    }

    public function kiennghiAction(){
        //init model
        $canboModel = $this->getServiceLocator()->get('Manager\Model\CanBoModel');

        if($this->getRequest()->isPost()){
            //message to browser
            $view['message'] = 'Đã gửi kiến nghị, xin chờ để được giải quyết';


            //get CanBoID
            $auth = (new AuthenticationService());
            $curId = $auth->getIdentity()->Identifier_Info;

            //if ID is not exist, stop, do nothing
            if(null==$curId) {
                $view['message'] = 'User không phải là cán bộ';
                return new ViewModel($view);
            }

            //get current time (before upload file)
            $cur_timestamp = time();

            //get files from client
            $files = $this->getRequest()->getFiles();


            //check file size, if file is exist
            $zip_name = null;
            $max_file_size = 1048576; //1024*1024 bytes
            if(0>=$files['filedinhkem']['size']){
                $view['message'].=', không có file đính kèm';
            }
            else if($max_file_size<=$files['filedinhkem']['size']){
                $view['message'].=', file đính kèm có kích thước lớn nên không gửi được';
            }
            else{

                //save to server
                $file_name = $files['filedinhkem']['name'];
                $uploadObj = new \Zend\File\Transfer\Adapter\Http(); //$uploader = new \Zend\File\Transfer;
                $uploadObj->setDestination(PROOF_FILES_PATH); //move_uploaded_file()
                $uploadObj->receive($file_name);
                $file_path = PROOF_FILES_PATH.'/'.$file_name; //get file path in server


                //nén các files
                $zip_name = $curId.'_'. $cur_timestamp .".zip";         // Tên file zip <mã cán bộ>_<thời gian hiện tại>.zip
                $zip_path = PROOF_FILES_PATH.'/'.$zip_name;

                $zipper = new \ZipArchive();
                $zipper->open($zip_path, \ZIPARCHIVE::CREATE); //mở file zip để ghi các file vào
                $zipper->addFile($file_path,$file_name); ////add files into zip, can be  multi
                $zipper->close();

                //xóa các files đã được nén
                unlink($file_path);

                /*
                var_dump($zip_path);exit;
                header('Content-type: application/zip');
                h8eader('Content-Disposition: attachment; filename="'.$zip_name.'"');
                readfile($zip_path);
                */



                /*
                //filter
                // File upload input
                $fileInput = new FileInput('filedinhkem');           // Special File Input type
                $fileInput->getValidatorChain()               // Validators are run first w/ FileInput
                    ->attach(new UploadFile());
                $fileInput->getFilterChain()                  // Filters are run second w/ FileInput
                    ->attach(new RenameUpload(array(
                        'target'    => './data/tmpuploads/file',
                        'randomize' => true,
                    )));

                $inputFilter = new InputFilter();
                $inputFilter->add($fileInput)
                    ->setData($files);

                $data = null;
                if ($inputFilter->isValid()) {           // FileInput validators are run, but not the filters...
                    echo "The form is valid\n";
                    $data = $inputFilter->getValues();   // This is when the FileInput filters are run.

                }
                $data = $inputFilter->getValues();
                var_dump($data);exit;
                */
            }

            //get parameter (from post)
            $parameters = $this->getRequest()->getPost();
            //save info
            try{
                $canboModel->guiKienNghi(
                    $curId,
                    date('Y-m-d H:i:s',$cur_timestamp), //the same time with file;
                    $parameters['tenkiennghi'],
                    $parameters['noidung'],
                    $zip_name //save file to database for download this later
                );
            }catch(InvalidQueryException $exc) {
                //var_dump($exc);
                $view['message'] = 'Kiến nghị không gửi được'.' ['.$exc->getCode().']';
            }




            return new ViewModel($view);
        }
    }

    public function qlcbcapduoiAction(){
        //init model
        $canboModel = $this->getServiceLocator()->get('Manager\Model\CanBoModel');
        //init view


        $id = null;
        //var_dump( $id); exit;
        if($this->getRequest()->isPost()){
            $parameters = $this->getRequest()->getPost();
            //get CanBoID

            $id = $parameters['dshoten'];
        }


        //get data from model
        $view['lylich'] = $canboModel->getLyLichCanBo($id);
        $view['dao_tao_boi_duong'] = $canboModel->getDaoTaoBoiDuong($id);
        $view['dacdiemlichsu'] = $canboModel->getDacDiemLichSu($id);
        $view['quatrinhcongtac'] = $canboModel->getQuaTrinhCongTac($id);
        //var_dump($view['quatrinhcongtac']);exit;
        $view['dsCanBo'] = null;

        //get CanBo info
        $auth = (new AuthenticationService());
        $role = $auth->getIdentity()->Role_Name;
        $curID = $auth->getIdentity()->Identifier_Info;

        //
        if($canboModel->isCBThuongTrucTD($curID)){
            $view['dsCanBo'] = $canboModel->getAllBriefInfo();
        }
        elseif($ban = $canboModel->isTruongPhoBan($curID)){
            $view['dsCanBo'] = $canboModel->getDSCanBoTrucThuoc($curID);
        }


        //send data to view
        return new ViewModel($view);
    }



}

