<?php

namespace Fir\Controllers;

use Fir\Libraries\Search;

class Preferences extends Controller {

    /**
     * @var object
     */
    protected $model;

    /**
     * @var array
     */
    protected $pages = ['language', 'theme', 'search'];

    public function index() {
        // Redirect to the default Preference Page
        redirect('preferences/language');
    }

    public function language() {
        $data['menu_view'] = $this->menu();

        $data['user_language'] = $this->language;
        $data['languages'] = $this->languages;
        $data['preferences_view'] = $this->view->render($data, 'preferences/language');

        if(isset($_POST['submit']) && isset($_POST['site_language'])) {
            $_SESSION['message'][] = ['success', $this->lang['settings_saved']];
            redirect('preferences/language');
        }

        $data['page_title'] = $this->lang['language'];

        $this->view->metadata['title'] = [$this->lang['preferences'], $this->lang['language']];
        return ['content' => $this->view->render($data, 'preferences/content')];
    }

    public function theme() {
        $data['menu_view'] = $this->menu();

        $data['user_theme'] = $_COOKIE['dark_mode'];

        $data['preferences_view'] = $this->view->render($data, 'preferences/theme');

        if(isset($_POST['submit'])) {
            if(isset($_POST['dark_mode']) && $_POST['dark_mode'] == 1) {
                setcookie("dark_mode", 1, time() + (10 * 365 * 24 * 60 * 60), COOKIE_PATH);
            } else {
                setcookie("dark_mode", 0, time() + (10 * 365 * 24 * 60 * 60), COOKIE_PATH);
            }

            if(isset($_POST['center_content']) && $_POST['center_content'] == 1) {
                setcookie("center_content", 1, time() + (10 * 365 * 24 * 60 * 60), COOKIE_PATH);
            } else {
                setcookie("center_content", 0, time() + (10 * 365 * 24 * 60 * 60), COOKIE_PATH);
            }

            if(isset($_POST['backgrounds']) && $_POST['backgrounds'] == 1) {
                setcookie("backgrounds", 1, time() + (10 * 365 * 24 * 60 * 60), COOKIE_PATH);
            } else {
                setcookie("backgrounds", 0, time() + (10 * 365 * 24 * 60 * 60), COOKIE_PATH);
            }

            $_SESSION['message'][] = ['success', $this->lang['settings_saved']];
            redirect('preferences/theme');
        }

        $data['page_title'] = $this->lang['theme'];

        $this->view->metadata['title'] = [$this->lang['preferences'], $this->lang['theme']];
        return ['content' => $this->view->render($data, 'preferences/content')];
    }

    public function search() {
        $data['menu_view'] = $this->menu();

        $search = new Search();

        $data['markets'] = $search->getMarkets();

        $data['user_new_window'] = $_COOKIE['new_window'];

        $data['user_market'] = (isset($_COOKIE['market']) ? $_COOKIE['market'] : $this->settings['search_market']);

        $data['preferences_view'] = $this->view->render($data, 'preferences/search');

        if(isset($_POST['submit'])) {
            if(isset($_POST['new_window']) && $_POST['new_window'] == 0) {
                setcookie("new_window", 0, time() + (10 * 365 * 24 * 60 * 60), COOKIE_PATH);
            } else {
                setcookie("new_window", 1, time() + (10 * 365 * 24 * 60 * 60), COOKIE_PATH);
            }

            if(isset($_POST['safe_search']) && in_array($_POST['safe_search'], ['Off', 'Moderate', 'Strict'])) {
                setcookie("safe_search", $_POST['safe_search'], time() + (10 * 365 * 24 * 60 * 60), COOKIE_PATH);
            }

            if(isset($_POST['highlight']) && in_array($_POST['highlight'], ['false', 'true'])) {
                setcookie("highlight", $_POST['highlight'], time() + (10 * 365 * 24 * 60 * 60), COOKIE_PATH);
            }

            if(isset($_POST['market']) && in_array($_POST['market'], array_keys($data['markets']))) {
                setcookie("market", $_POST['market'], time() + (10 * 365 * 24 * 60 * 60), COOKIE_PATH);
            } else {
                setcookie("market", $this->settings['search_market'], time() + (10 * 365 * 24 * 60 * 60), COOKIE_PATH);
            }

            $_SESSION['message'][] = ['success', $this->lang['settings_saved']];
            redirect('preferences/search');
        }

        $data['page_title'] = $this->lang['search'];

        $this->view->metadata['title'] = [$this->lang['preferences'], $this->lang['search']];
        return ['content' => $this->view->render($data, 'preferences/content')];
    }

    /**
     * The Preferences menu
     *
     * @return  string
     */
    private function menu() {
        $pages = $this->pages;

        // Array Map: Key(Menu Elements) => Array(Bold, Not Dynamic tag, Title)
        foreach($pages as $page) {
            $data['menu'][$page] = [false, false, $page];
        }

        // If on the current route, enable the Bold flag
        $data['menu'][$this->url[1]][0] = true;

        return $this->view->render($data, 'preferences/menu');
    }
}