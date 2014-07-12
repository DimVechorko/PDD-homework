<?php
$validation=new validateForm();
$errors = array(); // set the errors array to empty, by default
$message = "";

if (isset($_POST["submit"]))
{
    $rules = array();


    $rules[] = "required,user_name,Пожалуйста, введите Ваше имя.";
    $rules[] = "required,email,Пожалуйста введите ваш email.";
    $rules[] = "valid_email,email,Введен некорректный email.";
    $rules[] = "length>=50,msg,Пожалуйста введите сообщение не менее 50 символов длинной.";
    $rules[] = "captcha,captcha,Введен неверный результат выражения.";

    $errors = $validation->validateFields($_POST, $rules);


    if (!empty($errors))
    {
        echo "<div class='error'>Please fix the following errors:\n<ul>";
        foreach ($errors as $error)
            echo "<li>$error</li>\n";

        echo "</ul></div>";
    }


    else
    {
        $data=new dataOperations();
        $tmp=$data->formData;
        $data::saveData($tmp);
        var_dump($tmp);
        $dbase=new dbDataProcessing();
        $dbase->saveData($tmp);
       header('Location: '.$_SERVER['REQUEST_URI']);


    }
    $_SESSION['user_name']=$_POST['user_name'];
    $_SESSION['email']=$_POST['email'];
}

$form="<div class=\"input-group\">";
$htmlForm=new myForm();
$htmlForm->myInput('text','user_name','form-control','Ваше имя');
$htmlForm->myInput('text','email','form-control','Ваш e-mail');
$htmlForm->myTextArea('msg','form-control',5,5);
$htmlForm->genCaptcha();
$htmlForm->myInput('submit','submit','btn btn-default','Отправить сообщение');
$htmlForm->crtForm('post');
$form.=$htmlForm->form;
$form.="</div>";
?>