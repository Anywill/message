<?php

define('SCRIPT_ROOT', __DIR__ . '/');
{
    private $gwUrl          = 'http://sdk999ws.eucp.b2m.cn:8080/sdk/SDKService';
    private $serialNumber   = '9SDK-EMY-0999-JERSQ';
    private $password       = '364446';
    private $sessionKey     = '798745';
    private $connectTimeOut = 2;
    private $readTimeOut    = 10;
    private $proxyhost      = false;
    private $proxyport      = false;
    private $proxyusername  = false;
    private $proxypassword  = false;
    private $client;

    public function __construct()
    {
        $this->client = new Client($this->gwUrl, $this->serialNumber, $this->password, '', $this->proxyhost, $this->proxyport, $this->proxyusername, $this->proxypassword, $this->connectTimeOut, $this->readTimeOut);
        $this->client->setOutgoingEncoding('GBK');
        $this->client->sessionKey = $this->sessionKey;
    }


	 */







	 * @param  string $text   utf-8编码的短信内容
	 * @return int    $status 返回状态值，具体查看doc下文档
	 */
















	 * 子数组:























	 * @return string 返回格式化后的时间字符串，比如"2015-05-25 21:11:25"，或者原字符串
	 */
















}