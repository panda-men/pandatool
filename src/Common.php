<?php
/**
 * 常用函数
 * Date: 2020/3/14
 * Time: 下午 11:47
 */

namespace Pandamen\Pandatool;


use Pandamen\Pandatool\Component\Singleton;

class Common
{
    use Singleton;
    
    /** 
     * 人民币小写转大写 
     * 
     * @param string $number 数值 
     * @param string $int_unit 币种单位，默认"元"，有的需求可能为"圆" 
     * @param bool $is_round 是否对小数进行四舍五入 
     * @param bool $is_extra_zero 是否对整数部分以0结尾，小数存在的数字附加0,比如1960.30， 
     *             有的系统要求输出"壹仟玖佰陆拾元零叁角"，实际上"壹仟玖佰陆拾元叁角"也是对的 
     * @return string 
     */ 
    public static function  num2rmb($number = 0, $int_unit = '', $is_round = FALSE, $is_extra_zero = FALSE , $rmb = FALSE) 
    { 
        // 将数字切分成两段 
        $parts = explode('.', $number, 2); 
        $int = isset($parts[0]) ? strval($parts[0]) : '0'; 
        $dec = isset($parts[1]) ? strval($parts[1]) : ''; 
    
        // 如果小数点后多于2位，不四舍五入就直接截，否则就处理 
        $dec_len = strlen($dec); 
        if (isset($parts[1]) && $dec_len > 2) 
        { 
            $dec = $is_round 
                    ? substr(strrchr(strval(round(floatval("0.".$dec), 2)), '.'), 1) 
                    : substr($parts[1], 0, 2); 
        } 
    
        // 当number为0.001时，小数点后的金额为0元 
        if(empty($int) && empty($dec)) 
        { 
            return '零'; 
        } 
    
        // 定义 
        if($rmb){
            $chs = array('0','壹','贰','叁','肆','伍','陆','柒','捌','玖'); 
            $uni = array('','拾','佰','仟'); 
            $dec_uni = array('角', '分'); 
            $exp = array('', '万'); 
        }else {
            $chs = array('0','一','二','三','四','五','六','七','八','九'); 
            $uni = array('','十','百','千'); 
            $dec_uni = array('角', '分'); 
            $exp = array('', '万'); 
        }

        $res = ''; 
    
        // 整数部分从右向左找 
        for($i = strlen($int) - 1, $k = 0; $i >= 0; $k++) 
        { 
            $str = ''; 
            // 按照中文读写习惯，每4个字为一段进行转化，i一直在减 
            for($j = 0; $j < 4 && $i >= 0; $j++, $i--) 
            { 
                $u = $int{$i} > 0 ? $uni[$j] : ''; // 非0的数字后面添加单位 
                $str = $chs[$int{$i}] . $u . $str; 
            } 
            //echo $str."|".($k - 2)."<br>"; 
            $str = rtrim($str, '0');// 去掉末尾的0 
            $str = preg_replace("/0+/", "零", $str); // 替换多个连续的0 
            if(!isset($exp[$k])) 
            { 
                $exp[$k] = $exp[$k - 2] . '亿'; // 构建单位 
            } 
            $u2 = $str != '' ? $exp[$k] : ''; 
            $res = $str . $u2 . $res; 
        } 
    
        // 如果小数部分处理完之后是00，需要处理下 
        $dec = rtrim($dec, '0'); 
    
        // 小数部分从左向右找 
        if(!empty($dec)) 
        { 
            $res .= $int_unit; 
    
            // 是否要在整数部分以0结尾的数字后附加0，有的系统有这要求 
            if ($is_extra_zero) 
            { 
                if (substr($int, -1) === '0') 
                { 
                    $res.= '零'; 
                } 
            } 
    
            for($i = 0, $cnt = strlen($dec); $i < $cnt; $i++) 
            { 
                $u = $dec{$i} > 0 ? $dec_uni[$i] : ''; // 非0的数字后面添加单位 
                $res .= $chs[$dec{$i}] . $u; 
            } 
            $res = rtrim($res, '0');// 去掉末尾的0 
            $res = preg_replace("/0+/", "零", $res); // 替换多个连续的0 
        } 
        else 
        { 
            if($rmb){
                $res .= $int_unit . '整'; 
            }
            
        } 
        return $res; 
    } 

    /**
     * 生成唯一订单号
     */
    public static function order_number(){
        static $ORDERSN=array();                                        //静态变量
        $ors=date('ymd').substr(time(),-5).substr(microtime(),2,8);     //生成16位数字基本号
        if (isset($ORDERSN[$ors])) {                                    //判断是否有基本订单号
            $ORDERSN[$ors]++;                                           //如果存在,将值自增1
        }else{
            $ORDERSN[$ors]=1;
        }
        return $ors.str_pad($ORDERSN[$ors],2,'0',STR_PAD_LEFT);     //链接字符串
    }


    /**
     * 生成GUID
     * F512D3CB-71CE-BC5D-1549-D7CF80032CD5
     */
    public static function guid(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = chr(123)// "{"
                    .substr($charid, 0, 8).$hyphen
                    .substr($charid, 8, 4).$hyphen
                    .substr($charid,12, 4).$hyphen
                    .substr($charid,16, 4).$hyphen
                    .substr($charid,20,12)
                    .chr(125);// "}"
            return $uuid;
        }
    }


    /**
     * 计算相差时间 **：**：**
     * @param $begin_time
     * @param $end_time
     * @return array
     */
    public static function timediff($begin_time, $end_time)
    {
        if ($begin_time < $end_time) {
            $starttime = $begin_time;
            $endtime = $end_time;
        } else {
            $starttime = $end_time;
            $endtime = $begin_time;
        }
        //计算天数
        $timediff = $endtime - $starttime;
        $days = intval($timediff / 86400);//计算小时数
        $remain = $timediff % 86400;
        $hours = intval($remain / 3600);
        $remain = $remain % 3600;
        $mins = intval($remain / 60);
        $secs = $remain % 60;
        $res = array("day" => $days, "hour" => $hours, "min" => $mins, "sec" => $secs);
        return $res;
    }


    /**
     * 友好时间显示
     * @param $time
     * @return bool|string
     */
    public static function friend_date($time)
    {
        if (!$time)
            return false;
        $fdate = '';
        $d = time() - intval($time);
        $ld = $time - mktime(0, 0, 0, 0, 0, date('Y')); //得出年
        $md = $time - mktime(0, 0, 0, date('m'), 0, date('Y')); //得出月
        $byd = $time - mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')); //前天
        $yd = $time - mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')); //昨天
        $dd = $time - mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天
        $td = $time - mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')); //明天
        $atd = $time - mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')); //后天
        if ($d == 0) {
            $fdate = '刚刚';
        } else {
            switch ($d) {
                case $d < $atd:
                    $fdate = date('Y年m月d日', $time);
                    break;
                case $d < $td:
                    $fdate = '后天' . date('H:i', $time);
                    break;
                case $d < 0:
                    $fdate = '明天' . date('H:i', $time);
                    break;
                case $d < 60:
                    $fdate = $d . '秒前';
                    break;
                case $d < 3600:
                    $fdate = floor($d / 60) . '分钟前';
                    break;
                case $d < $dd:
                    $fdate = floor($d / 3600) . '小时前';
                    break;
                case $d < $yd:
                    $fdate = '昨天' . date('H:i', $time);
                    break;
                case $d < $byd:
                    $fdate = '前天' . date('H:i', $time);
                    break;
                case $d < $md:
                    $fdate = date('m月d日 H:i', $time);
                    break;
                case $d < $ld:
                    $fdate = date('m月d日', $time);
                    break;
                default:
                    $fdate = date('Y年m月d日', $time);
                    break;
            }
        }
        return $fdate;
    }

 
    /**
     * 获取随机字符串
     * @param int $randLength  长度
     * @param int $addtime  是否加入当前时间戳
     * @param int $includenumber   是否包含数字
     * @return string
     */
    public static function get_rand_str($randLength=6,$addtime=1,$includenumber=0){
        if ($includenumber){
            $chars='abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQEST123456789';
        }else {
            $chars='abcdefghijklmnopqrstuvwxyz';
        }
        $len=strlen($chars);
        $randStr='';
        for ($i=0;$i<$randLength;$i++){
            $randStr.=$chars[rand(0,$len-1)];
        }
        $tokenvalue=$randStr;
        if ($addtime){
            $tokenvalue=$randStr.time();
        }
        return $tokenvalue;
    }


    /**
     * 将数组转换为xml，可以多维
     * @param array $data
     * @param bool $root 是否添加头部
     * @return string
     */
    public static function arrayToXml($data, $root = true)
    {
        $str="";
        if($root)$str .= '<?xml version="1.0" encoding="UTF-8"?>';
        foreach($data as $key => $val){
            //去掉key中的下标[]
            $key = preg_replace('/\[\d*\]/', '', $key);
            if(is_array($val)){
                $child = self::arrayToXml($val, false);
                $str .= "<$key>$child</$key>";
            }else{
                $str.= "<$key>$val</$key>";
            }
        }
        return $str;
    }


    public static function xmlToArray($xml)
    {
        // 清理替换没有闭合标签的
        $xml = preg_replace('/\<(\w+)\/\>/','<$1></$1>',$xml);

        $object = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $array  = json_decode(json_encode($object),true);
        $array  = self::_checkType($array);
        return $array;
    }

    /**
     * 遍历检查数组格式，如果是空数组则转为字符串
     * @param $array
     * @return array
     */
    public static function _checkType($array){
        $tem = [];
        foreach ($array as $key => $value){
            if (is_array($value)){
                if (empty($value)){
                    $tem[$key] = "";
                }else{
                    $tem[$key] = self::_checkType($value);
                }
            }else{
                $tem[$key] = $value;
            }
        }
        return $tem;
    }

    /**
     * 加密
     * @param $data
     * @return mixed|null|string
     */
    public static function base64Encode($data)
    {
        if (!is_string($data)){
            return NULL;
        }
        $base64 = base64_encode($data);
        $base64 = str_replace("/", "_",$base64);
        $base64 = str_replace("+", "_a_",$base64);
        $base64 = str_replace("=", "_b_",$base64);
        return $base64;
    }

    /**
     * 解密
     * @param $str
     * @return mixed|null|string
     */
    public static function base64Decode($str)
    {
        if (!is_string($str)){
            return NULL;
        }
        $str = str_replace("_b_", "=",$str);
        $str = str_replace("_a_", "+",$str);
        $str = str_replace("_", "/",$str);
        $str = base64_decode($str);
        return $str;
    }

    /**
     * 获取客户端IP
     * @param   $isLong     是否为longint类型
     * @return  string or integer
     */
    public static function getClientIp($isLong = false){
        if (isset($_SERVER['REMOTE_ADDR'])){
            return $isLong ? ip2long($_SERVER['REMOTE_ADDR']) : $_SERVER['REMOTE_ADDR'];
        } else {
            return null;
        }
    }

    /**
     * 获取客户端header
     * @param   $key 
     * @return  array or string [<description>]
     */
    static $clientHeader = false;
    static function getClientHeader($key = null) {
        if (self::$clientHeader === false) {
            foreach ($_SERVER as $k => $v) {
                if (substr($k, 0, 5) == 'HTTP_') {
                    $headerKey = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($k, 5)))));
                    self::$clientHeader[$headerKey] = $v;
                }
            }
        }

        if ($key !== null) {
            return self::$clientHeader[$key] ?? null;
        }

        return self::$clientHeader;
    }


    /**
     * 随机字符串获取
     * @param   $length
     * @return  $string
     */
    public static function randStr($length = 10){
        $loop       = ceil($length/32);
        $surplus    = 32 - $length%32;
        $string     = '';
        for($i = 0; $i < $loop; $i++){
            $string     .= md5(rand(1000,9999));
        }

        return strtoupper(substr($string, $surplus));
    }

    /**
     * 计算执行时间
     * @param   $tag
     * @param   $tag2
     * @return  boolean || time
     */
    static $executeTimeTag  = [];
    public static function exeTime($tag, $tag2 = null){
        if($tag2 === null){
            //记录执行时间
            self::$executeTimeTag[$tag]     = microtime(true);
            return true;
        }else if(isset(self::$executeTimeTag[$tag]) && isset(self::$executeTimeTag[$tag2])){
            //返回执行时间
            return abs(round(self::$executeTimeTag[$tag2] - self::$executeTimeTag[$tag], 3));
        }else{
            return true;
        }
    }
}