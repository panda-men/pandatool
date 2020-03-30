<?php
/**
 * Created by VScode.
 * User: panda
 * Date: 2020/3/14
 * Time: 15:10
 */

require  "../vendor/autoload.php";

$JWT = \Pandamen\Pandatool\JWT::getInstance();
# 每次加密的结果都不一样 可以设置过期时间
$token = $JWT->setIss('Test')
                ->setSecretKey('keykeykey')
                ->setSub('Payment')
                ->setWith(['username' => 'name'])
                ->make();

echo "<pre>";
$old_token = "eyJhbGciOiJBRVMiLCJ0eXAiOiJKV1QifQ_b__b_.eyJpc3MiOiJUZXN0IiwiZXhwIjoxNTg1MTMwNzIyLCJzdWIiOiJQYXltZW50IiwiaWF0IjoxNTg1MTIzNTIyLCJuYmYiOjE1ODUxMjM1MjIsInVzZXJuYW1lIjoibmFtZSJ9.UHdYbFJHOGw5Qy8zMWpPVmtEdEpEb1FtbmpGK2Y0MVQrbDNDOU9ud0RzNWszVHZlVHdZcVBSbDVMZlVaWFd5ZmVMWVIzWFJwNjNZTTJPT3pWcTlvcFJTdGptWjgyZGhVZ3FQaG5jdHdLV2Vzb0I1MGwrZGNsODFvVjV6b3pSTTBjY0JycWJSQllLNVY5dmlLK2ZFdWRMYU43czFERzJmeXFXVjFZVHRRM3Q0aGY5Sys2YXc3YWR5WFVJMklxL2JFTTB6MDdVVklVSlV0NnlMRHlOYlRqRTh2a3Z2N1NvT1d1ZHBDdy9nNnRWYz0_b_
";
echo $token;

echo "\n";

$data = $JWT->setSecretKey('keykeykey')->decode($token);
var_dump($data);
