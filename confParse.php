<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set("log_errors", 1);
ini_set("error_log", "/www/zendsvr/htdocs/Test/Erik/dev1/php-error.log");
date_default_timezone_set('America/Los_Angeles');

/**
* parseConf is a class that parses a conf file and populates $_SESSION['conf'] 
* if all required variables pass validation
*
* The required fields are host, user & server_id
* Look at test.php to see an example usage.
*
* @version    Release: 1
*/
class confParse {

    private $auth_hosts = array();
    private $auth_users = array();
    private $error;
    private $lines = array();
    private $data;
    public $session_data = array();


    public function __construct() {
        $debug = false;

        // prepair the validation arrays for hosts and users
        $this->error .= $this->loadList('auth_hosts');
        $this->error .= $this->loadList('auth_users');

        if ($debug) {
            $this->dump_data($this->auth_hosts);
            $this->dump_data($this->auth_users);
        }

    }// End - __construct


	/* function parse
	 * Parses and validates the conf file
	 * Arg0: $filemane - the relative path and name of the conf file
	 * Returns: an error string with all validation errors.  On success this is blank
	 * On Success the parsed $session_data['conf'] is copied to $_SESSION
	 */
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
                            $this->error .= $this->populateSessionStringVerified($columns,$this->auth_hosts, 'host');
                            break;
                        case 'user':
                            $this->error .= $this->populateSessionStringVerified($columns,$this->auth_users, 'user');
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


	/* function validateConf
	 * Returns: an error string if one or more required fileds are missing.  On success this is blank
	 * Required fileds are host, user & server_id
	 */
    private function validateConf(){
        if ( !isset($this->session_data['conf']['host']) 
          || !isset($this->session_data['conf']['user']) 
          || !isset($this->session_data['conf']['host']) ) {
            return "\n<br>Required Field Missing : host, user or server_id<br>";
        }
    }


	/* function populateSession
	 * Copies $this->session_data['conf'] to $_SESSION['conf'] 
	 * Returns: an error string is $_SESSION['conf'] is not populated.  On success this is blank
	 */
    private function populateSession(){
        if ( $_SESSION['conf'] = $this->session_data['conf'] ) {
            // $_SESSION is set
        } else {
            return "\n<br>\$_SESSION['conf'] not populated.";
        }
    }


	/* function loadList
	 * uses file() to load teh validation list file for hosts or users
	 * Arg0: $file - name of the auth file
	 * Returns: an error string if loading fails.  On success this is blank
	 * On Success set $this->$file = $data;
	 */
    private function loadList($file){
        $data = '';
        if($data = file("./allowed/".$file)) {
        		// OS X adds an extra space to the end of each array element when one uses file()
            for ($i = 0; $i < count($data); $i++) {
                $data[$i] = rtrim($data[$i], "\n\r");
            }
            //var_dump($data);
            $this->$file = $data;
        } else {
            return "\n<br>Error:{$file} not loaded";
        }
    }


	/* function populateSessionStringVerified
	 * Make sure that hosts or users are in the auth_hosts or auth_users lists
	 * Arg0: $value - Array with [Key] and [value] to validate
	 * Arg1: $list - list of users or hosts
	 * Arg2: $type - conf field name
	 * Returns: an error string if loading fails.  On success this is blank
	 * On Success set $this->$file = $data;
	 */
    private function populateSessionStringVerified($value, $list, $type) {

        if ( in_array($value[1],$list) ){
            $this->session_data['conf'][$type] = $value[1];
        } else {
            return "\n<br>Data mismatch Field: " . $value[0] . " = " . $value[1];
        }
    }


	/* function populateSessionString
	 * populates string values into $session_data['conf'] 
	 * Arg0: $columns - Array with [Key] and [value] to populate
	 * Returns: an error string if loading fails.  On success this is blank
	 * On Success add this key->value pair to $session_data['conf']
	 */
    private function populateSessionString($columns){
        if(isset($columns[0]) && isset($columns[1])) {
            $this->session_data['conf'][$columns[0]] = $columns[1];
        }else{
            return "\n<br>Data mismatch Field: " . $value[0] . " = " . $value[1];
        }
    }


	/* function populateSessionBool
	 * populates string bool values into $session_data['conf'] 
	 * Arg0: $columns - Array with [Key] and [value] to populate
	 * Returns: an error string if loading fails.  On success this is blank
	 * true  = true,yes,on
	 * false = false,no,off
	 * On Success add this key->value pair to $session_data['conf']
	 */
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


    private function dumpSessionData(){
        echo"<br><hr><br>\$session_data:<br>";
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