<?php
$param = array('spam' => array(10,20), 'pan' => array(100,200));
$encParam = json_encode($param);
$param2 = array('bacon' => array(30,40), 'egg' => array(32.2,12.5));
$encParam2 = json_encode($param2);
 
$cmd = "R --vanilla --slave --args '$encParam' '$encParam2' < sample.R";
//print_r($cmd);
exec($cmd, $response);


$res = $response[0];
//print_r($res);
print_r(json_decode($res));
