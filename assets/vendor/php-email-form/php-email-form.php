<?php

class PHP_Email_Form {
  public $to = '';
  public $from_name = '';
  public $from_email = '';
  public $subject = '';
  public $smtp = array();
  public $messages = array();
  public $ajax = false;

  // Agrega mensajes al correo
  public function add_message($content, $label, $priority = 0) {
    $this->messages[] = array(
      'content' => $content,
      'label' => $label,
      'priority' => $priority
    );
  }

  // Envía el correo
  public function send() {
    if (empty($this->to) || empty($this->from_email) || empty($this->subject)) {
      return 'Faltan parámetros esenciales!';
    }

    // Configuración del correo
    $email_headers = "From: {$this->from_name} <{$this->from_email}>\r\n";
    $email_headers .= "Reply-To: {$this->from_email}\r\n";
    $email_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Cuerpo del correo
    $email_body = "Tienes un nuevo mensaje:\n\n";
    foreach ($this->messages as $message) {
      $email_body .= "{$message['label']}: {$message['content']}\n";
    }

    // Si se configuró SMTP
    if (!empty($this->smtp)) {
      return $this->send_smtp($email_body);
    }

    // Envío estándar (mail function)
    if (mail($this->to, $this->subject, $email_body, $email_headers)) {
      return 'OK';
    } else {
      return 'Error al enviar el correo!';
    }
  }

  // Envío mediante SMTP
  private function send_smtp($email_body) {
    $host = $this->smtp['host'];
    $username = $this->smtp['username'];
    $password = $this->smtp['password'];
    $port = $this->smtp['port'];

    // Configuración de PHPMailer
    require 'PHPMailer-master/src/PHPMailer.php';
    require 'PHPMailer-master/src/SMTP.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host = $host;
    $mail->SMTPAuth = true;
    $mail->Username = $username;
    $mail->Password = $password;
    $mail->SMTPSecure = 'tls';
    $mail->Port = $port;

    $mail->setFrom($this->from_email, $this->from_name);
    $mail->addAddress($this->to);
    $mail->Subject = $this->subject;
    $mail->Body = $email_body;

    if ($mail->send()) {
      return 'OK';
    } else {
      return 'Error SMTP: ' . $mail->ErrorInfo;
    }
  }
}
