<?php
    echo "
    <table class='menu' width='700px' border='0' align='center' cellpadding='0' cellspacing='0'>
    <tr>
    <td width='16'><img src='images/top_lef.gif' width='16' height='16'></td>
    <td height='16' background='images/top_mid.gif'><img src='images/top_mid.gif' width='16' height='16'></td>

    <td width='24'><img src='images/top_rig.gif' width='24' height='16'></td>
    </tr>
    <tr>
    <td width='16' background='images/cen_lef.gif'><img src='images/cen_lef.gif' width='16' height='11'></td>
    <td align='center' valign='middle' bgcolor='#FFFFFF'>";


    echo "<form action='indexlog.php' method='post'><table class='menu'><tr>";
    echo "<td class='grid' width='200px'><p>页脚文本:</p><td class='grid'><textarea name='ftext' cols='100' rows='2' class='tinymce'>".$xml[data][0][_c][footertext][0][_v]."</textarea></tr>";

    echo"</tr>";

    echo"<tr><td class='grid' colspan='2'><p>LOGO 上传 (请上传 300px * 132px 的PNG格式图片)</p></td>";

    echo"</tr>";  
    echo"</td></tr>";

    echo"<tr><td class='grid' colspan='2'><p><a id='logo' href='./plupload/upload/logo.html'><img src='img/upload.png'/><br>上传 Logo</a></p></td>";

    echo"</tr>";  
    
     echo"<tr><td class='grid'><p>选择颜色风格</p></td><td class='grid'>";

    //MUSIC
    echo   "<label><p>
    <input name='color' type='radio' value='black'";
    if ($xml[data][0][_c][blackorwhite][0][_v]=='black'  ){

        echo"checked='checked' />";
    }else{
        echo" />";
    }echo"

    黑色 </p></label>
    <label><p>
    <input type='radio' name='color' value='white' ";
    if ($xml[data][0][_c][blackorwhite][0][_v]!='black'  ){

        echo"checked='checked' />";
    }else{
        echo" />";
    }
    echo"
    白色</p></label>" ;
    echo"</td></tr>";


    echo"<tr><td class='grid'><p>自动播放音乐</p></td><td class='grid'>";

    //MUSIC
    echo   "<label><p>
    <input name='music' type='radio' value='true'";
    if ($xml[data][0][_c][musicatstart][0][_v]=='true'  ){

        echo"checked='checked' />";
    }else{
        echo" />";
    }echo"

    开启 </p></label>
    <label><p>
    <input type='radio' name='music' value='false' ";
    if ($xml[data][0][_c][musicatstart][0][_v]=='false'  ){

        echo"checked='checked' />";
    }else{
        echo" />";
    }
    echo"
    关闭</p></label>" ;
    echo"</td></tr>";

    echo"<tr><td class='grid' colspan='2'><p><a id='music' href='./plupload/upload/music.html'><img src='img/upload.png'/><br>上传背景音乐</a></p></td>";

    echo"</tr>";  
    //SOCIAL NETWORKS
    echo"<tr><td class='grid'><p>启用网络分享</p></td><td class='grid'>";
    echo   "<label><p>
    <input name='social' type='radio' value='true'";
    if ($xml[data][0][_c][socialnetwork][0][_v]=='true'  ){

        echo"checked='checked' />";
    }else{
        echo" />";
    }echo"

    启用 </p></label>
    <label><p>
    <input type='radio' name='social' value='false' ";
    if ($xml[data][0][_c][socialnetwork][0][_v]=='false'  ){

        echo"checked='checked' />";
    }else{
        echo" />";
    }
    echo"
    关闭</p></label>" ; 
    echo"</td></tr>";  
    echo"<tr><td colspan='2' class='grid'>";
    echo "<p><input name='settings' type='submit' class='save' value='   ' /></p></form></td></tr></table>";
    echo"</td>
    <td width='24' background='images/cen_rig.gif'><img src='images/cen_rig.gif' width='24' height='11'></td>
    </tr>
    <tr>
    <td width='16' height='16'><img src='images/bot_lef.gif' width='16' height='16'></td>

    <td height='16' background='images/bot_mid.gif'><img src='images/bot_mid.gif' width='16' height='16'></td>
    <td width='24' height='16'><img src='images/bot_rig.gif' width='24' height='16'></td>
    </tr>
    </table>"; 
?>
