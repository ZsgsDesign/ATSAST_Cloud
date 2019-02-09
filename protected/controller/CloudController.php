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
        $result = $users->find(['uid'=>$_SESSION['uid']]);
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
        SUCCESS::Catcher('success',$db->query('select filename,time,filesize,is_dir from disk_file where uid=:uid,path=:path,deleted=0',[':uid'=>$_SESSION['uid'],':path'=>$path]));
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
        SUCCESS::Catcher('success',$db->query('select filename,time,filesize,is_dir,path from disk_file where uid=:uid,deleted=0',[':uid'=>$_SESSION['uid']]));
    }

    //查看文件内容
    public function actionPreview()
    {
        if (!$this->islogin) ERR::Catcher(2001);
        if (!arg('path')) ERR::Catcher(1003);
        if(!is_path_legal(arg('path'))) ERR::Catcher(1004);
        if(!is_path_existed(arg('path'))) ERR::Catcher(6002);
        $name=getName(arg('path'));
        $filename=$name[0];
        $path=$name[1];
        $extension=strrchr($filename, '.');
        $condition=array('uid'=>$_SESSION['uid'],'deleted'=>0, 'path'=>$path,'filename'=>$filename);
        $result=$db->find($condition);
        if(empty($result))ERR::Catcher(6001);
        $hash=$result['hash']; 
        $dir=CONFIG::GET('CLOUD_FILE_DIRECTORY').DS.substr($hash, 0, 2).DS.substr($hash, 2);
        $mime=array('.txt'=>'text/plain','.jpg'=>'image/jpeg','.pdf'=>'application/pdf','.bmp'=>'image/x-windows-bmp','.png'=>'image/png');
        if(!array_key_exists($extension,$mime))ERROR::Catcher(1005);
        header('Content-Type:'.$mime[$extension]);
        header("filename:".$filename);
        readfile($dir);
        exit;
               
    }

    //删除文件及文件夹
    public function actionDelete()
    {
        if (!$this->islogin) ERR::Catcher(2001);
        if (!arg('path')) ERR::Catcher(1003);
        if(!is_path_legal(arg('path'))) ERR::Catcher(1004);
        if(!is_path_existed(arg('path'))) ERR::Catcher(6002);
        $name=getName(arg('path'));
        $filename=$name[0];
        $path=$name[1];
        //判断
        $db=new Model('disk_file');
        $condition=array('uid'=>$_SESSION['uid'],'deleted'=>0, 'path'=>$path,'filename'=>$filename);
        $result=$db->find($condition);
        if(empty($result))ERR::Catcher(6001);
        //文件
        if($result['is_dir']==0)
        {
            $sumsize=$result['filesize'];
            $db->update($condition,array('deleted'=>1));
            //改变空间大小
            $users = new Model('users');
            $condition=array('uid'=>$_SESSION['uid']);
            $result=$db->find($condition);
            $used=$result['used']+$sumsize;
            $newrow = array("used" => $used);
            $db->update($condition,$newrow);
            SUCCESS::Catcher('success', ['total'=>intval($result['capacity']), 'used'=>intval($result['used']), 'available'=>$result['capacity']-$result['used']]);
        }
        //文件夹
        if($result['is_dir']==1)
        {
        //改变空间大小
        $db=new Model("disk_file");
        $condition=array('path like :word',':word'=>arg('path').'%','uid'=>$_SESSION['uid'],'deleted'=>0);
        $result=$db->findAll($condition);
        if(empty($result))ERR::Catcher(6001);
        $sumsize=0;
        for($i=0;count($result,0);$i++){
            $sumsize=$sumsize+$result[$i]['filesize'];
        }
        //删除文件
        $db=new Model("disk_file");
        $condition=array('path'=>arg('path'),'filename'=>$filename,'uid'=>$_SESSION['uid']);
        $result=$db->update($condition,array('deleted'=>1));
        if($result==0)ERR::Catcher(1002);
        $condition=array('path'=>$path,'filename'=>$filename,'uid'=>$_SESSION['uid']);
        $result=$db->update($condition,array('deleted'=>1));
        if($result==0)ERR::Catcher(1002);
        //更新剩余空间
        $users = new Model('users');
        $condition=array('uid'=>$_SESSION['uid']);
        $result=$db->find($condition);
        $used=$result['used']+$sumsize;
        $newrow = array("used" => $used);
        $db->update($condition,$newrow);
        SUCCESS::Catcher('success', ['total'=>intval($result['capacity']), 'used'=>intval($result['used']), 'available'=>$result['capacity']-$result['used']]);
        }
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
        if(substr_compare($path, '/', -1) === 0)
        {
            $name=array();
            if(strcmp($path,'/')) {
                $name[1]='/';
                return $name;
            }
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