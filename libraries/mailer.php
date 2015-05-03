<?php

class Mailer {
	private $mail;

    private $from = 'dolacz@popieramkukiza.pl';
    private $fromName = 'dolacz@popieramkukiza.pl';
    private $smtpUser = 'dolacz@popieramkukiza.pl';
    private $smtpPassword = SMTP_PASSWORD;
    private $smtpHost = SMTP_HOST;
	
	public function __construct() {
		require dirname(__FILE__).'/phpmailer/class.phpmailer.php';
		$mail = new PHPMailer();

		$mail->PluginDir = dirname(__FILE__).'/phpmailer/';
		$mail->From = $this->from; //adres naszego konta
		$mail->FromName = $this->fromName;//nagłówek From
		$mail->Host = $this->smtpHost;//adres serwera SMTP
		$mail->Mailer = 'smtp';
		$mail->Username = $this->smtpUser;//nazwa użytkownika
		$mail->Password = $this->smtpPassword;//nasze hasło do konta SMTP
		$mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->CharSet = 'UTF-8';
        $mail->Port = '465';
		$mail->SetLanguage('pl', '../includes/phpmailer/language/');
		$this->mail = $mail;
	}

	public function sendMessage($subject, $content, $email, $fromName) {
		$this->mail->Subject = $subject;
		$this->mail->Body = $content;
        $this->mail->IsHTML(true);
        $this->mail->FromName = $fromName;
		$this->mail->AddAddress($email, '');
		if(!$this->mail->Send()) {
            return false;
        }

		$this->mail->ClearAddresses();
		$this->mail->ClearAttachments();

		return true;
	}
}
