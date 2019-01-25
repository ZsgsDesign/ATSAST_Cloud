<?php

require_once(APP_DIR.'/protected/model/CONFIG.php');

class BaseController extends Controller
{
    public $layout = "layout.html";
    public function init()
    {
        
        if (!session_id()) {
            session_start();
        }

        header("Content-type: text/html; charset=utf-8");
        require(APP_DIR.'/protected/include/functions.php');

        $this->islogin=is_login();
        $this->ATSAST_DOMAIN=CONFIG::GET('ATSAST_DOMAIN');
        $this->ATSAST_CDN=CONFIG::GET('ATSAST_CDN');
        $this->ATSAST_SALT=CONFIG::GET('ATSAST_SALT');
    }

    public function jump($url, $delay = 0)
    {
        echo "<html><head><meta http-equiv='refresh' content='{$delay};url={$url}'></head><body></body></html>";
        exit;
    }
}
