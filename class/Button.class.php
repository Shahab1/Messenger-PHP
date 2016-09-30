<?php


class Button {

    public $type;
    public $title;

    public function __construct($type, $title, $payload, $ext = false, $wv_height_ratio = 'compact') {

        if ($type != 'web_url' && $type != 'postback' && $type != 'phone_number') throw new Exception('Invalid type');
        if (empty($title)) throw new Exception('Empty button title');
        $this->type = $type;
        $this->title = $title;
        if ($type == 'web_url'):
            if (!filter_var($payload, FILTER_VALIDATE_URL)) throw new Exception('Invalid URL');
            $this->url = $payload;
            if ($ext):
                $this->messenger_extensions = true;
                $this->webview_height_ratio = $wv_height_ratio;
            endif;
        else:
            if (empty($payload)) throw new Exception('Empty payload');
            $this->payload = $payload;
        endif;

    } // __construct

} // Button
