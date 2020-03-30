<?php
/**
 * 常用函数
 * Created by VScode.
 * User: panda
 * Date: 2020/3/14
 * Time: 15:10
 */

require "../vendor/autoload.php";

$fun = \Pandamen\Pandatool\Common::getInstance();

# 每次加密的结果都不一样 可以设置过期时间
$res = $fun::num2rmb(2145235);
echo $res;
echo "<br>";

