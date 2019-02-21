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
        if(!self::is_path_legal(arg('path'))) ERR::Catcher(1004);
        if(!self::is_path_existed(arg('path'))) ERR::Catcher(6002);
        $path=arg('path');
        $db=new Model("disk_file");
        SUCCESS::Catcher('success',$db->query('select filename,time,filesize,is_dir from disk_file where uid=:uid and path=:path and deleted=0',array(':uid'=>$_SESSION['uid'],':path'=>$path)));
    }

    //回收站
    public function actionRecycle()
    {
        if (!$this->islogin) ERR::Catcher(2001);
        $db=new Model("disk_file");
        SUCCESS::Catcher('success',$db->query('select filename,time,filesize,is_dir,path from disk_file where uid=:uid and deleted=1',array(':uid'=>$_SESSION['uid'])));   
    }

    //删除文件及文件夹
    public function actionDelete()
    {
        if (!$this->islogin) ERR::Catcher(2001);
        if (!arg('path')) ERR::Catcher(1003);
        if(!self::is_path_legal(arg('path'))) ERR::Catcher(1004);
        if(!self::is_path_existed(arg('path'))) ERR::Catcher(6002);
        $name=self::getName(arg('path'));
        $filename=$name[0];
        $path=$name[1];
        //判断
        $db=new Model('disk_file');
        $condition=array('uid'=>$_SESSION['uid'],'deleted'=>0, 'path'=>$path,'filename'=>$filename);
        $result=$db->find($condition);
        if(empty($result))ERR::Catcher(6003);
        //文件
        if($result['is_dir']==0)
        {
            $sumsize=$result['filesize'];
            $db->update($condition,array('deleted'=>1));
            //改变空间大小
            $db = new Model('users');
            $condition=array('uid'=>$_SESSION['uid']);
            $result=$db->find($condition);
            $used=$result['used']-$sumsize;
            $newrow = array("used" => $used);
            $db->update($condition,$newrow);
            $result = $db->find(['uid=:uid', ':uid'=>$_SESSION['uid']]);
            SUCCESS::Catcher('success', ['total'=>intval($result['capacity']), 'used'=>intval($result['used']), 'available'=>$result['capacity']-$result['used']]);
        }
        //文件夹
        
        //改变空间大小
        $condition=array('path like :path and uid=:uid and deleted=0',':path'=>arg('path').'%',':uid'=>$_SESSION['uid']);
        $result=$db->findAll($condition);
        if(empty($result))ERR::Catcher(1002);
        $sumsize=0;
        for($i=0;$i<count($result);$i++){
            $sumsize=$sumsize+$result[$i]['filesize'];
        }
        //删除文件夹下属文件及文件夹
        $result=$db->update($condition,array('deleted'=>1));
        if($result==0)ERR::Catcher(1002);
        //删除文件夹本身
        $condition=array('path'=>$path,'filename'=>$filename,'uid'=>$_SESSION['uid']);
        $result=$db->update($condition,array('deleted'=>1));
        if($result==0)ERR::Catcher(1002);
        //更新剩余空间
        $db = new Model('users');
        $condition=array('uid'=>$_SESSION['uid']);
        $result=$db->find($condition);
        $used=$result['used']-$sumsize;
        $newrow = array("used" => $used);
        $db->update($condition,$newrow);
        $result = $db->find(['uid=:uid', ':uid'=>$_SESSION['uid']]);
        SUCCESS::Catcher('success', ['total'=>intval($result['capacity']), 'used'=>intval($result['used']), 'available'=>$result['capacity']-$result['used']]);
        
    }

    //新建文件夹
    public function actionNewfolder()
    {
        if (!$this->islogin) ERR::Catcher(2001); 
        if (!arg('path')) ERR::Catcher(1003);
        if(!self::is_path_legal(arg('path'))) ERR::Catcher(1004);
        $name=self::getName(arg('path'));
        $filename=$name[0];
        $path=$name[1];
        if(!self::is_path_existed($path)) ERR::Catcher(6002);
        $db=new Model("disk_file");
        $newrow=array(
            "uid"       =>$_SESSION['uid'],
            "filename"  =>$filename,
            "path"      =>$path,
            "is_dir"    =>1,
            "filesize"  =>0,
            "hash"      =>0

        );
        $result=$db->create($newrow);
        if(!$result)ERR::Catcher(1002);
        SUCCESS::Catcher('success',TRUE);
    }

    //复制文件
    public function actionCopy()
    {
        if (!$this->islogin) ERR::Catcher(2001); 
        if (!arg('src')) ERR::Catcher(1003);
        if (!arg('dst')) ERR::Catcher(1003);
        if(!self::is_path_legal(arg('src'))) ERR::Catcher(1004);
        if(!self::is_path_existed(arg('src'))) ERR::Catcher(6002);
        if(!self::is_path_legal(arg('dst'))) ERR::Catcher(1004);
        if(!self::is_path_existed(arg('dst'))) ERR::Catcher(6002);
        $name1=self::getName(arg('src'));
        $filename1=$name1[0];
        $path1=$name1[1];
        $name2=self::getName(arg('dst'));
        $filename2=$name2[0];
        $path2=$name2[1];
        $users = new Model('users');
        $result = $users->find(['uid=:uid', ':uid'=>$_SESSION['uid']]);
        $maxsize=$result['capacity']-$result['used'];
        $db=new Model("disk_file");
        $condition=array('uid'=>$_SESSION['uid'],'filename'=>$filename1,'path'=>$path1);
        $result=$db->find($condition);
        $sumsize=$result['filesize'];
        if($sumsize>=$maxsize)ERR::Catcher(6001);
        $newrow=array(
            "uid"       =>$_SESSION['uid'],
            "filename"  =>$filename2,
            "path"      =>$path2,
            "is_dir"    =>$result['is_dir'],
            "filesize"  =>$result['filesize'],
            "hash"      =>$result['hash'],
            'deleted'   =>$result['deleted']

        );
        $result=$db->create($newrow);
        if($result==FALSE)ERR::Catcher(1002);
        //修改剩余空间
        $db = new Model('users');
        $condition=array('uid'=>$_SESSION['uid']);
        $result=$db->find($condition);
        $used=$result['used']+$sumsize;
        $newrow = array("used" => $used);
        $db->update($condition,$newrow);
        $result = $db->find(['uid=:uid', ':uid'=>$_SESSION['uid']]);
        SUCCESS::Catcher('success', ['total'=>intval($result['capacity']), 'used'=>intval($result['used']), 'available'=>$result['capacity']-$result['used']]);
    }












    //查看文件内容
    public function actionPreview()
    {
        if (!$this->islogin) ERR::Catcher(2001);
        if (!arg('path')) ERR::Catcher(1003);
        if(!self::is_path_legal(arg('path'))) ERR::Catcher(1004);
        if(!self::is_path_existed(arg('path'))) ERR::Catcher(6002);
        $name=self::getName(arg('path'));
        $filename=$name[0];
        $path=$name[1];
        $extension=strrchr($filename, '.');
        $condition=array('uid'=>$_SESSION['uid'],'deleted'=>0, 'path'=>$path,'filename'=>$filename);
        $result=$db->find($condition);
        if(empty($result))ERR::Catcher(6003);
        $hash=$result['hash']; 
        $dir=CONFIG::GET('CLOUD_FILE_DIRECTORY').DS.substr($hash, 0, 2).DS.substr($hash, 2);
        $mime=array('.txt'=>'text/plain','.jpg'=>'image/jpeg','.pdf'=>'application/pdf','.bmp'=>'image/x-windows-bmp','.png'=>'image/png');
        if(!array_key_exists($extension,$mime))ERROR::Catcher(1005);
        header('Content-Type:'.$mime[$extension]);
        header("filename:".$filename);
        readfile($dir);
        SUCCESS::Catcher('success',TRUE);
        exit;       
    }

    //下载文件
    public function actionDownload()
    {
        if (!$this->islogin) ERR::Catcher(2001);
        if (!arg('path')) ERR::Catcher(1003);
        if(!self::is_path_legal(arg('path'))) ERR::Catcher(1004);
        if(!self::is_path_existed(arg('path'))) ERR::Catcher(6002);
        $name=self::getName(arg('path'));
        $filename=$name[0];
        $path=$name[1];
        $condition=array('uid'=>$_SESSION['uid'],'deleted'=>0, 'path'=>$path,'filename'=>$filename);
        $result=$db->find($condition);
        if(empty($result))ERR::Catcher(6003);
        $hash=$result['hash']; 
        $dir=CONFIG::GET('CLOUD_FILE_DIRECTORY').DS.substr($hash, 0, 2).DS.substr($hash, 2);         
        header ("Content-type: application/octet-stream");    
        header ("Accept-Ranges: bytes");    
        header ("Accept-Length: ".filesize($dir) );    
        header ( "Content-Disposition: attachment; filename=".$filename );       
        readfile($file);    
        SUCCESS::Catcher('success',TRUE);  
    }

    //普通上传
    public function actionUpload()
    {
        if (!$this->islogin) ERR::Catcher(2001);
        if (!arg('path')) ERR::Catcher(1003);
        if(!self::is_path_legal(arg('path'))) ERR::Catcher(1004);
        if(!self::is_path_existed(arg('path'))) ERR::Catcher(6002);
        $name=self::getName(arg('path'));
        $filename=$name[0];
        $path=$name[1];
        //重名
        $db=new Model('disk_file');
        $condition=array('uid'=>$_SESSION['uid'],'deleted'=>0, 'path'=>$path,'filename'=>$filename);
        $result=$db->find($condition);
        if(!empty($result))ERR::Catcher(6004);
        //上传文件
        $fileInfo=$_FILES['file'];
        $users = new Model('users');
        $result = $users->find(['uid=:uid', ':uid'=>$_SESSION['uid']]);
        $maxsize=$result['capacity']-$result['used'];
        $filesize=$fileInfo['size'];
        $hash=getSHA($fileInfo);
        $dir=CONFIG::GET('CLOUD_FILE_DIRECTORY').DS.substr($hash, 0, 2).DS.substr($hash, 2);
    
        if($fileInfo['error']>0)ERR::Catcher(6005);      
        if(is_uploaded_file($fileInfo['tmp_name']))
        {
            if($fileInfo['size']<=$maxsize)
            {
                if(!file_exists($dir))
                {
                    move_uploaded_file($fileInfo['tmp_name'],$dir);
                    
                }
            }else
            {
                ERR::Catcher(6005); 
            }
        }else
        {
            ERR::Catcher(6005);

        }
        
        //数据库操作
        $newrow=array(
            'uid'     =>$_SESSION['uid'],
            'path'    =>$path,
            'filename'=>$filename,
            'hash'    =>$hash,
            'is_dir'  =>0,
            'filesize'=>$filesize
        );
        $db=new Model('disk_file');
        $db->create($newrow);
        if($result==FALSE)ERR::Catcher(1002);
        //剩余空间
        $users = new Model('users');
        $condition=array('uid'=>$_SESSION['uid']);
        $result=$db->find($condition);
        $used=$result['used']+$filesize;
        $newrow = array("used" => $used);
        $db->update($condition,$newrow);
        $result = $db->find(['uid=:uid', ':uid'=>$_SESSION['uid']]);
        SUCCESS::Catcher('success', ['total'=>intval($result['capacity']), 'used'=>intval($result['used']), 'available'=>$result['capacity']-$result['used']]);
    }

    //哈希上传
    public function actionHashupload()
    {
        if (!$this->islogin) ERR::Catcher(2001);
        if (!arg('hash')) ERR::Catcher(1003);
        if (!arg('path')) ERR::Catcher(1003);
        if(!self::is_path_legal(arg('path'))) ERR::Catcher(1004);
        if(!self::is_path_existed(arg('path'))) ERR::Catcher(6002);
        $name=self::getName(arg('path'));
        $filename=$name[0];
        $path=$name[1];
        $hash = strtolower(arg('hash'));
        if (!preg_match('/^[0-9a-f]{40}$/', $hash)) ERR::Catcher(1004);
        $dir=CONFIG::GET('CLOUD_FILE_DIRECTORY').DS.substr($hash, 0, 2).DS.substr($hash, 2);
        
        //数据库上传
        if(!file_exists($dir))ERR::Cacther(6003);
        $newrow=array(
            'uid'     =>$_SESSION['uid'],
            'path'    =>$path,
            'filename'=>$filename,
            'time'    =>time(),
            'hash'    =>$hash,
            'is_dir'  =>0,
            'filesize'=>$result['filesize']
        );
        $db=new Model('disk_file');
        $db->create($newrow);
        if($result==FALSE)ERR::Catcher(1002);
        //剩余空间
        $users = new Model('users');
        $condition=array('uid'=>$_SESSION['uid']);
        $result=$db->find($condition);
        $used=$result['used']+$filesize;
        $newrow = array("used" => $used);
        $db->update($condition,$newrow);
        $result = $db->find(['uid=:uid', ':uid'=>$_SESSION['uid']]);
        SUCCESS::Catcher('success', ['total'=>intval($result['capacity']), 'used'=>intval($result['used']), 'available'=>$result['capacity']-$result['used']]);
    }
    
    //重命名
    public function actionRename(){
        if (!$this->islogin) ERR::Catcher(2001);
        if (!$path=arg('path')) ERR::Catcher(1003);
        if (!self::is_path_legal(arg('path'))) ERR::Catcher(1004);
        if (!self::is_path_existed(arg('path'))) ERR::Catcher(6002);
        if (!$oldname=arg("oldname")) ERR::Catcher(1003);
        if (!$newname=arg("newname")) ERR::Catcher(1003);
        //重名
        $db=new Model('disk_file');
        $condition=array(
            'uid'=>$_SESSION['uid'],
            'deleted'=>0,
            'path'=>$path,
            'filename'=>$newname,
        );
        $result=$db->find($condition);
        if (!empty($result)) {
            ERR::Catcher(6004);
        }
        //检验是否为文件夹
        $condition3=array(
            'uid'=>$_SESSION['uid'],
            'deleted'=>0,
            'path'=>$path,
            'filename'=>$oldname,
        );
        $result3=$db->find($condition);
        //若是文件
        if (empty($result3['is_dir'])||$result3['is_dir']==0) {
            $db->update(
               $condition3,
               array(
                    'filename'=>$newname,
                )
            );
        } 
        //若是文件夹
        else {
            if (namaIllegal($newname)) {
                ERR::Catcher(1004);
            }
            //目标是否有子文件
            $oldpath=$path."/".$oldname;
            $condition2=array(
            "uid=:uid and deleted=0 and path like :path",
            ":path"=>$oldpath.'%',
            ':uid'=>$_SESSION['uid'],
            );
            $result2=$db->find($condition2);
            //若无
            if (empty($result2)) {
                $db->update(
                    $condition3,
                    array(
                        'path'=>$path,
                    )
                );
            } 
            //若有
            else {
                    $db->query('update diskfile set path =replace(path,:oldpath,:newpath) where uid=:uid and deleted=0',
                array(
                    ':oldpath'=>$oldpath,
                    ':newpath'=>$newpath,
                    ':uid'=>$_SESSION['uid'],
                ));
            }
        }
        SUCCESS::Catcher('success', true);
    }

    //移动
    public function actionMove(){
        if (!$this->islogin) ERR::Catcher(2001);
        if ((!arg('oldpath'))||(!arg('newpath'))||(!arg('filename'))) ERR::Catcher(2003);
        if ((!self::is_path_legal(arg('oldpath')))||(!self::is_path_legal(arg('newpath')))) ERR::Catcher(1004);
        if ((!self::is_path_existed(arg('oldpath')))||(!self::is_path_existed(arg('newpath')))) ERR::Catcher(6002);
        $oldpath=arg('oldpath');
        $newpath=arg('newpath');
        $filename=arg('filename');
        //重名
        $db=new Model('disk_file');
        $condition=array(
            'uid'=>$_SESSION['uid'],
            'deleted'=>0,
            'path'=>$newpath,
            'filename'=>$filename,
        );
        $result=$db->find($condition);
        if(!empty($result)){
            ERR::Catcher(6004);
        }
        //所移动目标是否为文件夹
        $condition2=array(
            'uid'=>$_SESSION['uid'],
            'deleted'=>0,
            'path'=>$oldpath,
            'filename'=>$filename,
        );
        $result2=$db->find($condition2);
        //若是文件
        if (empty($result3['is_dir'])||$result3['is_dir']==0) {
            $db->update(
                $condition2,
                array(
                    'path'=>$newpath,
                ));
            SUCCESS::Catcher('success', true);
        }
        //若是文件夹
        //判断文件夹内是否为空
        //若空
        $path2=$oldpath.'/'.$filename;
        $condition3=array(
            'uid'=>$_SESSION['uid'],
            'deleted'=>0,
            'path'=>$path2,
        );
        $result3=$db->find($condition3);
        if (empty($result3)) {
            $db->update(
                $condition2,
                array(
                    'path'=>$newpath,
                ));
            SUCCESS::Catcher('success', true);
        }        
        //若不为空
        else{
            $db->query(
            'update diskfile set path =replace(path,:oldpath,:newpath) where uid=:uid and deleted=0',
            array(
                ':oldpath'=>$oldpath,
                ':newpath'=>$newpath,
                ':uid'=>$_SESSION['uid'],
            ));
            SUCCESS::Catcher('success', true);
        }
    }

    //用到的函数

    private function getSHA($path)
    {
        $fp = fopen($path, 'rb');
        $ctx = hash_init('sha1');
        hash_update($ctx, filesize($path)."\0");
        while (!feof($fp)) {
            hash_update($ctx, fread($fp, 65536));
        }
        return hash_final($ctx);
    }
    
    //文件路径是否正确
    private function is_path_legal($path)
    {
        $pattern='/[\\\\:*?"<>|]|\/\.\/|\/\.\.\//';
        if(preg_match($pattern, $path)) return false;
        if ($path[0] != '/') return false;
        return true;
    }

    //路径是否存在
    private function is_path_existed($path)
    {
        if($path[-1]!='/') $path=getName($path)[1];//获取文件所在文件夹
        if($path=='/') return true;
        $name=self::getName($path);
        $filename=$name[0];
        $path=$name[1];
        $db=new Model("disk_file");
        $condition=array('uid'=>$_SESSION['uid'],'path'=>$path);
        $result=$db->find($condition);
        if(empty($result)) return false;
        return true;

    }

    private function getName($path)
    {
        //是否以/结尾

        if($path[-1]=='/')
        {
            $name=array();
            if($path=='/') 
            {
                $name[0]='';
                $name[1]='/'; 
                return $name;
            }

            $result=explode('/',$path);
            $name[0]=$result[count($result)-2];
            $name[1]=substr($path,0,strlen($path)-strlen($name[0])-1);
            return $name;
        }
        else
        {

            $name=array();
            $result=explode('/',$path);
            $name[0]=$result[count($result)-1];
            $name[1]=substr($path,0,strlen($path)-strlen($name[0])-1)."/";
            return $name;
        }
    }
    
    //检验文件名称非法
    private function nameIllegal($name){
        $pattern="/<|>|\?|\*|\/|\|/";
        if(preg_match($pattern,$name)) return true;
        return false;
    }

    public function actionCreateShare() //创建分享
    {
        if (!$this->islogin)
            ERR::Catcher(2001);
        if (!arg('fid'))
            ERR::Catcher(1003);
        $share = new Model('disk_share');

    }

    public function actionCancelShare() //取消分享
    {
        if (!$this->islogin)
            ERR::Catcher(2001);
    }
}
