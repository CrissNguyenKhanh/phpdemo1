<h1>day la file dashboard</h1><?php


$data = [
   'pagetitle' =>'Trang Dashboard'
];
layouts('header',$data);
$checklogin = false;

if(!isLogin()){
   redirect('?module=auth&action=login');
}

if(!defined('_CODE')){
   die('Acesss Denied...');
}
?>

<?php

layouts('footer',$data);
?>