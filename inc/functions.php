<?php
session_start();

/**
 * проверяет, отправку формы
 * @return bool
 *
 */
function ifFormSubmitted(){
    return(isset($_POST)and !empty($_POST));
}

/**
 * Собирает данные в массив
 * @return array
 */
function getData(){
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
/**
 * генерирует капчу, на выходе массив с выражением для вывода и правильным ответом
 * вносит ответ в сессию
 * @return array
 *
 */
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
    $_SESSION['captcha']=$captcha[1];
    return $captcha[0];
}


/**
 * Сверяет капчу из сессии с ответом пользователя
 * @param $captcha
 * @return bool
 *
 */
function validateCaptcha($captcha){
    if(isset($_SESSION['captcha'])){
        $check=$_SESSION['captcha'];
    }
    else{
        $check='';
    }
    return $check==$captcha;
}

/**
 * Проверяет валидность формы, если не валидна - выводит массив ошибок
 * @param array $formArray
 * @return array|bool
 */
function validateForm(array $formArray){
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

/**
 * записывает сообщение в data
 * @param array $formData
 * @return string
 */
function writeDataToFile(array $formData){
    file_put_contents('data\data.txt', implode('|',$formData)."\n",FILE_APPEND | LOCK_EX);
    return ("data.txt");

}

/**
 * преобразует data в массив отсортированный в обратном порядке
 * @return array
 *
 */
function getMessageFromData(){
    $array=array_reverse(file('data\data.txt'));
    return $array;
}

/**
 * считает колво строк в массиве
 * @param $array
 * @return int
 */
function countData(array $array){
    $num=count($array);
    return $num;
}

/**
 * @param $num колво строк
 * @param $pageNum размер страницы
 * @return float
 */
function countPages($num,$pageSize){
    $count=$num/$pageSize;
    if(is_int($count)===true){
        return $count;
    }
    else{
        return (int)$count+1;
    }
}

function getPaginator($count){
    isset($_GET['page']) ? $page = $_GET['page'] : $page = 1;
    isset($_GET['size']) ? $pageSize = $_GET['size'] : $pageSize = 15;
    switch ($pageSize) {
        case 15:
            $active15 = 'class="activeNum"';
            break;
        case 30:
            $active30 = 'class="activeNum"';
            break;
        case 45:
            $active45 = 'class="activeNum"';
            break;
    }
    $html = "";

    for ($i=1; $i <= $count; $i++) {
        $html .= ($i == $page) ? "<a class='pItem active' href=\"?page=$i&size=$pageSize#text\">$i</a>" : "<a class='pItem' href=\"?page=$i&size=$pageSize#text\">$i</a>";
    }
    $html .= "<span>Кол-во записей:<a $active15 href=\"?page=$page&size=15#text\">15</a><a $active30 href=\"?page=$page&size=30#text\">30</a><a $active45 href=\"?page=$page&size=45#text\">45</a></span>";

    return $html;
}
/**
 * Возвращает шаблон по его имени, если он найден в папке шаблонов. иначе - пустую строку
 * @param $name string - имя шаблона
 * @return string
 */
function getTemplate($name){
    $tpl = "";
    $fileName = 'tpl' . DIRECTORY_SEPARATOR . $name . '.html';
    if(file_exists($fileName)){
        $tpl = file_get_contents($fileName);
    }
    return $tpl;
}
/**
 * Выполняет подстановки в переданный шаблон
 * @param $tpl string - строка с макросами подстановки вида {{NAME}}
 * @param array $data - массив подстановок вида array('NAME' => 'code')
 * @return string
 */
function processTemplace($tpl, array $data = array()){
    foreach($data as $key => $val){
        $tpl = str_replace('{{'.$key.'}}', $val, $tpl);
    }
    return $tpl;
}

function processTemplateErrorOutput($tpl, array $data = array()){
    foreach($data as $key => $val){
        $tpl = str_replace(
            "<p class=\"help-block\" data-name=\"$key\"></p>",
            "<p class=\"help-block\" data-name=\"$key\">$val</p>",
            $tpl
        );
    };

    return $tpl;
}
/**
 * Выводит срез страниц
 * @param $storage массив со строками
 * @param $pageNum номер требуемой страницы
 * @param $pageSize количество выводимых строк на странице
 * @return array требуемые строки на странице
 */
function getNumPage($storage, $pageNum, $pageSize){
    $startIndex = ($pageNum - 1)*$pageSize;
    return array_slice($storage, $startIndex, $pageSize);
}
function pageText($getNumPage){
    $html = "";
    $count=count($getNumPage);
    for ($i=0;$i<$count;$i++) {
        $tmp = explode('|', $getNumPage[$i]);
        $html .= "<li><div><span>$tmp[3]</span><span class='date'>$tmp[4]</span></div><p>$tmp[5]</p></li>";
    }
    unset($tmp);
    return $html;
}