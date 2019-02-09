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

    //显示文件及文件夹    
    public function actionShow()
    {
        if (!$this->islogin) ERR::Catcher(2001);
        if (!arg('path')) ERR::Catcher(1003);
        if(!is_path_legal(arg('path'))) ERR::Catcher(1004);
        if(!is_path_existed(arg('path'))) ERR::Catcher(6002);
        $name=getName(arg('path'));
        $path=$name[1];
        $db=new Model("disk_file");
        SUCCESS::Catcher('success',$db->query('select filename,time,filesize,is_dir from disk_file where uid=:uid,path=:path,deleted=0',':uid'=>$_SESSION['uid'],':path'=>$path));
    }
    
    //回收站
    public function actionRecycle()
    {
        if (!$this->islogin) ERR::Catcher(2001);
        if (!arg('path')) ERR::Catcher(1003);
        if(!is_path_legal(arg('path'))) ERR::Catcher(1004);
        if(!is_path_existed(arg('path'))) ERR::Catcher(6002);
        $name=getName(arg('path'));
        $path=$name[1];
        $db=new Model("disk_file");
        SUCCESS::Catcher('success',$db->query('select filename,time,filesize,is_dir,path from disk_file where uid=:uid,deleted=0',':uid'=>$_SESSION['uid']));   
    }


    

    //用到的函数

    public function getSHA($path)
    {
        $fp = fopen($path, 'rb');
        $ctx = hash_init('sha1');
        hash_update($ctx, filesize($path)."\0");
        while (!feof($fp)) {
            hash_update($ctx, fread($fp, 65536));
        }
        return hash_final($ctx);
    }
    
    //文件夹是否存在
    public function is_path_legal($path)
    {
        $pattern='/[\:*?"<>|]|\/\.\/|\/\.\.\//';
        if(!preg_match($pattern, $path)) return false;
        return true;
    }

    public function is_path_existed($path)
    {
        $db=new Model("disk_file");
        $condition=array('uid'=>$_SESSION['uid'],'path'=>$path);
        $result=$db->findAll($condition);
        if(empty($result)) return false;
        return true;

    }

    public function getName($path)
    {
        //是否以/结尾
        if(substr_compare($path, '/', -strlen('/')) === 0)
        {
            $name=array();
            if(strcmp($path,'/')) $name[1]='/' return $name;
            $path=rtrim($arr_str,'/');
            $result=explode('/',$path);
            $name[0]=$result[count($result)-1];
            $name[1]=substr($path,0,strlen($path)-strlen($name[0])-1);
            return $name;
        }
        else
        {

            $name=array();
            $result=explode('/',$path);
            $name[0]=$name[0]=$result[count($result)-1];;
            $name[1]=substr($path,0,strlen($path)-strlen($name[0])-1);
            return $name;
        }
    }


}