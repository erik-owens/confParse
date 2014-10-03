<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set("log_errors", 1);
ini_set("error_log", "/www/zendsvr/htdocs/Test/Erik/dev1/php-error.log");
date_default_timezone_set('America/Los_Angeles');


class confParse {

    private $app_hosts = array();
    private $app_users = array();
    private $error;
    private $lines = array();
    private $data;
    public $session_data = array();


    public function __construct() {
        $debug = false;
        $func = __FILE__ . '->' . __FUNCTION__ . '()';

        //$this->dump_session_conf_data();

        $this->app_hosts = $this->loadList('auth_hosts');

        $this->app_users = $this->loadList('auth_users');

        if ($debug) {
            $this->dump_data($this->app_hosts);
            $this->dump_data($this->app_users);
        }

    }// End - __construct


    public function parse($filename){
        $debug = true;

        if ($debug) {
            echo"\$confFile:";
            print_r($filename);
            echo"\n";
        }

        $this->lines = file($filename);

        foreach ( $this->lines as $line ) {

            if ( !preg_match("|^\#|", $line )) {
                if (strlen($line) > 1) {
                    $line = preg_replace('|\s|',"",$line);

                    $columns = preg_split('|=|',$line);

                    switch ($columns[0]) {
                        case "host":
                            $this->error .= $this->populateSessionStringVerified($columns,$this->app_hosts, 'host');
                            break;
                        case 'user':
                            $this->error .= $this->populateSessionStringVerified($columns,$this->app_users, 'user');
                            break;
                        case 'server_id':
                            //populateSessionInt($columns);
                        case 'server_load_alarm':
                        case 'log_file_path':
                            $this->error .= $this->populateSessionString($columns);
                            break;
                        case 'send_notifications':
                        case 'verbose':
                        case 'test_mode':
                        case 'debug_mode':
                            $this->error .= $this->populateSessionBool($columns);
                            break;
                    }

                }
            }
        } // foreach $line end
        $this->error .= $this->validateConf();

        if ( strlen($this->error) == 0 ){
            $this->populateSession();
        }

        if ( $debug ) {
            $this->dumpSessionData();
            $this->dumpSession();
        }


        if( strlen($this->error) < 1 ){
        } else {
            return $this->error;
        }

    }

    private function validateConf(){
        if ( !isset($this->session_data['conf']['host']) || !isset($this->session_data['conf']['user']) || !isset($this->session_data['conf']['server_id']) ) {
            return "\n<br>Required Field Missing : host, user or server_id<br>";
        }
    }

    private function populateSession(){
        if ( $_SESSION['conf'] = $this->session_data['conf'] ) {
            // $_SESSION is set
        } else {
            return "\n<br>\$_SESSION['conf'] not populated.";
        }
    }

    private function loadList($file){
        $data = '';
        if($data = file("./allowed/".$file)) {
            for ($i = 0; $i < count($data); $i++) {
                $data[$i] = rtrim($data[$i], "\n\r");
            }
            //var_dump($data);
            return $data;
        } else {
            return "Error:{$file} not loaded";
        }
    }

    private function populateSessionStringVerified($value, $list, $type) {

        if ( in_array($value[1],$list) ){
            $this->session_data['conf'][$type] = $value[1];
        } else {
            return "\n<br>Data mismatch Field: " . $value[0] . " = " . $value[1];
        }


    }

    private function populateSessionString($columns){
        if(isset($columns[0]) && isset($columns[1])) {
            $this->session_data['conf'][$columns[0]] = $columns[1];
        }else{
            return "\n<br>Data mismatch Field: " . $value[0] . " = " . $value[1];
        }
    }


    private function populateSessionBool($columns){
        if( !preg_match('/true|yes|on|false|no|off/',$columns[1]) ) {
            return "\n<br>Data mismatch Field: " . $columns[0] . " = " . $columns[1];
        } else {
            if (preg_match('/true|yes|on/', $columns[1])) {
                $this->session_data['conf'][$columns[0]] = 'true';
            } else {
                $this->session_data['conf'][$columns[0]] = 'false';
            }
        }
    }

    private function populateSessionInt($host, $allowed_hosts){

        if ( in_array($host[1],$allowed_hosts) ){
            $this->session_data['conf']['host'] = $host[1];
            return true;
        } else {
            return false;
        }


    }

    private function dumpSessionData(){
        echo"<br><hr><br>\$session_data before population of \$_SESSION['conf']:<br>";
        echo'<pre>';
        print_r($this->session_data);
        echo'</pre>';
    }

    private function dumpSession(){
        echo"<br><hr><br>\$_SESSION:<br>";
        echo'<pre>';
        print_r($_SESSION);
        echo'</pre>';
    }


    private function dump_data($data){
        echo'<pre>';
        print_r($data);
        echo'</pre>';
    }





} // end class