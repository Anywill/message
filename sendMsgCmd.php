<?php
/**
 * 供其他进程命令行调用
 * utf-8.
 * @param $argv[1] 要发送短信的号码，单个号码
 * @param $argv[2] 要发送的短信内容
 */
require_once 'Message.php';

$msg   = new Message();
$phone = $argv[1];
$text  = $argv[2];
$msg->sendDirectMessage([$phone], $text, false);
