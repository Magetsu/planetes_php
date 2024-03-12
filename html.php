<?php

//
// Tulostaa HTML-sivun alkuosan valitun otsikon kanssa.
//
function luo_html_alku($otsikko) {
    echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"";
    echo "\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
    echo "<html>\n";
    echo "<head>\n";
    echo "  <meta http-equiv=\"Content-Type\"
      content=\"text/html;charset=utf-8\" />\n";
    echo "  <link rel=\"stylesheet\" type=\"text/css\"
      href=\"planetes.css\" />\n";
    echo "  <title>".$otsikko."</title>\n";
    echo "</head>\n";
    echo "<body>\n\n";
}

//
// Tulostaa HTML-sivun loppuosan.
//
function luo_html_loppu() {
    echo "</body>\n";
    echo "</html>\n";
}

//
// Tulostaa otsikkopaneelin
//
function create_html_header_panel() {
    
    echo "Tervetuloa Komeetta- ja pikkuplaneettatietokantaan.<br>\n";
    echo "Täällä voi katsella eri otannoilla nykyisiä komeettoja ja pikkuplaneettoja. Laatikoista voi valita millä arvoilla haluaa otantoja tehdä.<br>\n";

}

//
// Tulostaa lukemapaneelin
//
function create_html_count_panel($comet_count, $asteroid_count) {
    echo "<div class=\"count\">Tietokannassa on tällä hetkellä ".$comet_count." komeettaa ja ".$asteroid_count." pikkuplaneettaa</div><br>\n";
}

//
// Tulostaa pääkäyttäjäpainikkeen
//
function create_html_admin_button() {

    echo "<div class=\"buttonfloat\">\n";
    echo " <button class=\"button\" onclick=\"document.getElementById('admin').style.display='block'\">Admin kirjautuminen</button>\n";
    echo "</div>\n";
    echo "<div id=\"admin\" class=\"modal\">\n";
    echo " <form class=\"modal-content animate\" action=\"admin.php\" method=\"post\">\n";
    echo "  <div class=\"imgcontainer\">\n";
    echo "   <span onclick=\"document.getElementById('admin').style.display='none'\" class=\"close\" title=\"Close Modal\">&times;</span>\n";
    echo "  </div>\n";
    echo "  <div class=\"container\">\n";
    echo "   <label for=\"username\"><b>Admintunnus</b></label>";
    echo "   <input type=\"text\" name=\"username\" required>\n";
    echo "   <label for=\"password\"><b>Adminsalasana</b></label>";
    echo "   <input type=\"password\" name=\"password\" required>\n";
    echo "   <button class=\"button\" type=\"submit\">Kirjaudu</button>\n";
    echo "  </div>\n";
    echo " </form>\n";
    echo "</div>\n";
}

//
// Tulostaa tietojen lataussivun
//
function create_html_upload() {

    echo "<form class=\"modal-content animate\" action=\"upload.php\" method=\"post\" enctype=\"multipart/form-data\">\n"; 
    echo " <div class=\"container\">\n";
    echo "  <label for=\"cometdata\"><b>Komeetat</b></label>";
    echo "  <input type=\"file\" name=\"cometdata\" id=\"cometdata\">\n";
    echo "  <label for=\"numbered\"><b>Numeroidut pikkuplaneetat</b></label>";
    echo "  <input type=\"file\" name=\"numbered\" id=\"numbered\">\n";
    echo "  <label for=\"unnumbered\"><b>Numeroimattomat pikkuplaneetat</b></label>";
    echo "  <input type=\"file\" name=\"unnumbered\" id=\"unnumbered\">\n";
    echo "  <button class=\"button\" type=\"submit\" name=\"upload\">Lataa tiedostot</button>\n";
    echo "  <button class=\"button\" type=\"button\" onclick=\"window.location.href='planetes.php'\">Palaa takaisin</button>\n";
    echo " </div>\n";
    echo "</form>\n";
    
}

//
// Tulostaa valintapaneelin
//
function create_html_selection_panel() {
    echo "<form action=\"planetes.php\" method=\"post\">\n";
    echo " <input type=\"submit\" name=\"comet\" value=\"Komeetat\">\n";
    echo " <input type=\"submit\" name=\"asteroid\" value=\"Pikkuplaneetat\">\n";
    echo "</form>\n";
}

//
// Tulostaa rajoituspaneelin
//
function create_html_comet_constraints_panel() {

    echo "<div class=\"row\">\n";
    echo "  <form action=\"comet.php\" method=\"post\">\n";
    echo "  <div class=\"column_comet\">\n";
    echo "      <label for=\"perihelion\">Perihelietäisyys : </label><span class=\"error\">".$_SESSION["perihelion_error"]."</span><br>\n";
    echo "      <label for=\"minper\">Minimiarvo:</label>\n";
    echo "      <input type=\"text\" id=\"minper\" name=\"minper\" value=\"".$_SESSION["minumum_perihelion"]."\"><br>\n";
    echo "      <label for=\"maxper\">Maksimiarvo:</label>\n";
    echo "      <input type=\"text\" id=\"maxper\" name=\"maxper\" value=\"".$_SESSION["maximum_perihelion"]."\">\n";
    echo "  </div>\n";
    
    echo "  <div class=\"column_comet\">\n";
    echo "      <label for=\"eccentricity\">Eksentrisyys : </label><span class=\"error\">".$_SESSION["eccentricity_error"]."</span><br>\n";
    echo "      <label for=\"minecc\">Minimiarvo:</label>\n";
    echo "      <input type=\"text\" id=\"minecc\" name=\"minecc\" value=\"".$_SESSION["minumum_eccentricity"]."\"><br>\n";
    echo "      <label for=\"maxecc\">Maksimiarvo:</label>\n";
    echo "      <input type=\"text\" id=\"maxecc\" name=\"maxecc\" value=\"".$_SESSION["maximum_eccentricity"]."\">\n";
    echo "  </div>\n";
    
    echo "  <div class=\"column_comet\">\n";
    echo "      <label for=\"inclination\">Inklinaatio : </label><span class=\"error\">".$_SESSION["inclination_error"]."</span><br>\n";
    echo "      <label for=\"mininc\">Minimiarvo:</label>\n";
    echo "      <input type=\"text\" id=\"mininc\" name=\"mininc\" value=\"".$_SESSION["minumum_inclination"]."\"><br>\n";
    echo "      <label for=\"maxinc\">Maksimiarvo:</label>\n";
    echo "      <input type=\"text\" id=\"maxinc\" name=\"maxinc\" value=\"".$_SESSION["maximum_inclination"]."\">\n";
    echo "  </div>\n";
    
    echo "  <div class=\"column_comet\">\n";
    echo "      <label for=\"perarg\">Perihelin argumentti : </label><span class=\"error\">".$_SESSION["perarg_error"]."</span><br>\n";
    echo "      <label for=\"minperarg\">Minimiarvo:</label>\n";
    echo "      <input type=\"text\" id=\"minperarg\" name=\"minperarg\" value=\"".$_SESSION["minumum_perihelionarg"]."\"><br>\n";
    echo "      <label for=\"maxperarg\">Maksimiarvo:</label>\n";
    echo "      <input type=\"text\" id=\"maxperarg\" name=\"maxperarg\" value=\"".$_SESSION["maximum_perihelionarg"]."\">\n";
    echo "  </div>\n";
    
    echo "  <div class=\"column_comet\">\n";
    echo "      <label for=\"node\">Nousevan solmun pituus : </label><span class=\"error\">".$_SESSION["node_error"]."</span><br>\n";
    echo "      <label for=\"minnode\">Minimiarvo:</label>\n";
    echo "      <input type=\"text\" id=\"minnode\" name=\"minnode\" value=\"".$_SESSION["minumum_node"]."\"><br>\n";
    echo "      <label for=\"maxnode\">Maksimiarvo:</label>\n";
    echo "      <input type=\"text\" id=\"maxnode\" name=\"maxnode\" value=\"".$_SESSION["maximum_node"]."\">\n";
    echo "  </div>\n";
    
    echo "  <div class=\"column_comet\">";
    echo "      <button class=\"button\" type=\"submit\" name=\"comet_search\">Hae rajauksella</button>\n";
    echo "      <button class=\"button\" type=\"submit\" name=\"comet_name\">Hae nimellä</button><br>\n";
    echo "      <label for=\"name\">Komeetan nimi:</label>";
    echo "      <input type=\"text\" id=\"name\" name=\"name\" value=\"".$_SESSION["comet_name"]."\"><br>";
    echo "  </form>\n";
    echo "  </div>\n";
    echo "</div>\n";
}

function create_html_asteroid_constraints_panel() {
    
    echo "<div class=\"row\">";
    echo "  <form action=\"asteroid.php\" method=\"post\">\n";
    echo "  <div class=\"column_asteroid\">";
    echo "      <label for=\"meandist\">Keskietäisyys auringosta : </label><span class=\"error\">".$_SESSION["meandistance_error"]."</span><br>";
    echo "      <label for=\"minmeandist\">Minimiarvo:</label>";
    echo "      <input type=\"text\" id=\"minmeandist\" name=\"minmeandist\" value=\"".$_SESSION["minumum_meandistance"]."\"><br>";
    echo "      <label for=\"maxmeandist\">Maksimiarvo:</label>";
    echo "      <input type=\"text\" id=\"maxmeandist\" name=\"maxmeandist\" value=\"".$_SESSION["maximum_meandistance"]."\">";
    echo "  </div>\n";
    
    echo "  <div class=\"column_asteroid\">";
    echo "      <label for=\"eccentricity\">Eksentrisyys : </label><span class=\"error\">".$_SESSION["eccentricity_error"]."</span><br>";
    echo "      <label for=\"minecc\">Minimiarvo:</label>";
    echo "      <input type=\"text\" id=\"minecc\" name=\"minecc\" value=\"".$_SESSION["minumum_eccentricity"]."\"><br>";
    echo "      <label for=\"maxecc\">Maksimiarvo:</label>";
    echo "      <input type=\"text\" id=\"maxecc\" name=\"maxecc\" value=\"".$_SESSION["maximum_eccentricity"]."\">";
    echo "  </div>\n";
    
    echo "  <div class=\"column_asteroid\">";
    echo "      <label for=\"inclination\">Inklinaatio : </label><span class=\"error\">".$_SESSION["inclination_error"]."</span><br>";
    echo "      <label for=\"mininc\">Minimiarvo:</label>";
    echo "      <input type=\"text\" id=\"mininc\" name=\"mininc\" value=\"".$_SESSION["minumum_inclination"]."\"><br>";
    echo "      <label for=\"maxinc\">Maksimiarvo:</label>";
    echo "      <input type=\"text\" id=\"maxinc\" name=\"maxinc\" value=\"".$_SESSION["maximum_inclination"]."\">";
    echo "  </div>\n";
    
    echo "  <div class=\"column_asteroid\">";
    echo "      <label for=\"perarg\">Perihelin argumentti : </label><span class=\"error\">".$_SESSION["perarg_error"]."</span><br>";
    echo "      <label for=\"minperarg\">Minimiarvo:</label>";
    echo "      <input type=\"text\" id=\"minperarg\" name=\"minperarg\" value=\"".$_SESSION["minumum_perihelionarg"]."\"><br>";
    echo "      <label for=\"maxperarg\">Maksimiarvo:</label>";
    echo "      <input type=\"text\" id=\"maxperarg\" name=\"maxperarg\" value=\"".$_SESSION["maximum_perihelionarg"]."\">";
    echo "  </div>\n";
    
    echo "  <div class=\"column_asteroid\">";
    echo "      <label for=\"node\">Nousevan solmun pituus : </label><span class=\"error\">".$_SESSION["node_error"]."</span><br>";
    echo "      <label for=\"minnode\">Minimiarvo:</label>";
    echo "      <input type=\"text\" id=\"minnode\" name=\"minnode\" value=\"".$_SESSION["minumum_node"]."\"><br>";
    echo "      <label for=\"maxnode\">Maksimiarvo:</label>";
    echo "      <input type=\"text\" id=\"maxnode\" name=\"maxnode\" value=\"".$_SESSION["maximum_node"]."\">";
    echo "  </div>\n";

    echo "  <div class=\"column_asteroid\">";
    echo "      <label for=\"meananomaly\">Keskianomalia : </label><span class=\"error\">".$_SESSION["meananomaly_error"]."</span><br>";
    echo "      <label for=\"minmean\">Minimiarvo:</label>";
    echo "      <input type=\"text\" id=\"minmean\" name=\"minmean\" value=\"".$_SESSION["minumum_meananomaly"]."\"><br>";
    echo "      <label for=\"maxmean\">Maksimiarvo:</label>";
    echo "      <input type=\"text\" id=\"maxmean\" name=\"maxmean\" value=\"".$_SESSION["maximum_meananomaly"]."\">";
    echo "  </div>\n";

    echo "  <div class=\"column_asteroid\">";
    echo "      <button class=\"button\" type=\"submit\" name=\"asteroid_search\">Hae rajauksella</button>\n";
    echo "      <button class=\"button\" type=\"submit\" name=\"asteroid_name\">Hae nimellä</button><br>\n";
    echo "      <label for=\"name\">Pikkuplaneetan nimi:</label>";
    echo "      <input type=\"text\" id=\"name\" name=\"name\" value=\"".$_SESSION["asteroid_name"]."\"><br>";
    echo "  </form>\n";
    echo "  </div>\n";
    echo "</div>";
}

//
// Tulostaa tietopaneelin
//
function create_html_comet_data_panel($pages, $pagenumber, $array) {
        
    echo "<div class=\"pages\" id=\"pages\">\n";
    for($page = 0; $page < $pages; ++$page) {
    
        if($pagenumber == $page)
            echo $page + 1, " ";
            else
                echo "<a href=\"comet.php?page=".$page."\">",
                $page + 1, "</a> ";
    }
    echo "\n</div>\n";
    
    echo "<table border='1'>\n";

    echo "  <tr>\n";
    echo "      <td>Komeetan nimi</td>\n";
    echo "      <td>Epookki</td>\n";
    echo "      <td>Lähin etäisyys (AU)</td>\n";
    echo "      <td>Eksentrisyys</td>\n";
    echo "      <td>Inklinaatio (°)</td>\n";
    echo "      <td>Perihelin argumentti (°)</td>\n";
    echo "      <td>Nouseva solmu (°)</td>\n";
    echo "      <td>Periheliaika</td>\n";
    echo "  </tr>\n";
    
    foreach ($array as $comet) {
        
        echo "      <tr>\n";
        echo "          <td>".$comet['name']."</td>\n";
        echo "          <td>".$comet['epoch']."</td>\n";
        echo "          <td>".$comet['q']."</td>\n";
        echo "          <td>".$comet['e']."</td>\n";
        echo "          <td>".$comet['i']."</td>\n";
        echo "          <td>".$comet['w']."</td>\n";
        echo "          <td>".$comet['node']."</td>\n";
        echo "          <td>".$comet['tp']."</td>\n";
        echo "</tr>\n";
    }
    echo "</table>\n";
}

//
// Tulostaa tietopaneelin
//
function create_html_asteroid_data_panel($pages, $pagenumber, $array) {
    
    echo "<div class=\"pages\" id=\"pages\">\n";
    for($page = 0; $page < $pages; ++$page)
    {
        if($pagenumber == $page)
            echo $page + 1, " ";
            else
                echo "<a href=\"asteroid.php?page=".$page."\">",
                $page + 1, "</a> ";
    }
    echo "</div>\n";
    
    echo "<table border='1'>\n";
         
    echo "  <tr>\n";
    echo "      <td>Asteroidin nimi</td>\n";
    echo "      <td>Epookki</td>\n";
    echo "      <td>Keskietäisyys (AU)</td>\n";
    echo "      <td>Eksentrisyys</td>\n";
    echo "      <td>Inklinaatio (°)</td>\n";
    echo "      <td>Perihelin argumentti (°)</td>\n";
    echo "      <td>Nouseva solmu (°)</td>\n";
    echo "      <td>Keskianomalia (°)</td>\n";
    echo "      <td>Absoluuttinen magnitudi</td>\n";
    echo "      <td>Kirkkauden jyrkkyys</td>\n";
    echo "  </tr>\n";
    
    foreach ($array as $asteroid) {
        
        echo "      <tr>\n";
        echo "          <td>".$asteroid['name']."</td>\n";
        echo "          <td>".$asteroid['epoch']."</td>\n";
        echo "          <td>".$asteroid['a']."</td>\n";
        echo "          <td>".$asteroid['e']."</td>\n\n";
        echo "          <td>".$asteroid['i']."</td>\n";
        echo "          <td>".$asteroid['w']."</td>\n";
        echo "          <td>".$asteroid['node']."</td>\n";
        echo "          <td>".$asteroid['m']."</td>\n";
        echo "          <td>".$asteroid['h']."</td>\n";
        echo "          <td>".$asteroid['g']."</td>\n";
        echo "  </tr>\n";
    }
        
    echo "</table>\n";
}

//
// Tulostaa lukemapaneelin
//
function create_html_footer_panel($choice, $count) {
    echo "<div class=\"count\">Haun tulos : ".$count." ".$choice."</div><br>\n";
}

//
// Uudelleenohjaa käyttäjän toiselle sivulle.
//
function redirect($sivu) {
    
    header("Location: http://" . $_SERVER["HTTP_HOST"]
        . dirname(htmlspecialchars($_SERVER["PHP_SELF"]))
        . "/" . $sivu);
}

?>