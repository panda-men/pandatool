<?php
/**
 * Created by VScode.
 * User: panda
 * Date: 2020/3/14
 * Time: 15:10
 */

require "../vendor/autoload.php";

$cryption = \panda\Cryption::getInstance();

# 每次加密的结果都不一样 可以设置过期时间
$res = $cryption->encode("My name is panda", "panda");
echo $res;

echo "<br/>\n";

# 解码
$deRes = $cryption->decode("7c80mR4b0tRG4fNB86pwJfDNz8ZLNzTZqfLYTpiO2w6liPyY5qWN2vD4Udc", "panda");
echo $deRes;

