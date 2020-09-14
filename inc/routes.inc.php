<?php
$found = 0;
$urlraw = explode("/", $_SERVER['QUERY_STRING']);
$urls = ['home', 'login', 'register', 'myaccount', 'logout', 'pommesimp_game', 'recipes', 'viewrecipe', 'profile'];
if(in_array($urlraw[0], $urls)) {
    $url = explode("/", $_SERVER['QUERY_STRING']);
    if(file_exists('pagefiles/' . $url[0] . '.php')) {
        $found = 1;
    } else {
        header('HTTP/1.0 404 Not Found');
        $return = '<div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Fehler!</h4>
                        <p>Diese Seite wurde nicht gefunden.</p>
                        <hr>
                        <p class="mb-0"><small>Fehlercode: 404</small></p>
                    </div>';
        $found = 0;
    }
} else {
    if($urlraw[0] == "") {
        header("Location: home/");
    } else {
        header('HTTP/1.0 403 Forbidden');
        $return = '<div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Fehler!</h4>
                        <p>Diese Seite wurde nicht in unserer Datenbank gefunden.</p>
                        <hr>
                        <p class="mb-0"><small>Fehlercode: 403</small></p>
                    </div>';
    }
}

function base() {
    echo str_replace('index.php', '', $_SERVER['PHP_SELF']);
}

function insertBase() {
    return str_replace('index.php', '', $_SERVER['PHP_SELF']);
}

function getBaseACP() {
    
}