<?php

namespace Fir\Controllers;

use Fir\Libraries\Search;

class Requests extends Controller {

    /**
     * @var object
     */
    protected $model;

    public function index() {
        redirect();
    }

    public function suggestions() {
        $search_limit = false;

        // If search per IP is enabled
        if($this->settings['suggestions_per_ip'] > 0) {
            $this->model = $this->model('SuggestionsLimit');

            $user = $this->model->getIp(['ip' => $_SERVER['REMOTE_ADDR']]);

            $user['count'] = isset($user['count']) ? $user['count'] : 0;

            // If the user has done more queries than allowed in a given time frame
            if($user['count'] >= $this->settings['suggestions_per_ip'] && (time()-strtotime($user['updated_at']) < $this->settings['search_time'])) {
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
        if(isset($_POST['q']) == false || empty($_POST['q']) || $this->settings['search_suggestions'] == 0) {
            redirect();
        }

        $search = new Search();
        $search->key = $this->settings['search_api_key'];
        $search->endpoint = 'Suggestions';

        // Market
        $params['mkt'] = (in_array($_COOKIE['market'], array_keys($search->getMarkets())) ? $_COOKIE['market'] : 'en-US');

        // Query
        $params['q'] = $_POST['q'];

        if($search_limit == false) {
            $request = $search->request($params);
        } else {
            $request = false;
        }

        $data['searchType'] = $_POST['searchType'];
        $data['query'] = $_POST['q'];
        $data['response'] = json_decode($request, true);

        return ['search-results' => $this->view->render($data, 'requests/search')];
    }
}