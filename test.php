<?php

require 'class/BotApp.class.php';


class TestBot extends BotApp {

    public function receivedUnknown($msg) {
    } // receivedUnknown

    public function receivedPostback($msg) {
    } // receivedPostback

    public function receivedDelivery($msg) {
    } // receivedDelivery

    public function receivedAuthentication($msg) {
    } // receivedAuthentication

    public function receivedLocation($msg) {
    } // receivedLocation

    public function receivedFile($msg) {
    } // receivedFile

    public function receivedAudio($msg) {
    } // receivedAudio

    public function receivedVideo($msg) {
    } // receivedVideo

    public function receivedImage($msg) {
    } // receivedImage

    public function receivedMessage($msg) {
    } // receivedMessage

    public function receivedEcho($msg) {
    } // receivedEcho

    public function receivedQuickreply($msg) {
    } // receivedQuickreply

} // TestBot


$config = array(
    'verify_token' => 'xxxx',
    'page_access_token' => 'yyyy',
    'debug' => true
);

$bot = new TestBot($config);
$bot->run();

