<?php

class ProviderFactory
{
    private $provider = null;

    public function __construct($provider) {
        $this->loadProvider($provider);
    }

    public function setCredentials($login, $password) {
        $this->provider->setLogin($login);
        $this->provider->setPassword($password);
    }

    public function getContacts() {
        return $this->provider->getContacts();
    }

    public static function verifyProvider($email, $provider) {
        if (stripos($email, 'onet.pl') !== false
                || stripos($email, '@vp.pl') !== false
                || stripos($email, '@op.pl') !== false
                || stripos($email, 'amorki.pl') !== false
                || stripos($email, 'autograf.pl') !== false
                || stripos($email, 'buziaczek.pl') !== false
                || stripos($email, 'onet.eu') !== false) {
            return 'onet';
        }

        if (stripos($email, '@o2.pl') !== false
                || stripos($email, '@poczta.o2.pl') !== false
                || stripos($email, '@go2.pl') !== false
                || stripos($email, '@tlen.pl') !== false) {
            return 'o2';
        }

        if (stripos($email, '@wp.pl') !== false) {
            return 'wp';
        }

        if (stripos($email, 'interia.pl') !== false
                || stripos($email, 'poczta.fm') !== false
                || stripos($email, 'interia.eu') !== false
                || stripos($email, '1gb.pl') !== false
                || stripos($email, '2gb.pl') !== false
                || stripos($email, 'serwus.pl') !== false
                || stripos($email, 'akcja.pl') !== false
                || stripos($email, 'czateria.pl') !== false) {
            return 'interia';
        }

        return $provider;
    }

    private function loadProvider($provider) {
        require_once dirname(__FILE__).'/'.$provider.'.php';

        $this->provider = new $provider();
    }
}