<?php
    ob_start();
    session_start(); /// initialize session 
    include("./inc/pw.php"); 
    check_logged();
    include("./inc/dirscan.php");
    include("./inc/array_xml.php");
    //html meta
    include("./inc/jscript.php");


    $xml= xml2ary(file_get_contents('../data.xml'));
    // print_r($xml);
    if(isset($_POST['addnews'])){
        $selected= $_POST['itemnumber'];
        $tot= count($xml[data][0][_c][item][$selected][_c][news]);

        $xml[data][0][_c][item][$selected][_c][news][$tot][_v]="<h5>06-18-2012</h5><p></p><h3>标题</h3><p><span class='newstext'>内容...</span></p>";

        $last=ary2xml($xml);

        //write();
        $fh = fopen("../data.xml", "w");

        if($fh==false)
            die("unable to create file");

        fwrite($fh,$last);
        fclose($fh);
        header('Location:indexlog.php?page=menu&edit='.$selected);
        ob_end_flush();


    }

    if(isset($_POST['deletenews'])){
        $selected= $_POST['itemnumber'];
        $newssel= $_POST['newsnumber'];
        if ($newssel==0){


            array_shift($xml[data][0][_c][item][$selected][_c][news]);


        }else{


            unset($xml[data][0][_c][item][$selected][_c][news][$newssel]);



        }
        $last=ary2xml($xml);

        //write();
        $fh = fopen("../data.xml", "w");

        if($fh==false)
            die("unable to create file");

        fwrite($fh,$last);
        fclose($fh);
        header('Location:indexlog.php?page=menu&edit='.$selected);
        ob_end_flush();


    }

    if(isset($_POST['savenews'])){
        $selected= $_POST['itemnumber'];
        $newssel= $_POST['newsnumber'];
        $newtext = str_replace("../", "", $_POST['text']);


        $xml[data][0][_c][item][$selected][_c][news][$newssel][_v]=$newtext;




        $last=ary2xml($xml);

        //write();
        $fh = fopen("../data.xml", "w");

        if($fh==false)
            die("unable to create file");

        fwrite($fh,$last);
        fclose($fh);
        header('Location:indexlog.php?page=menu&edit='.$selected.'&action=saved');
        ob_end_flush();


    }

    if(isset($_POST['savename'])){
        $selected= $_POST['itemnumber'];



        $xml[data][0][_c][item][$selected][_c][testo][0][_v]=$_POST['name'];




        $last=ary2xml($xml);

        //write();
        $fh = fopen("../data.xml", "w");

        if($fh==false)
            die("unable to create file");

        fwrite($fh,$last);
        fclose($fh);
        header('Location:indexlog.php?page=menu&edit='.$selected.'&action=saved');
        ob_end_flush();


    }
 if(isset($_POST['saveimage'])){
        $selected= $_POST['itemnumber'];



        $xml[data][0][_c][item][$selected][_c][img][0][_v]=$_POST['image'];




        $last=ary2xml($xml);

        //write();
        $fh = fopen("../data.xml", "w");

        if($fh==false)
            die("unable to create file");

        fwrite($fh,$last);
        fclose($fh);
        header('Location:indexlog.php?page=menu&edit='.$selected.'&action=saved');
        ob_end_flush();


    }
    if(isset($_POST['savebkg'])){
        $selected= $_POST['itemnumber'];



        $xml[data][0][_c][item][$selected][_c][background][0][_v]=$_POST['bkg'];




        $last=ary2xml($xml);

        //write();
        $fh = fopen("../data.xml", "w");

        if($fh==false)
            die("unable to create file");

        fwrite($fh,$last);
        fclose($fh);
        header('Location:indexlog.php?page=menu&edit='.$selected.'&action=saved');
        ob_end_flush();


    }

    if(isset($_POST['up'])){
        $selected= $_POST['itemnumber'];
        $newssel= $_POST['newsnumber'];


        
        $temp = $xml[data][0][_c][item][$selected][_c][news][$newssel-1];
        $xml[data][0][_c][item][$selected][_c][news][$newssel-1] = $xml[data][0][_c][item][$selected][_c][news][$newssel];
        $xml[data][0][_c][item][$selected][_c][news][$newssel] = $temp;




        $last=ary2xml($xml);

        //write();
        $fh = fopen("../data.xml", "w");

        if($fh==false)
            die("unable to create file");

        fwrite($fh,$last);
        fclose($fh);
        header('Location:indexlog.php?page=menu&edit='.$selected);
        ob_end_flush();


    }
    
    if(isset($_POST['down'])){
        $selected= $_POST['itemnumber'];
        $newssel= $_POST['newsnumber'];


        
        $temp = $xml[data][0][_c][item][$selected][_c][news][$newssel+1];
        $xml[data][0][_c][item][$selected][_c][news][$newssel+1] = $xml[data][0][_c][item][$selected][_c][news][$newssel];
        $xml[data][0][_c][item][$selected][_c][news][$newssel] = $temp;




        $last=ary2xml($xml);

        //write();
        $fh = fopen("../data.xml", "w");

        if($fh==false)
            die("unable to create file");

        fwrite($fh,$last);
        fclose($fh);
        header('Location:indexlog.php?page=menu&edit='.$selected);
        ob_end_flush();


    }




?>