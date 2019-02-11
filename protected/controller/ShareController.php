<?php
class ShareController extends BaseController
{
    public function actionIndex()
    {

    }

    public function actionView()
    {
        $this->sid = arg('sid');
        if(empty($this->sid))
            $this->display('share_404.html');

    }

    public function actionCreate() //创建分享
    {
        if (!$this->islogin)
            ERR::Catcher(2001);
    }

    public function actionCancel() //取消分享
    {
        if (!$this->islogin)
            ERR::Catcher(2001);
    }
}