<?php
namespace app\abstracts;

use app\exceptions\SmsConfigException;

/**
 * This class is the parent of every sms panel
 * if U want use any sms panel, please extend this class too your class
 * and then override the abstract method and enjoy it :)
 * Class SmsHandler
 * @package app\modules\sms\abstracts
 * @author Mehran
 */
abstract class SmsHandler
{
    /**
     * @var $username
     */
    private $username;
    /**
     * @var $password
     */
    private $password;
    /**
     * @var $ref_number
     */
    private $ref_number;

    /**
     * SmsHandler constructor.
     * Put extra config for every panel in extra_config array and after override the checkSmsConfig method for check it
     * @param array $config
     * @param array $extra_config
     * @throws SmsConfigException
     * @author Mehran
     */
    public function __construct(array $config,  array $extra_config = [])
    {
        self::checkSmsConfig($config, $extra_config);
    }

    /**
     * @return mixed
     * @author Mehran
     */
    protected function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     * @author Mehran
     */
    protected function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     * @author Mehran
     */
    protected function getRefNumber()
    {
        return $this->ref_number;
    }

    /**
     * @return mixed
     * @author Mehran
     */
    protected function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     * @author Mehran
     */
    protected function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     * @author Mehran
     */
    protected function setRefNumber($ref_number)
    {
        $this->ref_number = $ref_number;
    }

    /**
     * This method has been used for check configuration of sms
     * @param array $config
     * @param array $extra_config
     * @throws SmsConfigException
     * @author Mehran
     */
    protected function checkSmsConfig(array $config, array $extra_config = [])
    {
        if (!array_key_exists("username",$config)) {
            Throw new SmsConfigException("The Username for sms is not config yet");
        } elseif (!array_key_exists("password",$config)) {
            Throw new SmsConfigException("The Password for sms is not config yet");
        } elseif (!array_key_exists("ref_number",$config)) {
            Throw new SmsConfigException("The RefNumber for sms is not config yet");
        }
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->ref_number = $config['ref_number'];
    }

    /**
     * This method for send any request to Sms web service
     *
     * @param $message
     * @param $mobiles
     * @return bool|mixed|string
     * @throws SmsPanelException
     * @author Mehran
     */
    abstract public function sendSms($message, $mobiles);

    /**
     * This action has been written for send Sms to users
     *
     * @return bool|mixed|string
     * @throws SmsPanelException
     * @author Mehran
     */
    abstract public function checkCreditSMS();

    /**
     * This method has been used for check credit of a panel
     *
     * @return bool|mixed|string
     * @throws SmsPanelException
     * @author Mehran
     */
    abstract public function checkPanelExpireDate();

    /**
     * This method has been used for check expire date panel
     *
     * @return bool|mixed|string
     * @throws SmsPanelException
     * @author Mehran
     */
    abstract public function sendGroupSms($message, $mobile);

    /**
     * this action has been used for send sms for several numbers
     *
     * @param $url
     * @param $data
     * @param $method
     * @return bool|mixed|string
     * @throws SmsPanelException
     * @author Mehran
     */
    abstract protected function sendRequest($url, $data, $method = null);
}
