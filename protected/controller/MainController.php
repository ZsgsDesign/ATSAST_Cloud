<?php
class MainController extends BaseController
{
    public function actionIndex()
    {
        if ($this->islogin) {
            $this->jump("{$this->ATSAST_DOMAIN}/cloud/");
        } else {
            $this->jump("{$this->ATSAST_DOMAIN}/account/");
        }
    }
}
