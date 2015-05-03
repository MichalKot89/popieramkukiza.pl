<?php
class ContactBase
{
    public $config = array(
        'login' => array(
            'url' => '',
            'postUrl' => '',
            'form' => array(
                'login' => '',
                'password' => ''
            )
        )
    );

    private $login;
    private $password;
    private $response;

    private $ch;

    public function init()
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch, CURLOPT_COOKIESESSION, true );
        curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');

    }

    public function retrieveHeaders($val)
    {
        curl_setopt($this->ch, CURLOPT_HEADER, $val);
    }

    public function setCookieFile($cookie)
    {
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, $cookie);
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getCurrentUrl()
    {
        return curl_getinfo($this->ch, CURLINFO_EFFECTIVE_URL);
    }

    public function login($params = null)
    {
        if ($params == null)
        {
            $params = $this->config['login']['form']['login'] . "=" . $this->login . "&"
                . $this->config['login']['form']['password'] . "=" . $this->password;
        }

        $this->getSite($this->config['login']['url']);
        $data = $this->getSite($this->config['login']['postUrl'], $params);
        $this->response = $data;
        return $this->isLoggedIn($data);
    }


    public function parseCsv($data, $startPosition = 1, $del = ',')
    {
        $tmp = explode("\n", $data);
        $l = count($tmp);
        $result = array();

        for ($i = $startPosition; $i < $l; $i++)
        {
            $result[] = str_getcsv($tmp[$i], $del);
        }
        return $result;
    }

    public function getSite($url, $data = null)
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        if ($data)
        {
            curl_setopt($this->ch, CURLOPT_POST, 1);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        }


        return curl_exec($this->ch);
    }

    public function getCookies($data)
    {
        $result = null;

        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $data, $result);

        return $result;
    }
}