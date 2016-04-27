<?php

class Template {
    public static $tableContent ='';
    public static function render($template, $data)
    {
        // registered passed variables as local variables
        extract($data);

        // load passed template and store contents for usage in layout
        ob_start();
        include(TEMPLATES_PATH . '/' . $template .'.php');
        if ($template!=='login') {
            include(TEMPLATES_PATH . '/menuBar.php');
            include(TEMPLATES_PATH . '/rss.php');
            include(TEMPLATES_PATH . '/rightBar.php');
        }
        $content_for_layout = ob_get_clean();

        // load and show layout
        include(TEMPLATES_PATH . "/layout.php");
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
