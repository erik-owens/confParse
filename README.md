confParse
=========
Use the test.php file to test the confParse class.

//Test for site1.conf
$conf_handler = new confParse();
$error = $conf_handler->parse('./conf/site1.conf');

There are test conf files for sites 1, 2 & 3
