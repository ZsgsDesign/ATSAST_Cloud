<?php
   echo "
   <table class='menu' width='1000'  align='center' cellpadding='0' cellspacing='0'>
    <tr>
      <td width='16'><img src='images/top_lef.gif' width='16' height='16'></td>
      <td colspan='10' height='16' background='images/top_mid.gif'></td>
      <td width='24'><img src='images/top_rig.gif' width='24' height='16'></td>
    </tr>

   <tr>
   <td width='16' background='images/cen_lef.gif'></td>
   <td class='menuimg'><a href='indexlog.php?page=home'><img src='img/home.png' /><br>首页</a></td>
  <td class='menuimg'><a href='indexlog.php?page=settings'><img src='img/settings.png' /><BR>基本设置</a></td>
  <td class='menuimg'><a href='indexlog.php?page=menu'><img src='img/menu.png' /><BR>菜单管理</a></td>
 <td class='menuimg'><a id='various3' href='./plupload/upload/menuimage.html'><img src='img/upload.png' /><BR>上传菜单图标</a></td>
 <td class='menuimg'><a id='various31' href='./plupload/upload/backimage.html'><img src='img/upload1.png' /><BR>上传背景</a></td>
 <td class='menuimg'><a id='various32' href='./plupload/upload/generic.html'><img src='img/upload.png' /><BR>上传通用图片</a></td>
  <td class='menuimg'><a href='indexlog.php?page=gallery'><img src='img/gallery.png' /><BR>视频/图片管理</a></td>
  <td class='menuimg'><a href='indexlog.php?page=social'><img src='img/social.png' /><BR>网站底部连接</a></td>
   <td class='menuimg'><a id='various31' href='../index.html' target='_blank'><img src='img/test.png' /><BR>查看前台</a></td>
";
  echo "<td class='menu' style='text-align: center;padding:4px;'><a href='logout.php' title='退出登录'><img src='img/Exit.png' /><BR>退出登录</a> </td>
<td width='24' background='images/cen_rig.gif'></td>

</tr>
<tr>
      <td width='16' height='16'><img src='images/bot_lef.gif' width='16' height='16'></td>
      <td colspan='10' height='16' background='images/bot_mid.gif'></td>
      <td width='24' height='16'><img src='images/bot_rig.gif' width='24' height='16'></td>
    </tr>

</table>

";
if(isset($_GET['action'])=='saved'){
  echo "<div class='comunication'>".$_GET['page']." saved succesfully!</div>";
  }
   //////////GESTIONE POPUP//////////////
            echo "<script language='JavaScript'>
            jQuery(document).ready(function() {
            $('a#menu').fancybox(
            {
            
            'autoDimensions'    : true,
            'overlayShow'    : false,
            'overflow'          :'auto'
            
            }
            );
            });</script>";
       
        /////////////////////FINE GESTIONE POPUP////////////////////
?>