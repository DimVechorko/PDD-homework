<?php
class dataOperations{
    const DATA = 'data/data.txt';

    public $formData=array();
    function __construct(){
        date_default_timezone_set("Europe/Minsk");
        if(isset($_SERVER['REQUEST_TIME'])){
            $this->formData['time']=date('d').".".date('m').".".date('Y')."-".date('G').".".date('i').".".date('s');
        }
        else {
            $this->formData['time']='';
        }
        if(isset($_SERVER['REMOTE_ADDR'])){
            $this->formData['ip']=$_SERVER['REMOTE_ADDR'];
        }
        else{
            $this->formData['ip']='';
        }
        if(isset($_SERVER['HTTP_USER_AGENT'])){
            $this->formData['browser']=$_SERVER['HTTP_USER_AGENT'];
        }
        else{
            $this->formData['browser']='';
        }
        if(isset($_POST['user_name'])){
            $this->formData['user_name']=$_POST['user_name'];
        }
        else{
            $this->formData['user_name']='';
        }
        if(isset($_POST['email'])){
            $this->formData['email']=$_POST['email'];
        }
        else{
            $this->formData['email']='';
        }
        if(isset($_POST['msg'])){
            $this->formData['msg']=$_POST['msg'];
        }
        else{
            $this->formData['msg']='';
        }

        return $this->formData;
    }

    public static function saveData(array $formData)
    {
        if (file_exists(self::DATA)) {
            $msg = implode(";*^^*;", $formData);
            $msg .= "\n";
            file_put_contents(self::DATA, $msg, FILE_APPEND);
            print "Сообщение сохранено\n";
            return true;
        } else {
            print "Сообщение не сохранено\n";
            return false;
        }
    }
    /*
    public static function returnMsg($index)
    {
        if (file_exists(self::DATA)) {
            $buffer = file(self::DATA);
            $keys = array('messageText', 'userName', 'userEmail', 'date', 'userAgent', 'IP');
            $msg = explode(";*^^*;", $buffer[$index]);
            $msg = array_combine($keys, $msg);
            return $msg;
        } else {
            return false;
        }
    }
    */
    public static function getPageData(){
        $file=file(self::DATA);
        if (file_exists(self::DATA) AND !empty($file)) {
            $html='<ul class="list-group">';
            $new=array();
            $keys=array('Date','IP','browser','name','email','message');
            $pageNum=isset($_GET['page']) ? $_GET['page'] : 1;
            $pageSize=isset($_GET['Size']) ? $_GET['Size'] : 10;
            $startIndex = ($pageNum - 1)*$pageSize;
            $array=array_reverse(file(self::DATA));
            $pageData=array_slice($array, $startIndex, $pageSize);

            for ($i=0; $i < count($pageData) ; $i++) {
                $tmp = explode(';*^^*;', $pageData[$i]);
                $new=array_combine($keys, $tmp);
                $html .= "<li class=list-group-item>Отправлено "."$new[name]"." в "."$new[Date]".", IP адрес: "."$new[IP]".", браузер: "."$new[browser]".", e-mail:"."$new[email]"."<p>"."$new[message]"."</p></li>";

            }
            var_dump($new);
            $html.='</ul>';
            return $html;
        }
        else{
            return false;
        }
    }
}