<?php

class Attachment {

    public $type;
    public $payload;

    public function __construct($type, $payload) {
        $this->type = $type;
        $this->payload = $payload;
    } // __construct

} // Attachment
