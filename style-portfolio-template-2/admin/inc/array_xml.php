<?php
    /*
    Working with XML. Usage: 
    $xml=xml2ary(file_get_contents('1.xml'));
    $link=&$xml['ddd']['_c'];
    $link['twomore']=$link['onemore'];
    // ins2ary(); // dot not insert a link, and arrays with links inside!
    echo ary2xml($xml);
    */

    function loadXML(){

      /*  if( ! $_xml = simplexml_load_file('../data.xml','SimpleXMLElement', LIBXML_NOCDATA) )
        {
            echo 'unable to load XML file';
        }
        else
        {
            echo 'XML file loaded successfully';
        }*/
        
        $doc = new DOMDocument("1.0","utf-8");
        $doc->formatOutput = true;
        $doc->load('../data.xml');
      //  echo $doc->saveXML();
        return $doc;
    }




    // XML to Array
    function xml2ary(&$string) {
    $parser = xml_parser_create();
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parse_into_struct($parser, $string, $vals, $index);
    xml_parser_free($parser);
    
    $mnary=array();
   // print_r($ary);
    $ary=&$mnary;
    foreach ($vals as $r) {
    
    $t=$r['tag'];
    if ($r['type']=='open') {
    if (isset($ary[$t])) {
    if (isset($ary[$t][0])) $ary[$t][]=array(); else $ary[$t]=array($ary[$t], array());
    $cv=&$ary[$t][count($ary[$t])-1];
    } else $cv=&$ary[$t][];//[] aggiunte da me
    if (isset($r['attributes'])) {foreach ($r['attributes'] as $k=>$v) $cv['_a'][$k]=$v;}
    $cv['_c']=array();
    $cv['_c']['_p']=&$ary;
    $ary=&$cv['_c'];

    } elseif ($r['type']=='complete') {
    if (isset($ary[$t])) { // same as open
    if (isset($ary[$t][0])) $ary[$t][]=array(); else $ary[$t]=array($ary[$t], array());
    $cv=&$ary[$t][count($ary[$t])-1];
    } else $cv=&$ary[$t][];//[] aggiunte da me;
    if (isset($r['attributes'])) {foreach ($r['attributes'] as $k=>$v) $cv['_a'][$k]=$v;}
    $cv['_v']=(isset($r['value']) ? $r['value'] : '');

    } elseif ($r['type']=='close') {
    $ary=&$ary['_p'];
    }
    }    

    _del_p($mnary);
    return $mnary;
    }

    // _Internal: Remove recursion in result array
    function _del_p(&$ary) {
    foreach ($ary as $k=>$v) {
    if ($k==='_p') unset($ary[$k]);
    elseif (is_array($ary[$k])) _del_p($ary[$k]);
    }
    }

    // Array to XML
    function ary2xml($cary, $d=0, $forcetag='') {
    $res=array();
    foreach ($cary as $tag=>$r) {
    if (isset($r[0])) {
    $res[]=ary2xml($r, $d, $tag);
    } else {
    if ($forcetag) $tag=$forcetag;
    $sp=str_repeat("\t", $d);
    $res[]="$sp<$tag";
    if (isset($r['_a'])) {foreach ($r['_a'] as $at=>$av) $res[]=" $at=\"".htmlspecialchars($av)."\"";}
    $res[]=">".((isset($r['_c'])) ? "\n" : '');
    if (isset($r['_c'])) $res[]=ary2xml($r['_c'], $d+1);
    // small hack ;)
    elseif (isset($r['_v'])) $res[]="<![CDATA[".stripslashes($r['_v'])."]]>";
    $res[]=(isset($r['_c']) ? $sp : '')."</$tag>\n";
    }

    }
    return implode('', $res);
    }

    // Insert element into array
    function ins2ary(&$ary, $element, $pos) {
    $ar1=array_slice($ary, 0, $pos); $ar1[]=$element;
    $ary=array_merge($ar1, array_slice($ary, $pos));
    }


    function stripslashes_deep($value)
    {
    $value = is_array($value) ?
    array_map('stripslashes_deep', $value) :
    stripslashes($value);

    return $value;
    }
   
    /////////////////smart copy///////////////

    function smartCopy($source, $dest, $folderPermission=0755,$filePermission=0644){
        # source=file & dest=dir => copy file from source-dir to dest-dir
        # source=file & dest=file / not there yet => copy file from source-dir to dest and overwrite a file there, if present

        # source=dir & dest=dir => copy all content from source to dir
        # source=dir & dest not there yet => copy all content from source to a, yet to be created, dest-dir
        $result=false;

        if (is_file($source)) { # $source is file
            if(is_dir($dest)) { # $dest is folder
                if ($dest[strlen($dest)-1]!='/') # add '/' if necessary
                    $__dest=$dest."/";
                $__dest .= basename($source);
            }
            else { # $dest is (new) filename
                $__dest=$dest;
            }
            $result=copy($source, $__dest);
            chmod($__dest,$filePermission);
        }
        elseif(is_dir($source)) { # $source is dir
            if(!is_dir($dest)) { # dest-dir not there yet, create it
                @mkdir($dest,$folderPermission);
                chmod($dest,$folderPermission);
            }
            if ($source[strlen($source)-1]!='/') # add '/' if necessary
                $source=$source."/";
            if ($dest[strlen($dest)-1]!='/') # add '/' if necessary
                $dest=$dest."/";

            # find all elements in $source
            $result = true; # in case this dir is empty it would otherwise return false
            $dirHandle=opendir($source);
            while($file=readdir($dirHandle)) { # note that $file can also be a folder
                if($file!="." && $file!="..") { # filter starting elements and pass the rest to this function again
                    #                echo "$source$file ||| $dest$file<br />\n";
                    $result=smartCopy($source.$file, $dest.$file, $folderPermission, $filePermission);
                }
            }
            closedir($dirHandle);
        }
        else {
            $result=false;
        }
        return $result;
    } 


    function array_searchRecursive( $needle, $haystack, $strict=false, $path=array() )
    {
        if( !is_array($haystack) ) {
            return false;
        }

        foreach( $haystack as $key => $val ) {
            if( is_array($val)&& $subPath = array_searchRecursive($needle, $val, $strict, $path) ) {
                $path = array_merge($path, array($key), $subPath);
                return $path;
            } elseif( (!$strict && $val == $needle) || ($strict && $val === $needle) ) {
                $path[] = $key;
                return $path;
            }
        }
        return false;
    }

    ////////////ARRAY TO STRING///////////////
    function implodeMDA($array, $delimeter, $keyssofar = '') {
        $output = '';
        foreach($array as $key => $value) {
            if (!is_array($value)) {
                $value = str_replace($delimeter, '/'.$delimeter, $value);
                $key = str_replace($delimeter, '/'.$delimeter, $key);
                if ($keyssofar != '') $key = $key.$delimeter.$delimeter;
                $pair = $key.$keyssofar.$delimeter.$delimeter.$delimeter.$value;
                if ($output != '') $output .= $delimeter.$delimeter.$delimeter.$delimeter;
                $output .= $pair;
            }
            else {
                if ($output != '') $output .= $delimeter.$delimeter.$delimeter.$delimeter;
                if ($keyssofar != '') $key = $key.$delimeter.$delimeter;
                $output .= $this->implodeMDA($value, $delimeter, $key.$keyssofar);
            }
        }
        return $output;
    }



    // ------------ lixlpixel recursive PHP functions -------------
    // recursive_remove_directory( directory to delete, empty )
    // expects path to directory and optional TRUE / FALSE to empty
    // of course PHP has to have the rights to delete the directory
    // you specify and all files and folders inside the directory
    // ------------------------------------------------------------

    // to use this function to totally remove a directory, write:
    // recursive_remove_directory('path/to/directory/to/delete');

    // to use this function to empty a directory, write:
    // recursive_remove_directory('path/to/full_directory',TRUE);
    function recursive_remove_directory($directory, $empty=FALSE)
    {
        // if the path has a slash at the end we remove it here
        if(substr($directory,-1) == '/')
        {
            $directory = substr($directory,0,-1);
        }

        // if the path is not valid or is not a directory ...
        if(!file_exists($directory) || !is_dir($directory))
        {
            // ... we return false and exit the function
            return FALSE;

            // ... if the path is not readable
        }elseif(!is_readable($directory))
        {
            // ... we return false and exit the function
            return FALSE;

            // ... else if the path is readable
        }else{

            // we open the directory
            $handle = opendir($directory);

            // and scan through the items inside
            while (FALSE !== ($item = readdir($handle)))
            {
                // if the filepointer is not the current directory
                // or the parent directory
                if($item != '.' && $item != '..')
                {
                    // we build the new path to delete
                    $path = $directory.'/'.$item;

                    // if the new path is a directory
                    if(is_dir($path)) 
                    {
                        // we call this function with the new path
                        recursive_remove_directory($path);

                        // if the new path is a file
                    }else{
                        // we remove the file
                        unlink($path);
                    }
                }
            }
            // close the directory
            closedir($handle);

            // if the option to empty is not set to true
            if($empty == FALSE)
            {
                // try to delete the now empty directory
                if(!rmdir($directory))
                {
                    // return false if not possible
                    return FALSE;
                }
            }
            // return success
            return TRUE;
        }
    }
    // ------------------------------------------------------------




    ///////////////////////////////
?>