<?php
class CloudController extends BaseController
{
    public function actionIndex()
    {
        $this->title='SAST Cloud';
    }

    public function actionFileExists()
    {
        if (!arg('hash')) ERR::Catcher(1003);
        $hash = strtolower(arg('hash'));
        if (!preg_match('/^[0-9a-f]{40}$/', $hash)) ERR::Catcher(1004);
        SUCCESS::Catcher('success', file_exists(CONFIG::GET('CLOUD_FILE_DIRECTORY').DS.substr($hash, 0, 2).DS.substr($hash, 2)));
    }
}