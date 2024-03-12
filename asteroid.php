<?php

require_once("html.php");
require_once("logging.php");
require_once("sql.php");

session_start();

define ("RESULTS_IN_PAGE", 1000);
define ("MINIMUM_MEANDIST",0);
define ("MAXIMUM_MEANDIST",10000);
define ("MINIMUM_ECCENTRICITY",0);
define ("MAXIMUM_ECCENTRICITY",2);
define ("MINIMUM_INCLINATION",0);
define ("MAXIMUM_INCLINATION",360);
define ("MINIMUM_PERARG",0);
define ("MAXIMUM_PERARG",360);
define ("MINIMUM_NODE",0);
define ("MAXIMUM_NODE",360);
define ("MINIMUM_MEANANOMALY",0);
define ("MAXIMUM_MEANANOMALY",360);

$pagenumber = 0;
$query = "";

$_SESSION["meandistance_error"] = "";
$_SESSION["eccentricity_error"] = "";
$_SESSION["inclination_error"] = "";
$_SESSION["perarg_error"] = "";
$_SESSION["node_error"] = "";
$_SESSION["meananomaly_error"] = "";

$meandistRange = array(
    'options' => array(
        'min_range' => MINIMUM_MEANDIST,
        'max_range' => MAXIMUM_MEANDIST
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

$meananomalyRange = array(
    'options' => array(
        'min_range' => MINIMUM_MEANANOMALY,
        'max_range' => MAXIMUM_MEANANOMALY
    ));

if(isset($_GET["page"]) && ctype_digit($_GET["page"])) {
    
    $pagenumber = $_GET["page"];
    
    if ($_SESSION["asteroid_constraints_search"]) {
        
        $query = $_SESSION["asteroid_constraints_query"];
        
    } else if ($_SESSION["asteroid_name_search"])  {
        
        $query = $_SESSION["asteroid_name_query"];
    }
}

if(isset($_POST["asteroid_search"])) {
    
    LOGTEXT("Haetaan pikkuplaneettatietokannasta");
    
    $constraintsCount = 0;
    $_SESSION["asteroid_name"] = "";
    clear_asteroid_fields();
    
    if ($_POST["minmeandist"] || $_POST["maxmeandist"]) {
        
        LOGTEXT("Haetaan käyttäjältä rajaukset keskietäisyys : ".$_POST["minmeandist"]."-".$_POST["maxmeandist"]);
        
        $meandistanceQuery = check_limits("a", $meandistRange, $_POST["minmeandist"], $_POST["maxmeandist"]);
        
        if ($meandistanceQuery == "error") {
            
            $_SESSION["meandistance_error"] = "Virhe tiedoissa!";
        }
        
        $_SESSION["minumum_meandistance"] = $_POST["minmeandist"];
        $_SESSION["maximum_meandistance"] = $_POST["maxmeandist"];
        
        $query .= $meandistanceQuery;
        
        ++$constraintsCount;
    }
    
    if ($_POST["minecc"] || $_POST["maxecc"]) {
        
        LOGTEXT("Haetaan käyttäjältä rajaukset eksentrisyys : ".$_POST["minecc"]."-".$_POST["maxecc"]);
        
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
        
        LOGTEXT("Haetaan käyttäjältä rajaukset inklinaatio : ".$_POST["mininc"]."-".$_POST["maxinc"]);
        
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
        
        LOGTEXT("Haetaan käyttäjältä rajaukset perihelin argumentti : ".$_POST["minperarg"]."-".$_POST["maxperarg"]);
        
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
        
        LOGTEXT("Haetaan käyttäjältä rajaukset perihelin nousevan solmun pituus : ".$_POST["minnode"]."-".$_POST["maxnode"]);
        
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
        
        ++$constraintsCount;
    }

    if ($_POST["minmean"] || $_POST["maxmean"]) {
        
        LOGTEXT("Haetaan käyttäjältä rajaukset keskietäisyys : ".$_POST["minmean"]."-".$_POST["maxmean"]);

        if ($constraintsCount > 0) {
            
            $query .= " AND ";
        }
        
        $meananomalyQuery = check_limits("m", $meananomalyRange, $_POST["minmean"], $_POST["maxmean"]);
        
        if ($meananomalyQuery == "error") {
            
            $_SESSION["meandistance_error"] = "Virhe tiedoissa!";
        }
        
        $_SESSION["minumum_meananomaly"] = $_POST["minmean"];
        $_SESSION["maximum_meananomaly"] = $_POST["maxmean"];
        
        $query .= $meananomalyQuery;
        
        ++$constraintsCount;
    }
    
    if ($meandistanceQuery == "error" ||
        $eccentricityQuery == "error"||
        $inclinationQuery == "error"||
        $perargQuery == "error"||
        $nodeQuery == "error" ||
        $meananomalyQuery == "error") {
            
            $query = "";
        }

        if ($meandistanceQuery == "" &&
            $eccentricityQuery == "" &&
            $inclinationQuery == "" &&
            $perargQuery == "" &&
            $nodeQuery == "" &&
            $meananomalyQuery == "") {
                
                clear_asteroid_fields();
            }
            
        $_SESSION["asteroid_constraints_query"] = $query;
        $_SESSION["asteroid_constraints_search"] = true;
        
        LOGTEXT("Hakulauseke on : ".$query);
}

if(isset($_POST["asteroid_name"])) {
    
    LOGTEXT("POST[asteroid_name]) : Haetaan pikkuplaneettatietokannasta nimellä ".$_POST["name"]);
    
    clear_asteroid_fields();
    
    $query = " name LIKE '%".$_POST["name"]."%'";
    
    LOGTEXT("_POST[asteroid_name]) : Hakulauseke on : ".$query);
    
    $_SESSION["asteroid_name"] = $_POST["name"];
    $_SESSION["asteroid_name_query"] = $query;
    $_SESSION["asteroid_name_search"] = true;
    
    if ($_POST["name"] = "") {
        
        $_SESSION["asteroid_name"] = "";
        $_SESSION["asteroid_name_search"] = false;
    }
    
}

$limit = intval(RESULTS_IN_PAGE);
$offset = intval($pagenumber * RESULTS_IN_PAGE);

$asteroid_count = get_asteroid_count($query);
$pages = ceil($asteroid_count/RESULTS_IN_PAGE);

LOGTEXT("Sivujen määrä : ".$pages);

luo_html_alku("Planetes");

create_html_header_panel();
create_html_admin_button();
create_html_count_panel(get_comet_count(null),get_asteroid_count(null));
create_html_selection_panel();
create_html_asteroid_constraints_panel();
create_html_footer_panel("pikkuplaneettaa", $asteroid_count);

$comet = get_asteroid_database($query, $limit, $offset);
create_html_asteroid_data_panel($pages, $pagenumber, $comet);

luo_html_loppu();

function get_asteroid_database($query, $limit, $offset) {
    
    LOGTEXT("Haetaan pikkuplaneettatietokanta muistiin");
    
    $connection = connect_to_database();
    
    if (check_database($connection)) {
        
        // Haetaan pikkuplaneetat tietokannasta
        $asteroidsql = fetch_asteroid_database($query, $limit, $offset);
        
    } else if(!check_database($connection)){
        
        // Tietokantaa ei ole joten luodaan se
        create_asteroid_database($connection);
        
    }
    return $asteroidsql;
}

function check_limits($constraint, $range, $min, $max) {
    
    LOGTEXT($constraint." : tarkistetaan arvot : ".$min." - ".$max);
    
    if(filter_var($min, FILTER_VALIDATE_FLOAT,$range) && filter_var($max, FILTER_VALIDATE_FLOAT,$range)) {
        
        LOGTEXT($constraint." : Minimi ja maksimi ovat numeroita ja ovat rajojen sisällä");
        
        $string = $constraint." BETWEEN ".$min." AND ".$max;
        
        LOGTEXT($constraint." : Lausekelisäys on ".$string);
        
        return $string;
        
    } else if ($min == null && filter_var($max, FILTER_VALIDATE_FLOAT,$range)) {
        
        LOGTEXT($constraint." : Maksimi on numero ja on rajojen sisällä");
        
        $string = $constraint." < ".$max;
        
        LOGTEXT($constraint." : Lausekelisäys on ".$string);
        
        return $string;
        
    } else if (filter_var($min, FILTER_VALIDATE_FLOAT,$range) && $max == null) {
        
        LOGTEXT($constraint." : Minimi on numero ja on rajojen sisällä");
        
        $string = $constraint." > ".$min;
        
        LOGTEXT($constraint." : Lausekelisäys on ".$string);
        
        return $string;
        
    } else {
        
        LOGTEXT($constraint." : Arvossa on virhe");
        return "error";
    }
}

function clear_asteroid_fields() {
    
    LOGTEXT("CLEAR_ASTEROID_FIELDS : Tyhjennetään rajauskentät");
    
    $_SESSION["minumum_meandistance"] = "";
    $_SESSION["maximum_meandistance"] = "";
    $_SESSION["minumum_eccentricity"] = "";
    $_SESSION["maximum_eccentricity"] = "";
    $_SESSION["minumum_inclination"] = "";
    $_SESSION["maximum_inclination"] = "";
    $_SESSION["minumum_perihelionarg"] = "";
    $_SESSION["maximum_perihelionarg"] = "";
    $_SESSION["minumum_node"] = "";
    $_SESSION["maximum_node"] = "";
    $_SESSION["minumum_meananomaly"] = "";
    $_SESSION["maximum_meananomaly"] = "";
    
    $_SESSION["asteroid_constraints_search"] = false;
}
?>