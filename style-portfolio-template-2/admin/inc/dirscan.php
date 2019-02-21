<?php
$syscode = "GB2312";//文件系统编码

//UTF-8转换为系统编码
function tosyscode($str){
    return (iconv("UTF-8",$syscode."//IGNORE",$str));
}
//系统编码转换为UTF-8
function touftcode($str){
    return (iconv($syscode,"UTF-8",$str));
}
function lto($urlstr){
    echo ("<script type='text/javascript'>location.href='".$urlstr."';</script>");
}
  // ------------ lixlpixel recursive PHP functions -------------
// scan_directory_recursively( directory to scan, filter )
// expects path to directory and optional an extension to filter
// ------------------------------------------------------------
function scan_directory_recursively($directory, $filter=FALSE)
{
    if(substr($directory,-1) == '/')
    {
        $directory = substr($directory,0,-1);
    }
    if(!file_exists($directory) || !is_dir($directory))
    {
        return FALSE;
    }elseif(is_readable($directory))
    {
        $directory_list = opendir($directory);
        while($file = readdir($directory_list))
        {
            if($file != '.' && $file != '..')
            {
                $path = $directory.'/'.$file;
                if(is_readable($path))
                {
                    $subdirectories = explode('/',$path);
                    if(is_dir($path))
                    {
                        $directory_tree[] = array(
                            'path'      => substr($path, 3 ),
                            'name'      => end($subdirectories),
                            'kind'      => 'directory',
                            'content'   => scan_directory_recursively($path, $filter));
                    }elseif(is_file($path))
                    {
                        $extension = end(explode('.',end($subdirectories)));
                        if($filter === FALSE || $filter == $extension)
                        {
                            $directory_tree[] = array(
                            'path'        => substr($path, 3 ),
                            'name'        => end($subdirectories),
                            'extension' => $extension,
                            'size'        => filesize($path),
                            'kind'        => 'file');
                        }
                    }
                }
            }
        }
        closedir($directory_list); 
        return $directory_tree;
    }else{
        return FALSE;    
    }
}
// ------------------------------------------------------------

?>