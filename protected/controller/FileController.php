<?php
class FileController extends BaseController{
    //显示文件及文件夹    
    public function actionShow()
    {
        if (!$this->islogin) ERR::Catcher(2001);
        if (!arg('path')) ERR::Catcher(1003);
        $path=arg('path');
        $db=new Model("disk_file");
        $condition=array('uid'=>$_SESSION['uid'], 'path'=>$path);
        $result=$db->findAll($condition);
        SUCCESS::Catcher($result);
    }

    //查看文件内容
    public function actionVisitFile()
    {
        if (!$this->islogin) ERR::Catcher(2001);
        if (!arg('hash')) ERR::Catcher(1003);
        $hash = strtolower(arg('hash'));
        if (!preg_match('/^[0-9a-f]{40}$/', $hash)) ERR::Catcher(1004);
        SUCCESS::Catcher(file_get_contents(CONFIG::GET('CLOUD_FILE_DIRECTORY').DS.substr($hash, 0, 2).DS.substr($hash, 2)));
    }

    //删除文件及文件夹
    public function actionDeleteFile()
    {
        if (!$this->islogin) ERR::Catcher(2001);
        if (!arg('path')) ERR::Catcher(1003);
        if (!arg('filename')) ERR::Catcher(1003);
        $db=new Model("disk_file");
        $condition=array('path'=>arg('path'),'filename'=>arg('filename'),'uid'=>$_SESSION['uid']);
        $db->update($condition,array('deleted'=>1));
    }

    //新建文件夹
    public function actionNewFolder()
    {
        if (!$this->islogin) ERR::Catcher(2001);
        if (!arg('path')) ERR::Catcher(1003);
        if (!arg('filename')) ERR::Catcher(1003);
        $db=new Model("disk_file");
        $path=arg('path').DS.arg('filename').DS;
        $newrow=array(
            "uid"       =>$_SESSION['uid'],
            "filename"  =>arg('filename'),
            "path"      =>$path,
            "is_dir"    =>1

        );
        $db->create($newrow);
    }

    //下载文件

    //上传文件




    



    //用到的函数
    public function transByte($size){
        $arr=array("byte","kb","mb","gb","tb","eb")
        $i=0;
        while($size>=1024){
            $size/=1024;
            $i++;
        }
        return round($size,2).$arr[$i];
    }



    

}

?>