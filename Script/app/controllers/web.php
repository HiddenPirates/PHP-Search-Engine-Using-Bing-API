<?php

namespace Fir\Controllers;

use Fir\Libraries\HexConverter;
use Fir\Libraries\MorseCode;
use Fir\Libraries\LoremIpsum;
use Fir\Libraries\Search;

class Web extends Controller {

    /**
     * @var object
     */
    protected $model;

    public function index() {
        $search_limit = false;

        $data['menu_view'] = $this->getMenu();

        // If search per IP is enabled
        if($this->settings['search_per_ip'] > 0) {
            $this->model = $this->model('SearchLimit');

            $user = $this->model->getIp(['ip' => $_SERVER['REMOTE_ADDR']]);

            $user['count'] = isset($user['count']) ? $user['count'] : 0;

            // If the user has done more queries than allowed in a given time frame
            if($user['count'] >= $this->settings['search_per_ip'] && (time()-strtotime($user['updated_at']) < $this->settings['search_time'])) {
                $search_limit = true;
            } else {
                // Reset the counter if the time frame has exceeded
                if(isset($user['updated_at']) && time()-strtotime($user['updated_at']) > $this->settings['search_time']) {
                    $this->model->resetIp(['ip' => $_SERVER['REMOTE_ADDR']]);
                } else {
                    $this->model->addIp(['ip' => $_SERVER['REMOTE_ADDR'], 'count' => $user['count']+1]);
                }
            }
        }

        // If there's no query, redirect the user to the home page
        if(isset($_GET['q']) == false || empty($_GET['q']) || $this->settings['web_per_page'] == 0) {
            redirect();
        }

        $search = new Search();
        $search->key = $this->settings['search_api_key'];
        $search->endpoint = 'search';

        $responseFilter[] = "WebPages";
        // Include the search type in the responseFilter, if they are enabled
        if($this->settings['images_per_page'] > 0 && $this->settings['search_answers']) {
            $responseFilter[] = "Images";
        }
        if($this->settings['videos_per_page'] > 0 && $this->settings['search_answers']) {
            $responseFilter[] = "Videos";
        }
        if($this->settings['news_per_page'] > 0 && $this->settings['search_answers']) {
            $responseFilter[] = "News";
        }
        if($this->settings['search_related'] > 0) {
            $responseFilter[] = "RelatedSearches";
        }
        if($this->settings['search_entities']) {
            // Request entities only from the available markets
            if(in_array((isset($_COOKIE['market']) && in_array($_COOKIE['market'], array_keys($search->getMarkets())) ? $_COOKIE['market'] : $this->settings['search_market']), ['en-AU', 'en-CA', 'fr-CA', 'fr-FR', 'de-DE', 'en-IN', 'it-IT', 'es-MX', 'en-GB', 'en-US', 'en-US', 'es-US', 'es-ES', 'pt-BR'])) {
                $responseFilter[] = 'Entities';
            }
        }
        $search->responseFilter = implode(',', $responseFilter);

        $perPage = $this->settings['web_per_page'];
        $filters = $this->searchFilters(true);

        // Results per page filter (added +1 result to verify if there's extra results for next page
        $params['count'] = $perPage+1;
        // Pagination filter
        if(isset($_GET['offset']) && ctype_digit($_GET['offset'])) {
            $params['offset'] = $_GET['offset'];
        } else {
            $params['offset'] = 0;
        }
        // Past period filter
        if(isset($_GET['freshness']) && in_array($_GET['freshness'], array_keys($filters[0]['period'][1]))) {
            $params['freshness'] = $_GET['freshness'];
        }
        // File type filter
        if(isset($_GET['fileType']) && in_array($_GET['fileType'], array_keys($filters[0]['type'][1]))) {
            $fileType = 'filetype:'.$_GET['fileType'].' ';
        } else {
            $fileType = '';
        }
        // Safe search filter
        if(isset($_GET['safeSearch']) && in_array($_GET['safeSearch'], array_keys($filters[0]['safe_search'][1]))) {
            $params['safeSearch'] = $_GET['safeSearch'];
        } else {
            $params['safeSearch'] = $_COOKIE['safe_search'];
        }
        // Highlight filter
        $params['textDecorations'] = $_COOKIE['highlight'];
        $params['textFormat'] = 'HTML';

        // Filters filter
        if(isset($_GET['filters'])) {
            $params['filters'] = $_GET['filters'];
        }

        // Market
        $params['mkt'] = (in_array($_COOKIE['market'], array_keys($search->getMarkets())) ? $_COOKIE['market'] : 'en-US');

        // Search specific sites
        $specificSites = $search->specificSites($this->settings['search_sites']);

        // Query
        $params['q'] = $fileType.$_GET['q'].$specificSites;

        // Get Instant Answers if on the first page of the search
        $data['result_ia_view'] = ($params['offset'] == 0 ? $this->evaluateQuery($_GET['q']) : false);

        if($search_limit == false) {
            $request = $search->request($params);
        } else {
            $request = false;
        }

        $data['filters_view'] = $this->searchFilters();

        $data['response'] = json_decode($request, true);

        // If the Safe Ads feature is enabled and the Safe Search is off
        if($this->settings['ads_safe'] == 1 && $params['safeSearch'] == 'Off') {
            $data['show_ads'] = false;
        } else {
            $data['show_ads'] = true;
        }
        $data['entities_view'] = false;

        $errType = 0;

        if(isset($data['response']['webPages']['value']) && !empty($data['response']['webPages']['value']) && $search_limit == false) {
            // Validate data
            if(isset($data['response']['images']['value'])) {
                foreach($data['response']['images']['value'] as $key => $value) {
                    // Get the domain name from URL and clean up www prefix
                    $data['response']['images']['value'][$key]['displayUrl'] = str_replace('www.', '', parse_url($value['hostPageUrl'], PHP_URL_HOST));
                }
            }

            if(isset($data['response']['videos']['value'])) {
                foreach($data['response']['videos']['value'] as $key => $value) {
                    // Format the views counter
                    $data['response']['videos']['value'][$key]['viewCount'] = isset($value['viewCount']) ? formatViews($value['viewCount']) : false;
                    // Format the published date
                    $date = isset($value['datePublished']) ? explode('-', date('Y-m-d', strtotime($value['datePublished']))) : '';
                    $data['response']['videos']['value'][$key]['datePublished'] = !empty($date) ? sprintf($this->lang['date_format'], $date[0], substr($this->lang['month_'.$date[1]], 0, 3), $date[2]) : '';

                    if(isset($value['duration'])) {
                        $formatTime = new \DateInterval($value['duration']);
                        $data['response']['videos']['value'][$key]['duration'] = formatDuration($formatTime->format('%H:%I:%S'));
                    }
                }
            }

            if(isset($data['response']['news']['value'])) {
                foreach($data['response']['news']['value'] as $key => $value) {
                    // Format the published date
                    $date = isset($value['datePublished']) ? explode('-', date('Y-m-d', strtotime($value['datePublished']))) : '';
                    $data['response']['news']['value'][$key]['datePublished'] = !empty($date) ? sprintf($this->lang['date_format'], $date[0], substr($this->lang['month_'.$date[1]], 0, 3), $date[2]) : '';
                }
            }

            // If there are extra results for the next page
            foreach($data['response']['rankingResponse']['mainline']['items'] as $key => $value) {
                if ($key >= $perPage) {
                    $data['next_button'] = true;
                    unset($data['response']['rankingResponse']['mainline']['items'][$key]);
                } else {
                    $data['next_button'] = false;
                }
            }

            $data['estimated_results'] = $data['response']['webPages']['totalEstimatedMatches'];
            $data['query'] = $_GET['q'];
            $data['filters'] = $filters[1];
            $data['prev_button'] = $params['offset'] > 0 ? true : false;
            $data['current_page'] = $params['offset'];
            $data['per_page'] = $perPage;

            if(isset($data['response']['entities'])) {
                foreach($data['response']['entities']['value'] as $key => $value) {
                    // Format the display url
                    if(isset($value['url'])) {
                        $data['response']['entities']['value'][$key]['displayUrl'] = str_replace('www.', '', parse_url($value['url'], PHP_URL_HOST));
                    }

                    if(isset($value['contractualRules'])) {
                        foreach($value['contractualRules'] as $y => $z) {
                            // Check for any contractual rule required, and store it as a flag
                            if($z['_type'] == 'ContractualRules/MediaAttribution') {
                                $data['response']['entities']['value'][$key]['helper']['contract']['media']['attribution'] = true;
                            }

                            if(($z['_type'] == 'ContractualRules/TextAttribution' || $z['_type'] == 'ContractualRules/LinkAttribution') && $z['targetPropertyName'] == 'description') {
                                $data['response']['entities']['value'][$key]['helper']['contract']['description']['attribution'] = true;
                            }

                            if($z['_type'] == 'ContractualRules/LicenseAttribution' && $z['targetPropertyName'] == 'description') {
                                $data['response']['entities']['value'][$key]['helper']['contract']['description']['license'] = true;
                            }

                            if(($z['_type'] == 'ContractualRules/TextAttribution' || $z['_type'] == 'ContractualRules/LinkAttribution') && isset($z['targetPropertyName']) == false) {
                                $data['response']['entities']['value'][$key]['helper']['contract']['footer']['attribution'] = true;
                            }
                        }
                    }
                }

                if(isset($entityDominant)) {
                    $data['entity_dominant'] = array_slice($data['response']['entities']['value'], 0, 1);
                }

                $data['entities_view'] = $this->view->render($data, 'web/entities');
            }

            $data['pagination_view'] = $this->view->render($data, 'shared/search_pagination');
            $data['search_results_view'] = $this->view->render($data, 'web/search_results');
        } else {
            // If the API returns an error and there's no previous error (prevents looping)
            if(((isset($data['response']['statusCode']) && isset($data['response']['message']) && isset($_SESSION['message']) == false) && $errType = 1) || (isset($data['response']['errors']) && isset($_SESSION['message']) == false && $errType = 2) || ($search_limit == true && isset($_SESSION['message']) == false)) {
                if($search_limit) {
                    $_SESSION['message'][] = ['info', $this->lang['search_l_e']];
                } elseif($errType == 1) {
                    $_SESSION['message'][] = ['error', $data['response']['statusCode'].$data['response']['message']];
                } elseif($errType == 2) {
                    foreach($data['response']['errors'] as $error) {
                        $_SESSION['message'][] = ['error', $error['moreDetails'].$error['parameter'].$error['value']];
                    }
                }
                redirect('web?q='.urlencode($_GET['q']));
            }

            $data['search_results_view'] = $this->view->render($data, 'shared/search_error');
        }

        $this->view->metadata['title'] = [$this->lang['web'], $_GET['q']];

        return ['content' => $this->view->render($data, 'web/content')];
    }

    /**
     * Evaluate the query to provide an Instant Answer
     *
     * @param   string  $query The string to be evaluated
     *
     * @return  string
     */
    private function evaluateQuery($query) {
        // What's my IP
        foreach($this->lang['ia']['ip'] as $t) {
            // Matches "trigger"
            if(preg_match(sprintf('/^%s$/ui', $t), $query, $match)) {
                return $this->iaGetUserIp();
            }
        }

        // Current user time
        foreach($this->lang['ia']['time'] as $t) {
            // Matches "trigger"
            if(preg_match(sprintf('/^%s$/ui', $t), $query, $match)) {
                return $this->iaGetUserTime();
            }
        }

        // Current user date
        foreach($this->lang['ia']['date'] as $t) {
            // Matches "trigger"
            if(preg_match(sprintf('/^%s$/ui', $t), $query, $match)) {
                return $this->iaGetUserDate();
            }
        }

        // Flip coin
        foreach($this->lang['ia']['flip_coin'] as $t) {
            // Matches "trigger"
            if(preg_match(sprintf('/^%s$/ui', $t), $query, $match)) {
                return $this->iaFlipCoin();
            }
        }

        // Stopwatch
        foreach($this->lang['ia']['stopwatch'] as $t) {
            // Matches "trigger"
            if(preg_match(sprintf('/^%s$/ui', $t), $query, $match)) {
                return $this->iaStopwatch();
            }
        }

        // Roll
        foreach($this->lang['ia']['roll'] as $t) {
            // Matches "trigger digits"
            if(preg_match(sprintf('/%s ([0-9]+)/iu', $t), $query, $match)) {
                return $this->iaRoll($match);
            }
        }

        // QR Code
        foreach($this->lang['ia']['qr_code'] as $t) {
            // Matches "trigger string", "string trigger"
            if(preg_match(sprintf('/^%s\s(.+)|(.+)\s%s$/iu', $t, $t), $query, $match)) {
                return $this->iaQrCode($match);
            }
        }

        // Sort descending
        foreach($this->lang['ia']['sort_desc'] as $t) {
            // Matches "trigger string"
            if(preg_match(sprintf('/^%s\s(.+)$/iu', $t), $query, $match)) {
                return $this->iaSort($match, 2);
            }
        }

        // Sort ascending
        foreach($this->lang['ia']['sort_asc'] as $t) {
            // Matches "trigger string"
            if(preg_match(sprintf('/^%s\s(.+)$/iu', $t), $query, $match)) {
                return $this->iaSort($match, 1);
            }
        }

        // Reverse text
        foreach($this->lang['ia']['reverse_text'] as $t) {
            // Matches "trigger string"
            if(preg_match(sprintf('/^%s\s(.+)$/iu', $t), $query, $match)) {
                return $this->iaReverseText($match);
            }
        }

        // Hex color
        if(preg_match('/^#([a-fA-F0-9]{6})$/iu', $query, $match) || preg_match('/^#([a-fA-F0-9]{3})/iu', $query, $match)) {
            return $this->iaHexColor($match);
        }

        // MD5
        foreach($this->lang['ia']['md5'] as $t) {
            // Matches "trigger string"
            if(preg_match(sprintf('/^%s\s(.+)$/iu', $t), $query, $match)) {
                return $this->iaMD5($match);
            }
        }

        // Base64 encode
        foreach($this->lang['ia']['base64_encode'] as $t) {
            // Matches "trigger string"
            if(preg_match(sprintf('/^%s\s(.+)$/iu', $t), $query, $match)) {
                return $this->iaBase64($match, 1);
            }
        }

        // Base64 decode
        foreach($this->lang['ia']['base64_decode'] as $t) {
            // Matches "trigger string"
            if(preg_match(sprintf('/^%s\s(.+)$/iu', $t), $query, $match)) {
                return $this->iaBase64($match, 2);
            }
        }

        // Lowercase
        foreach($this->lang['ia']['lowercase'] as $t) {
            // Matches "trigger string"
            if(preg_match(sprintf('/^%s\s(.+)$/iu', $t), $query, $match)) {
                return $this->iaCase($match, 1);
            }
        }

        // Uppercase
        foreach($this->lang['ia']['uppercase'] as $t) {
            // Matches "trigger string"
            if(preg_match(sprintf('/^%s\s(.+)$/iu', $t), $query, $match)) {
                return $this->iaCase($match, 2);
            }
        }

        // Leap Year
        foreach($this->lang['ia']['leap_year'] as $t) {
            // Matches "trigger digits", "digits trigger"
            if(preg_match(sprintf('/(.*?)(%s)(.*?)(\d+)(.*?)$/iu', $t), $query, $match) || preg_match(sprintf('/(.*?)(\d+)(.*?)(%s)(.*?)$/iu', $t), $query, $match)) {
                return $this->iaLeapYear($match);
            }
        }

        // Screen Resolution
        foreach($this->lang['ia']['screen_resolution'] as $t) {
            // Matches "trigger"
            if(preg_match(sprintf('/^%s$/ui', $t), $query, $match)) {
                return $this->iaUserScreenResolution();
            }
        }

        // Pi
        foreach($this->lang['ia']['pi'] as $t) {
            // Matches "trigger"
            if(preg_match(sprintf('/^%s$/ui', $t), $query, $match)) {
                return $this->iaPi();
            }
        }

        // Morse Code
        foreach($this->lang['ia']['morse_code'] as $t) {
            // Matches "trigger string"
            if(preg_match(sprintf('/^%s\s([.\-\/\s]+)*$/iu', $t), $query, $match)) {
                return $this->iaMorseCode($match, 2);
            }

            // Matches "trigger string"
            if(preg_match(sprintf('/^%s\s(.+)$/iu', $t), $query, $match)) {
                return $this->iaMorseCode($match, 1);
            }
        }

        // Unix Time
        foreach($this->lang['ia']['unix_time'] as $t) {
            // Matches "trigger digits"
            if(preg_match(sprintf('/^%s\s(\d+)$/iu', $t), $query, $match)) {
                return $this->iaUnixTime($match, 1);
            }

            // Matches "trigger"
            if(preg_match(sprintf('/^%s$/iu', $t), $query, $match)) {
                return $this->iaUnixTime($match, 2);
            }
        }

        // Lorem Ipsum
        foreach($this->lang['ia']['lorem_ipsum'] as $t) {
            // Matches "trigger", "trigger digits"
            if(preg_match(sprintf('/^(%s)\s?(\d+)?$/ui', $t), $query, $match)) {
                return $this->iaLoremIpsum($match);
            }
        }

        // Atbash Cipher
        foreach($this->lang['ia']['atbash'] as $t) {
            // Matches "trigger string"
            if(preg_match(sprintf('/^%s\s([a-z0-9\s]+)$/i', $t), $query, $match)) {
                return $this->iaAtbashCipher($match);
            }
        }

        return false;
    }

    /**
     * @return string
     */
    private function iaGetUserIp() {
        $data['result'] = $_SERVER['REMOTE_ADDR'];
        return $this->view->render($data, 'web/ia/user_ip');
    }

    /**
     * @return string
     */
    private function iaGetUserTime() {
        $data = [];
        for($i = 1; $i <= 12; $i++) {
            $data['months'][] = $this->lang['month_'.sprintf('%02d', $i)];
        }
        return $this->view->render($data, 'web/ia/user_time');
    }

    /**
     * @return string
     */
    private function iaGetUserDate() {
        $data = [];
        for($i = 1; $i <= 12; $i++) {
            $data['months'][] = $this->lang['month_'.sprintf('%02d', $i)];
        }
        return $this->view->render($data, 'web/ia/user_date');
    }

    /**
     * @return string
     */
    private function iaFlipCoin() {
        $rand = rand(0, 1);
        $data['result'] = $this->lang['coin_'.$rand];
        return $this->view->render($data, 'web/ia/flip_coin');
    }

    /**
     * @return string
     */
    private function iaStopwatch() {
        return $this->view->render(null, 'web/ia/stopwatch');
    }

    /**
     * @param   $value
     * @return  string
     */
    private function iaRoll($value) {
        // If the value provided is larger than 1 million, default to 1 million (prevents breaking mt_rand max value)
        if($value[1] > 1000000) {
            $total = 1000000;
        } elseif($value[1] < 1) {
            $total = 1;
        } else {
            $total = $value[1];
        }
        $data['result'] = number_format(mt_rand(1, $total), 0, $this->lang['decimals_separator'], $this->lang['thousands_separator']);
        $data['total'] = number_format($total, 0, $this->lang['decimals_separator'], $this->lang['thousands_separator']);

        return $this->view->render($data, 'web/ia/roll');
    }

    /**
     * @param $value
     * @return string
     */
    private function iaQrCode($value) {
        $data['result'] = !empty($value[1]) ? $value[1] : $value[2];
        return $this->view->render($data, 'web/ia/qr_code');
    }

    /**
     * @param   $value
     * @param   $direction
     * @return  string
     */
    private function iaSort($value, $direction) {
        $data['direction'] = $direction;
        $data['result'] = preg_split('/[\s,;]+/iu', $value[1]);

        // Remove any non digit character from the list
        foreach($data['result'] as $key => $val) {
            if(!is_numeric($val)) {
                unset($data['result'][$key]);
            }
        }

        // Order direction
        if($direction == 1) {
            sort($data['result']);
        } else {
            rsort($data['result']);
        }

        return $this->view->render($data, 'web/ia/sort');
    }

    /**
     * @param   $value
     * @return  string
     */
    private function iaReverseText($value) {
        $data['result'] = strrev($value[1]);
        return $this->view->render($data, 'web/ia/reverse_text');
    }

    /**
     * @param   $value
     * @return  string
     */
    private function iaHexColor($value) {
        $hex = new HexConverter($value[1]);
        $data['hex'] = $hex->hex();
        $data['rgb'] = $hex->rgb();
        $data['hsl'] = $hex->hsl();
        $data['cmyk'] = $hex->cmyk();

        return $this->view->render($data, 'web/ia/hex_color');
    }

    /**
     * @param   $value
     * @return  string
     */
    private function iaMD5($value) {
        $data['query'] = $value[1];
        $data['result'] = md5($value[1]);

        return $this->view->render($data, 'web/ia/md5');
    }

    /**
     * @param   $value
     * @param   $type
     * @return  string
     */
    private function iaBase64($value, $type) {
        $data['query'] = $value[1];
        $data['type'] = $type;

        if($type == 1) {
            $data['result'] = base64_encode($value[1]);
        } else {
            $data['result'] = base64_decode($value[1], true);

            // If the decoded base64 string is not valid
            if(empty(htmlspecialchars($data['result']))) {
                return false;
            }
        }

        return $this->view->render($data, 'web/ia/base64');
    }

    /**
     * @param   $value
     * @param   $type
     * @return  string
     */
    private function iaCase($value, $type) {
        $data['query'] = $value[1];
        $data['type'] = $type;
        if($type == 1) {
            $data['result'] = mb_strtolower($value[1]);
        } else {
            $data['result'] = mb_strtoupper($value[1]);
        }

        return $this->view->render($data, 'web/ia/case');
    }

    /**
     * @param   $value
     * @return  string
     */
    private function iaLeapYear($value) {
        if(is_numeric($value[2])) {
            $data['query'] = $value[2];
        } else {
            $data['query'] = $value[4];
        }

        // Check if the date is a leap year
        if(date('L', strtotime((int)$data['query'].'-01-01'))) {
            $data['type'] = 1;
        } else {
            $data['type'] = 2;
        }

        return $this->view->render($data, 'web/ia/leap_year');
    }

    /**
     * @return string
     */
    private function iaUserScreenResolution() {
        return $this->view->render(null, 'web/ia/user_screen_resolution');
    }

    /**
     * @return string
     */
    private function iaPi() {
        $data['result'] = pi();
        return $this->view->render($data, 'web/ia/pi');
    }

    /**
     * @param   $value
     * @param   $type
     * @return  string
     */
    private function iaMorseCode($value, $type) {
        $data['query'] = $value[1];

        $mc = new MorseCode($value[1]);

        if($type == 1) {
            $data['result'] = $mc->encode();
        } else {
            $data['result'] = $mc->decode();
        }

        return $this->view->render($data, 'web/ia/morse_code');
    }

    /**
     * @param   $value
     * @param   $type
     * @return  string
     */
    private function iaUnixTime($value, $type) {
        $data['type'] = $type;
        if($type == 1) {
            $data['query'] = $value[1];
            $data['date'] = explode('-', gmdate('Y-m-d', $value[1]));
            $data['time'] = gmdate('H:i:s', $value[1]);
        } else {
            $data['unix_time'] = time();
        }

        return $this->view->render($data, 'web/ia/unix_time');
    }

    /**
     * @param   $value
     * @return  string
     */
    private function iaLoremIpsum($value) {
        $count = 3;
        $max = 50;
        $min = 1;
        if(isset($value[2])) {
            if($value[2] > $max) {
                $count = $max;
            } elseif($value[2] < $min) {
                $count = $min;
            } else {
                $count = $value[2];
            }
        }

        $data['count'] = $count;
        $data['result'] = (new LoremIpsum())->generate($count);

        return $this->view->render($data, 'web/ia/lorem_ipsum');
    }

    /**
     * @param   $value
     * @return  string
     */
    private function iaAtbashCipher($value) {
        $az = range('a', 'z');
        $za = range('z', 'a');

        $string = strtolower($value[1]);
        $len = strlen($value[1]);
        $encoded = [];

        $count = 0;
        foreach(str_split($string) as $char) {
            $count++;
            // If the character is a digit
            if(is_numeric($char)) {
                $encoded[] = $char;
            }

            // Get the reversed character
            if(ctype_alpha($char)) {
                $encoded[] = $za[array_search($char, $az)];
            }

            // Add a space after every 5 characters
            if($count % 5 == 0 && $count < $len) {
                $encoded[] = ' ';
            }
        }

        $data['query'] = $value[1];
        $data['result'] = implode('', $encoded);

        return $this->view->render($data, 'web/ia/atbash');
    }

    /**
     * Return the Filters view or the Filters array
     *
     * @param   boolean $type   Return the Filters array if true
     * @return  string | array
     */
    private function searchFilters($type = null) {
        $data['query'] = $_GET['q'];

        /**
         * Array Map: Array(categoryTitle) => Array(categoryParameter), Array(categoryFilters), Array(currentFilter)
         */
        $data['menu'] = [
            'period'        => [
                ['freshness'],
                [
                    ''          => $this->lang['all'],
                    'Day'       => $this->lang['past_day'],
                    'Week'      => $this->lang['past_week'],
                    'Month'     => $this->lang['past_month']
                ],
                ['']
            ],
            'type'          => [
                ['fileType'],
                [
                    ''          => $this->lang['all'],
                    'doc'       => 'doc',
                    'docx'      => 'docx',
                    'dwf'       => 'dwf',
                    'pdf'       => 'pdf',
                    'ppt'       => 'ppt',
                    'pptx'      => 'pptx',
                    'psd'       => 'psd',
                    'xls'       => 'xls',
                    'xlsx'      => 'xlsx',
                ],
                ['']
            ],
            'safe_search'   => [
                ['safeSearch'],
                [
                    'Off'       => $this->lang['off'],
                    'Moderate'  => $this->lang['moderate'],
                    'Strict'    => $this->lang['strict']
                ],
                ['']
            ]
        ];

        // The search filters
        $data['filters'] = ['freshness', 'fileType', 'safeSearch'];

        if($type) {
            // Remove empty fields from the list
            foreach($data['menu'] as $key => $value) {
                unset($data['menu'][$key][1]['']);
            }
            return [$data['menu'], $data['filters']];
        }

        if(isset($_GET['freshness']) && in_array($_GET['freshness'], array_keys($data['menu']['period'][1]))) {
            $data['menu']['period'][2] = $_GET['freshness'];
        } else {
            $data['menu']['period'][2] = '';
        }

        if(isset($_GET['fileType']) && in_array($_GET['fileType'], array_keys($data['menu']['type'][1]))) {
            $data['menu']['type'][2] = $_GET['fileType'];
        } else {
            $data['menu']['type'][2] = '';
        }

        if(isset($_GET['safeSearch']) && in_array($_GET['safeSearch'], array_keys($data['menu']['safe_search'][1]))) {
            $data['menu']['safe_search'][2] = $_GET['safeSearch'];
        } else {
            $data['menu']['safe_search'][2] = $_COOKIE['safe_search'];
        }

        return $this->view->render($data, 'shared/search_filters');
    }
}