<?php

require_once dirname(__FILE__).'/Google/autoload.php';

class Gmail
{
    private $secret = GOOGLE_API_SECRET;
    private $token = null;

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getName()
    {
        $user = file_get_contents('https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token=' . $this->token);

        if ($user === false) {
            return false;
        }

        $user = json_decode($user, true);

        if ($user === null) {
            return false;
        }

        if (isset($user['error']) || !isset($user['name'])) {
            return false;
        }

        return $user['name'];
    }

    public function getData()
    {
        $data = file_get_contents('https://www.google.com/m8/feeds/contacts/default/full?alt=json&v=3.0&max-results=1000&access_token=' . $this->token);

        if ($data === false) {
            return false;
        }

        $data = json_decode($data, true);

        if ($data === null) {
            return false;
        }

        $results = array(
            'contacts' => array()
        );

        $results['name'] = $data['feed']['author'][0]['name']['$t'];
        $results['email'] = $data['feed']['author'][0]['email']['$t'];

        foreach ($data['feed']['entry'] as $entry) {
            if (!isset($entry['gd$email'][0]['address'])) {
                continue;
            }

            $results['contacts'][] = array(
                'name' => $entry['title']['$t'],
                'email' => $entry['gd$email'][0]['address']
            );
        }

        return $results;
    }
}