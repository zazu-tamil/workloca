<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $timeout_seconds = 5220; // 1:45 minutes

    public function __construct() {
        parent::__construct();

        // Skip timeout check on login controller
        $current_class = strtolower($this->router->fetch_class());
        if (in_array($current_class, ['login'])) {
            return;
        }

        // Check login session
        if (!$this->session->userdata(SESS_HD . 'logged_in')) {
            redirect('login');
        }

        // Session timeout check
        $login_time = $this->session->userdata(SESS_HD . 'login_time');
        if ($login_time && (time() - $login_time) > $this->timeout_seconds) {
            redirect('login/logout/timeout');
        } else {
            // Reset timer if active
            //$this->session->set_userdata(SESS_HD . 'login_time', time());
        }
    }
}