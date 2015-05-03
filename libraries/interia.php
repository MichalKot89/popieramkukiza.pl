<?php
require_once "contactbase.php";

class Interia extends ContactBase
{
    public $config = array(
        'login' => array(
            'url' => 'https://poczta.interia.pl/',
            'postUrl' => 'https://logowanie.interia.pl/poczta/zaloguj?referer=http%3A%2F%2Fpoczta.interia.pl',
            'form' => array(
                'login' => 'email',
                'password' => 'pass'
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
        return (false === strpos($data, "Błędny login lub hasło"));
    }

    public function getContacts()
    {
        $this->init();
        $this->setCookieFile("cookieinteria.txt");
        $this->retrieveHeaders(0);
        if (!$this->login())
        {
            return false;
        }

        $url = parse_url($this->getCurrentUrl());
        $uid = str_replace('uid=', '', $url['query']);
        $data = $this->getSite("https://poczta.interia.pl/next/contact-export,uid,$uid",
            "exData=1&application=gmail");
        $contacts = $this->_cleanData($this->parseCsv($data));

        return $contacts;
    }
}