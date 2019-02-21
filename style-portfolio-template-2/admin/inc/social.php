<?php

    $social= xml2ary(file_get_contents('../social.xml'));
    $filesocial=scan_directory_recursively("../s_images");
    $filesize=sizeof($filesocial);
    //print_r($social);

    if(isset($_POST['addsocial'])){

        $tot=sizeof($social[data][0][_c][sname]);
        $social[data][0][_c][sname][$tot][_v]=$_POST['name'];
        $social[data][0][_c][img][$tot][_v]=$_POST['image'];
        $social[data][0][_c][link][$tot][_v]=$_POST['link'];





        $last= ary2xml($social);




        //write();
        $fh = fopen("../social.xml", "w");

        if($fh==false)
            die("unable to create file");

        fwrite($fh, $last);
        fclose($fh);
        lto("indexlog.php?page=social");
        ob_end_flush();

    } else if(isset($_GET['delete'])){

            $selected=$_GET['delete'];


            if ($selected==0){
                array_shift($social[data][0][_c][sname]);
                array_shift($social[data][0][_c][img]);
                array_shift($social[data][0][_c][link]);
            }else{
                unset($social[data][0][_c][sname][$selected]);
                unset($social[data][0][_c][img][$selected]);
                unset($social[data][0][_c][link][$selected]);
            }




            $last= ary2xml($social);




            //write();
            $fh = fopen("../social.xml", "w");

            if($fh==false)
                die("unable to create file");

            fwrite($fh, $last);
        fclose($fh);
        lto("indexlog.php?page=social");
        ob_end_flush();







    }else if (isset($_POST['UP'])){

            $selected= $_POST['itemnumber'];
            $temp = $social[data][0][_c][sname][$selected-1];
            $tempd = $social[data][0][_c][img][$selected-1];
            $tempt = $social[data][0][_c][link][$selected-1];
            $social[data][0][_c][sname][$selected-1] = $social[data][0][_c][sname][$selected];
            $social[data][0][_c][img][$selected-1] = $social[data][0][_c][img][$selected];
            $social[data][0][_c][link][$selected-1] = $social[data][0][_c][link][$selected];
            $social[data][0][_c][sname][$selected] = $temp;
            $social[data][0][_c][img][$selected] = $tempd;
            $social[data][0][_c][link][$selected] = $tempt;






            $last= ary2xml($social);




            //write();
            $fh = fopen("../social.xml", "w");

            if($fh==false)
                die("unable to create file");

            fwrite($fh, $last);
        fclose($fh);
        lto("indexlog.php?page=social");
        ob_end_flush();

    }



    else if (isset($_POST['DOWN'])){

            $selected= $_POST['itemnumber'];
            $temp = $social[data][0][_c][sname][$selected+1];
            $tempd = $social[data][0][_c][img][$selected+1];
            $tempt = $social[data][0][_c][link][$selected+1];
            $social[data][0][_c][sname][$selected+1] = $social[data][0][_c][sname][$selected];
            $social[data][0][_c][img][$selected+1] = $social[data][0][_c][img][$selected];
            $social[data][0][_c][link][$selected+1] = $social[data][0][_c][link][$selected];
            $social[data][0][_c][sname][$selected] = $temp;
            $social[data][0][_c][img][$selected] = $tempd;
            $social[data][0][_c][link][$selected] = $tempt;

            $last= ary2xml($social);
            //write();
            $fh = fopen("../social.xml", "w");

            if($fh==false)
                die("unable to create file");

            fwrite($fh, $last);
        fclose($fh);
        lto("indexlog.php?page=social");
        ob_end_flush();

    }

    else if (isset($_POST['saveedit'])){

            $selected= $_POST['itemnumber'];
            
           $social[data][0][_c][sname][$selected][_v]=$_POST['name'];
           $social[data][0][_c][img][$selected][_v]=$_POST['image'];
           $social[data][0][_c][link][$selected][_v]=$_POST['link'];
           
           $last= ary2xml($social);

            //write();
            $fh = fopen("../social.xml", "w");

            if($fh==false)
                die("unable to create file");

            fwrite($fh, $last);
        fclose($fh);
        lto("indexlog.php?page=social");
        ob_end_flush();

    }



    else{



        echo "
        <table class='menu' width='1000px' border='0' align='center' cellpadding='0' cellspacing='0'>
        <tr>
        <td width='16'><img src='images/top_lef.gif' width='16' height='16'></td>
        <td height='16' background='images/top_mid.gif'><img src='images/top_mid.gif' width='16' height='16'></td>

        <td width='24'><img src='images/top_rig.gif' width='24' height='16'></td>
        </tr>
        <tr>
        <td width='16' background='images/cen_lef.gif'><img src='images/cen_lef.gif' width='16' height='11'></td>
        <td align='center' valign='middle' bgcolor='#FFFFFF'>";
        /////////////////////////////////    
        echo"<table class='menu' width='100%' border='0' cellpadding='0'  align='center'>";
        echo"<tr>";
        echo "<td class='mfirst' colspan='4'></td>";
        echo"</tr>";
        echo"<tr><form action='indexlog.php?page=social' method='post'>";
        echo "<td class='grid' colspan='4'><p >添加 网站底部 链接:<br> ";
        echo"名称:<input name='name' value='insert_name' type='text'> 地址: <input name='link' value='http://' type='text'> 图标: ";

        echo  "<select name='image' >";
        for ($a=0;$a<$filesize;$a++){
            if ($filesocial[$a]['kind']=="file"){
                echo "<option value='".touftcode($filesocial[$a]['path'])."'>".touftcode($filesocial[$a]['path'])."</option>";
            }

        }
        echo"</select>";
        echo "<input name='addsocial' type='submit' class='btn' value='添加' />";
        echo"</form></p></td>";
        echo"</tr>";
        echo"<tr><td class='grid' colspan='4'><p><a id='social' href='./plupload/upload/social.html'><img src='img/upload.png'/><br>上传链接图标 (请上传23px *23px的png格式图片)</a></p></td>";
         
          echo"</tr>";  
        echo" <tr><td class='grid' colspan='4'><h4>网站底部链接列表</h4></td></tr> ";
        for ($i=0;$i<sizeof($social[data][0][_c][sname]);$i++){
            echo" <tr><form action='indexlog.php?page=social' method='post'>";
            echo"<td class='grid' ><p><a id='example1' href='../".$social[data][0][_c][img][$i][_v]."'><img class='border' src='../".$social[data][0][_c][img][$i][_v]."' height='23' width='23' /></a></p></td>";
            echo"<td class='grid' ><p>".$social[data][0][_c][sname][$i][_v]."</p></td>";
            echo"<td class='grid' ><p>".$social[data][0][_c][link][$i][_v]."</p></td>";
            if(sizeof($social[data][0][_c][sname])>1){
                echo "<td class='grid'><p><a href='indexlog.php?page=social&edit=".$i."' title='Edit'><img src='img/document-edit.png' alt='Edit' /></a>
                <a href='javascript:void(0)' onClick=\"submitFormDelSocial('".$i."','social','delete');\" ><img src='img/dialog-close-2.png' alt='Delete' /></a>";
                if ($i==0){
                    echo"<br><input name='DOWN' type='submit' class='down' value='   ' />";   
                }else if ($i>0 && $i <sizeof($social[data][0][_c][sname])-1){
                        echo"<br><input name='UP' type='submit' class='up' value='   ' /><input name='DOWN' type='submit' class='down' value='   ' />";   
                    }else{
                        echo"<br><input name='UP' type='submit' class='up' value='   ' />";    
                }



                echo"</p></td>";
            }else{
                echo "<td class='grid'><p><a href='indexlog.php?page=social&edit=".$i."' title='Edit'><img src='img/document-edit.png'  /></a></p></td>";

            }
            echo"</tr><input name='itemnumber' type='hidden' value='".$i."' /></form>";  
        }
        echo"</table>";
        //////////////////
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
    if(isset($_GET['edit'])){
        echo "<script language='JavaScript'>
        jQuery(document).ready(function() {
        $.fancybox(
        {";


        echo" 'href':'#edit' ,";


        echo"     
        'autoDimensions'    : false,
        'autoscale':true,
        'width':800,
        'height':400,
        'overlayShow'       : false,
        'overflow'          :'auto',
        'onCleanup'        : function() {   
        window.location = 'indexlog.php?page=social'; 
        }


        }
        );
        });</script>";
    }


    //EDIT HIDDEN CODE /////    
    echo"<div style='display: none;'>
    <div id='edit'><form action='indexlog.php?page=social' method='post'>
    <table class='menu' width='100%' border='0' cellpadding='2'>
    <tr>
    <td class='grid' colspan='2'><h4>编辑 网站底部 链接 <input name='saveedit' type='submit' class='save' value='   ' /><input name='itemnumber' type='hidden' value='".$_GET['edit']."' /></h4></td></tr>";
    echo "<tr>"; 
    echo  "<td class='grid'><p>图标</p></td><td class='grid'><select name='image' >"; 
    echo "<option value='".$social[data][0][_c][img][$_GET['edit']][_v]."'>".$social[data][0][_c][img][$_GET['edit']][_v]."</option>";
    for ($j=0;$j<$filesize;$j++){

        if ($filesocial[$j]['kind']=="file"){
            echo "<option value='".touftcode($filesocial[$j]['path'])."'>".touftcode($filesocial[$j]['path'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr>";
    echo"<tr>
    <td class='grid'><p>名称:</p></td>
    <td class='grid'><input name='name' value='".$social[data][0][_c][sname][$_GET['edit']][_v]."' type='text'></td>
    </tr>";
    echo"<tr>
    <td class='grid'><p>链接地址:</p></td>
    <td class='grid'><input name='link' value='".$social[data][0][_c][link][$_GET['edit']][_v]."' type='text'></td>
    </tr>";
    echo"<tr>
    <td class='grid' colspan='2'><p><input name='saveedit' type='submit' class='save' value='   ' /></p></td>
    </tr>
    </table></form>
    </div>
    </div> ";
    //END EDIt HIDDEN CODE ///// 



?>
