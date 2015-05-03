            <div class="container col-centered" style="margin-top: -18px !important;">
              <div class="col-lg-8 col-md-8 col-centered top-box" style="background:#fff;">
           		<h3 class="text-center mytxt">Jak To Działa?</h3>
                  <div class="qst">
                      <p>1. Wybierasz znajomych, których chcesz powiadomić o poparciu dla Pawła Kukiza</p>
                      <p>2. Zapraszamy Twoich znajomych do dołączenia do akcji</p>
                      <p>3. Twoi znajomi zapraszają swoich znajomych</p>
                      <p>4. Razem możemy coś zmienić</p>
                   </div>
                </div>
        	</div>
            <br/><br/><br/>

            <div class="container">
                <div class="col-lg-2 col-md-2 "> </div>
                <div class="col-lg-8 col-md-8  top-box">
                    <h3 class="text-center mytxt">Wybierz swoją skrzynkę pocztową</h3>
                    <div class="qst" style="margin-right: 10%;">
                        <img src="img/email.jpg" class="img-responsive img-clickable" data-id="onet" />
                        <img src="img/s (1).jpg" class="img-responsive img-clickable" data-id="wp" />
                        <img src="img/s (2).jpg" class="img-responsive img-clickable" data-id="interia" />
                        <img src="img/s (3).jpg" class="img-responsive img-clickable" data-id="gmail" />
                        <img src="img/s (4).jpg" class="img-responsive img-clickable" data-id="o2" />

                        <br /><br />

                        <div id="emailFormMessage" style="text-align: center"> </div>

                        <br/><br/>

                        <div style="display: none" id="emailFormContainer">
                            <form method="post" action="index.php?action=step2" id="emailForm">
                                <div class="form-group">
                                    <label for="name">Twoje imię i nazwisko</label>
                                    <input type="text" class="form-control" name="name" />
                                </div>
                                <div class="form-group">
                                    <label for="email">Adres email</label>
                                    <input type="email" class="form-control" name="email" />
                                </div>
                                <div class="form-group">
                                    <label for="password">Hasło do poczty*</label>
                                    <input type="password" class="form-control" name="password" />
                                </div>

                                <div id="emailFormIcon"></div>
                                    <label style="font-size:12px; color: grey;">* Hasło nie jest dla nas widoczne, a po pobraniu kontaktów jest natychmiast usunięte z systemu i zapomniane.<br />
                                    Jako dowód, udostępniamy publicznie <a href="https://github.com/MichalKot89/popieramkukiza.pl" target="_blank">kod źródłowy strony</a>.
                                    </label>
                                <button type="submit" class="btn btn-default btn pull-right">Wybierz kontakty</button>

                                <input type="hidden" name="provider" id="emailProvider" />

                                <div style="clear:both;"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script type="text/javascript">
                var clientId = '511788824835-88srqbbunvtpkgkq0bldf4kt8nfmk68d.apps.googleusercontent.com';
                var scopes = 'https://www.googleapis.com/auth/contacts.readonly';

                jQuery('.img-clickable').on('click', function() {
                    var selectedId = this.getAttribute('data-id');

                    jQuery('#emailProvider').val(selectedId);

                    if (selectedId === 'gmail') {
                        var api = new GmailAPI();

                        api.auth();

                        return false;
                    }

                    jQuery('.img-clickable').each(function() {
                        if (this.getAttribute('data-id') === selectedId) {
                            jQuery(this).animate({opacity: 1}, 500);
                        } else {
                            jQuery(this).animate({opacity: 0.3}, 500);
                        }

                        if (jQuery('#emailFormContainer').css('display') === 'none') {
                            jQuery('#emailFormContainer').fadeIn(1000);
                        }
                    });
                });

                jQuery('#emailForm').on('submit', function() {
                    var email = jQuery("input[name='email']").val().trim();
                    if (email === '') {
                        alert('Musisz podać swój adres email.');

                        return false;
                    }

                    var name = jQuery("input[name='name']").val().trim();
                    if (name === '') {
                        alert('Musisz podać swoje imię.');

                        return false;
                    }

                    var password = jQuery("input[name='password']").val().trim();
                    if (password === '') {
                        alert('Musisz podać swoje hasło.');

                        return false;
                    }

                    var provider = jQuery("input[name='provider']").val().trim();
                    if (provider === '') {
                        alert('Musisz wybrać swojego dostawcę poczty.');

                        return false;
                    }

                    jQuery.ajax({
                        url: '/index.php?action=step2',
                        data: {
                            name: name,
                            email: email,
                            password: password,
                            provider: provider
                        },
                        dataType: 'html',
                        method: 'POST',
                        beforeSend: function (xhr) {
                            jQuery('#emailFormIcon').html('<img class="email-form-icon" style="float: left" src="/img/loading.gif" />');
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            jQuery('#emailFormIcon').html(' ');
                            jQuery('#emailFormMessage').html('<div class="alert-danger alert--center">Niestety wystąpił błąd. Spróbuj ponownie.</div>');
                        },
                        success: function (data, textStatus, jqXHR) {
                            jQuery('#emailFormIcon').html(' ');
                            jQuery('#emailFormMessage').html(' ');
    
                            if (data.indexOf('alert-') === -1) {
                                jQuery('#mainContainer').html(data);
                            } else {
                                jQuery('#emailFormMessage').html(data);
                            }
                        }
                    });

                    return false;
                });

                function GmailAPI() {
                    return {
                        auth: function() {
                            gapi.auth.authorize({client_id: clientId, scope: scopes, immediate: false, approval_prompt: 'force', authuser: -1}, this.handleAuthResult);
                        },

                        handleAuthResult: function(result) {
                            if (typeof result.error !== 'undefined') {
                                jQuery('#emailFormMessage').html('<div class="alert-danger alert--center">Nie udało się pobrać kontaktów. Spróbuj ponownie</div>');

                                return;
                            } else {
                                jQuery.ajax({
                                    url: '/index.php?action=step2',
                                    data: {
                                        provider: 'gmail',
                                        gmailtoken: result.access_token
                                    },
                                    dataType: 'html',
                                    method: 'POST',
                                    beforeSend: function (xhr) {
                                        jQuery('#emailFormMessage').html('<img class="email-form-icon" src="/img/loading.gif" />');
                                    },
                                    error: function (jqXHR, textStatus, errorThrown) {
                                        jQuery('#emailFormMessage').html('<div class="alert-danger alert--center">Niestety wystąpił błąd. Spróbuj ponownie.</div>');
                                    },
                                    success: function (data, textStatus, jqXHR) {
                                        if (data.indexOf('alert-') === -1) {
                                            jQuery('#emailFormMessage').html(' ');

                                            jQuery('#mainContainer').html(data);
                                        } else {
                                            jQuery('#emailFormMessage').html(data);
                                        }
                                    }
                                });
                            }
                        }
                    };
                }
            </script>
            <script src="https://apis.google.com/js/client.js?onload=handleClientLoad"></script>