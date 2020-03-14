<?php
/**
 * PHP精确计算  主要用于货币的计算用
 * Date: 2020/3/14
 * Time: 下午 11:47
 */

namespace panda;
use panda\Component\Singleton;

class Calculation
{
    use Singleton;

    /**
     * PHP精确计算  主要用于货币的计算用
     * @param $n1 第一个数
     * @param $symbol 计算符号 + - * / %
     * @param $n2 第二个数
     * @param string $scale  精度 默认为小数点后两位
     * @return  string
     */
    public static function pricecalc($n1, $symbol, $n2, $scale = '2')
    {
        $res = "";
        switch ($symbol) {
            case "+"://加法
                $res = bcadd($n1, $n2, $scale);
                break;
            case "-"://减法
                $res = bcsub($n1, $n2, $scale);
                break;
            case "*"://乘法
                $res = bcmul($n1, $n2, $scale);
                break;
            case "/"://除法
                $res = bcdiv($n1, $n2, $scale);
                break;
            case "%"://求余、取模
                $res = bcmod($n1, $n2, $scale);
                break;
            default:
                $res = "";
                break;
        }
        return $res;
    }

    /**
    * 价格由元转分(用于微信支付单位转换)
    * @param $price 金额
    * @return int
    */
    public static function priceyuantofen($price){
        $price = intval(self::pricecalc(100, "*",$price));
        return $price;
    }
    /**
    * 价格由分转元
    * @param $price 金额
    * @return float
    */
    public static function pricefentoyuan($price){
        $price = self::pricecalc(self::priceformat($price),"/",100);
        return $price;
    }

    /**
    * 价格格式化
    * @param int $price
    * @return string    $price_format
    */
    public static function priceformat($price){
        $price_format = number_format($price, 2, '.', '');
        return $price_format;
    }

}