<?php

class CallButton extends Button {

    public function __construct($title, $payload) {
        $this->type = "phone_number";
        $this->title = $title;
        $this->payload = $payload;
    } // __construct

} // CallButton
