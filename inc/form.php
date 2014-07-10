<?php
class Form{
    private function ifFormSubmitted(){
        return(isset($_POST)and !empty($_POST));
    }
    public function getData(){
        $formData=array();

        if(isset($_SERVER['REQUEST_TIME'])){
            $formData['time']=date("m","d","Y");
        }
        else {
            $formData['time']='';
        }
        if(isset($_SERVER['REMOTE_ADDR'])){
            $formData['ip']=$_SERVER['REMOTE_ADDR'];
        }
        else{
            $formData['ip']='';
        }
        if(isset($_SERVER['HTTP_USER_AGENT'])){
            $formData['browser']=$_SERVER['HTTP_USER_AGENT'];
        }
        else{
            $formData['browser']='';
        }
        if(isset($_POST['name'])){
            $formData['name']=$_POST['name'];
        }
        else{
            $formData['name']='';
        }
        if(isset($_POST['email'])){
            $formData['email']=$_POST['email'];
        }
        else{
            $formData['email']='';
        }
        if(isset($_POST['text'])){
            $formData['text']=$_POST['text'];
        }
        else{
            $formData['text']='';
        }

        return $formData;
    }
    private function validateCaptcha($captcha){
        if(isset($_SESSION['captcha'])){
            $check=$_SESSION['captcha'];
        }
        else{
            $check='';
        }
        return $check==$captcha;
    }
    public function genCaptcha(){
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
        $_SESSION['captcha']=$captcha[1];
        return $captcha[0];
    }
    public function validateForm(array $formArray){
        $flag=true;
        if(strlen($formArray['name'])<0){
            $errors[]='Введите имя';
            $flag=false;
        }
        if(strlen ($formArray['email'])<5){
            $errors['email']='Введите email';
            $flag=false;
        }
        if(strlen ($formArray['text'])<50){
            $errors['text']='Введите сообщение не менее 50 символов';
            $flag=false;
        }
        if(!validateCaptcha($_SESSION['captcha'])){
            $errors['captcha']='Неправильный ответ';
            $flag=false;
        }
        if(!$flag){
            return $errors;
        }
        else{
            return $flag;
        }
    }
    private function writeDataToFile(array $formData){
        file_put_contents('data\data.txt', implode('|',$formData)."\n",FILE_APPEND | LOCK_EX);
        return ("data.txt");

    }
    public $formData=array();

    public function getForm($formData){
        if(ifFormSubmitted()){
            $rezult=validateForm($formData);

            if($rezult==true){
                writeDataToFile($formData);
                header('Location: '.$_SERVER['REQUEST_URI']);
            }
            else {
                $form = processTemplateErrorOutput($form, $rezult);
            }
        }
    }
}
