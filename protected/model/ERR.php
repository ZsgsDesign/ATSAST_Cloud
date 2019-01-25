<?php

class ERR {
    
    /**
     * An old-fashioned error catcher mainly to provide error description
     * existed here only to avoid direct SQL database access
     * return a hundred present pure string
     *
     * @author John Zhang
     * @param string $ERR_CODE
     */

    public static function Catcher($ERR_CODE)
    {
        if(($ERR_CODE<1000)) $ERR_CODE=1000;
        $output=array(
             'ret' => $ERR_CODE,
            'desc' => self::Desc($ERR_CODE),
            'data' => null
        );
        exit(json_encode($output));
    }
     
    private static function Desc($ERR_CODE)
    {
        $ERR_DESC=array(
            
            '1000' => "Unspecified Error",  /** Under normal condictions those errors shouldn't displayed to end users unless they attempt to do so
                                             *  some submissions should be intercepted by the frontend before the request sended 
                                             */
            '1001' => "Internal Sever Error : SECURE_VALUE 非法",
            '1002' => "内部服务器错误：操作失败",
            '1003' => "内部服务器错误：参数不全",
            '1004' => "内部服务器错误：参数非法",
            '1005' => "内部服务器错误：文件类型不被支持",
            '1006' => "内部服务器错误：输入过长",

            '2000' => "Account-Related Error",

            '2001' => "请先登录",
            
            '6000' => 'Cloud-Related Error',
        );
        return isset($ERR_DESC[$ERR_CODE])?$ERR_DESC[$ERR_CODE]:$ERR_DESC['1000'];
    }

}
