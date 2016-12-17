<?php
namespace QLCB\Authentication;

use stdClass;
use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result as AuthenticationResult;

class DkAuthAdapter implements AdapterInterface
{
    /**
     * Database Connection
     *
     * @var DbAdapter
     */
    protected $db = null;

    protected $username;
    protected $password;

    public function __construct($db, $username, $password)
    {
        $this->db = $db;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Performs an authentication attempt
     *
     * @return \Zend\Authentication\Result
     */
    public function authenticate()
    {

        $result = $this->login($this->username,$this->password);
        if ($result["@errcode"]===NULL)
        {
            $identity = new stdClass();
            $identity->userid = $result["@userid"];
            $identity->fullname = $result["@fullname"];

            return new AuthenticationResult(
                AuthenticationResult::SUCCESS,
                $identity,
                array()
            );
        }else {

            return new AuthenticationResult(
                AuthenticationResult::FAILURE,
                null,
                array($result["@errmsg"])
            );
        }
    }
    function login($username, $password)
    {
        $password = md5(md5($password));

        $this->db->query("CALL uac_users_login(?,?,@errcode,@errmsg,@userid,@fullname)", array($username, $password));
        $result = $this->db->query("select @errcode, @errmsg, @userid,@userid,@fullname");
        $result = $result->execute()->current();
        return $result;
    }
}
