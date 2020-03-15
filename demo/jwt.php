<?php
/**
 * Created by VScode.
 * User: panda
 * Date: 2020/3/14
 * Time: 15:10
 */

require  "../vendor/autoload.php";

$JWT = \Pandamen\Pandatool\JWT::getInstance();
$token = $JWT->setIss('Test')
                ->setSecretKey('keykeykey')
                ->setSub('Payment')
                ->setWith(['username' => 'name'])
                ->make();

echo "<pre>";
echo $token;

echo "\n";

$data = $JWT->setSecretKey('keykeykey')->decode($token);
var_dump($data);