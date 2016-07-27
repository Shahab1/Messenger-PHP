<?php

require 'class/BotApp.class.php';


class TestBot extends BotApp {


    public function receivedMessage($msg) {
    } // receivedMessage


    public function receivedPostback($msg) {
        switch ($msg->postback->payload):
        endswitch;
    } // receivedPostback


    public function receivedQuickreply($msg) {
    } // receivedQuickreply


    public function receivedEcho($msg) {
    } // receivedEcho


    public function receivedUnknown($msg) {
    } // receivedEcho


    public function receivedDelivery($msg) {
    } // receivedEcho


} // TestBot


$config = array(
    'verify_token' => 'xxxxxxxxx',
    'page_access_token' => 'yyyyyyyy',
    'debug' => true
);

$bot = new TestBot($config);
$bot->run();

