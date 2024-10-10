<?php


 if(!defined('_CODE')){
    die('Acesss Denied...');
 
   }
   use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


   function layouts($_layoutName = 'header' ,$data = []){
      if(file_exists(_WEB_PATH_TEMPLATES.'/layout/'.$_layoutName .'.php')){
         require_once _WEB_PATH_TEMPLATES.'/layout/'.$_layoutName .'.php';

      }
   }
 // ham gui maik
 function sendMail($to,$subject,$content){





$mail = new PHPMailer(true);

try {
    //Server settings
    
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'quockhanhdz234@gmail.com';                     //SMTP username
    $mail->Password   = 'mokh eyns cvpq pnpn';
                                    //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('quockhanhdz295@gmail.com', 'Quockhanh');
    $mail->addAddress($to);     //Add a recipient
    

    // cerificate and veri failed
    $mail->SMTPOptions = array(
      'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
      )
      );

    //Content
    $mail ->CharSet = "UTF-8";
    $mail->isHTML(true);   
                                   //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $content;
    

   $sendmail =     $mail->send();
   if($sendmail){
      return $sendmail;
   }
   //  echo 'Gửi thành công';
} catch (Exception $e) {
    echo "Gửi mail thất bại. Mailer Error: {$mail->ErrorInfo}";
}
 }
 // kiem tra phuong thuc get 
 function isGet(){
   if($_SERVER['REQUEST_METHOD']== 'GET'){
     return true;
      
   }
   return false;
 }
 function isPost(){
   if($_SERVER['REQUEST_METHOD']== 'POST'){
     return true;
      
   }
   return false;
 }
 function pre($value){
 echo "<pre>";
 print_r($value);
 echo "</pre>";

 }
// hàm filter loc du lieu
function filter() {
   $filterArr =[];
   if(isGet()){
      if(!empty($_GET)){
         foreach($_GET as $key =>$value){
            $key =strip_tags($key);
               if(is_array($value)){
                  $filterArr[$key] = filter_input(INPUT_GET,$key,FILTER_SANITIZE_SPECIAL_CHARS,FILTER_REQUIRE_ARRAY);
               }else{
                  $filterArr[$key] = filter_input(INPUT_GET,$key,FILTER_SANITIZE_SPECIAL_CHARS);
               }
               
   
         }
      }
      
   }
   if(isPost()){
      if(!empty($_POST)){
         foreach($_POST as $key =>$value){
            $key =strip_tags($key);
               if(is_array($value)){
                  $filterArr[$key] = filter_input(INPUT_POST,$key,FILTER_SANITIZE_SPECIAL_CHARS,FILTER_REQUIRE_ARRAY);
               }else{
                  $filterArr[$key] = filter_input(INPUT_POST,$key,FILTER_SANITIZE_SPECIAL_CHARS);
               }
   
         }
      }
      
   }
   return $filterArr;
   
}
 function isEmail($email){
   $checkmail = filter_var($email,FILTER_VALIDATE_EMAIL);
   return $checkmail;

}
function checkNumber($number){
   $checknumber = filter_var($number,FILTER_VALIDATE_INT);
   return $checknumber;
  
}
function checkFloatNumber($number){
   $checkfloat = filter_Var($number,FILTER_VALIDATE_FLOAT);
return $checkfloat;

}
function kiemtrasodienthoai($number){
   $checkzero  = false;
   $checknumber = false;
   if($number[0] == '0'){
      $checkzero = true;
      $number = substr($number,1);

   }//
   $checknumber = false;
   if( (strlen($number) == 9 )){
      $checknumber =true;
   }
   if($checkzero  && $checknumber){
         return true;
   }
   return false;
}
// validate loi
function getSmg($smg, $type = 'success') {
   echo '<div class="alert alert-' . htmlspecialchars($type) . '">';
   echo htmlspecialchars($smg);
   echo '</div>';
}

// ham chuyen huong
function redirect($path='index.php')
{
header("location:$path");
exit;
}
//ham thong bao loi 
function form_erorr($fileName , $beforeHtml = '' , $afterHtml='',$errors){
   return  !empty($errors[$fileName]) 
   ?      $beforeHtml . reset($errors[$fileName]) . $afterHtml 
   : null;
}
//ham hien thi du lieu cu 
function old_form($fileName,$old ,$default=null){

         return    !empty($old[$fileName])
              ? $old[$fileName]:$default;
            
        
}
//ham kiem tra trang thai dang nhap 
function isLogin(){
   $checklogin = false
   ;
 if(getSession('loginToken')){
   $tokenlogin = getSession('loginToken');
   $querrytoken = getOneRaw("SELECT user_id from tokenlogin WHERE token = '$tokenlogin'");

 if(!empty($querrytoken)){
   $checklogin =true;

 }else{
   removeSession('loginToken');

 }
}
return $checklogin;
}