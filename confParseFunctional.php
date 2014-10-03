<?php
session_start();
session_destroy();
session_start();
ini_set("auto_detect_line_endings", true);


/**
 * Created by PhpStorm.
 * User: erik
 * Date: 9/30/14
 * Time: 2:36 PM
 */

$app_hosts = load_list('auth_hosts');

$app_users = load_list('auth_users');


$lines = file('site1.conf');


foreach ( $lines as $line ) {

    if ( !preg_match("|^\#|", $line )) {
        if (strlen($line) > 1) {
            $line = preg_replace('|\s|',"",$line);

            $columns = preg_split('|=|',$line);

            echo $columns[0] . "<br>";


            switch ($columns[0]) {
                case "host":
                    populate_session_string_verified($columns,$app_hosts, 'host');
                    break;
                case 'user':
                    populate_session_string_verified($columns,$app_users, 'user');
                    break;
                case 'server_id':
                    //populate_session_int($columns);
                case 'server_load_alarm':
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

dump_data($app_hosts);

function load_list($file){
    $data = '';
    if($data = file("./allowed/".$file)) {
        for ($i = 0; $i < count($data); $i++) {
            $data[$i] = rtrim($data[$i], "\n\r");
        }
        var_dump($data);
        return $data;
    } else {
        return $data['err'] . "Error:{$file} not loaded";
    }
}

function populate_session_string_verified($value, $list, $type){

    echo"req host: ~{$value[1]}~";

    echo"<br>host control: ~{$list[0]}~";

    if ( in_array($value[1],$list) ){
        $_SESSION['conf'][$type] = $value[1];
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


function dump_data($data){
    echo'<pre>';
    print_r($data);
    echo'</pre>';
}

