<?php
require_once "contactbase.php";

class Onet extends ContactBase
{
    public $config = array(
        'login' => array(
            'url' => 'https://konto.onet.pl/auth.html?app_id=poczta.onet.pl.front.onetapi.pl',
            'postUrl' => 'https://konto.onet.pl/login.html?app_id=poczta.onet.pl.front.onetapi.pl',
            'form' => array(
                'login' => 'login_username',
                'password' => 'login_password'
            )
        )
    );

    private function _cleanData($data)
    {
        $l = count($data);
        $result = array();
        if ($l < 1) {
            return null;
        }

        for ($i = 0; $i < $l; $i++)
        {
            if (!isset($data[$i][1])) {
                continue;
            }

            $result[] = array('email' => $data[$i][1], 'name' => $data[$i][0]);
        }

        return $result;
    }

    public function isLoggedIn($data)
    {
        return (false === strpos($data, ">Niepoprawne hasło.<"));
    }

    public function getContacts()
    {
        $this->init();
        $this->setCookieFile("cookieonet.txt");
        $params = array(
            'login' => $this->login,
            'password' => $this->password,
            'perm' => 1,
            'provider' => '',
            'access_token' => '',
            'referrer' => '',
            'cookie' => 'onet_ubi, onetzuo_ticket, onet_cid, __gfp_64b, onet_cinf, _ga, onet_sid, __utmt, __utma, __utmb, __utmc, __utmz, onet_crt_adtech, onet_uoi, __utmt, __utma, __utmb, __utmc, __utmz',
            'script' => 96,
            'adblock' => 1,
        );

        if (!$this->login($params))
        {
            return false;
        }

        $data = $this->getSite("http://kontakty.onet.pl");
        $data = $this->getSite("http://kontakty.onet.pl/export.html?type=outlook&groupId=0");

        $contacts = $this->_cleanData($this->parseCsv($data));

        return $contacts;
    }
}