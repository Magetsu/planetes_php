<?php

require_once("html.php");
require_once("logging.php");
require_once("sql.php");

session_start();

define ("RESULTS_IN_PAGE", 1000);
define ("MINIMUM_PERIHELION",0);
define ("MAXIMUM_PERIHELION",10000);
define ("MINIMUM_ECCENTRICITY",0);
define ("MAXIMUM_ECCENTRICITY",2);
define ("MINIMUM_INCLINATION",0);
define ("MAXIMUM_INCLINATION",360);
define ("MINIMUM_PERARG",0);
define ("MAXIMUM_PERARG",360);
define ("MINIMUM_NODE",0);
define ("MAXIMUM_NODE",360);

$pagenumber = 0;
$query = "";

$_SESSION["perihelion_error"] = "";
$_SESSION["eccentricity_error"] = "";
$_SESSION["inclination_error"] = "";
$_SESSION["perarg_error"] = "";
$_SESSION["node_error"] = "";

$perihelionRange = array(
    'options' => array(
        'min_range' => MINIMUM_PERIHELION,
        'max_range' => MAXIMUM_PERIHELION
    ));

$eccentricityRange = array(
    'options' => array(
        'min_range' => MINIMUM_ECCENTRICITY,
        'max_range' => MAXIMUM_ECCENTRICITY
    ));

$inclinationRange = array(
    'options' => array(
        'min_range' => MINIMUM_INCLINATION,
        'max_range' => MAXIMUM_INCLINATION
    ));

$perargRange = array(
    'options' => array(
        'min_range' => MINIMUM_PERARG,
        'max_range' => MAXIMUM_PERARG
    ));

$nodeRange = array(
    'options' => array(
        'min_range' => MINIMUM_NODE,
        'max_range' => MAXIMUM_NODE
    ));


if(isset($_GET["page"]) && ctype_digit($_GET["page"])) {
    
    $pagenumber = $_GET["page"];

    if ($_SESSION["comet_constraints_search"]) {
        
        $query = $_SESSION["comet_constraints_query"];
        
    } else if ($_SESSION["comet_name_search"])  {
        
        $query = $_SESSION["comet_name_query"];
    }
    
}

if(isset($_POST["comet_search"])) {
 
    LOGTEXT("POST[comet_search]) : Haetaan komeettatietokannasta rajauksella");

    $constraintsCount = 0;
    $_SESSION["comet_name"] = "";
    clear_comet_fields();
    
    if ($_POST["minper"] || $_POST["maxper"]) {
        
        LOGTEXT("POST[comet_search]) : Haetaan käyttäjältä rajaukset periheli : ".$_POST["minper"]."-".$_POST["maxper"]);
        
        $perihelionQuery = check_limits("q", $perihelionRange, $_POST["minper"], $_POST["maxper"]);
      
        if ($perihelionQuery == "error") {
            
            $_SESSION["perihelion_error"] = "Virhe tiedoissa!";
        }
        
        $_SESSION["minumum_perihelion"] = $_POST["minper"];
        $_SESSION["maximum_perihelion"] = $_POST["maxper"];
        
        $query .= $perihelionQuery;
        
        ++$constraintsCount;
    }
    
    if ($_POST["minecc"] || $_POST["maxecc"]) {
        
        LOGTEXT("POST[comet_search]) : Haetaan käyttäjältä rajaukset eksentrisyys : ".$_POST["minecc"]."-".$_POST["maxecc"]);
        
        if ($constraintsCount > 0) {
            
            $query .= " AND ";
        }
        
        $eccentricityQuery = check_limits("e", $eccentricityRange, $_POST["minecc"], $_POST["maxecc"]);
        
        if ($eccentricityQuery == "error") {
            
            $_SESSION["eccentricity_error"] = "Virhe tiedoissa!";
        }
        
        $_SESSION["minumum_eccentricity"] = $_POST["minecc"];
        $_SESSION["maximum_eccentricity"] = $_POST["maxecc"];
        
        $query .= $eccentricityQuery;
        
        ++$constraintsCount;
    }
    
    if ($_POST["mininc"] || $_POST["maxinc"]) {
        
        LOGTEXT("POST[comet_search]) : Haetaan käyttäjältä rajaukset inklinaatio : ".$_POST["mininc"]."-".$_POST["maxinc"]);
        
        if ($constraintsCount > 0) {
            
            $query .= " AND ";
        }
        
        $inclinationQuery = check_limits("i", $inclinationRange, $_POST["mininc"], $_POST["maxinc"]);
        
        if ($inclinationQuery == "error") {
            
            
            $_SESSION["inclination_error"] = "Virhe tiedoissa!";
                
        }
        
        $_SESSION["minumum_inclination"] = $_POST["mininc"];
        $_SESSION["maximum_inclination"] = $_POST["maxinc"];
        
        $query .= $inclinationQuery;
        
        ++$constraintsCount;
    }
    
    if ($_POST["minperarg"] || $_POST["maxperarg"]) {
        
        LOGTEXT("POST[comet_search]) : Haetaan käyttäjältä rajaukset perihelin argumentti : ".$_POST["minperarg"]."-".$_POST["maxperarg"]);
        
        if ($constraintsCount > 0) {
            
            $query .= " AND ";
        }
        
        $perargQuery = check_limits("w", $perargRange, $_POST["minperarg"], $_POST["maxperarg"]);
        
        if ($perargQuery == "error") {
            
            $_SESSION["perarg_error"] = "Virhe tiedoissa!";
        }
        
        $_SESSION["minumum_perihelionarg"] = $_POST["minperarg"];
        $_SESSION["maximum_perihelionarg"] = $_POST["maxperarg"];
        
        $query .= $perargQuery;
        
        ++$constraintsCount;
    }
    
    if ($_POST["minnode"] || $_POST["maxnode"]) {
        
        LOGTEXT("POST[comet_search]) : Haetaan käyttäjältä rajaukset perihelin nousevan solmun pituus : ".$_POST["minnode"]."-".$_POST["maxnode"]);
        
        if ($constraintsCount > 0) {
            
            $query .= " AND ";
        }
        
        $nodeQuery = check_limits("node", $nodeRange, $_POST["minnode"], $_POST["maxnode"]);
        
        if ($nodeQuery == "error") {
            
            $_SESSION["node_error"] = "Virhe tiedoissa!";
        }
        
        $_SESSION["minumum_node"] = $_POST["minnode"];
        $_SESSION["maximum_node"] = $_POST["maxnode"];
        
        $query .= $nodeQuery;       
        
        }

    if ($perihelionQuery == "error" ||
        $eccentricityQuery == "error" ||
        $inclinationQuery == "error" ||
        $perargQuery == "error" ||
        $nodeQuery == "error") {
            
            $query = "";
        }
        
    if ($perihelionQuery == "" &&
        $eccentricityQuery == "" &&
        $inclinationQuery == "" &&
        $perargQuery == "" &&
        $nodeQuery == "") {
            
            clear_comet_fields();
        }
    
    $_SESSION["comet_constraints_query"] = $query;
    $_SESSION["comet_constraints_search"] = true;

    LOGTEXT("POST[comet_search]) : Hakulauseke on : ".$query);
}

if(isset($_POST["comet_name"])) {
    
    LOGTEXT("POST[comet_name]) : Haetaan komeettatietokannasta nimellä ".$_POST["name"]);
    
    clear_comet_fields();
    
    $query = " name LIKE '%".$_POST["name"]."%'";

    LOGTEXT("_POST[comet_name]) : Hakulauseke on : ".$query);
    
    $_SESSION["comet_name"] = $_POST["name"];
    $_SESSION["comet_name_query"] = $query;
    $_SESSION["comet_name_search"] = true;
    
    if ($_POST["name"] = "") {
        
        $_SESSION["comet_name"] = "";
        $_SESSION["comet_name_search"] = false;
    }
    
}

$limit = intval(RESULTS_IN_PAGE);
$offset = intval($pagenumber * RESULTS_IN_PAGE);

$comet_count = get_comet_count($query);
$pages = ceil($comet_count/RESULTS_IN_PAGE);

LOGTEXT("Sivujen määrä : ".$pages);

luo_html_alku("Planetes");

create_html_header_panel();
create_html_admin_button();
create_html_count_panel(get_comet_count(null),get_asteroid_count(null));
create_html_selection_panel();
create_html_comet_constraints_panel();
create_html_footer_panel("komeettaa", $comet_count);

$comet = get_comet_database($query, $limit, $offset);
create_html_comet_data_panel($pages, $pagenumber, $comet);

luo_html_loppu();

function get_comet_database($query, $limit, $offset) {
    
    LOGTEXT("GET_COMET_DATABASE : Haetaan komeettatietokanta muistiin");
    
    $connection = connect_to_database();
    
    if (check_database($connection)) {
        
        // Haetaan kometat tietokannasta
        $cometsql = fetch_comet_database($query, $limit, $offset);
        
    } else if(!check_database($connection)){
        
        // Tietokantaa ei ole joten luodaan se
        create_comet_database($connection);
    }
    
    return $cometsql;
}

function check_limits($constraint, $range, $min, $max) {
    
    LOGTEXT("CHECK_LIMITS : ".$constraint." tarkistetaan arvot : ".$min." - ".$max);
       
    if(filter_var($min, FILTER_VALIDATE_FLOAT,$range) && filter_var($max, FILTER_VALIDATE_FLOAT,$range)) {
        
        LOGTEXT("CHECK_LIMITS : ".$constraint." Minimi ja maksimi ovat numeroita ja ovat rajojen sisällä");
        
        $string = $constraint." BETWEEN ".$min." AND ".$max;
        
        LOGTEXT("CHECK_LIMITS : ".$constraint." Lausekelisäys on ".$string);
        
        return $string;
     
    } else if ($min == null && filter_var($max, FILTER_VALIDATE_FLOAT,$range)) {
        
        LOGTEXT("CHECK_LIMITS : ".$constraint." Maksimi on numero ja on rajojen sisällä");
        
        $string = $constraint." < ".$max;
        
        LOGTEXT("CHECK_LIMITS : ".$constraint." Lausekelisäys on ".$string);
        
        return $string;
    
    } else if (filter_var($min, FILTER_VALIDATE_FLOAT,$range) && $max == null) {
        
        LOGTEXT("CHECK_LIMITS : ".$constraint." Minimi on numero ja on rajojen sisällä");
        
        $string = $constraint." > ".$min;
               
        LOGTEXT("CHECK_LIMITS : ".$constraint." Lausekelisäys on ".$string);
        
        return $string;
        
    } else {
        
        LOGTEXT("CHECK_LIMITS : ".$constraint." Arvossa on virhe");
        return "error";
    }
}

function clear_comet_fields() {
    
    LOGTEXT("CLEAR_COMET_FIELDS : Tyhjennetään rajauskentät");
    
    $_SESSION["minumum_perihelion"] = "";
    $_SESSION["maximum_perihelion"] = "";
    $_SESSION["minumum_eccentricity"] = "";
    $_SESSION["maximum_eccentricity"] = "";
    $_SESSION["minumum_inclination"] = "";
    $_SESSION["maximum_inclination"] = "";
    $_SESSION["minumum_perihelionarg"] = "";
    $_SESSION["maximum_perihelionarg"] = "";
    $_SESSION["minumum_node"] = "";
    $_SESSION["maximum_node"] = "";
    
    $_SESSION["comet_constraints_search"] = false;
}

?>