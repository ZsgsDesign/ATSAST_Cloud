<?php

    $filegallery=scan_directory_recursively('../gallery_xml','xml');
    $gallnum=sizeof($filegallery);
    // print_r($gallnum);
    // print_r($filegallery);

    if(isset($_POST['addgallery'])){
        // echo"test";
       
        $gallname = $_POST['name'] ;
        
        
        if(!file_exists("../gallery_xml/".tosyscode($gallname).".xml")){
            if (smartCopy("sample/assets_sample", "../assets_".tosyscode($gallname), $folderPermission=0777,$filePermission=0777)){

                if(copy("sample/sample.xml", "../gallery_xml/".tosyscode($gallname).".xml")){


                    $filetoedit=xml2ary(file_get_contents("../gallery_xml/".tosyscode($gallname).".xml"));
                    $num=count($filetoedit[data][0][_c][cate]);
                    for ($a=0;$a<$num;$a++){
                        $filetoedit[data][0][_c][cate][$a][_c][folder][0][_v]=str_replace("replaceme","assets_".$gallname,$filetoedit[data][0][_c][cate][$a][_c][folder][0][_v]);
                    }
                    $last= ary2xml($filetoedit);
                    //write();
                    $fh = fopen("../gallery_xml/".tosyscode($gallname).".xml", "w");

                    if($fh==false)
                        die("unable to create file");

                    fwrite($fh, $last);
                    fclose($fh);
                    lto("indexlog.php?page=gallery");
                    //header("Location:indexlog.php?page=gallery");
                    ob_end_flush();
                }

            }


        }else{
            echo"<script type='text/javascript'>alert('模块名称已被使用!');</script>";

        }
    }
    else if (isset($_POST['UP'])){
            $getinfo=xml2ary(file_get_contents("../gallery_xml/".tosyscode($_GET['edit']).".xml"));

            $selected= $_POST['itemnumber'];
            $temp = $getinfo[data][0][_c][cate][$selected-1];
            $getinfo[data][0][_c][cate][$selected-1] = $getinfo[data][0][_c][cate][$selected];
            $getinfo[data][0][_c][cate][$selected] = $temp;

            $last= ary2xml($getinfo);




            //write();
            $fh = fopen("../gallery_xml/".tosyscode($_GET['edit']).".xml", "w");

            if($fh==false)
                die("unable to create file");

            fwrite($fh, $last);
        fclose($fh);
        lto("indexlog.php?page=gallery&edit=".urlencode($_GET['edit']));
        //header("Location:indexlog.php?page=gallery&edit=".$_GET['edit']);
        ob_end_flush();

    }else    if (isset($_POST['DOWN'])){
            $getinfo=xml2ary(file_get_contents("../gallery_xml/".tosyscode($_GET['edit']).".xml"));

            $selected= $_POST['itemnumber'];
            $temp = $getinfo[data][0][_c][cate][$selected+1];
            $getinfo[data][0][_c][cate][$selected+1] = $getinfo[data][0][_c][cate][$selected];
            $getinfo[data][0][_c][cate][$selected] = $temp;

            $last= ary2xml($getinfo);




            //write();
            $fh = fopen("../gallery_xml/".tosyscode($_GET['edit']).".xml", "w");

            if($fh==false)
                die("unable to create file");

            fwrite($fh, $last);
        fclose($fh);
        lto("indexlog.php?page=gallery&edit=".urlencode($_GET['edit']));
        //header("Location:indexlog.php?page=gallery&edit=".$_GET['edit']);
        ob_end_flush();
    }

    ///ADD CATEGORY
    if(isset($_POST['addcategory'])){
        $filetoedit=xml2ary(file_get_contents("../gallery_xml/".tosyscode($_GET['edit']).".xml"));
        $fsample=xml2ary(file_get_contents("sample/sample.xml"));
        $gallname=// clean up the user specified foldername
        $gallname = stripslashes ( $_POST['name'] );
        //This erase white-spaces on the beginning and the end in each line ofa string:
        $gallname = preg_replace('~^(\s*)(.*?)(\s*)$~m', "\\2", $gallname);
        //erases all NON-alfanumerics
        //$gallname = ereg_replace("[^[:alnum:] ]","",$gallname);
        // take out repetative spaces:
        $gallname = preg_replace('/\s\s+/', ' ', $gallname);
        $gallname = str_replace(' ','_', $gallname);
        if ($gallname == ""){$gallname = "untitled";}
        
        if(!file_exists("../assets_".tosyscode($_GET['edit'])."/cat_".tosyscode($gallname)."/")){
            if (smartCopy("sample/assets_sample/cat_1", "../assets_".tosyscode($_GET['edit'])."/cat_".tosyscode($gallname), $folderPermission=0777,$filePermission=0777)){

                $num=count($filetoedit[data][0][_c][cate]);
                $temp=$fsample[data][0][_c][cate][0];
                $temp[_c][folder][0][_v]="assets_".$_GET['edit']."/cat_".$gallname."/";
                $temp[_c][nome][0][_v]="<h4>".$_POST['name']."</h4>";
                $filetoedit[data][0][_c][cate][$num]=$temp;
                $last= ary2xml($filetoedit);
                //write();
                $fh = fopen("../gallery_xml/".tosyscode($_GET['edit']).".xml", "w");

                if($fh==false)
                    die("unable to create file");

                fwrite($fh, $last);
                fclose($fh);
                lto("indexlog.php?page=gallery&edit=".urlencode($_GET['edit']));
                //header("Location:indexlog.php?page=gallery&edit=".$_GET['edit']);
                ob_end_flush();


            }


        }else{
            echo"<script type='text/javascript'>alert('类别名称已被使用!');</script>";

        }
    }




    //DELETE GALLERY
    if ($_GET['delete']=='delete'){

        $getinfo=xml2ary(file_get_contents("../gallery_xml/".tosyscode($_GET['edit']).".xml"));
        $directory= "../assets_".tosyscode($_GET['itemnumber']);
        // print_r($getinfo);
        // Delete it
        if (recursive_remove_directory($directory))
        {
            if(unlink("../gallery_xml/".tosyscode($_GET['itemnumber']).".xml")){
                lto("indexlog.php?page=gallery");
                //header("Location:indexlog.php?page=gallery");
                ob_end_flush();  
            }else{

                echo"无法删除 ../gallery_xml/".$_GET['itemnumber'].".xml";
            }

        }
        else
        {
        }   

    }

    //DELETE CATEGORY
    if ($_GET['delete']=='deletecat'){
        $getinfo=xml2ary(file_get_contents("../gallery_xml/".tosyscode($_GET['edit']).".xml"));
        $directory='../assets_'.tosyscode($_GET['itemnumber']);
        //print_r($getinfo[data][0][_c][cate][$_GET['catnumber']][_c][folder][0][_v]);
        //print_r($_GET['catnumber']);
        // Delete it
  if (recursive_remove_directory("../".tosyscode($getinfo[data][0][_c][cate][$_GET['catnumber']][_c][folder][0][_v])))
        {


            if ($_GET['catnumber']==0){
                array_shift($getinfo[data][0][_c][cate]);
            }else{
                unset($getinfo[data][0][_c][cate][$_GET['catnumber']]);
            }
            $last= ary2xml($getinfo);



            //write();
            $fh = fopen("../gallery_xml/".tosyscode($_GET['edit']).".xml", "w");

            if($fh==false)
                die("unable to create file");

            fwrite($fh, $last);
            fclose($fh);
       //header("Location:indexlog.php?page=gallery&edit=".$_GET['edit']);
                lto("indexlog.php?page=gallery&edit=".urlencode($_GET['edit']));
            ob_end_flush();  


        }
        else   {
        }  

    }




    echo "
    <table class='menu' width='1000px'  align='center' border='0' align='center' cellpadding='0' cellspacing='0'>
    <tr>
    <td width='16'><img src='images/top_lef.gif' width='16' height='16'></td>
    <td height='16' background='images/top_mid.gif'><img src='images/top_mid.gif' width='16' height='16'></td>

    <td width='24'><img src='images/top_rig.gif' width='24' height='16'></td>
    </tr>
    <tr>
    <td width='16' background='images/cen_lef.gif'><img src='images/cen_lef.gif' width='16' height='11'></td>
    <td align='center' valign='middle' bgcolor='#FFFFFF'>";

    echo"<table class='menu' width='100%' border='0' cellpadding='0'  align='center'>";
    echo"<tr>";
    echo "<td class='mfirst' colspan='2'></td>";
    echo"</tr>";
    echo"<tr><form action='indexlog.php?page=gallery' method='post'>";
    echo "<td class='grid' colspan='2'><p >添加新 图片/视频 模块: ";
    echo"<input name='name' value='insert_name' type='text'>";
    echo "<input name='addgallery' type='submit' class='btn' value='添加' /></form></p></td>";
    echo"</tr>";
    echo" <form action='indexlog.php?page=gallery' method='post'><tr><td class='grid' colspan='2'><h4>图片/视频 模块列表</h4></td></tr> ";
    for ($i=0;$i<$gallnum;$i++){
        if(rtrim($filegallery[$i][name],'.xml')==$_GET['edit']){
            echo" <tr><td class='gridev' ><h4>".touftcode(rtrim($filegallery[$i][name],'.xml'))."</h4></td>";  
        }else{
            echo" <tr><td class='grid' ><h4>".touftcode(rtrim($filegallery[$i][name],'.xml'))."</h4></td>";  
        }

        if($gallnum>1){
            echo "<td class='grid'><p><a href='indexlog.php?page=gallery&edit=".urlencode(touftcode(rtrim($filegallery[$i][name],'.xml')))."' title='编辑'>
              <img src='img/document-edit.png' alt='编辑' /></a>
            <a href='javascript:void(0)' onClick=\"submitForm('".touftcode(rtrim($filegallery[$i][name],'.xml'))."','gallery','delete');\" >
              <img src='img/dialog-close-2.png' alt='删除' /></a>";

            echo"</p></td></tr></form>";
        }else{
            echo "<td class='grid'><p><a href='indexlog.php?page=gallery&edit=".urlencode(touftcode(rtrim($filegallery[$i][name],'.xml')))."' title='编辑'><img src='img/document-edit.png'  alt='编辑' /></a></p></td></tr></form>";

        }


    }
    if ($_GET['edit']){
        $gname=$_GET['edit'];
        $getinfo=xml2ary(file_get_contents("../gallery_xml/".tosyscode($gname).".xml"));
        $catenum=sizeof($getinfo[data][0][_c][cate]);
        echo"<tr>";
        echo"<td class='grid' colspan='2'>";
        echo"<h6>".$gname." 类别管理</h6>";
        echo"";
        echo"</td >";
        echo"</tr>";
        echo"<tr><form action='indexlog.php?page=gallery&edit=".urlencode($gname)."' method='post'>";
        echo "<td class='grid' colspan='2'><p >新增类别: ";
        echo"<input name='name' value='新类别名称' type='text'>";
        echo "<input name='addcategory' type='submit' class='btn' value='添加' /></p></td>";
        echo"</form></tr>";
        for ($e=0;$e<$catenum;$e++){
            echo"<form action='indexlog.php?page=gallery&edit=".urlencode($gname)."' method='post'><input name='itemnumber' type='hidden' value='".$e."' /><tr>";

            echo"<td class='grid'><p>".$getinfo[data][0][_c][cate][$e][_c][nome][0][_v]."</p></td>";

            if($catenum>1){
                echo "<td class='grid'><p><a href='indexlog.php?page=category&edit=".urlencode($gname)."&catnumber=".$e."' title='编辑'><img src='img/document-edit.png' alt='编辑' /></a>
                <a href='javascript:void(0)' onClick=\"submitFormCat('".$gname."','".$e."','deletecat');\"  title='删除' ><img src='img/dialog-close-2.png' alt='删除' /></a>";
                if ($e==0){
                    echo"<br><input name='DOWN' type='submit' class='down' value='   '  title='下移' />";   
                }else if ($e>0 && $e <$catenum-1){
                        echo"<br><input name='UP' type='submit' class='up' value='   ' title='上移' /><input name='DOWN' type='submit' class='down' value='   ' title='下移' />";   
                    }else{
                        echo"<br><input name='UP' type='submit' class='up' value='   ' title='上移' />";    
                }



                echo"</p></td>";
            }else{
                echo "<td class='grid'><p><a href='indexlog.php?page=category&edit=".urlencode($gname)."&catnumber=".$e."' title='编辑'><img src='img/document-edit.png'   alt='编辑'/></a></p></td>";

            }


            echo"</form></tr>";  

        }







    }

    echo"</form></tr></table>";


    echo"</td>
    <td width='24' background='images/cen_rig.gif'><img src='images/cen_rig.gif' width='24' height='11'></td>
    </tr>
    <tr>
    <td width='16' height='16'><img src='images/bot_lef.gif' width='16' height='16'></td>

    <td height='16' background='images/bot_mid.gif'><img src='images/bot_mid.gif' width='16' height='16'></td>
    <td width='24' height='16'><img src='images/bot_rig.gif' width='24' height='16'></td>
    </tr>
    </table>";



    //////////GESTIONE POPUP//////////////
    if(isset($_GET['editto'])){
        $type=$menudata[substr($_GET['edit'],-1)][_c][driver][0][_v];
        echo "<script language='JavaScript'>
        jQuery(document).ready(function() {
        $.fancybox(
        {";
        if($type=="home"){

            echo" 'href':'#homeh' ,";
        }
        else if($type=="text"){

                echo" 'href':'#texth' ,";

            }else if ($type=="gallery"){
                    echo" '<h2>Hi gallery!</h2><p>".$type."</p>',";
                }else if ($type=="news"){
                        echo" 'href':'#newsh' ,";
                    }else if ($type=="contacts"){
                            echo" 'href':'#contactsh' ,";
                        }else if ($type=="link"){
                                echo" 'href':'#linkh' ,";
                            }




                            echo"     
        'autoDimensions'    : false,
        'autoscale':true,
        'width':800,
        'height':600,
        'overlayShow'       : false,
        'overflow'          :'auto',
        'onCleanup'        : function() {
        window.location = 'indexlog.php?page=menu';

        }
        }
        );
        });</script>";
    }
    /////////////////////FINE GESTIONE POPUP////////////////////



    //HOME HIDDEN CODE /////    
    echo"<div style='display: none;'>
    <div id='homeh'><form action='indexlog.php?page=menu' method='post'>
    <table class='menu' width='100%' border='0' cellpadding='2'>
    <tr>
    <td class='grid' colspan='2'><h4>Edit ".$type." menu type<input name='savehome' type='submit' class='save' value='   ' /></h4></td>

    </tr>";

    $correct=substr($_GET['edit'],-1);
    echo "<tr>"; 
    echo  "<td class='grid'><p>Image</p></td><td class='grid'><select name='image' >";
    echo "<option value='".$menudata[$correct][_c][img][0][_v]."'>".$menudata[$correct][_c][img][0][_v]."</option>";
    for ($j=0;$j<sizeof($filemenu);$j++){

        if ($filemenu[$j]['kind']=="file"){
            echo "<option value='".$filemenu[$j]['path']."'>".$filemenu[$j]['path']."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr>";
    echo"<tr>
    <td class='grid'><p>Menu item name:</p></td>
    <td class='grid'><input name='name' value='".$menudata[$correct][_c][testo][0][_v]."' type='text'></td>
    </tr>
    <tr>
    <td class='grid' colspan='2'><p><input name='savehome' type='submit' class='save' value='   ' /><input name='itemnumber' type='hidden' value='".$correct."' /></p></td>
    </tr>
    </table></form>
    </div>
    </div> ";
    //END HOME HIDDEN CODE /////   


?>