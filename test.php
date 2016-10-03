<?php

require 'class/BotApp.class.php';


class TestBot extends BotApp {

    // This method is called when a text message is received
    public function receivedMessage($msg) {
        // These are just examples of how to send different types of messages

        // Simple text
        $this->sendText('Hello World of Bots!');

        // Simple image (use sendVideo(), sendAudio() and sendFile() for videos, audios and files)
        $this->sendImage('https://example.com/image.jpg');

        // Button template: https://developers.facebook.com/docs/messenger-platform/send-api-reference/button-template
        $this->sendTemplate(
            new ButtonTemplate(
                'Please press one of the buttons below.',
                array(
                    new Button('web_url', 'Open URL', 'http://example.com/'),
                    new Button('postback', 'Button 1', 'BUTTON_1'),
                    new Button('postback', 'Button 2', 'BUTTON_2')
                )
            )
        );

        // Generic Template: https://developers.facebook.com/docs/messenger-platform/send-api-reference/generic-template
        $this->sendTemplate(
            new GenericTemplate(
                new Element(
                    'Title here',
                    'Your cool subtitle enters here',
                    'http://example.com/image.jpg',
                    array(
                        new Button('web_url', 'Open URL', 'http://example.com/'),
                        new Button('postback', 'Button 1', 'BUTTON_1'),
                        new Button('postback', 'Button 2', 'BUTTON_2')
                    )
                )
            )
        );

        // Quick Replies: https://developers.facebook.com/docs/messenger-platform/send-api-reference/quick-replies
        $this->sendQuickReply(
            new Attachment('image', new Payload('http://example.com/image.jpg')),
            array(
                new QuickReply('text', 'Option 1', 'OPT_1'),
                new QuickReply('text', 'Option 2', 'OPT_2')
            )
        );

    } // receivedMessage

    public function receivedUnknown($msg) {
    } // receivedUnknown

    // This is called when a postback is received. You can use a switch to take different actions for different postbacks
    public function receivedPostback($msg) {
        switch ($msg->postback->payload):
        case 'BUTTON_1':
            $this->sendText('You pressed button 1');
            break;
        case 'BUTTON_2':
            $this->sendText('You pressed button 2');
            break;
        default:
        endswitch;
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

