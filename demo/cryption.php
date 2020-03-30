<?php
/**
 * Created by VScode.
 * User: panda
 * Date: 2020/3/14
 * Time: 15:10
 */

require "../vendor/autoload.php";

$cryption = \Pandamen\Pandatool\Cryption::getInstance();


$res = $cryption->encode("My name is panda", "panda");
echo $res;
echo "<br>";

# 解码
$deRes = $cryption->decode($res, "panda");
echo $deRes;

