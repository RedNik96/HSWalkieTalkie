<?php

class Template {
    public static $tableContent ='';
    public static function render($template, $data)
    {
        // registered passed variables as local variables
        extract($data);

        // load passed template and store contents for usage in layout
        ob_start();
        include('templates/' . $template .'.php');
        if ($template!=='login') {
            include('templates/menuBar.php');
            include('templates/rss.php');
            include('templates/rightBar.php');
        }
        $content_for_layout = ob_get_clean();

        // load and show layout
        include('templates/layout.php');
    }
    public static function addLine($data)
    {
        // registered passed variables as local variables
        extract($data);

        // load passed template and store contents for usage in layout
        ob_start();
        include('templates/list.php');

        self::$tableContent= self::$tableContent . ob_get_clean();

    }
    public static function deleteRows() {
        self::$tableContent=null;
    }
}
