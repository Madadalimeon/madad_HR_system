<?php
session_start();
$OTP = $_SESSION['otp'];
echo $OTP;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
$mail = new PHPMailer(true);
try {
    $mail->SMTPDebug = 2;                                       
    $mail->isSMTP();                                            
    $mail->Host       = 'smtp.gmail.com';                    
    $mail->SMTPAuth   = true;                             
    $mail->Username   = 'madadalimemon90@gmail.com';                 
    $mail->Password   = 'hfcf ohbk htrg hgeg';                        
    $mail->SMTPSecure = 'tls';                              
    $mail->Port       = 587;  
    $mail->setFrom('madadalimemon90@gmail.com', 'HR system');           
    $mail->addAddress('madadalim903@gmail.com');
    $mail->addReplyTo('madadalimemon90@gmail.com', 'Name');
    $mail->isHTML(true);                                  
    $mail->Subject = 'Subject';
    $mail->Body    = "BELO your Verification code $OTP ";
    $mail->AltBody = 'Body in plain text for non-HTML mail clients';
    $mail->send();
    echo "Mail has been sent successfully!";
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

  header("Location:  index.php"); 
?>