<?php

header("Content-Type: text/html; charset=utf-8");
ini_set("error_reporting", E_ALL | E_STRICT);
ini_set("display_errors", 1);

require_once("sql.php");
require_once("logging.php");
require_once("html.php");

$username = htmlspecialchars($_POST['username']);
$password = htmlspecialchars($_POST['password']);

LOGTEXT("Admin tunnus : ".$username);
LOGTEXT("Admin salasana : ".$password);

//$password = sha1( $password );

$connection = connect_to_database();

$user_id = 0;

try {
    $user = array($username,$password);
    
    $sql = "SELECT user_id, username, password FROM accounts WHERE username = ? AND password = ?";
    
    LOGTEXT("Luodaan kysely : SELECT user_id, username, password FROM accounts WHERE username = ".$username." AND password = ".$password);
    
    $query = $connection->prepare($sql);
    
    $query->execute($user);
    
    $user_id = $query->fetchColumn();
    
} catch(Exception $e) {
    /*** if we are here, something has gone wrong with the database ***/
    echo 'We are unable to process your request. Please try again later';
}

LOGTEXT("Käyttäjä ID : ".$user_id);

if($user_id == 0) {
    
    LOGTEXT("Kirjautuminen epäonnistui");
    LOGTEXT("<p>Palaa <a href=\"planetes.php\">pääsivulle</a>.</p>");
    
    if (!GET_LOGGING())
        redirect('planetes.php');
    exit;
    
} else {
    
    LOGTEXT("Kirjautuminen onnistui");
    LOGTEXT("<p>Jatka <a href=\"upload.php\">lataamissivulle</a>.</p>");
    
    if (!GET_LOGGING())
        redirect('upload.php');
    exit;
}

?>