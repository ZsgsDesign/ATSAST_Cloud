<?php

    $menudata=$xml[data][0][_c][item];


    //prendo gli esempi
    $sample= xml2ary(file_get_contents('./inc/sample.xml'));
    $home_n=$sample[data][0][_c][item][0];
    $text_n=$sample[data][0][_c][item][1];
    $gallery_n=$sample[data][0][_c][item][2];
    $news_n=$sample[data][0][_c][item][3];
    $contacts_n=$sample[data][0][_c][item][4];
    $link_n=$sample[data][0][_c][item][5];



    //print_r($news_n);
    // print_r($menusize);
    
    

    /////////MOVE MENU
    if (isset($_POST['UP'])){

        $selected= $_POST['itemnumber'];
        $temp = $menudata[$selected-1];
        $menudata[$selected-1] = $menudata[$selected];
        $menudata[$selected] = $temp;
        $xml[data][0][_c][item]=$menudata; 
        $last= ary2xml($xml);




        //write();
        $fh = fopen("../data.xml", "w");

        if($fh==false)
            die("unable to create file");

        fwrite($fh, $last);
        fclose($fh);
        //echo ("edit effettuata<a href='list.php'> torna all'elenco</a>");
        //header("Location:indexlog.php?page=menu");
        lto("indexlog.php?page=menu");
        ob_end_flush();

    }
    
    
    
    else if (isset($_POST['DOWN'])){

            $selected= $_POST['itemnumber'];
            $temp = $menudata[$selected+1];
            $menudata[$selected+1] = $menudata[$selected];
            $menudata[$selected] = $temp;
            $xml[data][0][_c][item]=$menudata; 
            $last= ary2xml($xml);




            //write();
            $fh = fopen("../data.xml", "w");

            if($fh==false)
                die("unable to create file");

            fwrite($fh, $last);
        fclose($fh);
        //echo ("edit effettuata<a href='list.php'> torna all'elenco</a>");
        //header("Location:indexlog.php?page=menu");
        lto("indexlog.php?page=menu");
        ob_end_flush();

    }
    ///////////////////// END MOVE MENU/////////
 //////////////DELETE///////////////////
    else if(isset($_GET['delete'])){
            $selected=$_GET['delete'];
            unset($menudata[$selected]);
            $xml[data][0][_c][item]=$menudata; 
                


            $last=ary2xml($xml);

            //write();
            $fh = fopen("../data.xml", "w");

            if($fh==false)
                die("unable to create file");

            fwrite($fh,$last);
            fclose($fh);
            header('Location:indexlog.php?page=menu&action=saved');
            ob_end_flush();




        }


    ///////////////////// SAVE HOME ////////////
    else if (isset($_POST['savehome'])){
            $selected= $_POST['itemnumber'];
            $menudata[$selected][_c][testo][0][_v] =$_POST['name']; 
            $menudata[$selected][_c][img][0][_v] =$_POST['image']; 
            $xml[data][0][_c][item]=$menudata; 
            $last= ary2xml($xml);
            //write();
            $fh = fopen("../data.xml", "w");

            if($fh==false)
                die("unable to create file");

            fwrite($fh, $last);
        fclose($fh);
        header("Location:indexlog.php?page=menu&action=saved");
        ob_end_flush();

    }
    //////////////////////END SAVE HOME////////////////
   

    ///////////////////// SAVE TEXT ////////////
    else if (isset($_POST['savetext'])){
            $selected= $_POST['itemnumber'];
            $newtext = str_replace("../", "", $_POST['text']);
            $menudata[$selected][_c][testo][0][_v] =$_POST['name']; 
            $menudata[$selected][_c][simpletext][0][_v] =$newtext; 
            $menudata[$selected][_c][img][0][_v] =$_POST['image']; 
            $menudata[$selected][_c][background][0][_v] =$_POST['bkg']; 
            $xml[data][0][_c][item]=$menudata; 
            $last= ary2xml($xml);
            //write();
            $fh = fopen("../data.xml", "w");

            if($fh==false)
                die("unable to create file");

            fwrite($fh, $last);
        fclose($fh);
        //echo ("edit effettuata<a href='list.php'> torna all'elenco</a>");
        header("Location:indexlog.php?page=menu&action=saved");
        ob_end_flush();

    }
    //////////////////////END SAVE TEXT////////////////
     ///////////////////// SAVE NEW TEXT ////////////
    else if (isset($_POST['addtext'])){

            $tot= count($menudata);
            $text_n[_c][testo][0][_v] =$_POST['name']; 
            $text_n[_c][img][0][_v] =$_POST['image']; 
            $text_n[_c][simpletext][0][_v] =$_POST['text']; 
            $text_n[_c][background][0][_v] =$_POST['bkg']; 
            $menudata[$tot]=$text_n;
            $xml[data][0][_c][item]=$menudata; 
            $last= ary2xml($xml);
            //write();
            $fh = fopen("../data.xml", "w");

            if($fh==false)
                die("unable to create file");

            fwrite($fh, $last);
        fclose($fh);
        header("Location:indexlog.php?page=menu&action=saved");
        ob_end_flush();

    }
    //////////////////////END SAVE NEW TEXT////////////////
 ///////////////////// SAVE NEWS ADDED ////////////
    else if (isset($_POST['addnews'])){

            $tot= count($menudata);
            $news_n[_c][testo][0][_v] =$_POST['name']; 
            $news_n[_c][img][0][_v] =$_POST['image']; 
            $news_n[_c][background][0][_v] =$_POST['bkg']; 
            $menudata[$tot]=$news_n;
            $xml[data][0][_c][item]=$menudata; 
            $last= ary2xml($xml);
            //write();
            $fh = fopen("../data.xml", "w");

            if($fh==false)
                die("unable to create file");

            fwrite($fh, $last);
        fclose($fh);
        header("Location:indexlog.php?page=menu&action=saved");
        ob_end_flush();

    }
    //////////////////////END SAVE NEWS ADDED////////////////
    
    
    
    
    ///////////////////// SAVE CONTACTS ////////////
    else if (isset($_POST['savecontacts'])){
            $selected= $_POST['itemnumber'];

            $menudata[$selected][_c][testo][0][_v] =$_POST['name']; 
            $menudata[$selected][_c][img][0][_v] =$_POST['image']; 
            $menudata[$selected][_c][background][0][_v] =$_POST['bkg']; 
            $menudata[$selected][_c][f_name][0][_v] =$_POST['f_name']; 
            $menudata[$selected][_c][f_company][0][_v] =$_POST['f_company']; 
            $menudata[$selected][_c][f_mail][0][_v] =$_POST['f_mail']; 
            $menudata[$selected][_c][f_phone][0][_v] =$_POST['f_phone']; 
            $menudata[$selected][_c][f_message][0][_v] =$_POST['f_message']; 
            $menudata[$selected][_c][f_submit][0][_v] =$_POST['f_submit']; 
            $menudata[$selected][_c][address][0][_v] =$_POST['text']; 


            $xml[data][0][_c][item]=$menudata; 
            $last= ary2xml($xml);
            //write();
            $fh = fopen("../data.xml", "w");

            if($fh==false)
                die("unable to create file");

            fwrite($fh, $last);
        fclose($fh);
        //echo ("edit effettuata<a href='list.php'> torna all'elenco</a>");
        header("Location:indexlog.php?page=menu&action=saved");
        ob_end_flush();

    }
    //////////////////////END SAVE CONTACTS////////////////
    ///////////////////// SAVE CONTACTS ADDED ////////////
    else if (isset($_POST['addcontacts'])){

            $tot= count($menudata);
            $contacts_n[_c][testo][0][_v] =$_POST['name']; 
            $contacts_n[_c][img][0][_v] =$_POST['image']; 
            $contacts_n[_c][background][0][_v] =$_POST['bkg']; 
            $menudata[$tot]=$contacts_n;
            $xml[data][0][_c][item]=$menudata; 
            $last= ary2xml($xml);
            //write();
            $fh = fopen("../data.xml", "w");

            if($fh==false)
                die("unable to create file");

            fwrite($fh, $last);
        fclose($fh);
        header("Location:indexlog.php?page=menu&action=saved");
        ob_end_flush();

    }
    //////////////////////END SAVE CONTACTS ADDED////////////////

    ///////////////////// SAVE LINK ////////////
    else if (isset($_POST['savelink'])){
            $selected= $_POST['itemnumber'];
            $menudata[$selected][_c][testo][0][_v] =$_POST['name']; 
            $menudata[$selected][_c][img][0][_v] =$_POST['image']; 
            $menudata[$selected][_c][targetlink][0][_v] =$_POST['link']; 
            $xml[data][0][_c][item]=$menudata; 
            $last= ary2xml($xml);
            //write();
            $fh = fopen("../data.xml", "w");

            if($fh==false)
                die("unable to create file");

            fwrite($fh, $last);
        fclose($fh);
        header("Location:indexlog.php?page=menu&action=saved");
        ob_end_flush();

    }
    //////////////////////END SAVE LINK////////////////
     ///////////////////// SAVE LINK ////////////
    else if (isset($_POST['addlink'])){
            $tot= count($menudata);
            $link_n[_c][testo][0][_v] =$_POST['name']; 
            $link_n[_c][img][0][_v] =$_POST['image']; 
            $link_n[_c][targetlink][0][_v] =$_POST['link']; 
            $menudata[$tot]=$link_n;
            $xml[data][0][_c][item]=$menudata; 
            $last= ary2xml($xml);
            //write();
            $fh = fopen("../data.xml", "w");

            if($fh==false)
                die("unable to create file");

            fwrite($fh, $last);
        fclose($fh);
        header("Location:indexlog.php?page=menu&action=saved");
        ob_end_flush();

    }
    //////////////////////END SAVE LINK////////////////

 ///////////////////// SAVE gallery ////////////
    else if (isset($_POST['savegallery'])){
            $selected= $_POST['itemnumber'];
            $menudata[$selected][_c][testo][0][_v] =$_POST['name']; 
            $menudata[$selected][_c][xmlLocation][0][_v] =$_POST['galleryname']; 
            $menudata[$selected][_c][img][0][_v] =$_POST['image']; 
            $menudata[$selected][_c][background][0][_v] =$_POST['bkg']; 
            $menudata[$selected][_c][password][0][_v]=$_POST['pass'];
            $xml[data][0][_c][item]=$menudata; 
            $last= ary2xml($xml);
            //write();
            $fh = fopen("../data.xml", "w");

            if($fh==false)
                die("unable to create file");

            fwrite($fh, $last);
        fclose($fh);
        //echo ("edit effettuata<a href='list.php'> torna all'elenco</a>");
        header("Location:indexlog.php?page=menu&action=saved");
        ob_end_flush();

    }
    //////////////////////END SAVE gallery////////////////
    
     ///////////////////// ADD gallery ////////////
    else if (isset($_POST['addgallery'])){
            
            $tot= count($menudata);
            $gallery_n[_c][testo][0][_v] =$_POST['name']; 
            $gallery_n[_c][img][0][_v] =$_POST['image']; 
            $gallery_n[_c][xmlLocation][0][_v]=$_POST['galleryname'];
            $gallery_n[_c][password][0][_v]=$_POST['pass'];
            $menudata[$tot]=$gallery_n;
            $xml[data][0][_c][item]=$menudata; 
            $last= ary2xml($xml);
            //write();
            $fh = fopen("../data.xml", "w");

            if($fh==false)
                die("unable to create file");

            fwrite($fh, $last);
        fclose($fh);
        //echo ("edit effettuata<a href='list.php'> torna all'elenco</a>");
        header("Location:indexlog.php?page=menu&action=saved");
        ob_end_flush();

    }
    //////////////////////END ADD gallery////////////////





    else{

        $menusize=count($menudata); 
        $filemenu=scan_directory_recursively('../m_images');
        $filebkg=scan_directory_recursively('../backgrounds');

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

        echo"<table class='menu' width='100%' border='0' cellpadding='0'  align='center'>";
        echo"<tr>";
        echo "<td class='mfirst' colspan='6'></td>";
        echo"</tr>";
        echo"<tr><form action='indexlog.php?page=menu' method='get'>";
        echo "<td class='mfirst' colspan='6'><p >添加新菜单项: ";
        echo"<select name='type' >";
        echo "<option value='empty'>选择菜单项类型:</option>";
        echo "<option value='text'>文本</option>";
        echo "<option value='news'>新闻/文章</option>";
        echo "<option value='gallery'>图片/视频</option>";
        echo "<option value='contacts'>留言联系</option>";
        echo "<option value='link'>网址链接</option>";
        echo "</select>";
        echo "<input name='add' type='submit' class='btn' value='添加菜单' /><input name='page' type='hidden' value='menu' /></form></p></td>";
        echo"</tr>";
        echo"<tr>";
        echo "<td class='mfirst' colspan='6'></td>";
        echo"</tr>";
        echo "<tr>";
        echo "<td class='grid'><p>图标</p></td>";
        echo "<td class='grid'><p>名称</p></td>";
        echo "<td class='grid'><p>排序</p></td>";
        echo "<td class='grid'><p>类型</p></td>";
        echo "<td class='grid'><p>背景</p></td>";
        echo "<td class='grid'><p>操作</p></td>";

        echo "</tr>";

        for  ($i=0;$i<$menusize;$i++){
            echo "<tr>";

            echo "<td class='grid'><p><a id='example1' href='../".$menudata[$i][_c][img][0][_v]."'><img class='border' src='../".$menudata[$i][_c][img][0][_v]."' height='75' width='75' /></a></p></td>";
            echo "<td class='grid'>".$menudata[$i][_c][testo][0][_v]."</td>";
            if($i>0){
                if($i<$menusize-1 &&  $i >1){
                    echo "<td class='grid'><form action='indexlog.php?page=menu' method='post'> <p><input name='UP' type='submit' class='up' value='   ' /> <input name='DOWN' type='submit' class='down' value='   ' /></p><input name='itemnumber' type='hidden' value='".$i."' /></form></td>";     
                }else if ($i==1){
                        echo "<td class='grid'><form action='indexlog.php?page=menu' method='post'> <p><input name='DOWN' type='submit' class='down' value='   ' /></p><input name='itemnumber' type='hidden' value='".$i."' /></form></td>";     
                    }else if ($i==$menusize-1){
                            echo "<td class='grid'><form action='indexlog.php?page=menu' method='post'><p><input name='UP' type='submit' class='up' value='   ' /> </p><input name='itemnumber' type='hidden' value='".$i."' /></form></td>";  
                        }

            }else{
                echo "<td class='grid'>&nbsp;</td>";  
            }

            echo "<td class='grid'><p>".$menudata[$i][_c][driver][0][_v]."</p></td>";
            if($menudata[$i][_c][background][0][_v]){
                echo "<td class='grid'><p><a id='example1' href='../".$menudata[$i][_c][background][0][_v]."'><img class='border' src='../".$menudata[$i][_c][background][0][_v]."' height='100'/></a></p></td>" ;
            }   else {
                echo "<td class='grid'>&nbsp;</td>";
            }
            //echo "<td class='grid'><p><a id='various1' href='edit=menu#inline1' title='Lorem ipsum dolor sit amet'>EDIT</a> <a>DELETE</a></p></td>";
            if($i>0){
                echo "<td class='grid'><p><a href='indexlog.php?page=menu&edit=".$i."' title='Edit'><img src='img/document-edit.png' alt='Edit' /></a><a href='javascript:void(0)' onClick=\"submitFormDelMenu('".$i."','menu','delete');\" ><img src='img/dialog-close-2.png' alt='Delete' /></a> </p></td>";
            }else{
                echo "<td class='grid'><p><a href='indexlog.php?page=menu&edit=".$i."' title='Edit'><img src='img/document-edit.png'  /></a></p></td>";
            }


            echo "</tr>";
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



       







        //////////GESTIONE POPUP//////////////
        if(isset($_GET['edit'])){
            $type=$menudata[$_GET['edit']][_c][driver][0][_v];
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
                       echo" 'href':'#galleryh' ,";
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
        //////////GESTIONE POPUP ADD//////////////
        if(isset($_GET['add'])){
            $type=$_GET['type'];
            echo "<script language='JavaScript'>
            jQuery(document).ready(function() {
            $.fancybox(
            {";
            if($type=="text"){

                    echo" 'href':'#textadd' ,";

                }else if ($type=="gallery"){
                        echo" 'href':'#galleryadd' ,";
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
        /////////////////////FINE GESTIONE POPUP ADD////////////////////
    }

    //HOME HIDDEN CODE /////    
    echo"<div style='display: none;'>
    <div id='homeh'><form action='indexlog.php?page=menu' method='post'>
    <table class='menu' width='100%' border='0' cellpadding='2'>
    <tr>
    <td class='grid' colspan='2'><h4>编辑 ".$type." 菜单项<input name='savehome' type='submit' class='save' value='   ' /></h4></td>

    </tr>";

    $correct=$_GET['edit'];
    echo "<tr>"; 
    echo  "<td class='grid'><p>图标</p></td><td class='grid'><select name='image' >";
    echo "<option value='".$menudata[$correct][_c][img][0][_v]."'>".$menudata[$correct][_c][img][0][_v]."</option>";
    for ($j=0;$j<sizeof($filemenu);$j++){

        if ($filemenu[$j]['kind']=="file"){
            echo "<option value='".touftcode($filemenu[$j]['path'])."'>".touftcode($filemenu[$j]['path'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr>";
    echo"<tr>
    <td class='grid'><p>菜单项名称:</p></td>
    <td class='grid'><input name='name' value='".$menudata[$correct][_c][testo][0][_v]."' type='text'></td>
    </tr>
    <tr>
    <td class='grid' colspan='2'><p><input name='savehome' type='submit' class='save' value='   ' /><input name='itemnumber' type='hidden' value='".$correct."' /></p></td>
    </tr>
    </table></form>
    </div>
    </div> ";
    //END HOME HIDDEN CODE /////   

    //TEXT HIDDEN CODE /////    
    echo"<div style='display: none;'>
    <div id='texth'><form action='indexlog.php?page=menu' method='post'>
    <table class='menu' width='100%' border='0' cellpadding='2'>
    <tr>
    <td class='grid' colspan='2'><h4>编辑 ".$type." 菜单项<input name='savetext' type='submit' class='save' value='   ' /></h4></td></tr>";
    $correct=$_GET['edit'];
    $newtext = str_replace("src=\"", "src=\"../", $menudata[$_GET['edit']][_c][simpletext][0][_v]);
    echo "<tr>"; 
    echo  "<td class='grid'><p>图标</p></td><td class='grid'><select name='image' >";
    echo "<option value='".$menudata[$correct][_c][img][0][_v]."'>".$menudata[$correct][_c][img][0][_v]."</option>";
    for ($j=0;$j<sizeof($filemenu);$j++){

        if ($filemenu[$j]['kind']=="file"){
            echo "<option value='".touftcode($filemenu[$j]['path'])."'>".touftcode($filemenu[$j]['path'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr>";
    echo "<tr>"; 
    echo  "<td class='grid'><p>背景</p></td><td class='grid'><select name='bkg' >";
    echo "<option value='".$menudata[$correct][_c][background][0][_v]."'>".$menudata[$correct][_c][background][0][_v]."</option>";
    for ($e=0;$e<sizeof($filebkg);$e++){

        if ($filebkg[$e]['kind']=="file"){
            echo "<option value='".touftcode($filebkg[$e]['path'])."'>".touftcode($filebkg[$e]['path'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr>";
    echo"

    <tr>
    <td class='grid'><p>菜单项名称:</p></td>
    <td class='grid'><input name='name' value='".$menudata[$_GET['edit']][_c][testo][0][_v]."' type='text'></td>
    </tr>
    <tr>
    <td class='grid'><p>Text:</p></td>
    <td class='grid'> <textarea name='text' cols='120' rows='20' class='tinymce'>".$newtext."</textarea></td>
    </tr>

    <tr>
    <td class='grid' colspan='2'><p><input name='savetext' type='submit' class='save' value='   ' /><input name='itemnumber' type='hidden' value='".$_GET['edit']."' /></p></td>
    </tr>
    </table></form>
    </div>
    </div> ";
    //END TEXT HIDDEN CODE /////   

    //NEWS HIDDEN CODE /////    
    echo"<div style='display: none;'>
    <div id='newsh'>
    <table class='menu' width='100%' border='0' cellpadding='2'>
    <tr>
    <td class='grid'><h4>编辑 ".$type." 菜单项</h4></td><form action='addnews.php' method='post'><td class='grid'><input name='addnews' type='submit' class='btn' value='新增内容' /><input name='itemnumber' type='hidden' value='".$_GET['edit']."' /></td></form>

    </tr>";
    $correct=$_GET['edit'];
    echo "<form action='addnews.php' method='post'><input name='itemnumber' type='hidden' value='".$_GET['edit']."' /><tr>"; 
    echo  "<td class='grid'><p>图标<br><input name='saveimage' type='submit' class='save' value='   ' /></p></td><td class='grid'><select name='image' >";
    echo "<option value='".$menudata[$correct][_c][img][0][_v]."'>".$menudata[$correct][_c][img][0][_v]."</option>";
    for ($j=0;$j<sizeof($filemenu);$j++){

        if ($filemenu[$j]['kind']=="file"){
            echo "<option value='".touftcode($filemenu[$j]['path'])."'>".touftcode($filemenu[$j]['path'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr></form>";
    echo "<form action='addnews.php' method='post'><input name='itemnumber' type='hidden' value='".$_GET['edit']."' /><tr>"; 
    echo  "<td class='grid'><p>背景<br><input name='savebkg' type='submit' class='save' value='   ' /></p></td><td class='grid'><select name='bkg' >";
    echo "<option value='".$menudata[$correct][_c][background][0][_v]."'>".$menudata[$correct][_c][background][0][_v]."</option>";
    for ($e=0;$e<sizeof($filebkg);$e++){

        if ($filebkg[$e]['kind']=="file"){
            echo "<option value='".touftcode($filebkg[$e]['path'])."'>".touftcode($filebkg[$e]['path'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr></form>";
    echo" <tr>
    <td class='grid'> <form action='addnews.php' method='post'><input name='itemnumber' type='hidden' value='".$_GET['edit']."' /><p>菜单项名称:<br><input name='savename' type='submit' class='save' value='   ' /></p></td>
    <td class='grid'><input name='name' value='".$menudata[$_GET['edit']][_c][testo][0][_v]."' type='text'></td></form>
    </tr>";

    if (count($menudata[$_GET['edit']][_c][news])>1){
        
    
    for($a=0;$a<count($menudata[$_GET['edit']][_c][news]);$a++){
        $newtext = str_replace("src=\"", "src=\"../", $menudata[$_GET['edit']][_c][news][$a][_v]);
        echo "<tr>";
        if($a>0 && $a < (count($menudata[$_GET['edit']][_c][news])-1)){
            echo " <form action='addnews.php' method='post'><td class='grid'><p>内容 ".($a+1)."<br><input name='savenews' type='submit' class='save' value='   ' />
            <input name='deletenews' type='submit' class='delete' value='   ' /><br><input name='UP' type='submit' class='up' value='   ' /><input name='down' type='submit' class='down' value='   ' /></p></td><input name='itemnumber' type='hidden' value='".$_GET['edit']."' /><input name='newsnumber' type='hidden' value='".$a."' />";
        }else if($a==0){
                echo " <form action='addnews.php' method='post'><td class='grid'><p>内容 ".($a+1)."<br><input name='savenews' type='submit' class='save' value='   ' /><input name='deletenews' type='submit' class='delete' value='   ' /><br><input name='down' type='submit' class='down' value='   ' /></p></td><input name='itemnumber' type='hidden' value='".$_GET['edit']."' /><input name='newsnumber' type='hidden' value='".$a."' />";   
            }else if ($a>=count($menudata[$_GET['edit']][_c][news])-1){
                    echo " <form action='addnews.php' method='post'><td class='grid'><p>内容 ".($a+1)."<br><input name='savenews' type='submit' class='save' value='   ' /><input name='deletenews' type='submit' class='delete' value='   ' /><br><input name='UP' type='submit' class='up' value='   ' /></p></td><input name='itemnumber' type='hidden' value='".$_GET['edit']."' /><input name='newsnumber' type='hidden' value='".$a."' />";

                }
                echo" <td class='grid'> <textarea name='text' cols='90' rows='20' class='tinymce'>".$newtext."</textarea></td></form>
        </tr>";
    }
    }else{
        $newtextt = str_replace("src=\"", "src=\"../", $menudata[$_GET['edit']][_c][news][0][_v]);
         echo " <form action='addnews.php' method='post'><td class='grid'><p>内容 ".($a+1)."<br><input name='savenews' type='submit' class='save' value='   ' /></p></td><input name='itemnumber' type='hidden' value='".$_GET['edit']."' /><input name='newsnumber' type='hidden' value='news".$a."' />";   
          echo" <td class='grid'> <textarea name='text' cols='90' rows='20' class='tinymce'>".$newtextt."</textarea></td></form></tr>";
        
    }

    echo"
    </table>
    </div>
    </div> ";
    //END NEWS HIDDEN CODE /////   

    //CONTACTS HIDDEN CODE /////    
    echo"<div style='display: none;'>
    <div id='contactsh'><form action='indexlog.php?page=menu' method='post'>
    <table class='menu' width='100%' border='0' cellpadding='2'>
    <tr>
    <td class='grid' colspan='2'><h4>编辑 ".$type." 菜单项<input name='savecontacts' type='submit' class='save' value='   ' /></h4></td></tr>";
    echo "<tr>"; 
    echo  "<td class='grid'><p>图标</p></td><td class='grid'><select name='image' >";
    echo "<option value='".$menudata[$correct][_c][img][0][_v]."'>".$menudata[$correct][_c][img][0][_v]."</option>";
    for ($j=0;$j<sizeof($filemenu);$j++){

        if ($filemenu[$j]['kind']=="file"){
            echo "<option value='".touftcode($filemenu[$j]['path'])."'>".touftcode($filemenu[$j]['path'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr>";
    echo "<tr>"; 
    echo  "<td class='grid'><p>背景</p></td><td class='grid'><select name='bkg' >";
    echo "<option value='".$menudata[$correct][_c][background][0][_v]."'>".$menudata[$correct][_c][background][0][_v]."</option>";
    for ($e=0;$e<sizeof($filebkg);$e++){

        if ($filebkg[$e]['kind']=="file"){
            echo "<option value='".touftcode($filebkg[$e]['path'])."'>".touftcode($filebkg[$e]['path'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr>";
    echo"
    <tr>
    <td class='grid'><p>菜单项名称:</p></td>
    <td class='grid'><input name='name' value='".$menudata[$_GET['edit']][_c][testo][0][_v]."' type='text' size='100'></td>
    </tr>
    <tr>
    <td class='grid'><p>名称项</p></td>
    <td class='grid'><input name='f_name' value='".$menudata[$_GET['edit']][_c][f_name][0][_v]."' type='text' size='100'></td>
    </tr>
    <tr>
    <td class='grid'><p>企业项:</p></td>
    <td class='grid'><input name='f_company' value='".$menudata[$_GET['edit']][_c][f_company][0][_v]."' type='text' size='100'></td>
    </tr>
    <tr>
    <td class='grid'><p>邮件项:</p></td>
    <td class='grid'><input name='f_mail' value='".$menudata[$_GET['edit']][_c][f_mail][0][_v]."' type='text' size='100'></td>
    </tr>
    <tr>
    <td class='grid'><p>电话项:</p></td>
    <td class='grid'><input name='f_phone' value='".$menudata[$_GET['edit']][_c][f_phone][0][_v]."' type='text' size='100'></td>
    </tr>
    <tr>
    <td class='grid'><p>信息项:</p></td>
    <td class='grid'><input name='f_message' value='".$menudata[$_GET['edit']][_c][f_message][0][_v]."' type='text' size='100'></td>
    </tr>
    <tr>
    <td class='grid'><p>提交按钮:</p></td>
    <td class='grid'><input name='f_submit' value='".$menudata[$_GET['edit']][_c][f_submit][0][_v]."' type='text' size='100'></td>
    </tr>
    <tr>
    <td class='grid'><p>地址:</p></td>
    <td class='grid'> <textarea name='text' cols='120' rows='5' >".$menudata[$_GET['edit']][_c][address][0][_v]."</textarea></td>
    </tr>

    <tr>
    <td class='grid' colspan='2'><p><input name='savecontacts' type='submit' class='save' value='   ' /><input name='itemnumber' type='hidden' value='".$_GET['edit']."' /></p></td>
    </tr>
    </table></form>
    </div>
    </div> ";
    //END CONTACTS HIDDEN CODE ///// 

    //LINK HIDDEN CODE /////    
    echo"<div style='display: none;'>
    <div id='linkh'><form action='indexlog.php?page=menu' method='post'>
    <table class='menu' width='100%' border='0' cellpadding='2'>
    <tr>
    <td class='grid' colspan='2'><h4>编辑 ".$type." 菜单项<input name='savelink' type='submit' class='save' value='   ' /></h4></td>

    </tr>";

    $correct=$_GET['edit'];
    echo "<tr>"; 
    echo  "<td class='grid'><p>图标</p></td><td class='grid'><select name='image' >";
    echo "<option value='".$menudata[$correct][_c][img][0][_v]."'>".$menudata[$correct][_c][img][0][_v]."</option>";
    for ($j=0;$j<sizeof($filemenu);$j++){

        if ($filemenu[$j]['kind']=="file"){
            echo "<option value='".touftcode($filemenu[$j]['path'])."'>".touftcode($filemenu[$j]['path'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr>";
    echo"<tr>
    <td class='grid'><p>菜单项名称:</p></td>
    <td class='grid'><input name='name' value='".$menudata[$correct][_c][testo][0][_v]."' type='text'></td>
    </tr>
    <tr>
    <td class='grid'><p>菜单项链接:</p></td>
    <td class='grid'><input name='link' value='".$menudata[$correct][_c][targetlink][0][_v]."' type='text' size='50'></td>
    </tr>
    <tr>
    <td class='grid' colspan='2'><p><input name='savelink' type='submit' class='save' value='   ' /><input name='itemnumber' type='hidden' value='".$correct."' /></p></td>
    </tr>
    </table></form>
    </div>
    </div> ";
    //END LINK HIDDEN CODE /////     
    
     //PORTFOLIO HIDDEN CODE /////    
    echo"<div style='display: none;'>";
    $filegallery=scan_directory_recursively('../gallery_xml');
    echo"<div id='galleryh'><form action='indexlog.php?page=menu' method='post'>
    <table class='menu' width='100%' border='0' cellpadding='2'>
    <tr>
    <td class='grid' colspan='2'><h4>编辑 ".$type." 菜单项<input name='savegallery' type='submit' class='save' value='   ' /></h4></td>

    </tr>";

    $correct=$_GET['edit'];
    echo "<tr>"; 
    echo  "<td class='grid'><p>图标</p></td><td class='grid'><select name='image' >";
    echo "<option value='".$menudata[$correct][_c][img][0][_v]."'>".$menudata[$correct][_c][img][0][_v]."</option>";
    for ($j=0;$j<sizeof($filemenu);$j++){

        if ($filemenu[$j]['kind']=="file"){
            echo "<option value='".touftcode($filemenu[$j]['path'])."'>".touftcode($filemenu[$j]['path'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr>";
    echo"<tr>
    <td class='grid'><p>菜单项名称:</p></td>
    <td class='grid'><input name='name' value='".$menudata[$correct][_c][testo][0][_v]."' type='text'></td>
    </tr>";
     echo"<tr>
    <td class='grid'><p>密码 (如不需要密码设置为:nopass):</p></td>
    <td class='grid'><input name='pass' value='".$menudata[$correct][_c][password][0][_v]."' type='text'></td>
    </tr>";
     echo "<tr>"; 
    echo  "<td class='grid'><p>背景</p></td><td class='grid'><select name='bkg' >";
    echo "<option value='".$menudata[$correct][_c][background][0][_v]."'>".$menudata[$correct][_c][background][0][_v]."</option>";
    for ($e=0;$e<sizeof($filebkg);$e++){

        if ($filebkg[$e]['kind']=="file"){
            echo "<option value='".touftcode($filebkg[$e]['path'])."'>".touftcode($filebkg[$e]['path'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr>";
     echo "<tr>"; 
    echo  "<td class='grid'><p>调用XML数据:</p></td><td class='grid'><select name='galleryname' >";
    echo "<option value='".$menudata[$correct][_c][xmlLocation][0][_v]."'>".$menudata[$correct][_c][xmlLocation][0][_v]."</option>";
    for ($u=0;$u<sizeof($filegallery);$u++){

        if ($filegallery[$u]['kind']=="file"){
            echo "<option value='".touftcode($filegallery[$u]['path'])."'>".touftcode($filegallery[$u]['path'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr>";
    echo"<tr>
    <td class='grid' colspan='2'><p><input name='savegallery' type='submit' class='save' value='   ' /><input name='itemnumber' type='hidden' value='".$correct."' /></p></td>
    </tr>
    </table></form>
    </div>
    </div> ";
    //END PORTFOLIO HIDDEN CODE /////   

    /////////////////NEW MENU ITEMS ///////////////////////
    ///////////////////////////////////////////////////////
 echo"<div style='display: none;'>
    <div id='textadd'><form action='indexlog.php?page=menu' method='post'>
    <table class='menu' width='100%' border='0' cellpadding='2'>
    <tr>
    <td class='grid' colspan='2'><h4>添加 ".$type." 菜单项<input name='addtext' type='submit' class='save' value='   ' /></h4></td></tr>";
    $newtext = str_replace("src=\"", "src=\"../", $text_n[_c][simpletext][0][_v]);
    echo "<tr>"; 
    echo  "<td class='grid'><p>图标</p></td><td class='grid'><select name='image' >";
    for ($j=0;$j<sizeof($filemenu);$j++){

        if ($filemenu[$j]['kind']=="file"){
            echo "<option value='".touftcode($filemenu[$j]['path'])."'>".touftcode($filemenu[$j]['path'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr>";
    echo "<tr>"; 
    echo  "<td class='grid'><p>背景</p></td><td class='grid'><select name='bkg' >";
    for ($e=0;$e<sizeof($filebkg);$e++){

        if ($filebkg[$e]['kind']=="file"){
            echo "<option value='".touftcode($filebkg[$e]['path'])."'>".touftcode($filebkg[$e]['path'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr>";
    echo"

    <tr>
    <td class='grid'><p>菜单项名称:</p></td>
    <td class='grid'><input name='name' value='".$text_n[_c][testo][0][_v]."' type='text'></td>
    </tr>
    <tr>
    <td class='grid'><p>内容:</p></td>
    <td class='grid'> <textarea name='text' cols='120' rows='20' class='tinymce'>".$newtext."</textarea></td>
    </tr>

    <tr>
    <td class='grid' colspan='2'><p><input name='addtext' type='submit' class='save' value='   ' /></p></td>
    </tr>
    </table></form>
    </div>
    </div> ";
    //END TEXT HIDDEN CODE /////   
    //NEWS HIDDEN CODE /////    
    echo"<div style='display: none;'>
    <div id='newsadd'>
    <table class='menu' width='100%' border='0' cellpadding='2'>
    <tr>
    <td class='grid'><h4>添加 ".$type." 菜单项</h4></td><form action='indexlog.php?page=menu' method='post'><td class='grid'></td>

    </tr>";
    
    echo "<tr>"; 
    echo  "<td class='grid'><p>图标</p></td><td class='grid'><select name='image' >";
    for ($j=0;$j<sizeof($filemenu);$j++){

        if ($filemenu[$j]['kind']=="file"){
            echo "<option value='".touftcode($filemenu[$j]['path'])."'>".touftcode($filemenu[$j]['path'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr>";
    echo "<tr>"; 
    echo  "<td class='grid'><p>背景</p></td><td class='grid'><select name='bkg' >";
    for ($e=0;$e<sizeof($filebkg);$e++){

        if ($filebkg[$e]['kind']=="file"){
            echo "<option value='".touftcode($filebkg[$e]['path'])."'>".touftcode($filebkg[$e]['path'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr>";
    echo" <tr>
    <td class='grid'><p>菜单项名称: </p></td>
    <td class='grid'><input name='name' value='".$news_n[_c][testo][0][_v]."' type='text'></td>
    </tr><tr><td class='grid' colspan='2'><p><input name='addnews' type='submit' class='save' value='   ' /></p></td></tr></form>";


    echo"
    </table>
    </div>
    </div> ";
    //END NEWS HIDDEN CODE /////   

    //CONTACTS HIDDEN CODE /////    
    echo"<div style='display: none;'>
    <div id='contactsadd'><form action='indexlog.php?page=menu' method='post'>
    <table class='menu' width='100%' border='0' cellpadding='2'>
    <tr>
    <td class='grid' colspan='2'><h4>添加 ".$type." 菜单项<input name='addcontacts' type='submit' class='save' value='   ' /></h4></td></tr>";
    echo "<tr>"; 
    echo  "<td class='grid'><p>图标</p></td><td class='grid'><select name='image' >";
    for ($j=0;$j<sizeof($filemenu);$j++){

        if ($filemenu[$j]['kind']=="file"){
            echo "<option value='".touftcode($filemenu[$j]['path'])."'>".touftcode($filemenu[$j]['path'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr>";
    echo "<tr>"; 
    echo  "<td class='grid'><p>背景</p></td><td class='grid'><select name='bkg' >";
    echo "<option value='".$contacts_n[_c][background][0][_v]."'>".$contacts_n[_c][background][0][_v]."</option>";
    for ($e=0;$e<sizeof($filebkg);$e++){

        if ($filebkg[$e]['kind']=="file"){
            echo "<option value='".touftcode($filebkg[$e]['path'])."'>".touftcode($filebkg[$e]['path'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr><tr>
    <td class='grid'><p>菜单项名称:</p></td>
    <td class='grid'><input name='name' value='".$contacts_n[_c][testo][0][_v]."' type='text'></td>
    </tr>";
   echo"<tr>
    <td class='grid' colspan='2'><p><input name='addcontacts' type='submit' class='save' value='   ' /></p></td>
    </tr>
    </table></form>
    </div>
    </div> ";
    //END CONTACTS HIDDEN CODE ///// 
    //LINK HIDDEN CODE /////    
    echo"<div style='display: none;'>
    <div id='linkadd'><form action='indexlog.php?page=menu' method='post'>
    <table class='menu' width='100%' border='0' cellpadding='2'>
    <tr>
    <td class='grid' colspan='2'><h4>添加 ".$type." 菜单项<input name='addlink' type='submit' class='save' value='   ' /></h4></td>

    </tr>";

    
    echo "<tr>"; 
    echo  "<td class='grid'><p>图标</p></td><td class='grid'><select name='image' >";
    for ($j=0;$j<sizeof($filemenu);$j++){

        if ($filemenu[$j]['kind']=="file"){
            echo "<option value='".touftcode($filemenu[$j]['path'])."'>".touftcode($filemenu[$j]['path'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr>";
    echo"<tr>
    <td class='grid'><p>菜单项名称:</p></td>
    <td class='grid'><input name='name' value='".$link_n[_c][testo][0][_v]."' type='text'></td>
    </tr>
    <tr>
    <td class='grid'><p>菜单项链接:</p></td>
    <td class='grid'><input name='link' value='".$link_n[_c][targetlink][0][_v]."' type='text' size='50'></td>
    </tr>
    <tr>
    <td class='grid' colspan='2'><p><input name='addlink' type='submit' class='save' value='   ' /></p></td>
    </tr>
    </table></form>
    </div>
    </div> ";
    //END LINK HIDDEN CODE /////     
         //PORTFOLIO HIDDEN CODE /////    
    echo"<div style='display: none;'>";
    $filegallery=scan_directory_recursively('../gallery_xml');
    echo"<div id='galleryadd'><form action='indexlog.php?page=menu' method='post'>
    <table class='menu' width='100%' border='0' cellpadding='2'>
    <tr>
    <td class='grid' colspan='2'><h4>编辑 ".$type." 菜单项<input name='addgallery' type='submit' class='save' value='   ' /></h4></td>

    </tr>";

    $correct=$_GET['edit'];
    echo "<tr>"; 
    echo  "<td class='grid'><p>图标</p></td><td class='grid'><select name='image' >";
     for ($j=0;$j<sizeof($filemenu);$j++){

        if ($filemenu[$j]['kind']=="file"){
            echo "<option value='".touftcode($filemenu[$j]['path'])."'>".touftcode($filemenu[$j]['path'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr>";
    echo"<tr>
    <td class='grid'><p>菜单项名称:</p></td>
    <td class='grid'><input name='name' value='<h1>insert name</h1>' type='text'></td>
    </tr>";
        echo"<tr>
    <td class='grid'><p>密码 (如不需要密码设置为:nopass):</p></td>
    <td class='grid'><input name='pass' value='nopass' type='text'></td>
    </tr>";
     echo "<tr>"; 
    echo  "<td class='grid'><p>背景</p></td><td class='grid'><select name='bkg' >";
    for ($e=0;$e<sizeof($filebkg);$e++){

        if ($filebkg[$e]['kind']=="file"){
            echo "<option value='".touftcode($filebkg[$e]['path'])."'>".touftcode($filebkg[$e]['path'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr>";
     echo "<tr>"; 
    echo  "<td class='grid'><p>调用XML数据:</p></td><td class='grid'><select name='galleryname' >";
    for ($u=0;$u<sizeof($filegallery);$u++){

        if ($filegallery[$u]['kind']=="file"){
            echo "<option value='".touftcode($filegallery[$u]['path'])."'>".touftcode($filegallery[$u]['path'])."</option>";
        }
    }
    echo "</select></td>" ;
    echo "</tr>";
    echo"<tr>
    <td class='grid' colspan='2'><p><input name='addgallery' type='submit' class='save' value='   ' /></p></td>
    </tr>";
    echo"<tr>
    <td class='grid' colspan='2'><p><a href='indexlog.php?page=gallery' >点击这里新增 图片/视频 模块XML数据</a></p></td>
    </tr>";
   echo"</table></form>
    </div>
    </div> ";
    //END PORTFOLIO HIDDEN CODE /////   

?>