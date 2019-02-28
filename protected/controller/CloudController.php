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

    public function actionShowdir()
    {
        if (!$this->islogin) ERR::Catcher(2001);
        if (!arg('path')) ERR::Catcher(1003);
        if(!self::is_path_legal(arg('path'))) ERR::Catcher(1004);
        if(!self::is_path_existed(arg('path'))) ERR::Catcher(6002);
        $path=arg('path');
        $db=new Model("disk_file");
        SUCCESS::Catcher('success',$db->query('select filename from disk_file where uid=:uid and path=:path and deleted=0 and is_dir=1',array(':uid'=>$_SESSION['uid'],':path'=>$path)));
    }

    //回收站
    public function actionRecycle()
    {
        if (!$this->islogin) ERR::Catcher(2001);
        $db=new Model("disk_file");
        SUCCESS::Catcher('success',$db->query('select filename,time,filesize,is_dir,path,fid from disk_file where uid=:uid and deleted=-1',array(':uid'=>$_SESSION['uid'])));
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
        if($result==false)ERR::Catcher(6003);
        //文件
        if($result['is_dir']==0)
        {
            $sumsize=$result['filesize'];
            $db->update($condition,array('deleted'=>-1));
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

        $fid = $result['fid'];
        //改变空间大小
        $condition=array('path like :path and uid=:uid and deleted=0',':path'=>arg('path').'%',':uid'=>$_SESSION['uid']);
        $result=$db->findAll($condition);
        $sumsize=0;
        for($i=0;$i<count($result);$i++){
            $sumsize=$sumsize+$result[$i]['filesize'];
        }
        //删除文件夹下属文件及文件夹
        $result=$db->update($condition,array('deleted'=>$fid));
        //删除文件夹本身
        $condition=array('fid'=>$fid);
        $result=$db->update($condition,array('deleted'=>-1));
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

    public function actionRecover()
    {
        if (!$this->islogin) ERR::Catcher(2001);
        if (!arg('fid')) ERR::Catcher(1003);
        $db = new Model("disk_file");
        $result = $db->find(['uid'=>$_SESSION['uid'], 'deleted'=>-1, 'fid'=>arg('fid')]);
        if (!$result) ERR::Catcher(6003);
        if (!self::is_path_existed($result['path'])) ERR::Catcher(6002);
        if ($db->find(['uid'=>$_SESSION['uid'], 'deleted'=>0, 'path'=>$result['path'], 'filename'=>$result['filename']])) ERR::Catcher(6004);
        $sum = $result['filesize'];
        $db->update(['fid'=>arg('fid')], ['deleted'=>0]);
        $cond = ['uid'=>$_SESSION['uid'], 'deleted'=>arg('fid')];
        $result = $db->findAll($cond);
        for ($i = 0; $i < count($result); ++$i) $sum += $result[$i]['filesize'];
        $db->update($cond, ['deleted'=>0]);
        $db = new Model("users");
        $db->execute('update users set used=used+:addv where uid=:uid', [':addv'=>$sum, ':uid'=>$_SESSION['uid']]);
        $result = $db->find(['uid'=>$_SESSION['uid']]);
        SUCCESS::Catcher('success', ['total'=>intval($result['capacity']), 'used'=>intval($result['used']), 'available'=>$result['capacity']-$result['used']]);
    }

    public function actionDeleteForever()
    {
        if (!$this->islogin) ERR::Catcher(2001);
        if (!arg('fid')) ERR::Catcher(1003);
        $db = new Model("disk_file");
        $result = $db->find(['uid'=>$_SESSION['uid'], 'deleted'=>-1, 'fid'=>arg('fid')]);
        if (!$result) ERR::Catcher(6003);
        $db->delete(['fid'=>arg('fid')]);
        $db->delete(['deleted'=>arg('fid')]);
        SUCCESS::Catcher('success', TRUE);
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
        if ($db->find(['uid'=>$_SESSION['uid'], 'filename'=>$filename, 'path'=>$path])) ERR::Catcher(6004);
        $newrow=array(
            "uid"       =>$_SESSION['uid'],
            "filename"  =>$filename,
            "path"      =>$path,
            "is_dir"    =>1,
            "filesize"  =>0,
            "hash"      =>''
        );
        $result=$db->create($newrow);
        if(!$result)ERR::Catcher(1002);
        SUCCESS::Catcher('success',TRUE);
    }

    //复制文件 // not used
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
        $condition=array('uid'=>$_SESSION['uid']);
        $result=$users->find($condition);
        $used=$result['used']+$sumsize;
        $newrow = array("used" => $used);
        $users->update($condition,$newrow);
        $result = $users->find(['uid=:uid', ':uid'=>$_SESSION['uid']]);
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
        $db=new Model("disk_file");
        $result=$db->find($condition);
        if($result==false)ERR::Catcher(6003);
        $hash=$result['hash'];
        $dir=CONFIG::GET('CLOUD_FILE_DIRECTORY').DS.substr($hash, 0, 2).DS.substr($hash, 2);
        $mime=array('.txt'=>'text/plain','.jpg'=>'image/jpeg','.pdf'=>'application/pdf','.bmp'=>'image/x-windows-bmp','.png'=>'image/png');
        if(!array_key_exists($extension,$mime))ERROR::Catcher(1005);
        header('Content-Type:'.$mime[$extension]);
        header("filename:".$filename);
        readfile($dir);
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
        $db=new Model("disk_file");
        $result=$db->find($condition);
        if($result==false)ERR::Catcher(6003);
        $hash=$result['hash'];
        $dir=CONFIG::GET('CLOUD_FILE_DIRECTORY').DS.substr($hash, 0, 2).DS.substr($hash, 2);
        header ("Content-type: application/octet-stream");
        header ("Accept-Ranges: bytes");
        header ("Accept-Length: ".filesize($dir) );
        header ( "Content-Disposition: attachment; filename=".$filename );
        readfile($dir);
        exit;
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
        if($result)ERR::Catcher(6004);
        //上传文件
        if (empty($_FILES['file'])) ERR::Catcher(1003);
        $fileInfo=$_FILES['file'];
        if ($_FILES['file']['tmp_name'] == '') ERR::Catcher(6001); // File larger than limit in php.ini
        $users = new Model('users');
        $result = $users->find(['uid=:uid', ':uid'=>$_SESSION['uid']]);
        $maxsize=$result['capacity']-$result['used'];
        $filesize=$fileInfo['size'];
        $hash=self::getSHA($fileInfo['tmp_name']);
        $dir=CONFIG::GET('CLOUD_FILE_DIRECTORY').DS.substr($hash, 0, 2).DS.substr($hash, 2);
        if($fileInfo['error']>0)ERR::Catcher(6005);
        if(!is_uploaded_file($fileInfo['tmp_name']))ERR::Catcher(6005);
        if($fileInfo['size']>$maxsize)ERR::Catcher(6001);
        if(!file_exists($dir)) {
            $tmp = CONFIG::GET('CLOUD_FILE_DIRECTORY').DS.substr($hash, 0, 2);
            if (!file_exists($tmp)) mkdir($tmp);
            move_uploaded_file($fileInfo['tmp_name'],$dir);
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
        $db->create($newrow);
        if($result==false)ERR::Catcher(1002);
        //剩余空间
        $condition=array('uid'=>$_SESSION['uid']);
        $result=$users->find($condition);
        $used=$result['used']+$filesize;
        $newrow = array("used" => $used);
        $users->update($condition,$newrow);
        $result = $users->find(['uid=:uid', ':uid'=>$_SESSION['uid']]);
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
        //重名
        $db=new Model('disk_file');
        $condition=array('uid'=>$_SESSION['uid'],'deleted'=>0, 'path'=>$path,'filename'=>$filename);
        $result=$db->find($condition);
        if($result)ERR::Catcher(6004);
        
        $hash = strtolower(arg('hash'));
        if (!preg_match('/^[0-9a-f]{40}$/', $hash)) ERR::Catcher(1004);
        $dir=CONFIG::GET('CLOUD_FILE_DIRECTORY').DS.substr($hash, 0, 2).DS.substr($hash, 2);

        //数据库上传
        if(!file_exists($dir))ERR::Catcher(6003);
        $filesize=filesize($dir);
        $newrow=array(
            'uid'     =>$_SESSION['uid'],
            'path'    =>$path,
            'filename'=>$filename,
            'hash'    =>$hash,
            'is_dir'  =>0,
            'filesize'=>$filesize
        );
        $db=new Model('disk_file');
        $result=$db->create($newrow);
        if($result==false)ERR::Catcher(1002);
        //剩余空间
        $db = new Model('users');
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
        if (!$oldname=arg("oldname")) ERR::Catcher(1003);
        if (!$newname=arg("newname")) ERR::Catcher(1003);
        if (!self::is_path_legal($newname)) ERR::Catcher(1004);

        $newname = self::getName(arg("newname"));
        $newpath = $newname[1];
        $newname = $newname[0];
        if (!self::is_path_existed($newpath)) ERR::Catcher(6002);
        if (arg("oldname") == '/') ERR::Catcer(1004);
        $oldname = self::getName(arg("oldname"));
        $oldpath = $oldname[1];
        $oldname = $oldname[0];

        if (arg("oldname")[-1] == '/' && substr(arg("newname"), 0, strlen(arg("oldname"))) === arg("oldname")) ERR::Catcher(1004);

        //重名
        $db=new Model('disk_file');
        $condition=array(
            'uid'=>$_SESSION['uid'],
            'deleted'=>0,
            'path'=>$newpath,
            'filename'=>$newname,
        );
        $result=$db->find($condition);
        if (!empty($result)) {
            ERR::Catcher(6004);
        }

        $condition=array(
            'uid'=>$_SESSION['uid'],
            'deleted'=>0,
            'path'=>$oldpath,
            'filename'=>$oldname,
        );
        $result=$db->find($condition);
        if (empty($result)) {
            ERR::Catcher(6003);
        }
        $db->update(
           $condition,
           array(
                'path'=>$newpath,
                'filename'=>$newname,
            )
        );
        if ($result['is_dir']==1) {
            $db->query('update disk_file set path =replace(path,:oldpath,:newpath) where uid=:uid and deleted=0 and path like :pathp', array(
                ':oldpath'=>$oldpath.$oldname.'/',
                ':newpath'=>$newpath.$newname.'/',
                ':uid'=>$_SESSION['uid'],
                ':pathp'=>$oldpath.'%',
            ));
        }
        SUCCESS::Catcher('success', true);
    }

    public function actionSearch()
    {
        if (!$this->islogin) ERR::Catcher(2001);
        if (!arg('keyword')) ERR::Catcher(1003);
        $db=new Model("disk_file");
        SUCCESS::Catcher('success',$db->query('select filename,time,filesize,is_dir,path from disk_file where uid=:uid and filename like :filename and deleted=0',array(':uid'=>$_SESSION['uid'],':filename'=>'%'.arg('keyword').'%')));
    }

    public function actionViewShare() //查看分享
    {
        $sid = $this->sid = arg('sid');
        $this->err = false;
        $path=arg('path');

        $sql = "SELECT filename,disk_file.time,filesize,is_dir FROM disk_file JOIN disk_share ON disk_share.fid = disk_file.fid WHERE sid=:sid AND path=:path AND deleted=0";
        $share = new Model('disk_share');
        if(empty($this->sid))
            $this->jump("");
        else{
            $s = $share->findAll(array("sid=:sid","sid" => $sid));
            if(!count($s)) //找不到分享
                $this->display('share_404.html');
            else if(!empty($s[0]['pswd'])){
                if(empty($_COOKIE['share_'.$sid]))
                    $this->display('share_verify.html');
                else if ($_COOKIE['share_'.$sid] != $s[0]['pswd']){
                    $this->err = true;
                    $this->display('share_verify.html');
                }
                else{
                    SUCCESS::Catcher("success",$share->query($sql,array("sid"=>$sid,"path"=>$path)));
                    //$this->display('share_view.html');
                }
            }
            else {
                SUCCESS::Catcher("success",$share->query($sql,array("sid"=>$sid,"path"=>$path)));
                //$this->display('share_view.html');
            }
        }
    }

    public function actionCreateShare() //创建分享
    {
        if (!$this->islogin)
            ERR::Catcher(2001);
        if (!arg('fid'))
            ERR::Catcher(1003);
        $share = new Model('disk_share');
        $files = arg('fid');
        $pswd = arg('pswd');
        $expire = arg('expire');
        $sid = str_rand(8);
        foreach ($files as $f){
            $share->create(array(
                "sid"    => $sid,
                "uid"    => $_SESSION['uid'],
                "fid"    => $f,
                "pswd"   => $pswd,
                "expire" => $expire,
            ));
        }
        SUCCESS::Catcher("分享成功",array("sid"=>$sid));
    }

    public function actionCancelShare() //取消分享
    {
        if (!$this->islogin)
            ERR::Catcher(2001);
        else if (!arg('sid'))
            ERR::Catcher(1003);
        else{
            $share = new Model('disk_share');
            $count = $share->delete(array("sid=:sid AND uid=:uid",
                "sid" => arg('sid'),
                "uid" => $_SESSION['uid']));
            if($count)
                ERR::Catcher(6100);
            else
                SUCCESS::Catcher("取消分享成功");
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

    //路径是否存在（传入参数的目录）
    private function is_path_existed($path)
    {
        if($path[-1]!='/') $path=self::getName($path)[1];//获取文件所在文件夹
        if($path=='/') return true;
        $name=self::getName($path);
        $filename=$name[0];
        $path=$name[1];
        $db=new Model("disk_file");
        $condition=array('uid'=>$_SESSION['uid'],'path'=>$path,'filename'=>$filename,'is_dir'=>1,'deleted'=>0);
        $result=$db->find($condition);
        if($result==false) return false;
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

    private function str_rand($length = 8, $char = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
        if(!is_int($length) || $length < 0) {
            return false;
        }
        $string = '';
        for($i = $length; $i > 0; $i--) {
            $string .= $char[mt_rand(0, strlen($char) - 1)];
        }
        return $string;
    }
}
