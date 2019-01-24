<?php

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
        $this->ATSAST_DOMAIN='';
        $this->ATSAST_CDN='https://static.1cf.co';
        $this->ATSAST_SALT='@SAST+1s';
    }

    public function jump($url, $delay = 0)
    {
        echo "<html><head><meta http-equiv='refresh' content='{$delay};url={$url}'></head><body></body></html>";
        exit;
    }
}
