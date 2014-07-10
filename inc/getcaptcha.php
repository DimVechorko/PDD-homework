<?php
function genCaptcha(){
$a=rand(1,50);
$c=rand(50,100);
$b=$c-$a;
$tmp=array(0,1);
$operator=array_rand($tmp);
if($operator==0 && $a>$b){
    $captcha[]="$a-$b";
    $captcha[]=$a-$b;
}
else{
    $captcha[]="$a+$b";
    $captcha[]=$a+$b;
}
    return $captcha;
}
$captchaArray=genCaptcha();
$_SESSION['captcha']=$captchaArray[1];




