<?php
/**
 * 发送短信的demo.
 */
require_once 'Message.php';

$msg = new Message();
$msg->sendDirectMessage(['13824406962'], '这里是要发送的短信', false);
