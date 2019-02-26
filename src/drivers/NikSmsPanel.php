<?php
namespace mehrand\sms_panel\drivers;
use mehrand\sms_panel\exceptions\SmsConfigException;
use mehrand\sms_panel\exceptions\SmsPanelException;
use mehrand\sms_panel\modules\sms\abstracts\SmsHandler;
/**
 * Class NikSmsPanel
 * @package app\modules\sms\models
 * @author Mehran
 */
class NikSmsPanel extends SmsHandler
{
    /**
     * @var $sendOn
     */
    private $send_on;
    /**
     * @var $sendType
     */
    private $send_type;
    /**
     * @var $message_ids
     */
    private $message_ids;
    /**
     * NikSmsPanel constructor.
     *
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
     * @return mixed
     * @author Mehran
     */
    protected function getSendOn()
    {
        return $this->send_on;
    }
    /**
     * @return mixed
     * @author Mehran
     */
    protected function getSendType()
    {
        return $this->send_type;
    }
    /**
     * @return mixed
     * @author Mehran
     */
    protected function getMessageIds()
    {
        return $this->message_ids;
    }
    /**
     * @return mixed
     * @author Mehran
     */
    protected function setSendOn($send_on)
    {
        $this->send_on = $send_on;
    }
    /**
     * @return mixed
     * @author Mehran
     */
    protected function setMessageIds($message_ids)
    {
        $this->message_ids = $message_ids;
    }
    /**
     * @return mixed
     * @author Mehran
     */
    protected function setSendType($send_type)
    {
        $this->send_type = $send_type;
    }
    /**
     * This method has been used for check configuration of sms for NikSms panel
     *
     * @param array $config
     * @param array $extra_config
     * @throws SmsConfigException
     * @author Mehran
     */
    protected function checkSmsConfig(array $config, array $extra_config = [])
    {
        parent::checkSmsConfig($config, $extra_config);
        if (!array_key_exists("send_on",$extra_config)) {
            Throw new SmsConfigException("The SendOn for sms is not config yet");
        } elseif (!array_key_exists("send_type",$extra_config)) {
            Throw new SmsConfigException("The SendType for sms is not config yet");
        } elseif (!array_key_exists("message_ids",$extra_config)) {
            Throw new SmsConfigException("The MessageIds for sms is not config yet");
        }
        $this->send_on = $extra_config['send_on'];
        $this->send_type = $extra_config['send_type'];
        $this->message_ids = $extra_config['message_ids'];
    }
    /**
     * This method has been used for send sms to single number
     *
     * @param $url
     * @param $data
     * @return bool|mixed|string
     * @throws SmsPanelException
     * @author Mehran
     */
    protected function sendRequest($url, $data, $method = null)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "cache-control: no-cache"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        if ($response) {
            return $response;
        }
        Throw new SmsPanelException("There is some problem for send request to webservice");
    }
    /**
     * This action has been written for send Sms to users
     *
     * @param $message
     * @param $mobile
     * @return bool|mixed|string
     * @throws SmsPanelException
     * @author Mehran
     */
    public function sendSms($message, $mobile)
    {
        $url = "http://niksms.com/fa/PublicApi/PtpSms";
        $data = [
            'username' => parent::getUsername(),
            'password' => parent::getPassword(),
            'message' => $message,
            'numbers' => $mobile,
            'senderNumber' => parent::getRefNumber(),
            'sendOn' => self::getSendOn(),
            'yourMessageIds' => self::getMessageIds(),
            'sendType' => self::getSendType()
        ];
        $response = $this->sendRequest($url, $data);
        $response = json_decode($response);
        if ($response->Status != 1) {
            Throw new SmsPanelException('The sms can not send, The status code is: ' . $response->Status);
        }
        return $response;
    }
    /**
     * This method has been used for check credit of a panel
     *
     * @return bool|mixed|string
     * @throws SmsPanelException
     * @author Mehran
     */
    public function checkCreditSMS()
    {
        $url = "http://niksms.com/fa/publicapi/getCredit";
        $data = [
            'username' => parent::getUsername(),
            'password' => parent::getPassword(),
        ];
        $response = $this->sendRequest($url, $data);
        if ($response == null) {
            Throw new SmsPanelException('U can not check credit sms for this username.');
        }
        return $response;
    }
    /**
     * This method has been used for check expire date panel
     *
     * @return bool|mixed|string
     * @throws SmsPanelException
     * @author Mehran
     */
    public function checkPanelExpireDate()
    {
        $url = "http://niksms.com/fa/publicapi/getPanelExpireDate";
        $data = [
            'username' => parent::getUsername(),
            'password' => parent::getPassword(),
        ];
        $response = $this->sendRequest($url, $data);
        if ($response == null) {
            Throw new SmsPanelException('U can not check expire date sms panel for this username.');
        }
        return $response;
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
        $url = "http://niksms.com/fa/PublicApi/groupSms";
        $data = array(
            'username' => parent::getUsername(),
            'password' => parent::getPassword(),
            'message' => $message,
            'numbers' => $mobiles,
            'senderNumber' => parent::getRefNumber(),
            'sendOn' => self::getSendOn(),
            'yourMessageIds' => self::getMessageIds(),
            'sendType' => self::getSendType()
        );
        $response = $this->sendRequest($url, $data);
        if ($response->Status != 1) {
            Throw new SmsPanelException('The sms can not send, The status code is: ' . $response->Status);
        }
        return $response;
    }
}
?>
