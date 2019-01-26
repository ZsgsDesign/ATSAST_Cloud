<?php
class CloudController extends BaseController
{
    public function actionIndex()
    {
        $this->title='SAST Cloud';
    }

    public function actionFileExists()
    {
        if (!$this->islogin) ERR::Catcher(2001);
        if (!arg('hash')) ERR::Catcher(1003);
        $hash = strtolower(arg('hash'));
        if (!preg_match('/^[0-9a-f]{40}$/', $hash)) ERR::Catcher(1004);
        SUCCESS::Catcher('success', file_exists(CONFIG::GET('CLOUD_FILE_DIRECTORY').DS.substr($hash, 0, 2).DS.substr($hash, 2)));
    }

    public function actionCloudSpace()
    {
        if (!$this->islogin) ERR::Catcher(2001);
        $users = new Model('users');
        $result = $users->find(['uid=:uid', ':uid'=>$_SESSION['uid']]);
        SUCCESS::Catcher('success', ['total'=>intval($result['capacity']), 'used'=>intval($result['used']), 'available'=>$result['capacity']-$result['used']]);
    }
}