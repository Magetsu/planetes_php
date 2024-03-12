<?php

header("Content-Type: text/html; charset=utf-8");
ini_set("error_reporting", E_ALL | E_STRICT);
ini_set("display_errors", 1);

require_once("sql.php");
require_once("html.php");
require_once("logging.php");

if(isset($_POST["upload"])) {

    $comet_data = $_FILES['cometdata']['tmp_name'];
    $numbered_data = $_FILES['numbered']['tmp_name'];
    $unnumbered_data = $_FILES['unnumbered']['tmp_name'];
    
    // Tarkistetaan saatiinko yhtään tiedostoa.
    if (!file_exists($comet_data) && !file_exists($numbered_data) && !file_exists($unnumbered_data)) {
        
        // Ei saatu joten palataan pääsivulle.
        LOGTEXT("Ei ladattavia tiedostoja. Poistutaan...");
        LOGTEXT("<p>Palaa <a href=\"planetes.php\">pääsivulle</a>.</p>");
        
        if (!GET_LOGGING()) {
            redirect('planetes.php');
            exit;
        }
    }
    
    // Tarkistetaan ladattiinko komeettatiedosta.
    if(file_exists($comet_data) && is_uploaded_file($comet_data)) {
        
        LOGTEXT("Filename: " . $_FILES['cometdata']['name']);
        LOGTEXT("Type : " . $_FILES['cometdata']['type']);
        LOGTEXT("Size : " . $_FILES['cometdata']['size']);
        LOGTEXT("Temp name: " . $_FILES['cometdata']['tmp_name']);
        LOGTEXT("Error : " . $_FILES['cometdata']['error']);
        
        $connect = connect_to_database();
        
        // Löytyikö komeettatietokanta.
        if (!check_database($connect)) {
            
            // Ei löytynyt- Luodaan tietokanta.
            LOGTEXT("Luodaan komeettatietokanta");
            create_comet_database($connect);
            
        } else {
            
            // Löytyi. Poistetaan ja luodaan uusi tietokanta.
            LOGTEXT("Ei löytynyt. Komeettatietokanta löytyi. Poistetaan se ja luodaan uusi.");
            create_comet_database($connect);
        }
        
        $comet_file = fopen($_FILES['cometdata']['tmp_name'], "r");
        
        // Luetaan pois ensimmäiset kaksi riviä jotta päässään dataan käsiksi
        LOGTEXT("Eka rivi : ".fgets($comet_file));
        LOGTEXT("Toinen rivi : ".fgets($comet_file));
        
        // Luodaan lopuista tiedoista komeetta-array joka sisältää kaiken tarvittavan tiedon
        $comet_array = array();
        
        while(!feof($comet_file)) {
            
            $line = fgets($comet_file);
            
            $comet = [];
            
            $comet[] = substr($line, 0, 42);
            $comet[] = substr($line, 45, 7);
            $comet[] = substr($line, 53, 10);
            $comet[] = substr($line, 64, 10);
            $comet[] = substr($line, 75, 9);
            $comet[] = substr($line, 85, 9);
            $comet[] = substr($line, 95, 9);
            $comet[] = substr($line, 105, 15);
            
            array_push($comet_array, $comet);
        }
        
        fclose($comet_file);
        
        LOGARRAY($comet_array);
        
        populate_comet_database($connect,$comet_array);
        
        LOGTEXT("<p>Palaa <a href=\"planetes.php\">pääsivulle</a>.</p>");
        
        if (!GET_LOGGING()) {
            redirect('planetes.php');
            exit;
        }
    }
    
    if(file_exists($numbered_data) && is_uploaded_file($numbered_data)) {
// Pakko rajoittaa. Kaikkien saaminen vie liikaa muistia.        
//    if((file_exists($numbered_data) && is_uploaded_file($numbered_data))
//        && (file_exists($unnumbered_data) && is_uploaded_file($unnumbered_data))) {
        
        LOGTEXT("Filename: " . $_FILES['numbered']['name']);
        LOGTEXT("Type : " . $_FILES['numbered']['type']);
        LOGTEXT("Size : " . $_FILES['numbered']['size']);
        LOGTEXT("Temp name: " . $_FILES['numbered']['tmp_name']);
        LOGTEXT("Error : " . $_FILES['numbered']['error']);

        LOGTEXT("Filename: " . $_FILES['unnumbered']['name']);
        LOGTEXT("Type : " . $_FILES['unnumbered']['type']);
        LOGTEXT("Size : " . $_FILES['unnumbered']['size']);
        LOGTEXT("Temp name: " . $_FILES['unnumbered']['tmp_name']);
        LOGTEXT("Error : " . $_FILES['unnumbered']['error']);
        $connect = connect_to_database();

        // Löytyikö numeroidut pikkuplaneetat tietokanta.
        if (!check_database($connect)) {
            
            // Ei löytynyt- Luodaan tietokanta.
            LOGTEXT("Ei löytynyt. Luodaan pikkuplaneettatietokanta");
            create_asteroid_database($connect);
            
        } else {
            
            // Löytyi. Poistetaan ja luodaan uusi tietokanta.
            LOGTEXT("Pikkuplaneettatietokanta löytyi. Poistetaan se ja luodaan uusi.");
            create_asteroid_database($connect);
        }
    
        //-------------------------Luetaan numeroidut pikkuplaneetat----------------------------------------------------------------------------
        $numbered_file = fopen($_FILES['numbered']['tmp_name'], "r");
        
        // Luetaan pois ensimmäiset kaksi riviä jotta päässään dataan käsiksi
        LOGTEXT("Eka rivi : ".fgets($numbered_file));
        LOGTEXT("Toinen rivi : ".fgets($numbered_file));
        
        // Luodaan lopuista tiedoista pikkuplaneetta-array joka sisältää kaiken tarvittavan tiedon
        $asteroid_array = array();
        $linecount = 0;
        $count = 0;
        
        while(!feof($numbered_file)) {
            
            $line = fgets($numbered_file);

            $asteroid = [];
            
            $asteroid[] = substr($line, 0, 23);
            $asteroid[] = substr($line, 25, 5);
            $asteroid[] = substr($line, 31, 10);
            $asteroid[] = substr($line, 43, 10);
            $asteroid[] = substr($line, 54, 9);
            $asteroid[] = substr($line, 64, 9);
            $asteroid[] = substr($line, 73, 9);
            $asteroid[] = substr($line, 83, 11);
            $asteroid[] = substr($line, 95, 5);
            $asteroid[] = substr($line, 101, 5);
            
            array_push($asteroid_array, $asteroid);
            
            $linecount++;
                        
            if ($linecount>9999) {
                
                echo " ";
                $count++;
                LOGTEXT("populate_asteroid_database : ".$count);
                
                populate_asteroid_database($connect,$asteroid_array);
                
                $asteroid_array = [];
                $linecount = 0;
            }
        }
        
        fclose($numbered_file);      
/*        
        while(!feof($numbered_file)) {
            
            $line = fgets($numbered_file);
            
            $asteroid = [];
            
            $asteroid[] = substr($line, 0, 23);
            $asteroid[] = substr($line, 25, 5);
            $asteroid[] = substr($line, 31, 10);
            $asteroid[] = substr($line, 43, 10);
            $asteroid[] = substr($line, 54, 9);
            $asteroid[] = substr($line, 64, 9);
            $asteroid[] = substr($line, 73, 9);
            $asteroid[] = substr($line, 83, 11);
            $asteroid[] = substr($line, 95, 5);
            $asteroid[] = substr($line, 101, 5);
            
            array_push($asteroid_array, $asteroid);
        }
        
        fclose($numbered_file);
*/
/*        
        //-------------------------Luetaan numeroimattomat pikkuplaneetat-----------------------------------------------------------------------
        $unnumbered_file = fopen($_FILES['unnumbered']['tmp_name'], "r");
        
        // Luetaan pois ensimmäiset kaksi riviä jotta päässään dataan käsiksi
        LOGTEXT("Eka rivi : ".fgets($unnumbered_file));
        LOGTEXT("Toinen rivi : ".fgets($unnumbered_file));

        
        while(!feof($unnumbered_file)) {
            
            $line = fgets($unnumbered_file);
     
            $asteroid[] = substr($line, 0, 13);
            $asteroid[] = substr($line, 14, 5);
            $asteroid[] = substr($line, 19, 12);
            $asteroid[] = substr($line, 31, 11);
            $asteroid[] = substr($line, 43, 9);
            $asteroid[] = substr($line, 53, 9);
            $asteroid[] = substr($line, 63, 9);
            $asteroid[] = substr($line, 73, 11);
            $asteroid[] = substr($line, 85, 5);
            $asteroid[] = substr($line, 91, 5);
            
            array_push($asteroid_array, $asteroid);
        }
        
        fclose($unnumbered_file);
*/        
        //populate_asteroid_database($connect,$asteroid_array);
        
        LOGTEXT("<p>Palaa <a href=\"planetes.php\">pääsivulle</a>.</p>");
        
        if (!GET_LOGGING()) {
            redirect('planetes.php');
            exit;
        }
    }
    
    if(file_exists($unnumbered_data) && is_uploaded_file($unnumbered_data)) {
        
        // Joskus toiste tehdään että voi ladata erikseen pikkuplaneetta-tiedostot
    }
}

luo_html_alku("Upload");

create_html_upload();

luo_html_loppu();

?>