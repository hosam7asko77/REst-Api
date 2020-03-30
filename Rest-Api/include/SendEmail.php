<?php
require_once("../include/mail/PHPMailerAutoload.php");
class SendMail{

  public function sendToken($email,$verifyEmail){
    $mail = new PHPMailer;
        //$mail->SMTPDebug = 3;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'mazadiproject1993@gmail.com';
        $mail->Password = 'hosam7asko77';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom('mazadiproject1993@gmail.com', 'Mazadi');
        $mail->addAddress($email);
        $mail->addReplyTo('mazadiproject1993@gmail.com');
        $mail->isHTML(true);
        $mail->Subject = 'Get Password';

        $mail->Body    = 'This last step to register you have register with email :<br> <font size="3" color="blue">
        '.$email.' </font> <br> <a href="http://192.168.0.6/Slim/Rest-Api/TestVerify.html?token='.$verifyEmail.'">click here to confirm</a>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      if(!$mail->send()){
        return false;
      }else{
        return true;
      }
  }


}

 ?>
