<?php
    require 'C:/xampp/php/vendor/autoload.php';

    $ctdb = new MongoDB\Client("mongodb://localhost:27017");

    $db = $ctdb->tests
    var_dump($ctdb);
?>