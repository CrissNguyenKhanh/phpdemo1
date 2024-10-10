<?php
require_once 'D:\xamppoii\htdocs\baithigiuaki\config.php';

 if(!defined('_CODE')){
    die('Acesss Denied...');
 }



 try
{
  if(class_exists('PDO')){

   $dsn = 'mysql:dbname='._DB.';host='._HOST;
   $options=[

      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
   ];
   $conn =  new PDO($dsn,_USER , _PASS);


  


  }ELSE{

  }
}catch(Exeption $e){
   
}
