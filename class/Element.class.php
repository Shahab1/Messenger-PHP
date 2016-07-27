<?php

class Element {

    public $title;
    public $subtitle;
    public $image_url;

    public function __construct($title, $subtitle, $image, $buttons) {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->image_url = $image;
        $this->buttons = $buttons;
    } // __construct

} // Element
