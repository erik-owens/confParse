<?php
session_start();
session_destroy();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once('confParse.php');

//Test for site1.conf
$conf_handler = new confParse();
$error = $conf_handler->parse('./conf/site1.conf');






if ( isset($error) && strlen($error) > 0 ) {
    //error_log( "confParse finished with err=({$error})\n\n");
    echo "confParse finished with err=({$error})\n\n";
} else {
    //error_log("confParse Completed Successfully");
    echo "confParse Completed Successfully<br><hr><br>\$_SESSION['conf']:<br>";
    echo"<pre>";
    print_r($_SESSION['conf']);
    echo"</pre>";
}


