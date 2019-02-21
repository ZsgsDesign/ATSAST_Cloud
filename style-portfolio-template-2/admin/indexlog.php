<?php
    ob_start();
    session_start(); /// initialize session 
    include("./inc/pw.php"); 
    check_logged();
    include("./inc/dirscan.php");
    include("./inc/array_xml.php");
    //html meta
    include("./inc/meta.php");
    include("./inc/jscript.php");
    


    echo "</head><body>";
    //insert menu
    include("./inc/mainmenu.php");
    //data

    $xml= xml2ary(file_get_contents('../data.xml'));
    // print_r($xml);

    //MENU EDIT
    if ($_GET['page']=='home'){

        include("./inc/home.php");  

    }


    ///MAIN SETTINGS
    if ($_GET['page']=='settings'){

        include("./inc/settings.php");  

    }
    else if(isset($_POST['settings'])){


            $xml[data][0][_c][footertext][0][_v]=$_POST['ftext'] ;
            $xml[data][0][_c][musicatstart][0][_v]=$_POST['music'] ;
            $xml[data][0][_c][socialnetwork][0][_v]=$_POST['social'] ;
            $xml[data][0][_c][blackorwhite][0][_v]=$_POST['color'] ;

            $last=ary2xml($xml);

            //write();
            $fh = fopen("../data.xml", "w");

            if($fh==false)
                die("unable to create file");

            fwrite($fh,$last);
        fclose($fh);
        header("Location:indexlog.php?page=settings&action=saved");
        ob_end_flush();


    }

    //MENU EDIT
    if ($_GET['page']=='menu'){

        include("./inc/menu.php");  

    }

    //GALLERY EDIT
    if ($_GET['page']=='gallery'){

        include("./inc/gallery.php");  

    }

    if ($_GET['page']=='category'){

        include("./inc/category.php");  

    }

    if ($_GET['page']=='social'){

        include("./inc/social.php");  

    }


    //footer
    echo"";
    include("./inc/footer.php");
    
    echo"</body></html>"
?>