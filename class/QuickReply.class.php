<?php

class QuickReply {

    public $content_type = 'text';
    public $title;
    public $payload;

    public function __construct($title, $payload) {
        $this->title = $title;
        $this->payload = $payload;
    } // __construct

} // QuickReply
