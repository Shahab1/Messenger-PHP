<?php

class QuickReply {

    public $content_type = 'text';
    public $title;
    public $payload;

    public function __construct($type, $title, $payload) {
        $this->content_type = $type;
        if ($type != 'location'):
            $this->title = $title;
            $this->payload = $payload;
        endif;
    } // __construct

} // QuickReply
