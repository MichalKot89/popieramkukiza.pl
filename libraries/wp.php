<?php
require_once "contactbase.php";

class WP extends ContactBase
{
    public $config = array(
        'login' => array(
            'url' => 'http://profil.wp.pl/login.html?url=http%3A%2F%2Fpoczta.wp.pl%2Findexgwt.html%3Fflg%3D1&serwis=nowa_poczta_wp',
            'postUrl' => 'https://profil.wp.pl/login_poczta.html',
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
        if ($l < 1) return null;

        for ($i = 0; $i < $l; $i++)
        {
            if (!isset($data[$i][1])) continue;
            $result[] = array ('email' => $data[$i][5], 'name' => $data[$i][0]);
        }
        return $result;
    }

    public function isLoggedIn($data)
    {
        return ( false === strpos($data, "Niestety podany login lub") );
    }

    public function getContacts()
    {
        $this->init();
        $this->setCookieFile("cookiewp.txt");
        $params = array(
            '_action' => 'login',
            'countTest' => 1,
            'enticket' => '',
            'idu' => 99,
            'serwis' => 'nowa_poczta_wp',
            'url' => 'http://poczta.wp.pl/indexgwt.html?flg=1',
            'idu' => 99,
            'serwis' => 'nowa_poczta_wp',
            'login_username' => $this->login,
            'login_password' => $this->password
        );
        if (!$this->login($params))
        {
            return false;
        }

        $this->retrieveHeaders(0);
        $data = $this->getSite("http://kontakty.wp.pl/export.html?all=1");

        $contacts = $this->_cleanData($this->parseCsv($data));

        return $contacts;
    }
}