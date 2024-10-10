<?php


 if(!defined('_CODE')){
    die('Acesss Denied...');
 }
function setSession($key,$value){
   $_SESSION[$key] =$value;
   return $_SESSION[$key];
}
function getSession($key =''){
   if(empty($key)){
      return $_SESSION;
   } else{
      if(isset($_SESSION[$key])){
         return $_SESSION[$key];
      }
   }
}
function removeSession($key = '')
{
if(empty($key)){
   session_destroy();
   return true;
}else{
   if(isset($_SESSION[$key])){
      unset($_SESSION[$key]);
      return true; 
   }
}

}
// ham gan flas data
function setFlashData($key,$value){
   $key = 'flash_'.$key;
   return setSession($key,$value);

}
// ham doc flash data
function getFlashData($key){
   $key = 'flash_'.$key;
$data = getSession($key);
   removeSession($key);
   return $data;
}