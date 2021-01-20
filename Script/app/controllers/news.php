<?php

namespace Fir\Controllers;

use Fir\Libraries\Search;

class News extends Controller {

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
        if(isset($_GET['q']) == false || empty($_GET['q']) || $this->settings['news_per_page'] == 0) {
            redirect();
        }

        $search = new Search();
        $search->key = $this->settings['search_api_key'];
        $search->endpoint = 'news/search';

        $perPage = $this->settings['news_per_page'];
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
        // Sort by filter
        if(isset($_GET['sortBy']) && in_array($_GET['sortBy'], array_keys($filters[0]['sort_by'][1]))) {
            $params['sortBy'] = $_GET['sortBy'];
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

        // Market
        $params['mkt'] = (in_array($_COOKIE['market'], array_keys($search->getMarkets())) ? $_COOKIE['market'] : 'en-US');

        // Search specific sites
        $specificSites = $search->specificSites($this->settings['search_sites']);

        // Query
        $params['q'] = $_GET['q'].$specificSites;

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

        if(isset($data['response']['value']) && !empty($data['response']['value']) && $search_limit == false) {
            // If there are extra results for the next page
            if(count($data['response']['value']) == $perPage+1) {
                array_pop($data['response']['value']);
                $data['next_button'] = true;
            } else {
                $data['next_button'] = false;
            }

            // Validate data
            foreach($data['response']['value'] as $key => $value) {
                // Format the published date
                $date = isset($value['datePublished']) ? explode('-', date('Y-m-d', strtotime($value['datePublished']))) : '';
                $data['response']['value'][$key]['datePublished'] = !empty($date) ? sprintf($this->lang['date_format'], $date[0], substr($this->lang['month_'.$date[1]], 0, 3), $date[2]) : '';
            }

            $data['estimated_results'] = $data['response']['totalEstimatedMatches'];
            $data['query'] = $_GET['q'];
            $data['filters'] = $filters[1];
            $data['prev_button'] = $params['offset'] > 0 ? true : false;
            $data['current_page'] = $params['offset'];
            $data['per_page'] = $perPage;
            $data['pagination_view'] = $this->view->render($data, 'shared/search_pagination');
            $data['search_results_view'] = $this->view->render($data, 'news/search_results');
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
                redirect('news?q='.urlencode($_GET['q']));
            }

            $data['search_results_view'] = $this->view->render($data, 'shared/search_error');
        }

        $this->view->metadata['title'] = [$this->lang['news'], $_GET['q']];

        return ['content' => $this->view->render($data, 'news/content')];
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
                [''],
            ],
            'sort_by'          => [
                ['sortBy'],
                [
                    ''          => $this->lang['relevance'],
                    'date'      => $this->lang['date']
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
        $data['filters'] = ['freshness', 'sortBy', 'safeSearch'];

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

        if(isset($_GET['sortBy']) && in_array($_GET['sortBy'], array_keys($data['menu']['sort_by'][1]))) {
            $data['menu']['sort_by'][2] = $_GET['sortBy'];
        } else {
            $data['menu']['sort_by'][2] = '';
        }

        if(isset($_GET['safeSearch']) && in_array($_GET['safeSearch'], array_keys($data['menu']['safe_search'][1]))) {
            $data['menu']['safe_search'][2] = $_GET['safeSearch'];
        } else {
            $data['menu']['safe_search'][2] = $_COOKIE['safe_search'];
        }

        return $this->view->render($data, 'shared/search_filters');
    }
}