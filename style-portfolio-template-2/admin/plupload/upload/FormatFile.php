<?php
	header('Content-type: text/plain; charset=UTF-8');
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
$syscode = "GB2312";//文件系统编码

//UTF-8转换为系统编码
function tosyscode($str){
    return (iconv("UTF-8","GB2312//IGNORE",$str));
}
//系统编码转换为UTF-8
function touftcode($str){
    return (iconv($syscode,"UTF-8",$str));
}

function formatfilename($fstr){
   $cnstr = "";
   $filestr="";
//   preg_match_all("/[\x{4e00}-\x{9fa5}]+/u",$fstr,$str_aryas);
//   foreach($str_aryas as $value)
//	$cnstr =  $cnstr."".implode($value);

   $filestr = $cnstr.preg_replace('/[^\w\._]+/u', '', $fstr);
   return ($filestr);
}

//echo formatfilename("sgvs.当然.让@@国人.theh覆盖原计划.gif");
?>