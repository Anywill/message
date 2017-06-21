<?php

define('SCRIPT_ROOT', __DIR__ . '/');require_once SCRIPT_ROOT . 'include/Client.php';require_once SCRIPT_ROOT . 'include/logger.class.php';require_once SCRIPT_ROOT . 'include/DB.class.php';class Message
{
    private $gwUrl          = 'http://sdk999ws.eucp.b2m.cn:8080/sdk/SDKService';
    private $serialNumber   = '';
    private $password       = '';
    private $sessionKey     = '';
    private $connectTimeOut = 2;
    private $readTimeOut    = 10;
    private $proxyhost      = false;
    private $proxyport      = false;
    private $proxyusername  = false;
    private $proxypassword  = false;
    private $client;

    public function __construct($serialNumber, $password)
    {
        $this->serialNumber = $serialNumber;
        $this->password = $password;
        $this->client = new Client($this->gwUrl, $this->serialNumber, $this->password, '', $this->proxyhost, $this->proxyport, $this->proxyusername, $this->proxypassword, $this->connectTimeOut, $this->readTimeOut);
        $this->client->setOutgoingEncoding('GBK');
        $this->client->sessionKey = $this->sessionKey;
    }

    /**	 * 查询余额.	 * @return float 余额
	 */
    public function getBalance()
    {
        return $this->client->getBalance();

    }

    /**	 * 发送短信	 * @param  array  $phones 发送的号码数组
	 * @param  string $text   utf-8编码的短信内容
	 * @return int    $status 返回状态值，具体查看doc下文档
	 */
    public function sendDirectMessage($phones, $text)
    {
        $conn  = DB::init();
        $sql   = $conn->prepare('insert into msg(`content`) values(?)');
        $text  = '【优提示】' . $text;
        if ($sql->execute([$text])) {
            $text   = iconv('UTF-8', 'GBK//translit', $text); //translit            $seqId   = $conn->lastInsertId();
            $status = $this->client->sendSMS($phones, $text, '', '', 'GBK', 5, $seqId);
            return [$status, $seqId];

        }
        return [-1, 0];

    }

    /**	 * 查询发送状态	 * @return 二维数组，有一个的话返回的时一个一维数组，没有的话返回的是个空字符串，切记
	 * 子数组:	 *	["errorCode"]=> 未发送成功错误编码     *	["memo"]=>  备注     *	["mobile"]=> 电话号码     *	["receiveDate"]=> 接收时间（格式为yyyy-mm-dd hi24:mi:ss）     *	["reportStatus"]=> 状态报告的值（0发送成功）     *	["seqID"]=> 唯一消息ID     *	["serviceCodeAdd"]=> 短信下行时服务号码     *	["submitDate"]=> 发送时间（格式为yyyy-mm-dd hi24:mi:ss)	 */
    public function getReportAndSave()
    {
        $reportResult = $this->client->getReport();
        if (is_array($reportResult)) {
            $conn     = DB::init();
            if (array_key_exists('seqID', $reportResult)) {
                $reportResult = [$reportResult];

            }
            foreach ($reportResult as $res) {                // seqID是8888则不做处理，不需要记录                if ($res['seqID'] == '8888') {
                continue;

            }
                $sql = $conn->prepare('insert into record(seqID,reportStatus,mobile,submitDate,receiveDate,errorCode,memo,serviceCodeAdd) values(?,?,?,?,?,?,?,?)');
                $sql->execute([                $res['seqID'],                $res['reportStatus'],                $res['mobile'],                $this->formatDatetime($res['submitDate']),                $this->formatDatetime($res['receiveDate']),                $res['errorCode'],                $res['memo'],                $res['serviceCodeAdd']            ]);

            }

        }

    }

    /**	 * 格式化时间日期字符串.	 * @param  string $datetime 要格式化的时间字符串，比如"20150525211125"
	 * @return string 返回格式化后的时间字符串，比如"2015-05-25 21:11:25"，或者原字符串
	 */
    public function formatDatetime($datetime)
    {
        if (strlen($datetime) == 14) {
            $formatDatetime = '';
            $formatDatetime .= substr($datetime, 0, 4) . '-';
            $formatDatetime .= substr($datetime, 4, 2) . '-';
            $formatDatetime .= substr($datetime, 6, 2) . ' ';
            $formatDatetime .= substr($datetime, 8, 2) . ':';
            $formatDatetime .= substr($datetime, 10, 2) . ':';
            $formatDatetime .= substr($datetime, 12);
            return $formatDatetime;

        }
        return $datetime;

    }
}
