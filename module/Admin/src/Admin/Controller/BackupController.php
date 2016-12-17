<?php
namespace Admin\Controller;

//Load lớp AbstractActionController vào CONTROLLER
use Zend\Mvc\Controller\AbstractActionController;

//Load lớp ViewModel vào CONTROLLER
use Zend\View\Model\ViewModel;

//Chứng thực, phân quyền
use Zend\Authentication\Result;
use Zend\Permissions\Acl\Acl;
use Zend\Session\Container; //Session

class BackupController extends AbstractActionController
{
    var $dbName='qlcbd';
    public function indexAction()
    {
        $view['flag'] = false;
        $this->layout('layout/admin');

        if($this->getRequest()->isPost()){
            $parameters = $this->getRequest()->getPost();
            //$thongtin = $parameters->toArray();
            $this->Execute("*","E://");
            //file_put_contents($thongtin['location'] . '1.txt', 'Tuấn Anh');
            $view['flag'] = true;
        }
        return new ViewModel($view);
    }


    protected function Execute($tables = '*', $outputPath = '.')
    {

        $conn = mysql_connect("localhost", "root", "");
        mysql_select_db($this->dbName, $conn);
        //set character database
        mysql_set_charset("utf8",$conn);



        try
        {
            /**
             * Tables to export
             */
            if($tables == '*')
            {

                $tables = array();
                $result = mysql_query('SHOW TABLES');
                while($row = mysql_fetch_row($result))
                {
                    $tables[] = $row[0];
                }
            }
            else
            {
                $tables = is_array($tables) ? $tables : explode(',',$tables);
            }

            $sql = 'CREATE DATABASE IF NOT EXISTS '.$this->dbName.";\n\n";
            $sql .= 'USE '.$this->dbName.";\n";

            /**
             * Iterate tables
             */
            foreach($tables as $table)
            {
                $result = mysql_query('SELECT * FROM '.$table);
                $numFields = mysql_num_fields($result);

                //$sql .= 'DROP TABLE IF EXISTS '.$table.';';
                $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
                $sql.= "\n\n".$row2[1].";\n\n";


                for ($i = 0; $i < $numFields; $i++)
                {
                    while($row = mysql_fetch_row($result))
                    {
                        $sql .= 'INSERT INTO '.$table.' VALUES(';
                        for($j=0; $j<$numFields; $j++)
                        {
                            $row[$j] = addslashes($row[$j]);
                            if (isset($row[$j]))
                            {
                                $sql .= '"'.$row[$j].'"' ;
                            }
                            else
                            {
                                $sql.= '""';
                            }

                            if ($j < ($numFields-1))
                            {
                                $sql .= ',';
                            }
                        }

                        $sql.= ");\n";
                    }
                }

                $sql.="\n\n\n";
            }


            /**
             * trigger export
             */
            try
            {
                $triggerNames = array();
                $result = mysql_query('SHOW TRIGGERS IN '.$this->dbName);
                while( $row = mysql_fetch_row($result) )
                {
                    $triggerNames[] = $row[0];
                }

                foreach( $triggerNames as $triggerName)
                {
                    $values= array();
                    $result = mysql_query('SHOW CREATE TRIGGER '.$triggerName);
                    while ($row = mysql_fetch_row($result))
                    {
                        $values[] = $row[2];
                    }

                    foreach( $values as $value)
                    {
                        $sql.="DELIMITER //\n".$value."\n//\nDELIMITER ;\n\n";
                    }
                }
            }
            catch ( Exeption $e ) {};



            /**
             * procudure export
             */
            try
            {
                $procedureNames = array();
                $result = mysql_query("SHOW PROCEDURE STATUS WHERE db='".$this->dbName."'");
                while( $row = mysql_fetch_row($result) )
                {
                    $procedureNames[] = $row[1];
                }

                $procedures='';
                foreach( $procedureNames as $procedureName)
                {
                    // $sql.=$procedureName."\n";
                    $values= array();
                    $result = mysql_query('SHOW CREATE PROCEDURE '.$procedureName);
                    while ($row = mysql_fetch_row($result))
                    {
                        $values[] = $row[2];
                    }

                    foreach( $values as $value)
                    {
                        $procedures.=$value."$$\n\n";
                    }
                }
                if( $procedures!='')
                    $sql.="DELIMITER $$\n".$procedures."DELIMITER ;";
            }
            catch ( Exception $e){};

        }
        catch (Exception $e)
        {
            var_dump($e->getMessage());
            return false;
        }

        return $this->saveFile($sql, $outputPath);
    }

    protected function saveFile(&$sql, $outputPath = '.')
    {
        if (!$sql) return false;

        try
        {
            $handle = fopen($outputPath.'/qlcbd-backup-'.date("d_m_y h-i-s").'.sql','w+');
//            $handle = fopen($outputPath.'/qlcbd-backup.sql','w+');
            fwrite($handle, $sql);
            fclose($handle);
        }
        catch (Exception $e)
        {
            var_dump($e->getMessage());
            return false;
        }

        return true;
    }

}

