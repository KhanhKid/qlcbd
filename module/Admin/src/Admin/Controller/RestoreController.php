<?php
//Khai bao namespace 
namespace Admin\Controller;
ini_set('max_execution_time', 600);
//Load lớp AbstractActionController vào CONTROLLER
use Zend\Mvc\Controller\AbstractActionController;

//Load lớp ViewModel vào CONTROLLER
use Zend\View\Model\ViewModel;

//Chứng thực, phân quyền
use Zend\Authentication\Result;
use Zend\Permissions\Acl\Acl;
use Zend\Session\Container; //Session

class RestoreController extends AbstractActionController
{
    var $filePath='';
    var $dbName='qlcbd';
    public function indexAction()
    {
        //$dbName='test_backup1';

        $view['flag'] = false;
        $this->layout('layout/admin');

        if($this->getRequest()->isPost()){
            $parameters = $this->getRequest()->getPost();
            $thongtin = $parameters->toArray();
            $file = $_FILES['fileinput'];

            ///////////////////////////////////////////
            //$this->filePath = $file['tmp_name'];//file location
            $this->filePath = "E://qlcbd-backup.sql";//file location

            $this->Execute();

            $view['flag'] = true;
        }
        return new ViewModel($view);

    }

    protected function  Execute()
    {
        $triggers='';
        $procedures='';
        $tempvalue = '';
        // Read in entire file
        $lines = file($this->filePath);
        $checkline=false;///check next line is value insert??
        $checktrigger=false;
        $checkprocedure=false;
        $table='';
        $fKey='';
        $insert= array();



        $conn = mysql_connect("localhost", "root", "") or die('Không Thể Kết Nối Server ');

        //delete database qlcbd
        $sqlDrop = 'DROP DATABASE '.$this->dbName;
        try
        {
//            mysql_query( $sql, mysql_connect($this->host, $this->username, $this->pass) );
            mysql_query( $sqlDrop, $conn );
        }
        catch ( Exception $e)
        {
            return false;
        }

        //create database
        $createTable = 'CREATE DATABASE '.$this->dbName.' DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci';
        mysql_query( $createTable, $conn );

        // check database
        mysql_select_db($this->dbName);
        //set character
        mysql_set_charset("utf8",$conn);
        // Temporary variable, used to store current query


        foreach ($lines as $line)
        {

            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;

            if (substr($line,0,12) =='CREATE TABLE')
            {
                $table=substr($line,13,-3);
            }
            //continue if this is foreing key
            elseif(substr($line,2,3) == 'KEY')
            {
                //delete last ',' in line
                if(substr($tempvalue,-2,1) == ',')
                    $tempvalue = substr($tempvalue,0,-2);
                continue;
            }
            elseif(substr($line,2,10) == 'CONSTRAINT')
            {
                //delete last ',' in create table
                if(substr($tempvalue,-2,1) == ',')
                    $tempvalue = substr($tempvalue,0,-2);

                //delete ',' inline forgeing
                if(substr($line,-2,1) == ',')
                    $fk = substr($line,0,-2).';<br/>';
                else $fk = $line.';<br/>';
                $fKey.=' ALTER TABLE '.$table.' ADD '.$fk;
                continue;
            }
            elseif(substr($line,0,6) == 'INSERT')
            {
                $insert[sizeof($insert)] = $line;
                if(substr($line,-2,1) != ';')
                    $checkline = true;
                continue;
            }

            if($checkline)
            {
                try{
                    $insert[sizeof($insert)-1].=$line;
                    if(substr($line,-2,1) != ';')
                        $checkline = true;
                    else $checkline = false;
                    continue;
                }
                catch(Exception $e){}

            }

            //get trigger

            if(substr($line,0,12) == 'DELIMITER //')
            {
                $triggers.=$line.'<br/>';
                $checktrigger = true;
                continue;
            }
            if($checktrigger)
            {
                $triggers.=$line.'<br/>';
                if(substr($line,0,11) == 'DELIMITER ;')
                    $checktrigger = false;
                continue;
            }


            if(substr($line,0,12) == "DELIMITER $$")
            {
                $procedures.=@$line.'<br/>';
                $checkprocedure = true;
                continue;
            }
            if($checkprocedure)
            {
                $procedures.=@$line.'<br/>';
                if(substr($line,0,11) =="DELIMITER ;")
                    $checktrigger = false;
                continue;
            }



            // Add this line to the current segment
            $tempvalue .= $line;

            //// If it has a semicolon at the end, it's the end of the query
            if (substr(trim($line), -1, 1) == ';')
            {
                // Perform the query
                /////////////////////////////////////////////////////////////////////////////////
                //echo $tempvalue.'<br/>';
                mysql_query($tempvalue) or print('Error performing query \'<strong>' . $tempvalue . '\': ' . mysql_error() . '<br /><br />');
                // Reset temp variable to empty
                $tempvalue = '';
            }
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////
        //echo $triggers.'<br/><br/><br/>';
        mysql_query($fKey);
        mysql_query($triggers);
        mysql_query($procedures);
        while( sizeof($insert)!= 0)
        {
            try
            {
                ///////////////////////////////////////////////////////////////////////////////////
                //echo $insert[sizeof($insert)-1].'<br/>';
                mysql_query($insert[sizeof($insert)-1]);
                unset($insert[sizeof($insert)-1]);

            }
            catch( Exception $e)
            {
                return false;
            }
        }

        return true;
    }

}