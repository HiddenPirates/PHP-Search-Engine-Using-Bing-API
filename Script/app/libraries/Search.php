<?php

namespace Fir\Libraries;

class Search {

    /**
     * The Bing API Key
     * @var
     */
    public $key;

    /**
     * API end point
     * @var
     */
    public $endpoint;

    /**
     * The response filter
     * @var
     */
    public $responseFilter;

    private $markets = ['es-AR' => ['Argentina', 'Spanish'], 'en-AU' => ['Australia', 'English'], 'de-AT' => ['Austria', 'German'], 'nl-BE' => ['Belgium', 'Dutch'], 'fr-BE' => ['Belgium', 'French'], 'pt-BR' => ['Brazil', 'Portuguese'], 'en-CA' => ['Canada', 'English'], 'fr-CA' => ['Canada', 'French'], 'es-CL' => ['Chile', 'Spanish'], 'da-DK' => ['Denmark', 'Danish'], 'fi-FI' => ['Finland', 'Finnish'], 'fr-FR' => ['France', 'French'], 'de-DE' => ['Germany', 'German'], 'zh-HK' => ['Hong Kong SAR', 'Traditional Chinese'], 'en-IN' => ['India', 'English'], 'en-ID' => ['Indonesia', 'English'], 'it-IT' => ['Italy', 'Italian'], 'ja-JP' => ['Japan', 'Japanese'], 'ko-KR' => ['Korea', 'Korean'], 'en-MY' => ['Malaysia', 'English'], 'es-MX' => ['Mexico', 'Spanish'], 'nl-NL' => ['Netherlands', 'Dutch'], 'en-NZ' => ['New Zealand', 'English'], 'no-NO' => ['Norway', 'Norwegian'], 'zh-CN' => ['People\'s republic of China', 'Chinese'], 'pl-PL' => ['Poland', 'Polish'], 'pt-PT' => ['Portugal', 'Portuguese'], 'en-PH' => ['Republic of the Philippines', 'English'], 'ru-RU' => ['Russia', 'Russian'], 'ar-SA' => ['Saudi Arabia', 'Arabic'], 'en-ZA' => ['South Africa', 'English'], 'es-ES' => ['Spain', 'Spanish'], 'sv-SE' => ['Sweden', 'Swedish'], 'fr-CH' => ['Switzerland', 'French'], 'de-CH' => ['Switzerland', 'German'], 'zh-TW' => ['Taiwan', 'Traditional Chinese'], 'tr-TR' => ['Turkey', 'Turkish'], 'en-GB' => ['United Kingdom', 'English'], 'en-US' => ['United States', 'English'], 'es-US' => ['United States', 'Spanish']];

    /**
     * @param   array   $params
     * @return  string
     */
    public function request($params) {
        $params = http_build_query($params);

        $url = 'https://api.cognitive.microsoft.com/bing/v7.0/'.$this->endpoint.'?'.$params.(isset($this->responseFilter) ? '&responseFilter='.$this->responseFilter : '');

        $headers = ['Ocp-Apim-Subscription-Key: '.$this->key];

        $output = $this->fetchUrl($url, $headers);

        return $output;
    }

    /**
     * @param   string  $url        The URL to be fetched
     * @param   array   $headers    The request headers to be sent
     * @return  string
     */
    private function fetchUrl($url, $headers = null) {
        $response = null;
        if(function_exists('curl_exec')) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            if($headers) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36');
            $response = curl_exec($ch);
        }
        return $response;
    }

    /**
     * Returns the available markets
     *
     * @return  array
     */
    public function getMarkets() {
        return $this->markets;
    }

    /**
     * Builds the query syntax for the "site:" operator
     *
     * @param   string  $domains The list of the domains to be searched
     * @return  string
     */
    public function specificSites($domains) {
        $output = '';
        if(!empty($domains)) {
            $domains = explode(PHP_EOL, str_replace(array('http://', 'https://'), '', $domains));

            $urls = [];
            foreach($domains as $domain) {
                $urls[] = 'site:'.rtrim($domain, '/');
            }

            $output = ' ('.implode(' OR ', $urls).')';
        }
        return $output;
    }
}