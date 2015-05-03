<?php

require_once dirname(__FILE__).'/../libraries/providerfactory.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['provider']) || !in_array($_POST['provider'], array('o2', 'onet', 'wp', 'interia', 'gmail'))) {
        echo '<div class="alert-danger alert--center">Błędny dostawca!</div>';

        return;
    }

    if ($_POST['provider'] === 'gmail') {
        if (!isset($_POST['gmailtoken'])) {
            echo '<div class="alert-danger alert--center">Nie udało się zalogować! Spróbuj ponownie</div>';

            return;
        }

        require_once dirname(__FILE__).'/../libraries/gmail.php';

        $api = new Gmail();
        
        $api->setToken($_POST['gmailtoken']);

        $data = $api->getData();
        
        $contacts = $data['contacts'];
        $name = $data['name'];
        $email = $data['email'];
    } else {
        if (!isset($_POST['email']) || !isset($_POST['password'])) {
            echo '<div class="alert-danger alert--center">Musisz podać dane logowania!</div>';

            return;
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            echo '<div class="alert-danger alert--center">Nieprawidłowy adres! Spróbuj ponownie</div>';

            return;
        }

        $name = preg_replace('[^A-Za-z0-9\s]', '', $_POST['name']);

        $provider = ProviderFactory::verifyProvider($_POST['email'], $_POST['provider']);

        $factory = new ProviderFactory($provider);

        $factory->setCredentials($_POST['email'], $_POST['password']);

        $contacts = $factory->getContacts();

        $email = $_POST['email'];
    }


    if ($contacts === false) {
        echo '<div class="alert-danger alert--center">Nie udało się zalogować! Spróbuj ponownie</div>';
        
        return;
    }
}

$contactsName = 'kontaktów';
$contactsCount = count($contacts);

if ($contactsCount === 1) {
    $contactsName = 'kontakt';
    $contactsCount = 'jeden';
} else if ($contactsCount >= 2 && $contactsCount <= 4) {
    $contactsName = 'kontakty';
} else if ($contactsCount > 22 && in_array($contactsCount % 10, array(2, 3, 4))) {
    $contactsName = 'kontakty';
}

?>

            <br/><br/><br/>
            <div class="container">
                <div class="col-lg-2 col-md-2 "> </div>
                <div class="col-lg-8 col-md-8  top-box" style="padding: 0;">
                    <h3 class="text-center mytxt">Znaleziono <?php echo $contactsCount; ?> <?php echo $contactsName; ?></h3>

                    <input type="hidden" name="email" value="<?php echo $email; ?>" id="formEmail" />
                    <input type="hidden" name="name" value="<?php echo $name; ?>" id="formName" />

                    <div id="updt">
                        <div class="checkbox clk">
                            <label>
                                <input type="checkbox" value="" checked="checked" id="checkAll" />
                                Zaproś wszystkich
                             </label>
                        </div>
<?php
    if (is_array($contacts) && !empty($contacts)) {
        foreach ($contacts as $key => $contact) {
?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="<?php echo $contact['email']; ?>" name="emails[]" checked="checked" class="emailCheckbox" />
                                <?php echo $contact['email']; ?> <?php echo !empty($contact['name']) ? '('.$contact['name'].')' : ''; ?>
                            </label>
                        </div>
<?php
        }
    }
?>
                    </div>
                    <div class="text-center">
                        <div id="emailFormIcon"></div>
                        <button class="btn btn-default text-center" id="sendEmails">Wyślij</button>
                    </div>
                    <div style="clear:both;"></div>
                </div>
            </div>
            <script type="text/javascript">
                jQuery('#checkAll').on('change', function() {
                    jQuery('.emailCheckbox').prop('checked', this.checked);
                });

                jQuery('#sendEmails').on('click', function() {
                    var emails = [];

                    jQuery('.emailCheckbox').each(function() {
                        if (this.checked === true) {
                            emails[emails.length] = this.value;
                        }
                    });
                    
                    if (emails.length === 0) {
                        alert('Musisz najpierw kogoś wybrać.');

                        return false;
                    }

                    jQuery.ajax({
                        url: '/index.php?action=step3',
                        data: {
                            emails: emails,
                            email: jQuery('#formEmail').val(),
                            name: jQuery('#formName').val()
                        },
                        dataType: 'html',
                        method: 'POST',
                        beforeSend: function (xhr) {
                            jQuery('#emailFormIcon').html('<img class="email-form-icon" src="/img/loading.gif" />');
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            alert('Niestety wystąpił błąd. Spróbuj ponownie.');
                        },
                        complete: function (jqXHR, textStatus ) {
                            jQuery('#emailFormIcon').html(' ');
                        },
                        success: function (data, textStatus, jqXHR) {
                            jQuery('#mainContainer').html(data);
                        }
                    });

                    return false;
                });
            </script>