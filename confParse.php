<?php
session_start();
/**
 * Created by PhpStorm.
 * User: erik
 * Date: 9/30/14
 * Time: 2:36 PM
 */




$lines = file('site1.conf');

    echo"<pre>";
    var_dump($lines);
    echo"</pre>";



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


            switch ($column[0]) {
                case 'host':
                case 'server_id':
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


/*
host
server_id
server_load_alarm
case 'user':
case 'verbose':
case 'test_mode':
case 'debug_mode':
case 'log_file_path':
case 'send_notifications':
*/


        }
    }
} // foreach $line end


function populate_session_string($columns){
    if(isset($columns[0]) && isset($columns[1])) {
        $_SESSION[$columns[0]] = $columns[1];
        return true;
    }else{
        return false;
    }
}


function populate_session_bool($columns){
    if(isset($columns[0]) && isset($columns[1])) {
        $_SESSION[$columns[0]] = $columns[1];
        return true;
    }else{
        return false;
    }
}