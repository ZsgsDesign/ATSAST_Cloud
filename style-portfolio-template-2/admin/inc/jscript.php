<?php
echo " 
<!-- jscript.php -->
<script language='JavaScript'>
    $(document).ready(function(){

    });




    <!--

    function win1() {
        window.open('win1.php','Window1','menubar=no,width=460,height=360,toolbar=no');
    }

    function openWin2() {
        winnews = window.open('upnews.php', 'Upload news',
        'width=600,height=300,scrollbars=yes');
    }

    function openWin3() {
        win3 = window.open('', 'Window3', 'width=320,height=210,scrollbars=yes');
    }

    function writeTo3() {
        win3.document.writeln('<h2>This is written to Window 3 by the main window</h2>');

    }

    function openWinNav() {
        win2 = window.open('winnav.php', 'Navigation','width=200,height=300,scrollbars=no,status=no');
    }

    //-->
</script>

<script type='text/javascript'>

   //delete standard
    function submitForm(itemnumber,page,action) {
          // alert(action);
        if (action == 'delete' ) {
            if (confirm('Deleting '+ page +' item. Are you sure?')) {
                var location=('indexlog.php?page=gallery&itemnumber='+itemnumber+'&delete='+action);
                this.location.href = location;

            }else{
                // history.back();
            }
        }
    }
                //delete categories
        function submitFormCat(itemnumber,page,action) {
          // alert(action);
        if (action == 'deletecat' ) {
            if (confirm('Deleting category. Are you sure?')) {
                var location=('indexlog.php?page=gallery&edit='+itemnumber+'&delete='+action+'&catnumber='+page);
                this.location.href = location;

            }else{
                // history.back();
            }
        }
    }
    
      //delete images
        function submitFormDelImg(catnum,imgnum,action,gallery) {
          // alert(action);
        if (action == 'delete' ) {
            if (confirm('Deleting Image/Video. Are you sure?')) {
                var location=('indexlog.php?page=category&edit='+gallery+'&delete='+action+'&catnumber='+catnum+'&img='+imgnum);
                this.location.href = location;

            }else{
                // history.back();
            }
        }
    }
         //delete menu item
        function submitFormDelMenu(itemnumber,page,action) {
          // alert(action);
        if (action == 'delete' ) {
            if (confirm('Deleting Menu item. Are you sure?')) {
               var location=('indexlog.php?page=menu&delete='+itemnumber);
                this.location.href = location;

            }else{
                // history.back();
            }
        }
    }
    
     //delete social item
        function submitFormDelSocial(itemnumber,page,action) {
          // alert(action);
        if (action == 'delete' ) {
            if (confirm('Deleting Social item. Are you sure?')) {
               var location=('indexlog.php?page=social&delete='+itemnumber);
                this.location.href = location;

            }else{
                // history.back();
            }
        }
    }
   


  

</script>
<script language='JavaScript'>
    $().ready(function() {
    $('textarea.tinymce').tinymce({
            // Location of TinyMCE script
            script_url : 'inc/tiny_mce/tiny_mce.js',

            // General options
            theme : 'advanced',
            entity_encoding : 'raw',

            language : 'cn',

            plugins : 'safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template',

            // Theme options
            theme_advanced_buttons1 : 'bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect',
            theme_advanced_buttons2 : 'cut,copy,paste,|,search,replace,|,undo,redo,|,link,unlink,image,code,|,preview,|,visualchars,nonbreaking,',
            theme_advanced_buttons3 : 'fullscreen',
            theme_advanced_toolbar_location : 'top',
            theme_advanced_toolbar_align : 'left',
            theme_advanced_statusbar_location : 'bottom',
            theme_advanced_resizing : true,
          // theme_advanced_blockformats : 'None=p,Paragraph=p,Heading 1=h1,Heading 2=h2',

            // Example content CSS (should be your site CSS)
            content_css : '../style.css',
            cleanup : true,
            cleanup_on_startup : false,
            verify_html : false,
            relative_urls : true,
            force_p_newlines : false,

            
            // Drop lists for link/image/media/template dialogs
            template_external_list_url : 'lists/template_list.js',
            external_link_list_url : 'lists/link_list.js',
            external_image_list_url : 'imgmce.php',
            media_external_list_url : 'lists/media_list.js',

            // Replace values for the template plugin
            template_replace_values : {
                username : 'Some User',
                staffid : '991234'
            }
        });
    });
</script>
    

<!-- jscript.php End -->
";
?>