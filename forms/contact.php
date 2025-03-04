<?php
  $receiving_email_address = 'info@phycotech.bio';

  if (file_exists($php_email_form = '../assets/vendor/php-email-form/php-email-form.php')) {
    include($php_email_form);
  } else {
    die('Unable to load the "PHP Email Form" Library!');
  }

  $contact = new PHP_Email_Form;
  $contact->ajax = true;

  $contact->to = $receiving_email_address;
  $contact->from_name = 'Proyectos Dev Uruguay';
  $contact->from_email = 'proyectosdevuruguay@gmail.com';
  $contact->subject = 'Contacto desde el sitio web';

  // Configuración SMTP para Gmail
  $contact->smtp = array(
    'host' => 'smtp.gmail.com',
    'username' => 'proyectosdevuruguay@gmail.com',   // Cambia esto por tu correo de Gmail
    'password' => 'knan dgqv abxr zkmi',  // Usa una contraseña de aplicación, no la contraseña normal
    'port' => '587',
    'encryption' => 'tls'                 // Mantén TLS para Gmail
  );
  $contact->add_message($_POST['subject'], 'Subject');
  $contact->add_message($_POST['name'], 'From');
  $contact->add_message($_POST['email'], 'Email');
  $contact->add_message($_POST['message'], 'Message', 10);

  echo $contact->send();
