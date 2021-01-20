<?php

namespace Fir\Controllers;

class Home extends Controller {

    /**
     * @var object
     */
    protected $model;

    public function index() {
        $data = [];

        // If the Safe Ads feature is enabled and the Safe Search is off
        if($this->settings['ads_safe'] == 1 && $_COOKIE['safe_search'] == 'Off') {
            $data['show_ads'] = false;
        } else {
            $data['show_ads'] = true;
        }

        if($this->settings['web_per_page'] > 0) {
            $data['menu'][] = ['web', false];
        }
        if($this->settings['images_per_page'] > 0) {
            $data['menu'][] = ['images', false];
        }
        if($this->settings['videos_per_page'] > 0) {
            $data['menu'][] = ['videos', false];
        }
        if($this->settings['news_per_page'] > 0) {
            $data['menu'][] = ['news', false];
        }

        // Enable the default search page
        $data['menu'][0][1] = true;
        $data['autofocus'] = 1;

        $data['query_path'] = $data['menu'][0][0];
        $data['search_bar_view'] = $this->view->render($data, 'shared/search_bar');

        return ['content' => $this->view->render($data, 'home/content')];
    }
}