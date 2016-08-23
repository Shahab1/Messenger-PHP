<?php

// Templates
require 'Template.class.php';
require 'ButtonTemplate.class.php';
require 'GenericTemplate.class.php';

// Elements
require 'Element.class.php';
require 'Button.class.php';

// Misc classes
require 'Attachment.class.php';
require 'QuickReply.class.php';
require 'Payload.class.php';

class BotApp {


    private $verify_token;
    private $page_access_token;
    private $session;
    private $json;
    private $request;
    private $sender_id;


    public function __construct(array $config) {

        if (!isset($config['verify_token'])) throw new Exception('Missing verify_token in configuration');
        if (!isset($config['page_access_token'])) throw new Exception('Missing page_access_token in configuration');
        if (!function_exists('curl_init')) throw new Exception('cURL extension is needed');

        $this->verify_token = $config['verify_token'];
        $this->page_access_token = $config['page_access_token'];
        $this->debug = (isset($config['debug']) && $config['debug']);
        $this->log_file = (isset($config['log_file'])) ? $config['log_file'] : 'bot.log';

        $this->checkValidation();

        // message parsing and handling
        $this->json = file_get_contents('php://input');
        $this->request = json_decode($this->json);
        if (!is_object($this->request)):
            $this->log('Unknown stuff received: '.$this->json);
            exit;
        endif;

        // Manual session management - they don't give cookies :(
        $this->sender_id = $this->request->entry[0]->messaging[0]->sender->id; //FIXME: Messy, messy code
        session_id(get_class($this).$this->sender_id);
        session_start();
        $this->session = $_SESSION;
        if (empty($this->session)):
            $this->session = array();
        endif;

    } // __construct


    /* destructor. saves the session. */
    public function __destruct() {

        $_SESSION = $this->session;
        session_write_close();

    } // __destruct


    /* Bot webhook validation */
    private function checkValidation() {

        if (isset($_GET['hub_mode']) && $_GET['hub_mode'] == 'subscribe'):
            if (!isset($_GET['hub_verify_token']) || !isset($_GET['hub_challenge'])) exit;
            if ($_GET['hub_verify_token'] == $this->verify_token):
                print $_GET['hub_challenge'];
                exit;
            endif;
        endif;

    } // checkValidation


    public function getSession($key) {

        if (!isset($this->session[$key])) return null;
        return $this->session[$key];

    } // getSession


    public function setSession($key, $val) {

        $this->session[$key] = $val;

    } // setSession


    public function run() {

        // dispatch the request to the appropriate handler
        if ($this->request->object == 'page'):
            foreach ($this->request->entry as $entry):
                $page_id = $entry->id;
                $ts = $entry->time;
                foreach ($entry->messaging as $msg):
                    if (isset($msg->message)):
                        if (isset($msg->message->is_echo) && $msg->message->is_echo):
                            $this->log("Received ECHO: ".json_encode($msg));
                            $this->receivedEcho($msg);
                        elseif (isset($msg->message->quick_reply)):
                            $this->log("Received QUICKREPLY: ".json_encode($msg));
                            $this->receivedQuickreply($msg);
                        elseif (!isset($msg->message->attachments)):
                            $this->log("Received TEXT: ".json_encode($msg));
                            $this->receivedMessage($msg);
                        elseif (isset($msg->message->attachments)):
                            foreach ($message->attachments as $att):
                                switch ($att->type):
                                case 'image':
                                    $this->log("Received IMAGE: ".$att->payload->url);
                                    $this->receivedImage($msg);
                                    break;
                                case 'video':
                                    $this->log("Received VIDEO: ".$att->payload->url);
                                    $this->receivedVideo($msg);
                                    break;
                                case 'audio':
                                    $this->log("Received AUDIO: ".$att->payload->url);
                                    $this->receivedAudio($msg);
                                    break;
                                case 'file':
                                    $this->log("Received FILE: ".$att->payload->url);
                                    $this->receivedFile($msg);
                                    break;
                                case 'location':
                                    $this->log("Received COORDINATES: ".$att->payload->coordinates['lat'].",".$att->payload->coordinates['long']);
                                    $this->receivedLocation($msg);
                                    break;
                                endswitch;
                            endforeach;
                        endif;
                    elseif (isset($msg->optin)):
                        $this->log("Received OPTIN: ".json_encode($msg));
                        $this->receivedAuthentication($msg);
                    elseif (isset($msg->delivery)):
                        $this->log("Received DELIVERY: ".json_encode($msg));
                        $this->receivedDelivery($msg);
                    elseif (isset($msg->postback)):
                        $this->log("Received POSTBACK: ".json_encode($msg));
                        $this->receivedPostback($msg);
                    else:
                        $this->log("Received UNKNOWN: ".json_encode($msg));
                        $this->receivedUnknown($msg);
                    endif;
                endforeach;
            endforeach;
        endif;

    } // run


    /* low-level sending method */
    private function send($json) {

        $this->log("SENDING: {$json}");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v2.6/me/messages?access_token='.$this->page_access_token);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: '.strlen($json)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ret = curl_exec($ch);
        $this->log("RESPONSE: {$ret}");

    } // send


    /* content can be a string or an Attachment object */
    public function sendQuickReply($content, $quick_replies) {

        $obj = new StdClass();
        $obj->recipient = new StdClass();
        $obj->recipient->id = $this->sender_id;
        $obj->message = new StdClass();
        if ($content instanceof Attachment):
            $obj->message->attachment = $content;
        else:
            $obj->message->text = $text;
        endif;
        $obj->message->quick_replies = $quick_replies;
        $json = json_encode($obj);

        $this->send($json);

    } // sendQuickReply


    public function sendText($txt) {

        $obj = new StdClass();
        $obj->recipient = new StdClass();
        $obj->recipient->id = $this->sender_id;
        $obj->message = new StdClass();
        $obj->message->text = $txt;
        $json = json_encode($obj);

        $this->send($json);

    } // sendText


    public function sendTemplate(Template $template) {

        $obj = new StdClass();
        $obj->recipient = new StdClass();
        $obj->recipient->id = $this->sender_id;
        $obj->message = new StdClass();
        $obj->message->attachment = new Attachment(
            'template',
            $template
        );
        $json = json_encode($obj);

        $this->send($json);

    } // sendTemplate


    public function sendAttachment(Attachment $att) {

        $obj = new StdClass();
        $obj->recipient = new StdClass();
        $obj->recipient->id = $this->sender_id;
        $obj->message = new StdClass();
        $obj->message->attachment = $att;
        $json = json_encode($obj);

        $this->send($json);

    } // sendAttachment


    public function sendFile($url) {

        $att = new Attachment('file', new Payload($url));
        $this->sendAttachment($att);

    } // sendFile


    public function sendAudio($url) {

        $att = new Attachment('audio', new Payload($url));
        $this->sendAttachment($att);

    } // sendAudio


    public function sendVideo($url) {

        $att = new Attachment('video', new Payload($url));
        $this->sendAttachment($att);

    } // sendVideo


    public function sendImage($url) {

        $att = new Attachment('image', new Payload($url));
        $this->sendAttachment($att);

    } // sendImage


    protected function log($txt) {

        if (!$this->debug) return;

        $fd = fopen($this->log_file, 'a');
        fwrite($fd, date('r')."\n");
        fwrite($fd, $txt."\n\n");
        fclose($fd);

    } // log


    /* these functions should be implemented in the child class */
    public function receivedUnknown($msg) {}
    public function receivedPostback($msg) {}
    public function receivedDelivery($msg) {}
    public function receivedAuthentication($msg) {}
    public function receivedLocation($msg) {}
    public function receivedFile($msg) {}
    public function receivedAudio($msg) {}
    public function receivedVideo($msg) {}
    public function receivedImage($msg) {}
    public function receivedMessage($msg) {}
    public function receivedEcho($msg) {}
    public function receivedQuickreply($msg) {}

} // BotApp
