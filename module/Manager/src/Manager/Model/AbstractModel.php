<?php
namespace Manager\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Exception\InvalidQueryException;

class AbstractModel
{
    protected $adapter;

    //===============================================================

    public function __construct(Adapter $adapter = null)
    {
        $this->adapter = $adapter;

    }

    /**
     * query from database with build-in Sql and Parameters; And return a table of query
     * @param null $sql
     * @param null $parameters
     * @return array|null a 2d-data-table
     */
    public function query($sql = null, $parameters =null){
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
            $data[]= $row;

            $result->next();
        };


        return $data;
    }

    /**
     * execute a command from database with build-in Sql and Parameters;  return result only;
     * @param null $sql
     * @param null $parameters
     * @return mixed|null result of command
     */
    public function executeNonQuery($sql = null, $parameters =null){
        //process database
        $result = null;

        $sm = $this->adapter->createStatement();
        $sm->prepare($sql);
        try{
            $result = $sm->execute($parameters);
        } catch (InvalidQueryException $exc){   
            throw $exc;
        }

        //result
        $data  = $result->getGeneratedValue();
        //var_dump($result);exit;

        return $data;
    }

    public function executeNonQuery1($sql = null, $parameters =null){
        //process database
        $result = null;

        $sm = $this->adapter->createStatement();
        $sm->prepare($sql);

        try{
            $result = $sm->execute($parameters);
        } catch (InvalidQueryException $exc){   
            throw $exc;
        }
        
        //result
        $data  = $result->getGeneratedValue();
        //var_dump($result);exit;

        $output = print_r($sm, true);
        file_put_contents('file.txt', $output);

        return $data;
    }


    /**
     * Tool: format Date-String to 'yyyy-mm-dd' to conform with DBMS
     * @param $date
     * @return bool|string
     */
    protected function formatDateForDB($date){
        return ($date != null)?date('Y-m-d',strtotime(str_replace('/', '-', $date))):'1970-01-01';
    }


}