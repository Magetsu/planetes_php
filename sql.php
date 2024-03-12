<?php

require_once("logging.php");

//
//  Yhdistetään tietokantaan
//
function connect_to_database() {
    
    // Staattinen muuttuja ei katoa vaikka funktiosta palataankin.
    // Alustetaan muuttuja false-arvolla.
    static $connection = false;
    
    if($connection != false) {

        LOGTEXT("   CONNECT_TO_DATABASE : Käytetään olemassa olevaa yhteyttä");
        
        return $connection;
        
    }
        
        LOGTEXT("   CONNECT_TO_DATABASE : Luodaan uusi yhteys");
    
        // Jos yhteyttä ei ole vielä avattu, avataan se.
        // Jos yhteyden avaaminen ei onnistu, heitetään
        // poikkeus, joten se pitää napata.
        try {
            $connection = new PDO("mysql:host=db1.n.kapsi.fi;dbname=magetsu","magetsu","FgQiYWtkRX");
        } catch (PDOException $e) {
            exit("Tietokantavirhe: " . $e->getMessage());
        }
        
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->exec("set names utf8");
        
        LOGTEXT("   CONNECT_TO_DATABASE : Yhteys tietokantaan saatu");
        
        return $connection;
}

//
//   Tarkistetaan komeettatietokanta
//
function check_database($connection) {
  
    LOGTEXT("   CHECK_DATABASE : Tarkistetaan komeettatietokanta");
    
    $table = 'comet';
    
    $sql = 'SHOW TABLES';

    LOGTEXT("Luodaan kysely : ".$sql);

    $query = $connection->prepare($sql);
    $query->execute();
    $array = $query->fetchAll(PDO::FETCH_COLUMN, 0);

    LOGARRAY($array);
    
    if (in_array($table,$array)) {
        
        LOGTEXT("   CHECK_DATABASE : Tietokanta on olemassa");
        return true;
    } else {
    
        LOGTEXT("   CHECK_DATABASE : Tietokantaa ei ole olemassa");
        return false;
    }
}

//
//   Luodaan komeettatietokanta
//
function create_comet_database($connection) {

    LOGTEXT("create_comet_database : Luodaan komeettatietokantaa");
    
    // Poistetaan vanha tietokanta ensin.
    $sql = "DROP TABLE IF EXISTS magetsu.comet";
    $query = $connection->prepare($sql);

    LOGTEXT("create_comet_database : Poistetaan vanha ensin : ".$sql);

    $query->execute();
    
    // Luodaan nyt uusi komeettatietokanta.
    $sql="CREATE TABLE IF NOT EXISTS magetsu.comet ( name VARCHAR(43) NOT NULL,
													 epoch INT(7) NOT NULL,
													 q DECIMAL (11,8) NOT NULL,
													 e DECIMAL (10,9) NOT NULL,
													 i DECIMAL(9,5) NOT NULL,
													 w DECIMAL(9,5) NOT NULL,
													 node DECIMAL (9,5) NOT NULL,
													 tp DECIMAL (14,5) NOT NULL)";
    
    $query = $connection->prepare($sql);
        
    LOGTEXT("create_comet_database : Luodaan nyt uusi komeettatietokanta : ".$sql);
    $query->execute();
    
}

//
//   Luodaan komeettatietokanta
//
function create_asteroid_database($connection) {
    
    LOGTEXT("create_asteroid_database : Luodaan pikkuplaneettatietokantaa");
    
    // Poistetaan vanha tietokanta ensin.
    $sql = "DROP TABLE IF EXISTS magetsu.asteroid";
    $query = $connection->prepare($sql);
    
    LOGTEXT("create_asteroid_database : Poistetaan vanha ensin : ".$sql);
    
    $query->execute();
    
    // Luodaan nyt uusi pikkuplaneettatietokanta.
    $sql="CREATE TABLE IF NOT EXISTS magetsu.asteroid ( name VARCHAR(24) NOT NULL,
													 epoch INT(7) NOT NULL,
													 a DECIMAL (10,7) NOT NULL,
													 e DECIMAL (10,8) NOT NULL,
													 i DECIMAL(9,5) NOT NULL,
													 w DECIMAL(9,5) NOT NULL,
													 node DECIMAL (9,5) NOT NULL,
													 m DECIMAL (11,7) NOT NULL,
													 h DECIMAL (5,2) NOT NULL,
													 g DECIMAL (4,2) NOT NULL)";
    
    $query = $connection->prepare($sql);
    
    LOGTEXT("create_asteroid_database : Luodaan nyt uusi pikkuplaneettatietokanta : ".$sql);
    $query->execute();
    
}

//
// Komeetta-tietokanta täytetään tiedoilla joita saatiin tiedostosta
//
function populate_comet_database($connection,$comet_array) {
    
    LOGTEXT("populate_comet_database : Täytetään komeettatietokanta tiedoilla joita saatiin komeettatiedostosta");
    
    $count=0;
    
    $table = 'magetsu.comet';
    $sql = "INSERT INTO $table (name, epoch, q, e, i, w, node, tp) values ( ?, ?, ?, ?, ?, ?, ?, ?)";
    
    foreach ($comet_array as $comet) {
    
        /*
         * LOGTEXT('Suoritetaan INSERT INTO '.$table.' (name, epoch, q, e, i, w, node, tp) values ('
         *  .$comet[0].', '.$comet[1].', '
         *  .$comet[2].', '.$comet[3].', '
         *  .$comet[4].', '.$comet[5].', '
         *  .$comet[6].', '.$comet[7].')');
         */

        $query = $connection->prepare($sql);
        $query->execute([$comet[0],$comet[1],$comet[2],$comet[3],$comet[4],$comet[5],$comet[6],$comet[7]]);
        $count++;
    }      
}

//
// Pikkuplaneetta-tietokanta täytetään tiedoilla joita saatiin tiedostosta
//
function populate_asteroid_database($connection,$asteroid_array) {
    
    LOGTEXT("populate_asteroid_database : Täytetään pikkuplaneettatietokanta tiedoilla joita saatiin pikkuplaneettatiedostoista");
    
    $count=0;
    
    $table = 'magetsu.asteroid';
    $sql = "INSERT INTO $table (name, epoch, a, e, i, w, node, m, h, g) values ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    foreach ($asteroid_array as $comet) {
        
        /*
         * LOGTEXT('Suoritetaan INSERT INTO '.$table.' (name, epoch, a, e, i, w, node, m, h, g) values ('
         *  .$comet[0].', '.$comet[1].', '
         *  .$comet[2].', '.$comet[3].', '
         *  .$comet[4].', '.$comet[5].', '
         *  .$comet[6].', '.$comet[7].')'
         *  .$comet[8].', '.$comet[9].')');
         */
        
        $query = $connection->prepare($sql);
        $query->execute([$comet[0],$comet[1],$comet[2],$comet[3],$comet[4],$comet[5],$comet[6],$comet[7],$comet[8],$comet[9]]);
        $count++;
    }
}

function fetch_comet_database($query, $limit, $offset) {

    LOGTEXT("   FETCH_COMET_DATABASE : Haetaan komeettatietokantatiedot : ".$offset." - ".$offset+$limit);
    
    $connect = connect_to_database();
    
    $table = 'magetsu.comet';
    
    if ($query) {
        
        $sql = "SELECT * FROM $table WHERE $query LIMIT $limit OFFSET $offset";
        
    } else {
        
        $sql = "SELECT * FROM $table LIMIT $limit OFFSET $offset";
        
    }
    
    LOGTEXT("fetch_comet_database : Kysely on : ".$sql);
    
    $query = $connect->prepare($sql);
    $query->execute();
    $array = $query->fetchAll();

    return $array;
}

function fetch_asteroid_database($query, $limit, $offset) {
    
    LOGTEXT("   FETCH_ASTEROID_DATABASE : Haetaan pikkuplaneettatietokantatiedot : ".$offset." - ".$offset+$limit);
    
    $connect = connect_to_database();
    
    $table = 'magetsu.asteroid';
    
    if ($query) {
        
        $sql = "SELECT * FROM $table WHERE $query LIMIT $limit OFFSET $offset";
        
    } else {
        
        $sql = "SELECT * FROM $table LIMIT $limit OFFSET $offset";
        
    }
    
    LOGTEXT("fetch_asteroid_database : Kysely on : ".$sql);
    
    $query = $connect->prepare($sql);
    $query->execute();
    $array = $query->fetchAll();

    return $array;
}

function get_comet_count($query) {
    
    LOGTEXT("   GET_COMET_COUNT : Lasketaan komeettojen määrä");
    
    $connect = connect_to_database();
    
    $table = 'magetsu.comet';
    
    if ($query) {
        
        $sql = "SELECT COUNT(*) FROM $table WHERE $query";
        
    } else {
        
        $sql = "SELECT COUNT(*) FROM $table";    
    }
    
    $query = $connect->prepare($sql);
    $query->execute();
    $count = $query->fetchColumn();
        
    LOGTEXT("   GET_COMET_COUNT : Komeettojen määrä : ".$count);
    
    return $count;
}

function get_asteroid_count($query) {
    
    LOGTEXT("   GET_ASTEROID_COUNT : Lasketaan pikkuplaneettojen määrä");
    
    $connect = connect_to_database();
    
    $table = 'magetsu.asteroid';

    if ($query) {
        
        $sql = "SELECT COUNT(*) FROM $table WHERE $query";
        
    } else {
        
        $sql = "SELECT COUNT(*) FROM $table";
    }
        
    $query = $connect->prepare($sql);
    $query->execute();
    $count = $query->fetchColumn();
    
    LOGTEXT("   GET_ASTEROID_COUNT : Pikkuplaneettojen määrä : ".$count);
    
    return $count;
}

?>