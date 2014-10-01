<?php
session_start();
/**
 * Created by PhpStorm.
 * User: erik
 * Date: 9/30/14
 * Time: 2:36 PM
 */

$app_hosts = array(
                         'test.com'
                        ,'test2.com'
                        ,'test3.com'
                   );

$app_users = array(
                         'user'
                        ,'apache'
                        ,'developer_1'
                        ,'developer_2'
                   );

$lines = file('site1.conf');



foreach ( $lines as $line ) {

    if ( !preg_match("|^\#|", $line )) {
        if (strlen($line) > 1) {
            $line = preg_replace('|\s|',"",$line);
 // echo $line . "<br>";

            $columns = preg_split('|=|',$line);

            echo $columns[0] . "<br>";

/*
            echo"<br><pre>";
            var_dump($columns);
            echo"</pre>";*/


if(false) {


    switch ($column[0]) {
        case 'host':
            populate_session_var($columns);
            break;
        case 'server_id':
            populate_session_var($columns);
            break;
        case 'server_load_alarm':
            populate_session_var($columns);
            break;
        case 'user':
            populate_session_var($columns);
            break;
        case 'verbose':
            populate_session_var($columns);
            break;
        case 'test_mode':
            populate_session_var($columns);
            break;
        case 'debug_mode':
            populate_session_var($columns);
            break;
        case 'log_file_path':
            populate_session_var($columns);
            break;
        case 'send_notifications':
            populate_session_var($columns);
            break;
        default:
    }
}

            switch ($columns[0]) {
                case "host":
                    echo'populate host';
                    populate_session_string_host($columns,$app_hosts);
                    break;
                case 'server_id':
                    //populate_session_int($columns);
                case 'server_load_alarm':
                case 'user':
                case 'log_file_path':
                    populate_session_string($columns);
                    break;
                case 'send_notifications':
                case 'verbose':
                case 'test_mode':
                case 'debug_mode':
                    populate_session_bool($columns);
                    break;
            }

        }
    }
} // foreach $line end


dump_session_conf_data();




function populate_session_string_host($host, $hosts){

    if ( in_array($host[1],$hosts) ){
        $_SESSION['conf']['host'] = $host[1];
        return true;
    } else {
        return false;
    }


}

function populate_session_string($columns){
    if(isset($columns[0]) && isset($columns[1])) {
        $_SESSION['conf'][$columns[0]] = $columns[1];
        //return true;
    }else{
        //return false;
    }
}


function populate_session_bool($columns){

        if ( preg_match('/true|yes|on/',$columns[1]) ){
            $_SESSION['conf'][$columns[0]] = 'true';
        } else {
            $_SESSION['conf'][$columns[0]] = 'false';
        }



        // Set the $_SESSION var to true or false
        $_SESSION[$columns[0]] = $bool;

}

function populate_session_int($host, $allowed_hosts){

    if ( in_array($host[1],$allowed_hosts) ){
        $_SESSION['conf']['host'] = $host[1];
        return true;
    } else {
        return false;
    }


}

function dump_session_conf_data(){
    echo'<pre>';
    print_r($_SESSION);
    echo'</pre>';
}