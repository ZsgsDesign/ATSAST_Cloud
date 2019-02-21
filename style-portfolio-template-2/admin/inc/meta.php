<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<title>Style Template Flash网站管理面板</title>
<link href='style.css' rel='stylesheet' type='text/css' />
<script src='jquery.js' type='text/javascript'></script>
<script type='text/javascript' src='inc/tiny_mce/jquery.tinymce.js'></script>
<script>
    !window.jQuery && document.write('<script src="jquery-1.4.3.min.js"><\/script>');
    </script>
    <script type="text/javascript" src="./fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
    <script type="text/javascript" src="./fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link rel="stylesheet" type="text/css" href="./fancybox/jquery.fancybox-1.3.4.css" media="screen" />
    <script type="text/javascript">
        $(document).ready(function() {
            /*
            *   Examples - images
            */

            $("a#example1").fancybox({
            'transitionIn'    : 'elastic',
            'transitionOut'    : 'elastic'
            });

            $("a#example2").fancybox({
                'transitionIn'    : 'elastic',
                'transitionOut'    : 'elastic'
            });

            $("a#example3").fancybox({
                'transitionIn'    : 'none',
                'transitionOut'    : 'none'    
            });

            $("a#example4").fancybox({
                'opacity'        : true,
                'overlayShow'    : false,
                'transitionIn'    : 'elastic',
                'transitionOut'    : 'none'
            });

            $("a#example5").fancybox();

            $("a#example6").fancybox({
                'titlePosition'        : 'outside',
                'overlayColor'        : '#000',
                'overlayOpacity'    : 0.9
            });

            $("a#example7").fancybox({
                'titlePosition'    : 'inside'
            });

            $("a#example8").fancybox({
                'titlePosition'    : 'over'
            });

            $("a[rel=example_group]").fancybox({
                'transitionIn'        : 'none',
                'transitionOut'        : 'none',
                'titlePosition'     : 'over',
                'titleFormat'        : function(title, currentArray, currentIndex, currentOpts) {
                    return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
                }
            });

            /*
            *   Examples - various
            */

            $("#various1").fancybox({
                'titlePosition'        : 'inside',
                'transitionIn'        : 'none',
                'transitionOut'        : 'none'
            });

            $("#various2").fancybox();

            $("#various3").fancybox({
                'width'                : '75%',
                'height'            : '75%',
                'autoScale'            : false,
                'transitionIn'        : 'none',
                'transitionOut'        : 'none',
                'type'                : 'iframe',
                'onClosed'        : function() {
                window.location = 'indexlog.php?page=menu';
                
                 }

            });
            $("#various31").fancybox({
                'width'                : '75%',
                'height'            : '75%',
                'autoScale'            : false,
                'transitionIn'        : 'none',
                'transitionOut'        : 'none',
                'type'                : 'iframe',
                 'onClosed'        : function() {
                window.location = 'indexlog.php?page=menu';
                
                 }
            });
                 $("#various32").fancybox({
                'width'                : '75%',
                'height'            : '75%',
                'autoScale'            : false,
                'transitionIn'        : 'none',
                'transitionOut'        : 'none',
                'type'                : 'iframe',
                 'onClosed'        : function() {
                window.location = 'indexlog.php?page=home';
                
                 }
            });
                        $("#music").fancybox({
                'width'                : '75%',
                'height'            : '75%',
                'autoScale'            : false,
                'transitionIn'        : 'none',
                'transitionOut'        : 'none',
                'type'                : 'iframe',
                'onClosed'        : function() {
                window.location = 'indexlog.php?page=settings';
                
                 }

            });
            
                        $("#logo").fancybox({
                'width'                : '75%',
                'height'            : '75%',
                'autoScale'            : false,
                'transitionIn'        : 'none',
                'transitionOut'        : 'none',
                'type'                : 'iframe',
                'onClosed'        : function() {
                window.location = 'indexlog.php?page=settings';
                
                 }

            });
            
                 $("#gallery").fancybox({
                'width'                : '75%',
                'height'            : '75%',
                'autoScale'            : false,
                'transitionIn'        : 'none',
                'transitionOut'        : 'none',
                'type'                : 'iframe',
                'onClosed'        : function() {
               location.reload(true);
                
                 }

            });
            
                $("#social").fancybox({
                'width'                : '75%',
                'height'            : '75%',
                'autoScale'            : false,
                'transitionIn'        : 'none',
                'transitionOut'        : 'none',
                'type'                : 'iframe',
                'onClosed'        : function() {
               location.reload(true);
                
                 }

            });
            
            
            

            $("#various4").fancybox({
                'padding'            : 0,
                'autoScale'            : false,
                'transitionIn'        : 'none',
                'transitionOut'        : 'none'
            });
        });
    </script>
