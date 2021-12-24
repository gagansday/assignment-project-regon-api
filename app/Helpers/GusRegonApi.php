<?php

namespace App\Helpers;

class GusRegonApi
{
    protected $loginUrl = 'https://wyszukiwarkaregon.stat.gov.pl/wsBIR/UslugaBIRzewnPubl.svc/ajaxEndpoint/Zaloguj';
    protected $searchDataUrl = 'https://wyszukiwarkaregon.stat.gov.pl/wsBIR/UslugaBIRzewnPubl.svc/ajaxEndpoint/daneSzukaj';

    protected $loginTestUrl = 'https://wyszukiwarkaregontest.stat.gov.pl/wsBIR/UslugaBIRzewnPubl.svc/ajaxEndpoint/Zaloguj';
    protected $searchDataTestUrl = 'https://wyszukiwarkaregontest.stat.gov.pl/wsBIR/UslugaBIRzewnPubl.svc/ajaxEndpoint/daneSzukaj';

    protected $key = "abcde12345abcde12345";
    protected $session = null;

    protected function makeCurl($field, $url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $field);
        curl_setopt($curl, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($field), 'sid:' . $this->session]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.114 Safari/537.36');
        curl_setopt($curl, CURLOPT_HEADER, false);
        $result = curl_exec($curl);
        curl_close($curl);

        if ($this->session) $result = str_replace('\u000d\u000a', '', $result);
        return json_decode($result)->d;
    }

    protected function login()
    {
        $login = json_encode(["pKluczUzytkownika" => $this->key]);
        $result = $this->makeCurl($login, $this->loginTestUrl);
        return $result;
    }

    public function searchNIP($nip)
    {
        if (!$this->session) $this->session = $this->login();

        $search = json_encode(['pParametryWyszukiwania' => ['Nip' => $nip]]);
        $result = $this->makeCurl($search, $this->searchDataTestUrl);

        if (!$result) return false;
        return $this->transform(simplexml_load_string($result)->dane);
    }

    public function transform($record)
    {
        $record = (array) $record;

        return [
            'name' => $record['Nazwa'],
            'type' => $record['Typ'],
            'voivodeship' => $record['Wojewodztwo'],
            'address' => $record['Ulica'],
            'community' => $record['Gmina'],
            'township' => $record['Miejscowosc'],
            'district' => $record['Powiat'],
            'postcode' => $record['KodPocztowy'],
            'regon_id' => $record['Regon'],
            'silos_id' => $record['SilosID'],
        ];
    }
}
