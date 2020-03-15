<?php
/**
 * Created by VScode.
 * User: panda
 * Date: 2020/3/14
 * Time: 15:10
 */

require "../vendor/autoload.php";

$str = "你好panda";

$base64url = \Pandamen\Pandatool\Base64Url::encode($str);
echo $base64url ."\n";

echo \Pandamen\Pandatool\Base64Url::decode($base64url);


