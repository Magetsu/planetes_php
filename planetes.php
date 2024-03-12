<?php

header("Content-Type: text/html; charset=utf-8");
ini_set("error_reporting", E_ALL | E_STRICT);
ini_set("display_errors", 1);

require_once("logging.php");
require_once("html.php");

session_start();

if(isset($_POST["comet"])) {
  
    LOGARRAY($_POST);
    
    if (GET_LOGGING()) {
        
        LOGTEXT("Siirtyminen komeettasivulle POST:lla<br>");
        LOGTEXT("<p>Jatka <a href=\"comet.php\">komeettasivulle</a>.</p>\n");
        exit;
        
    } else {
        
        redirect("comet.php");
        exit;
    }
    
} else if (isset($_POST["asteroid"])) {
    
    LOGARRAY($_POST);
    
    if (GET_LOGGING()) {
        
        LOGTEXT("Siirtyminen pikkuplaneettasivulle POST:lla<br>");
        LOGTEXT("<p>Jatka <a href=\"asteroid.php\">pikkuplaneettasivulle</a>.</p>\n");
        exit;
        
    } else {
        
        redirect("asteroid.php");
        exit;
    }
}

redirect("comet.php");

?>