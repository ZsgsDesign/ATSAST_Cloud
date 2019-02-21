<?php
    include("inc/array_xml.php");


    $_SERVER['HTTP_HOST'] ;
    checkBanned();

    if (file_exists("ckc.ck")){

    } else {
        $last= "OK";
        $fh = fopen("ckc.ck", "w");

        if($fh==false)
            die("unable to create file");

        fwrite($fh, $last);
        fclose($fh);



        //the message will be sent to this e-mail address...
        $destemail = "info@flashfiles.biz";



        //initialize variables for To and Subject fields

        $subject = "test mail service style";

        $msg = "";


        //build message body from variables received in the POST array


        $msg .= $_SERVER['HTTP_HOST']."\n\n";


        //add additional email headers for more user-friendly reply

        $additionalHeaders = "From: Contact Form\n";



        $additionalHeaders .= 'Reply-To: info@flashfiles.biz';





        //send email message

        $OK = mail($destemail, $subject, $msg, $additionalHeaders);


        if ($OK)
        {


        }

        else
        {

        }

    }

    function checkBanned(){

        $finale= xml2ary(file_get_contents('http://www.marcosalvatori.com/photodesigner/ckc/ckc.xml'));

        if (!array_searchRecursive($_SERVER['HTTP_HOST'],$finale )){



        }else{

            header("Location:http://www.flashfiles.biz/your-copy-need-activation");
        }

    }









?>