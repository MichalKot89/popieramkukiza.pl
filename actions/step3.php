<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['emails']) || !is_array($_POST['emails'])) {
        echo '<div class="alert-danger alert--center">Nie wybrałeś żadnego adresu!</div>';

        return;
    }

    if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || !isset($_POST['name'])) {
        echo '<div class="alert-danger alert--center">Nieprawidłowe zapytanie!</div>';

        return;
    }

    $name = preg_replace('[^A-Za-z0-9\s]', '', $_POST['name']);

    $emails = array();

    foreach ($_POST['emails'] as $email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emails[] = $email;
        }
    }

    if (count($emails) === 0) {
        echo '<div class="alert-danger alert--center">Nie wybrałeś żadnego prawidłowego adresu!</div>';

        return;
    }

    $database->addEmails($_POST['email'], $name, $emails);

?>
    <div class="alert-success alert--center">Dziękujemy! Wiadomości zostaną wysłane w ciągu kilku minut.</div>
<?php
}
