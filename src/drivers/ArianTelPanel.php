<?php
namespace mehrand\sms_panel\drivers;
use mehrand\sms_panel\exceptions\SmsConfigException;
use mehrand\sms_panel\exceptions\SmsPanelException;
use mehrand\sms_panel\modules\sms\abstracts\SmsHandler;
use SoapClient;
use SoapFault;
/**
 * Class ArianTelPanel
 *
 * @package app\modules\sms\models
 * @author Mehran
 */
class ArianTelPanel extends SmsHandler
{
    /**
     * @var $udh
     */
    private $udh;
    /**
     * @var $is_flash
     */
    private $is_flash;
    /**
     * @var $rec_id
     */
    private $rec_id;
    /**
     * @var $status
     */
    private $status;
    /**
     * @return mixed
     * @author Mehran
     */
    public function getUdh()
    {
        return $this->udh;
    }
    /**
     * @return mixed
     * @author Mehran
     */
    public function getIsFlash()
    {
        return $this->is_flash;
    }
    /**
     * @return mixed
     * @author Mehran
     */
    public function getRecId()
    {
        return $this->rec_id;
    }
    /**
     * @return mixed
     * @author Mehran
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * @return mixed
     * @author Mehran
     */
    public function setUdh($udh)
    {
        $this->udh = $udh;
    }
    /**
     * @return mixed
     * @author Mehran
     */
    public function setIsFlash($is_flash)
    {
        $this->is_flash = $is_flash;
    }
    /**
     * @return mixed
     * @author Mehran
     */
    public function setRecId($rec_id)
    {
        $this->rec_id = $rec_id;
    }
    /**
     * @return mixed
     * @author Mehran
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
    /**
     * ArianTelPanel constructor.
     * @param array $config
     * @param array $extra_config
     * @throws \app\exceptions\SmsConfigException
     */
    public function __construct(array $config, array $extra_config = [])
    {
        parent::__construct($config);
        self::checkSmsConfig($config, $extra_config);
    }
    /**
     * This method has been used for check configuration of sms for Ariantel panel
     *
     * @param array $config
     * @param array $extra_config
     * @throws SmsConfigException
     * @author Mehran
     */
    protected function checkSmsConfig(array $config, array $extra_config = [])
    {
        parent::checkSmsConfig($config, $extra_config);
        if (!array_key_exists("udh",$extra_config)) {
            Throw new SmsConfigException("The udh for sms is not config yet");
        } elseif (!array_key_exists("is_flash",$extra_config)) {
            Throw new SmsConfigException("The is_flash for sms is not config yet");
        } elseif (!array_key_exists("rec_id",$extra_config)) {
            Throw new SmsConfigException("The rec_id for sms is not config yet");
        } elseif (!array_key_exists("status",$extra_config)) {
            Throw new SmsConfigException("The status for sms is not config yet");
        }
        $this->udh = $extra_config['udh'];
        $this->is_flash = $extra_config['is_flash'];
        $this->rec_id = $extra_config['rec_id'];
        $this->status = $extra_config['status'];
    }
    /**
     * This method has been used for send sms to single number
     *
     * @param $message
     * @param $mobiles, a mobile phone number
     * @return bool|mixed|string
     * @throws SmsPanelException
     * @author Mehran
     */
    public function sendSms($message, $mobiles)
    {
        try {
            $data = [
                'username' => parent::getUsername(),
                'password' => parent::getPassword(),
                'from' => parent::getRefNumber(),
                'to' => array($mobiles),
                'text' => $message,
                'isflash' => false,
                'udh' => self::getUdh(),
                'recId' => self::getRecId(),
                'status' =>  self::getStatus()
            ];
            $response = $this->sendRequest("http://sms.ariantel.ir/Post/Send.asmx?wsdl", $data, "SendSms");
            if ($response) {
                if ($response['SendSmsResult'] == 1) {
                    return $response;
                }
            }
            Throw new SmsPanelException("There some problem for send sms, the status code is : " . $response['SendSmsResult']);
        } catch (SoapFault $fault) {
            Throw new SmsPanelException("You can not use Sms panel because : " . $fault->getMessage());
        }
    }
    /**
     *  This method has been used for check credit of panel
     *
     * @return bool|mixed|string
     * @throws SmsPanelException
     * @author Mehran
     */
    public function checkCreditSMS()
    {
        try {
            $data = [
                'username' => parent::getUsername(),
                'password' => parent::getPassword()
            ];
            $response = $this->sendRequest("http://sms.ariantel.ir/Post/Send.asmx?wsdl", $data, "GetCredit");
            if ($response) {
                if (isset($response['GetCreditResult'])) {
                    return $response['GetCreditResult'];
                }
            }
            Throw new SmsPanelException("There some problem for check credit sms");
        } catch (SoapFault $fault) {
            Throw new SmsPanelException("You can not use Sms panel because : " . $fault->getMessage());
        }
    }
    /**
     * This method has been used for check expire date of panel
     *
     * @return bool|mixed|string
     * @throws SmsPanelException
     * @author Mehran
     */
    public function checkPanelExpireDate()
    {
        try {
            $data = [
                'username' => parent::getUsername(),
                'password' => parent::getPassword()
            ];
            $response = $this->sendRequest("http://sms.ariantel.ir/Post/Send.asmx?wsdl", $data, "GetExpireDate");
            if ($response) {
                if (isset($response['GetExpireDateResult'])) {
                    return $response['GetExpireDateResult'];
                }
            }
            Throw new SmsPanelException("There some problem for check credit sms");
        } catch (SoapFault $fault) {
            Throw new SmsPanelException("You can not use Sms panel because : " . $fault->getMessage());
        }
    }
    /**
     * this action has been used for send sms for several numbers
     *
     * @param $message
     * @param $mobiles
     * @return bool|mixed|string
     * @throws SmsPanelException
     * @author Mehran
     */
    public function sendGroupSms($message, $mobiles)
    {
        try {
            if (!is_array($mobiles)) {
                Throw new SmsPanelException("The number for use in sendGroup must be an array ");
            }
            $data = [
                'username' => parent::getUsername(),
                'password' => parent::getPassword(),
                'from' => parent::getRefNumber(),
                'to' => $mobiles,
                'text' => $message,
                'isflash' => false,
                'udh' => self::getUdh(),
                'recId' => self::getRecId(),
                'status' =>  self::getStatus()
            ];
            $response = $this->sendRequest("http://sms.ariantel.ir/Post/Send.asmx?wsdl", $data, "SendSms");
            if ($response) {
                if ($response['SendSmsResult'] == 1) {
                    return $response;
                }
            }
            Throw new SmsPanelException("There some problem for send sms, the status code is : " . $response['SendSmsResult']);
        } catch (SoapFault $fault) {
            Throw new SmsPanelException("You can not use Sms panel because : " . $fault->getMessage());
        }
    }
    /**
     * this action has been used for send request to ArianTel webservice
     *
     * @param $url
     * @param $data
     * @return bool|mixed|string
     * @throws SmsPanelException
     * @author Mehran
     */
    protected function sendRequest($url, $data, $method = null)
    {
        $soapClientObj = new SoapClient($url);
        $response = $soapClientObj->$method($data);
        /**
         * Convert STD Class To Php Array
         */
        $response = json_decode(json_encode($response), true);
        if ($response) {
            return $response;
        }
        Throw new SmsPanelException("There some problem in ArialTel sms panel");
    }
}
?>
