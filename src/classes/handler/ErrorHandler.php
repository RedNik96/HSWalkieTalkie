<?php


class ErrorHandler
{
    public static function get() {
        $templates=array(
            'template_right' => null,
            'template_left' => null,
            'template_top' => null
        );
        $data=array();
        Template::render('error',$data,$templates);
    }
}