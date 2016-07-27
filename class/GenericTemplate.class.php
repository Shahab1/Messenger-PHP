<?php


class GenericTemplate extends Template {

    public function __construct($elements) {

        $this->template_type = 'generic';
        if (!is_array($elements)) $elements = array($elements);
        if (count($elements) > 10):
            $elements = array_slice($elements, 0, 10);
            $this->log('Notice: more than 10 elements passed to Generic Template. Data was truncate.');
        endif;
        foreach ($elements as $e):
            if (!($e instanceof Element)) throw new Exception('Invalid argument passed to GenericTemplate. Excpected Element object.');
        endforeach;

        $this->elements = $elements;

    } // __construct

} // GenericTemplate
