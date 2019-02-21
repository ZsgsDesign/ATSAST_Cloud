<?php 
    session_start(); 
    ob_start();
   include("./inc/pw.php");
   include("./inc/meta.php");
   include("./inc/jscript.php");
  
    

    
    
    if ($_POST["ac"]=="log") { /// do after login form is submitted  
        if ($USERS[$_POST["username"]]==$_POST["password"]) { /// check if submitted 
            //   username and password exist in $USERS array  
            $_SESSION["logged"]=$_POST["username"]; 
        } else { 
            echo'<table table class="menu" width="400" border="0" align="center" cellpadding="5" cellspacing="0">
               <tr>
        <td colspan="2" align="center" class="menu"><h1>用户名或密码错误!</h1></td>
        </tr>
         </table>';
        }; 
    }; 
    if (array_key_exists($_SESSION["logged"],$USERS)) { //// check if user is logged or not  
        echo "您已经成功登陆.";
        header("Location: indexlog.php?page=home"); //// if user is logged show a message  
    } else { //// if not logged show login form 
      echo"
             <table class='menu' width='500px' border='0' align='center' cellpadding='0' cellspacing='0'>
        <tr>
        <td width='16'><img src='images/top_lef.gif' width='16' height='16'></td>
        <td height='16' background='images/top_mid.gif'><img src='images/top_mid.gif' width='16' height='16'></td>

        <td width='24'><img src='images/top_rig.gif' width='24' height='16'></td>
        </tr>
        <tr>
        <td width='16' background='images/cen_lef.gif'><img src='images/cen_lef.gif' width='16' height='11'></td>
        <td align='center' valign='middle' bgcolor='#FFFFFF'>";
        
        echo'<table class="menu" width="400" border="0" align="center" cellpadding="5" cellspacing="0">
        <form action="index.php" method="post"><input type="hidden" name="ac" value="log">
        <tr>
        <td colspan="2" align="center"><a href="#" target="_blank"><img src="img/splash.jpg" alt="Template Administration"  border="0" longdesc="#" /></a></td>
        </tr>
        <tr>
        <td><span class="menu">用户名:</span></td>
        <td align="center"><input type="text" name="username" /></td>
        </tr>
        <tr>
        <td><span class="menu">密码:</span></td>
        <td align="center"><input type="password" name="password" /></td>
        </tr>
        <tr>
        <td colspan="2" align="center"><input type="submit" value="登陆" class="btn"/></td>
        </tr>
        </form>
        </table>';
        
        
    
          echo"</td>
        <td width='24' background='images/cen_rig.gif'><img src='images/cen_rig.gif' width='24' height='11'></td>
        </tr>
        <tr>
        <td width='16' height='16'><img src='images/bot_lef.gif' width='16' height='16'></td>

        <td height='16' background='images/bot_mid.gif'><img src='images/bot_mid.gif' width='16' height='16'></td>
        <td width='24' height='16'><img src='images/bot_rig.gif' width='24' height='16'></td>
        </tr>
        </table>"; 

    };

?>
