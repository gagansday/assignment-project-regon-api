<?php

namespace App\Helpers;

class GusRegonApi
{
    protected $url = 'https://wyszukiwarkaregontest.stat.gov.pl/wsBIR/UslugaBIRzewnPubl.svc/ajaxEndpoint/';

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
        $method = 'Zaloguj';
        $result = $this->makeCurl($login, $this->url . $method);
        return $result;
    }

    public function searchNIP($nip)
    {
        if (!$this->session) $this->session = $this->login();

        $search = json_encode(['pParametryWyszukiwania' => ['Nip' => $nip]]);
        $method = 'daneSzukaj';
        $result = $this->makeCurl($search, $this->url . $method);

        if (!$result) return false;
        return $this->transformCompanyInfo(simplexml_load_string($result)->dane);
    }

    public function getPKD($record)
    {
        if (!$this->session) $this->session = $this->login();
        $search = json_encode([
            "pRegon" => str_pad($record['Regon'], 14, 0),
            "pNazwaRaportu" => "DaneRaportDzialalnosciFizycznejPubl",
            "pSilosID" => $record['SilosID']
        ]);
        $method = 'DanePobierzPelnyRaport';
        $result = $this->makeCurl($search, $this->url . $method);

        if (!$result) return [];
        $pdk = [];

        foreach (simplexml_load_string($result) as $row) {
            $pdk[] = $this->transformPKD((array)$row);
        }

        return $pdk;
    }

    public function transformCompanyInfo($record)
    {
        $record = (array)$record;

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
            'pkd' => $this->getPKD($record)
        ];
    }

    public function transformPKD($record)
    {
        return [
            'code' => $record['fiz_pkdKod'],
            'name' => $record['fiz_pkdNazwa'],
            'isPrincipalActivity'  => trim($record['fiz_pkdPrzewazajace']) ? true : false,
        ];
    }
}
