<?php
/**
 * Created by VScode.
 * User: panda
 * Date: 2020/3/14
 * Time: 15:10
 */

require "../vendor/autoload.php";

$Calculation = \Pandamen\Pandatool\Calculation::getInstance();

$price = $Calculation->pricecalc(6.08,'+',0.14,2);
echo $price;



