confParse
=========
Use the test.php file to test the confParse class.

//Test for site1.conf
$conf_handler = new confParse();
$error = $conf_handler->parse('./conf/site1.conf');

There are test conf files for sites 1, 2 & 3

site1 works as expected.
site2 has an incorrect fieldname where host is spelled incorrcely.
site3 has an incorrect fieldname where server_id is missing.

I wanted this to be close to sometiong that could be used in the real world  so I populated the conf key->value pairs into $_SESSION['conf'] on success.

Note: host, user & server_id are required, if they are not present that config will not make it to the $_SESSION['conf'] array;
