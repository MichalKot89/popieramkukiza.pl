<?php
require_once "contactbase.php";

class o2 extends ContactBase
{
    public $config = array(
        'login' => array(
            'url' => 'https://poczta.o2.pl/login.html',
            'postUrl' => 'https://poczta.o2.pl/login.html',
            'form' => array(
                'login' => 'username',
                'password' => 'password'
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

            $result[] = array('email' => $data[$i][11], 'name' => $data[$i][3]);
        }

        return $result;
    }

    public function isLoggedIn($data)
    {
        return (false === strpos($data, "Podany login i/lub hasło są nieprawidłowe."));
    }

    public function getContacts()
    {
        $this->init();
        $this->setCookieFile("cookieo2.txt");
        $this->retrieveHeaders(1);
        if (!$this->login())
        {
            return false;
        }

        $cookies = $this->getCookies($this->response);
        $ssid = str_replace('ssid=', '', $cookies[1][0]);

        if (!$ssid) {
            return false;
        }

        $this->retrieveHeaders(0);
        $data = $this->getSite("http://poczta.o2.pl/a?cmd=export_addressbook&requestid=4&xsfr-cookie=$ssid&fmt=xml&upid=14301454471864",
            "outputformat=outlook");

        $contacts = $this->_cleanData($this->parseCsv($data, 1, ';'));

        return $contacts;
    }
}