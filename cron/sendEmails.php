<?php

require_once dirname(__FILE__).'/../libraries/database.php';
require_once dirname(__FILE__).'/../libraries/mailer.php';

$database = new Database();

$emails = $database->getEmailsToSend();

if (count($emails) === 0) {
    return;
}

$content = file_get_contents(dirname(__FILE__).'/../templates/email.php');

$mailer = new Mailer();

$sent = array();

foreach ($emails as $email) {
    $preparedContent = str_replace(array('{name}'), $email['name'], $content);

$title1 = "Pomożesz?";
$title2 = "Dołącz do walki o lepsze jutro";
if(hexdec(substr(md5($email['receiver']), 1, 8)) % 2 > 0) {
	$title = $title1;
}
else {
	$title = $title2;
}
    if (!$mailer->sendMessage($title, $preparedContent, $email['receiver'], $email['name'])) {
        break;
    } else {
        $sent[] = $email['receiver'];
    }
}

if (count($sent) > 0) {
    $database->markAsSent($sent);
}