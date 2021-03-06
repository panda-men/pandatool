<?php
/**
 * Format.php
 * 格式检测工具
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2017-08-01
 */

namespace Pandamen\Pandatool;

use Pandamen\Pandatool\Component\Singleton;

class Format
{
    use Singleton;
    
    /**
     * 手机号格式检测
     * @param   $mobile
     * @return  boolean
     */
    static function isMobile($mobile){
        return preg_match('/^1[0-9]{10}$/', $mobile);
    }

    /**
     * 电子邮件格式检测 
     * @param   $email
     * @return  boolean
     */
    static function isEmail($email){
        return preg_match('/^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/', $email);
    }

    /**
     * URL格式检测
     * @param   $url
     * @return  boolean
     */
    static function isUrl($url){
        return preg_match('/^((https|http|ftp|rtsp|mms)?:\/\/)[^\s]+/', $url);
    }

    /**
     * 身份证号格式检测
     * @param   $idCard
     * @return  boolean
     */
    static function isIdCard($idCard){
        return preg_match('/^\d{17}[\d|x]$|^\d{15}$/', $idCard);
    }

    /**
     * 密码格式检测
     * @param   $password
     * @return  boolean
     */
    static function isPassword($password){
        // return preg_match('/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,12}$/', $password);
        return preg_match('/^[_A-z0-9]{6,20}$/', $password);
    }

    /**
     * 短信验证码格式检测
     * @param   $smsCode
     * @return  boolean
     */
    static function isSmsCode($smsCode){
        return is_numeric($smsCode) && strlen($smsCode) == 6;
    }


    const TINYINT = 'tiny';
    const SMALLINT = 'small';
    const INT = 'int';
    const BININT = 'big';

    /**
     * 整数检测
     * @param   $int 
     * @param   $size tiny、small、int、big
     * @param   $unsign 是否无符号
     * @return  boolean [<description>]
     */
    static function isInteger($int, $size = self::INT, $unsign = true){
        if ($unsign && !preg_match('/^\d{0,12}$/', $int)) {
            return false;
        }
        if (!$unsign && !preg_match('/^-{0,1}\d{1,12}$/', $int)) {
            return false;
        }

        $abs = abs($int);
        if ($size === self::TINYINT) {
            return $abs < 256 / ($unsign ? 1 : 2);
        } else if ($size === self::SMALLINT) {
            return $abs < 65536 / ($unsign ? 1 : 2);
        } else if ($size === self::INT) {
            return $abs < 4294967296 / ($unsign ? 1 : 2);
        }

        return true;
    }

    /**
     * json格式检测
     * @param   $string
     * @return  boolean [<description>]
     */
    static function isJson($string){
        return !is_numeric($string) && json_decode($string, true);
    }

    /**
     * 用户名格式检测
     * @param   $username
     * @return  boolean [<description>]
     */
    static function isUsername($username){
        return preg_match('/^[_A-z0-9]{6,16}$/', $username);
    }
}