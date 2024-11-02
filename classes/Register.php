<?php

include_once '../lib/Database.php';
include_once '../helpers/Format.php';

include_once '../phpmailer/Exception.php';
include_once '../phpmailer/PHPMailer.php';
include_once '../phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Register
{
    public $db;
    public $fr;

    public function __construct()
    {
        $this->db = new Database();
        $this->fr = new Format();
    }

    public function AddUser($data)
    {
        $name = $this->fr->validation($data['name']);
        $phone = $this->fr->validation($data['phone']);
        $email = $this->fr->validation($data['email']);
        $password = $this->fr->validation(md5($data['password']));
        $v_token = md5(rand());

        if (empty($name) || empty($phone) || empty($email) || empty($password)) {
            return "Fields must not be empty";
        } else {
            $email_query = "SELECT * FROM woo_user WHERE email = '$email'";
            $check_email = $this->db->select($email_query);

            if ($check_email > 0) {
                return "This email is already in use";
            } else {
                $insert_query = "INSERT INTO woo_user(username, email, phone, password, v_token) VALUES('$name','$email','$phone','$password','$v_token')";
                $insert_row = $this->db->insert($insert_query);
                if ($insert_row) {
                    $this->sentmail_verifi($name, $email, $v_token);
                    return "Registration successful. Please check your email inbox to verify your email.";
                } else {
                    return "Registration failed";
                }
            }
        }
    }

    // Move the email sending function outside AddUser for better code organization
    public function sentmail_verifi($name, $email, $v_token)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'alamincanva1@gmail.com';    // Replace with your email
            $mail->Password = 'ldguijwptdsrwuzs';       // Replace with App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('alamincanva1@gmail.com', $name);
            $mail->addAddress($email, $name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification';
            $mail->Body = "<h2>You have registered with Web Master</h2>
                           <h5>Verify your email address to log in. Please click the link below:</h5>
                           <a href='http://localhost/php-oop-practice/admin/verifi-email.php?token=$v_token'>Click here</a>";

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>