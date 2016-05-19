<?php

/**
 * Class EscapeUtil stellt eine Funktion bereit die ein übergebenes Array mit htmlspecialchars escapet
 */
class EscapeUtil
{
    /**
     * @param $variable Array das escapet werden soll
     * escaped alle Dateien in dem übergebenen Array mithilfe von htmlspecialchars
     */
    public static function escapeArray(&$variable) {
        foreach ($variable as &$value) {
            if (!is_array($value)) {
                $value = htmlspecialchars($value);
            }
            else {
                $value=escape_array($value);
            }
        }
        //return $variable;
    }

    public static function escapeArrayReturn($data) {
      foreach ((array)$data as $value) {
          if (!is_array($value)) {
              $value = htmlspecialchars($value);
          }
          else {
              $value=escape_array($value);
          }
      }
      return $data;
    }

}
?>
