<?php


class ButtonTemplate extends Template {

    // text
    // array of buttons
    public function __construct($text, $buttons) {

        $this->template_type = 'button';
        $this->text = $text;
        if (!is_array($buttons)) $buttons = array($buttons);
        foreach ($buttons as $b):
            if (!($b instanceof Button)) throw new Exception('Invalid argument to ButtonTemplate. Expected array of Button objects.');
        endforeach;
        if (count($buttons) > 3):
            $this->log('Noticed: more than 3 buttons passed. Data was truncated.');
            $buttons = array_slice($buttons, 0, 3);
        endif;

        $this->buttons = $buttons;

    } // __construct

} // ButtonTemplate
