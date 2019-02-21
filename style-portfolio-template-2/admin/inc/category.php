<?php

    $xml= xml2ary(file_get_contents("../gallery_xml/".tosyscode($_GET['edit']).".xml"));
    //  $thiscat=$_GET['catnumber'];
    $thiscat=$_GET['catnumber'];
    $catdata=$xml[data][0][_c][cate][$_GET['catnumber']];
    $imagesize=sizeof($catdata[_c][image]);
    $filemenu=scan_directory_recursively("../".tosyscode($catdata[_c][folder][0][_v]));
    // print_r($filemenu);
    // print_r($thiscat);




    /////////MOVE IMAGES
    if (isset($_POST['UP'])){

        $selected= $_POST['itemnumber']-1;
        $temp = $catdata[_c][image][$selected-1];
        $tempd = $catdata[_c][dida][$selected-1];
        $catdata[_c][image][$selected-1] = $catdata[_c][image][$selected];
        $catdata[_c][dida][$selected-1] = $catdata[_c][dida][$selected];
        $catdata[_c][image][$selected] = $temp;
        $catdata[_c][dida][$selected] = $tempd;
        $xml[data][0][_c][cate][$_GET['catnumber']]=$catdata; 
        $last= ary2xml($xml);




        //write();
        $fh = fopen("../gallery_xml/".tosyscode($_GET['edit']).".xml", "w");

        if($fh==false)
            die("无法创建文件");

        fwrite($fh, $last);
        fclose($fh);
        lto("indexlog.php?page=category&edit=".urlencode($_GET['edit'])."&catnumber=".$_GET['catnumber']);
        //lto("indexlog.php?page=category&edit=".$_GET['edit']."&catnumber=".$_GET['catnumber']);
        ob_end_flush();

    }



    else if (isset($_POST['DOWN'])){

            $selected= $_POST['itemnumber']-1;
            $temp = $catdata[_c][image][$selected+1];
            $tempd = $catdata[_c][dida][$selected+1];
            $catdata[_c][image][$selected+1] = $catdata[_c][image][$selected];
            $catdata[_c][dida][$selected+1] = $catdata[_c][dida][$selected];
            $catdata[_c][image][$selected] = $temp;
            $catdata[_c][dida][$selected] = $tempd;
            $xml[data][0][_c][cate][$_GET['catnumber']]=$catdata; 
            $last= ary2xml($xml);




            //write();
            $fh = fopen("../gallery_xml/".tosyscode($_GET['edit']).".xml", "w");

            if($fh==false)
                die("无法创建文件");

            fwrite($fh, $last);
        fclose($fh);
        lto("indexlog.php?page=category&edit=".urlencode($_GET['edit'])."&catnumber=".$_GET['catnumber']);
        ob_end_flush();
    }
    ///////////////////// END MOVE MENU/////////
    //////////////DELETE///////////////////
    else  if(isset($_GET['delete'])){
            $selected=$_GET['img'];
            unlink("../".$catdata[_c][folder][0][_v].$catdata[_c][image][$selected][_v]);
            if ($selected==0){
                array_shift($catdata[_c][image]);
                array_shift($catdata[_c][dida]);
            }else{
                unset($catdata[_c][image][$selected]);
                unset($catdata[_c][dida][$selected]);
            }


            $xml[data][0][_c][cate][$_GET['catnumber']]=$catdata;
            $last=ary2xml($xml);

            //write();
            $fh = fopen("../gallery_xml/".tosyscode($_GET['edit']).".xml", "w");

            if($fh==false)
                die("无法创建文件");

            fwrite($fh,$last);
        fclose($fh);
        lto("indexlog.php?page=category&edit=".urlencode($_GET['edit'])."&catnumber=".$_GET['catnumber']);
        ob_end_flush();




    }

    ////SAVE AFTER EDIT
    else if (isset($_POST['saveedit'])){

            $selected= $_POST['itemnumber'];
            if($_POST['tube']==""){
                $catdata[_c][image][$selected][_v]=$_POST['image'];
            }else{
               $catdata[_c][image][$selected][_v]=$_POST['tube']; 
            }
            
            $catdata[_c][dida][$selected][_v]=$_POST['dida'];

            $xml[data][0][_c][cate][$_GET['catnumber']]=$catdata; 
            $last= ary2xml($xml);




            //write();
            $fh = fopen("../gallery_xml/".tosyscode($_GET['edit']).".xml", "w");

            if($fh==false)
                die("无法创建文件");

            fwrite($fh, $last);
        fclose($fh);
        lto("indexlog.php?page=category&edit=".urlencode($_GET['edit'])."&catnumber=".$_GET['catnumber']);
        ob_end_flush();
    }
    //////// END SAVE AFTER EDIT///////////////
    
     ////ADD IMAGE//////////
    else if (isset($_POST['add'])){
            if($_POST['tube']==""){
                $catdata[_c][image][$selected][_v]=$_POST['image'];
                $catdata[_c][dida][$imagesize][_v]="<h2>".$_POST['image']."</h2><p><span class='infotext'>".$_POST['image']."</span></p>";
            }else{
               $catdata[_c][image][$selected][_v]=$_POST['tube'];  
              $catdata[_c][dida][$imagesize][_v]="<h2>Video</h2><p><span class='infotext'>video</span></p>"; 
            }
            //$catdata[_c][image][$imagesize][_v]=$_POST['image'];
           
            

            $xml[data][0][_c][cate][$_GET['catnumber']]=$catdata; 
            $last= ary2xml($xml);




            //write();
            $fh = fopen("../gallery_xml/".tosyscode($_GET['edit']).".xml", "w");

            if($fh==false)
                die("无法创建文件");

            fwrite($fh, $last);
        fclose($fh);
        lto("indexlog.php?page=category&edit=".urlencode($_GET['edit'])."&catnumber=".$_GET['catnumber']);
        ob_end_flush();
    }
    //////// END ADD IMAGE///////////////
    
     ////MAGIC//////////
    else if (isset($_POST['magic'])){
        
        
        
            for ($i=0;$i<sizeof($filemenu);$i++){
                
                
                  if (!array_searchRecursive($filemenu[$i]['name'],$catdata[_c][image])){
                      
                      $catdata[_c][image][$imagesize+$i][_v]=touftcode($filemenu[$i]['name']);
                      $catdata[_c][dida][$imagesize+$i][_v]="<h2>".touftcode($filemenu[$i]['name'])."</h2><p><span class='infotext'>".touftcode($filemenu[$i]['name'])."</span></p>";
                      
                  }  
                  
                
                
                
                
            }

            
            

            $xml[data][0][_c][cate][$_GET['catnumber']]=$catdata; 
            $last= ary2xml($xml);




            //write();
            $fh = fopen("../gallery_xml/".tosyscode($_GET['edit']).".xml", "w");

            if($fh==false)
                die("无法创建文件");

            fwrite($fh, $last);
        fclose($fh);
        lto("indexlog.php?page=category&edit=".urlencode($_GET['edit'])."&catnumber=".$_GET['catnumber']);
        ob_end_flush();
    }
    ////////MAGIC///////////////
    
    ////MAGIC//////////
    else if (isset($_POST['savename'])){
        
        
        
          
                      
          $catdata[_c][nome][0][_v]="<h4>".$_POST['name']."</h4>";
         
                      
                 

            $xml[data][0][_c][cate][$_GET['catnumber']]=$catdata; 
            $last= ary2xml($xml);




            //write();
            $fh = fopen("../gallery_xml/".tosyscode($_GET['edit']).".xml", "w");

            if($fh==false)
                die("无法创建文件");

            fwrite($fh, $last);
        fclose($fh);
        lto("indexlog.php?page=category&edit=".urlencode($_GET['edit'])."&catnumber=".$_GET['catnumber']);
        ob_end_flush();
    }
    ////////MAGIC///////////////



    else {



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
        echo "<td class='mfirst' colspan='4'></td>";
        echo"</tr>";
        echo"<tr>";
        echo "<td class='mfirst' colspan='4'><h4>编辑类别 [".strip_tags($catdata[_c][nome][0][_v])."] 数据 - 所属模块:<b><a href='indexlog.php?page=gallery&edit=".urlencode($_GET['edit'])."'>".$_GET['edit']."</a></b></h4></td>";
        echo"</tr><tr><td class='mfirst' colspan='4'>";
        
        
        echo" <table class='menu' width='800px' border='0' align='center' cellpadding='0' cellspacing='0'>
        <tr>
        <td width='16'><img src='images/top_lef.gif' width='16' height='16'></td>
        <td height='16' background='images/top_mid.gif'><img src='images/top_mid.gif' width='16' height='16'></td>

        <td width='24'><img src='images/top_rig.gif' width='24' height='16'></td>
        </tr>
        <tr>
        <td width='16' background='images/cen_lef.gif'><img src='images/cen_lef.gif' width='16' height='11'></td>
        <td align='center' valign='middle' bgcolor='#FFFFFF'>";
        
        
        echo"<table class='menu' width='100%'>";
         echo "<form action='indexlog.php?page=category&edit=".urlencode($_GET['edit'])."&catnumber=".$_GET['catnumber']."' method='post'>";
        echo"<tr>";
        echo "<td class='grid' colspan='4'><p>修改类别名称: <input name='name' value='".strip_tags($catdata[_c][nome][0][_v])."' type='text'><input name='savename' type='submit' class='save' value='   ' title='保存修改'/></p></td>";
        echo"</tr>";
        
        
       
        echo "<tr><td class='grid' colspan='2'><p >添加新的 图片/视频 条目:<br><br> ";
        echo  "<select name='image' >"; 
        for ($j=0;$j<sizeof($filemenu);$j++){

            if ($filemenu[$j]['kind']=="file"){
                echo "<option value='".touftcode($filemenu[$j]['name'])."'>".touftcode($filemenu[$j]['name'])."</option>";
            }
        }
        echo "</select><br><br><p>是否添加YouTube视频?<br>添加YouTube视频示例: <b>v=-zvCUmeoHpw</b><br><br>如不是YouTube视频?<br><b>请选择已上传的图片或视频!<br></b><input name='tube' value='".$catdata[_c][image][$_GET['editadd']][_v]."' type='text'><br>" ;
        echo "<input name='add' type='submit' class='btn' value='添加' /></p></td>";
        echo"<td class='grid'><p ><a id='gallery' href='./plupload/upload/gallery.html?folder=".$catdata[_c][folder][0][_v]."'><img src='img/upload.png'/><br>上传图片或视频</a></td><td class='grid'><p>自动生成列表!<br><input name='magic' type='submit' class='btn' value='快速生成' /></p></td>";
        echo"</tr></form></table>";
        
         echo"</td>
        <td width='24' background='images/cen_rig.gif'><img src='images/cen_rig.gif' width='24' height='11'></td>
        </tr>
        <tr>
        <td width='16' height='16'><img src='images/bot_lef.gif' width='16' height='16'></td>

        <td height='16' background='images/bot_mid.gif'><img src='images/bot_mid.gif' width='16' height='16'></td>
        <td width='24' height='16'><img src='images/bot_rig.gif' width='24' height='16'></td>
        </tr>
        </table>";
        
        
        
        
        
        
        
        echo"</td></tr><tr>";
        echo "<td class='mfirst' colspan='4'></td>";
        echo"</tr>";
        echo "<tr>";
        echo "<td class='grid'><p>图片/视频</p></td>";
        echo "<td class='grid'><p>标题/描述</p></td>";
        echo "<td class='grid'><p>排序</p></td>";
        echo "<td class='grid'><p>操作</p></td>";

        echo "</tr>";

        for  ($i=0;$i<$imagesize;$i++){
            echo "<tr><form action='indexlog.php?page=category&edit=".urlencode($_GET['edit'])."&catnumber=".$thiscat."' method='post'>";
            if(substr($catdata[_c][image][$i][_v],0,2)=="v="){
                  echo "<td class='grid'><p><a id='example1' href='./sample/tube.jpg'><img class='border' src='./sample/tube.jpg' height='75' width='75' /></a></p></td>";
                
            }else if (substr($catdata[_c][image][$i][_v],-3)=="flv" || substr($catdata[_c][image][$i][_v],-3)=="f4v" || substr($catdata[_c][image][$i][_v],-3)=="mp4"){
                 echo "<td class='grid'><p><a id='example1' href='./sample/flv.jpg'><img class='border' src='./sample/flv.jpg' height='75' width='75' /></a></p></td>";
            }else{
                            
            echo "<td class='grid'><p><a id='example1' href='../".$catdata[_c][folder][0][_v].$catdata[_c][image][$i][_v]."'><img class='border' src='../".$catdata[_c][folder][0][_v].$catdata[_c][image][$i][_v]."' height='75' width='75' /></a></p></td>";
        }
            echo "<td class='grid'><p>".strip_tags($catdata[_c][dida][$i][_v])."</p></td>";
            if($imagesize>1){
                if($i<$imagesize-1 &&  $i >0){
                    echo "<td class='grid'> <p><input name='UP' type='submit' class='up' value='   ' title='上移'/> 
                        <input name='DOWN' type='submit' class='down' value='   '  title='下移'/></p><input name='itemnumber' type='hidden' value='".($i+1)."' /></td>";     
                }else if ($i==0){
                        echo "<td class='grid'> <p><input name='DOWN' type='submit' class='down' value='   ' title='下移' /></p><input name='itemnumber' type='hidden' value='".($i+1)."' /></td>";     
                    }else if ($i==$imagesize-1){
                            echo "<td class='grid'><p><input name='UP' type='submit' class='up' value='   ' title='上移' /> </p><input name='itemnumber' type='hidden' value='".($i+1)."' /></td>";  
                        }

            }else{
                echo "<td class='grid'>&nbsp;</td>";  
            }


            //echo "<td class='grid'><p><a id='various1' href='edit=menu#inline1' title='Lorem ipsum dolor sit amet'>编辑</a> <a>DELETE</a></p></td>";
            if($imagesize>1){

                echo "<td class='grid'><p><a href='indexlog.php?page=category&editadd=".$i."&edit=".urlencode($_GET['edit'])."&catnumber=".$_GET['catnumber']."' title='编辑'><img src='img/document-edit.png' alt='编辑' /></a><a href='javascript:void(0)' onClick=\"submitFormDelImg('".$thiscat."','".$i."','delete','".$_GET['edit']."');\"  title='删除'><img src='img/dialog-close-2.png' alt='删除' /></a></p></td>";



                echo "</form></tr>";
            }else{
                echo "<td class='grid'><p><a href='indexlog.php?page=category&editadd=&editadd=".$i."&edit=".urlencode($_GET['edit'])."&catnumber=".$_GET['catnumber']."' title='Edit'><img src='img/document-edit.png'  /></a></p></td>";
            }
        }
        echo " </table>";
        echo"</td>
        <td width='24' background='images/cen_rig.gif'><img src='images/cen_rig.gif' width='24' height='11'></td>
        </tr>
        <tr>
        <td width='16' height='16'><img src='images/bot_lef.gif' width='16' height='16'></td>

        <td height='16' background='images/bot_mid.gif'><img src='images/bot_mid.gif' width='16' height='16'></td>
        <td width='24' height='16'><img src='images/bot_rig.gif' width='24' height='16'></td>
        </tr>
        </table>";







    }



    //////////GESTIONE POPUP//////////////
    if(isset($_GET['editadd'])){
        echo "<script language='JavaScript'>
        jQuery(document).ready(function() {
        $.fancybox(
        {";
        if($_GET['editadd']<> "new"){

            echo" 'href':'#edit' ,";
        }
        else  {

            echo" 'href':'#add' ,";

        }



        echo"     
        'autoDimensions'    : false,
        'autoscale':true,
        'width':800,
        'height':600,
        'overlayShow'       : false,
        'overflow'          :'auto',
        'onCleanup'        : function() {   
        window.location = 'indexlog.php?page=category&edit=".urlencode($_GET['edit'])."&catnumber=".$_GET['catnumber']."'; 
        }


        }
        );
        });</script>";
    }
    /////////////////////FINE GESTIONE POPUP//////////////////// 
    /*       //////////GESTIONE POPUP ADD//////////////
    if(isset($_GET['add'])){
    $type=$_GET['type'];
    echo "<script language='JavaScript'>
    jQuery(document).ready(function() {
    $.fancybox(
    {";
    if($type=="text"){

    echo" 'href':'#textadd' ,";

    }else if ($type=="gallery"){
    echo" '<h2>Hi gallery!</h2><p>".$type."</p>',";
    }else if ($type=="news"){
    echo" 'href':'#newsadd' ,";
    }else if ($type=="contacts"){
    echo" 'href':'#contactsadd' ,";
    }else if ($type=="link"){
    echo" 'href':'#linkadd' ,";
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
    /////////////////////FINE GESTIONE POPUP ADD////////////////////*/

    //CONTACTS HIDDEN CODE /////    
    echo"<div style='display: none;'>
    <div id='edit'><form action='indexlog.php?page=category&edit=".urlencode($_GET['edit'])."&catnumber=".$_GET['catnumber']."' method='post'>
    <table class='menu' width='100%' border='0' cellpadding='2'>
    <tr>
    <td class='grid' colspan='2'><h4>编辑 图片/视频 <input name='saveedit' type='submit' class='save' value='   ' /><input name='itemnumber' type='hidden' value='".$_GET['editadd']."' /></h4></td></tr>";
    echo "<tr>"; 
    echo  "<td class='grid'><p>图片/视频</p></td><td class='grid'><select name='image' >"; 
    echo "<option value='".$catdata[_c][image][$_GET['editadd']][_v]."'>".$catdata[_c][image][$_GET['editadd']][_v]."</option>";
    for ($j=0;$j<sizeof($filemenu);$j++){

        if ($filemenu[$j]['kind']=="file"){
            echo "<option value='".touftcode($filemenu[$j]['name'])."'>".touftcode($filemenu[$j]['name'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr>";
     echo "<tr>
    <td class='grid'><p>是否添加YouTube视频?
<br>添加YouTube视频示例:<b>v=-zvCUmeoHpw</b><br><br>如不是YouTube视频?<br><b>请选择已上传的图片或视频!</b></p></td>";
    if (substr($catdata[_c][image][$_GET['editadd']][_v],0,2)=="v="){
        
        echo"<td class='grid'> <input name='tube' value='".$catdata[_c][image][$_GET['editadd']][_v]."' type='text'></td>";
    }else{
        echo"<td class='grid'> <input name='tube' value='' type='text'></td>";
    }
    
    echo "</tr>";
    echo "<tr>
    <td class='grid'><p>标题/描述:</p></td>
    <td class='grid'><textarea name='dida' cols='100' rows='2' class='tinymce'>".$catdata[_c][dida][$_GET['editadd']][_v]."</textarea></td>
    </tr>";
    echo"<tr>
    <td class='grid' colspan='2'><p><input name='saveedit' type='submit' class='save' value='   ' /></p></td>
    </tr>
    </table></form>
    </div>
    </div> ";
    //END CONTACTS HIDDEN CODE ///// 


?>
