<?php
$osobName = 'osób';
$zaproszenName = 'znajomych';

if ($counts['senders'] === 1) {
    $contactsName = 'osoba';
} else if ($counts['senders'] >= 2 && $counts['senders'] <= 4) {
    $contactsName = 'osoby';
} else if ($counts['senders'] >= 22 && in_array($counts['senders'] % 10, array(2, 3, 4))) {
    $contactsName = 'osoby';
}

if ($counts['receivers'] === 1) {
    $zaproszenName = 'znajomego';
} else if ($counts['receivers'] >= 2 && $counts['receivers'] <= 4) {
    $zaproszenName = 'znajomych';
} else if ($counts['receivers'] >= 22 && in_array($counts['receivers'] % 10, array(2, 3, 4))) {
    $zaproszenName = 'znajomych';
}

?>
        </div>
        <br/><br/><br/>

        <div class="container">
            <div class="col-lg-2 col-md-2 "> </div>
            <div class="col-lg-8 col-md-8  top-box">
                <h3 class="text-center mytxt">Razem jesteśmy silni!</h3>
                <div class="qst" style="margin-right: 10%;">
                    <h4 class="text-center"> <?php echo $counts['senders'] . ' ' . $osobName; ?> powiadomiło <?php echo $counts['receivers'] . ' ' . $zaproszenName; ?></h4>
                </div>
            </div>
        </div>
        <br/><br/><br/>

        <div class="container">
            <div class="col-lg-2 col-md-2 "> </div>
            <div class="col-lg-8 col-md-8  top-box">
                <h3 class="text-center mytxt">Przydatne strony</h3>
                  <div class="qst">
                      <p>- <a href="http://jow.pl/abc/" target="_blank">ABC Jednomandatowych Okręgów Wyborczych</a></p>
                      <p>- <a href="http://kukiz.org" target="_blank">Oficjalna strona Pawła Kukiza</a></p>
                      <p>- <a href="https://www.facebook.com/kukizpawel" target="_blank">Paweł Kukiz na facebooku</a></p>
                      <p>- <a href="https://www.youtube.com/watch?v=21WefGf6hxk" target="_blank">Potrafisz Polsko! - oficjalny klip Kampanii Pawła Kukiza</a></p>
                   </div>
            </div>
        </div>
        <br/><br/><br/>

        <div class="container ftr">
            <div class="row row-centered">
                    <div class="col-lg-9">
                        Projekt powstał z inicjatywy obywatelskiej. <br />
                        Dane użytkowników nie są gromadzone i w żaden sposób nie będą wykorzystane do innych celów. <br />Dla niedowiarków udostępniamy publicznie <a href="https://github.com/MichalKot89/popieramkukiza.pl" target="_blank"><u>kod źródłowy strony</u></a>.
                    </div>
                    <div class="col-lg-3 text-right">
                         Autor: <a href="https://au.linkedin.com/pub/micha%C5%82-kot/52/254/693"><u>Michał Kot</u></a><br /> Sugestie: sugestie@popieramkukiza.pl
                    </div>
            </div>
        </div>
    </body>
</html>