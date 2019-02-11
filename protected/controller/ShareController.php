<?php
class ShareController extends BaseController
{
    public function actionIndex()
    {

    }

    public function actionView()
    {
        $this->sid = arg('sid');
        echo $this->sid;
        if(empty($this->sid))
            $this->display('share_404.html');
        else{
            $code=arg("code");
            if(empty($code)){
                $this->display('share_verify.html');
            }
            else if($code != 233){
                $this->err = true;
                $this->display('share_verify.html');
            }
            else {
                $this->display('share_view.html');
            }

        }
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