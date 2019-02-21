
<?php

    //the message will be sent to this e-mail address...
    $destemail = "info@flashfiles.biz";
    $autoResponder = "info@flashfiles.biz";

$msg1 = "来自您网站的邮件:"; // Change it to your language, if you will
$msgReceipt = "您好! 我们已经收到您的封电子邮件:"; // 回复给留言者
$Closure = "\n\n谢谢! 我们会尽快给您回复."; // 回复给留言者
$Disclaimer = "\n\nNote: If we have received this message by a mistake, please let us know."; // Disclaimer to sender - DO NOT USE STANDRAD DISCLAIMER - spam filters will trash your mail.

$subject="新的网站邮件 " . date("j/n-Y H:i:s"); // 发送给您的邮箱
$subject2 = "已收到你的电子邮件"; // 发送给留言者


    //if magic quotes turned on, remove slashes from escaped characters

        $from = stripslashes($_POST["textName"]);
        $textEmail = stripslashes($_POST["textEmail"]);

        $textBody = stripslashes($_POST["textBody"]);
        $values = array("\\");
        $textBody = str_replace($values, "", $textBody);

        $socName = stripslashes($_POST["socName"]);
        $tel = stripslashes($_POST["tel"]);

$senderName = $_POST["textName"];
$senderEmail = $_POST["textEmail"];
$senderMessage = $_POST["textBody"];
$senderCompany = $_POST["socName"];
$senderPhone = $_POST["tel"];

$senderName = stripslashes($senderName);
$senderEmail = stripslashes($senderEmail);
$senderMessage = stripslashes($senderMessage);
$senderCompany = stripslashes($senderCompany);
$senderPhone = stripslashes($senderPhone);

$vowels = array("\\");
$messageBody = str_replace($vowels, "", $senderMessage);

//initialize variables for To and Subject fields

//build message body from variables received in the POST array


$msg = "\n\n" . $messageBody;
$msg .= "\n\n发件人: " . $senderName;
$msg .= "\n电话: " . $senderPhone;
$msg .= "\n公司: " . $senderCompany;
$msg .= "\nEmail: " . $senderEmail;

$msg1 .= $msg;
$msgReceipt .= $msg . $Closure . $Disclaimer;

//发送邮件告诉留言人留言成功


$headers= "发件人: 您的网站名称 <".  $autoResponder .">\n";
$headers.="Content-type: text/plain; charset=utf-8";


$headersCopy = "发件人: 您的网站名称 <" . $autoResponder . ">\n";
$headersCopy .="Content-type: text/plain; charset=utf-8";

// echo $subject . "\n\n" . $msg;


//send email message


if (mail($destemail, $subject, $msg1, $headers) && mail($textEmail, $subject2, $msgReceipt, $headersCopy))
{
        echo "sent=".urlencode("OK");

    }

    else
{
        echo "sent=".urlencode("failed");

    }

/**/
?>