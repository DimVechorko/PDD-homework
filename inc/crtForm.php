<?php
class createForm
{
  public function __construct()
  {
    $content='';
    $form='';
  }
  
  public function crtInput($type,$name,$value=NULL,$disable=NULL,$label=NULL,$size=NULL)
  {
    if($label)
      $this->content.="<label for=\"".$name."\">".$label."</label> ";
    $this->content.="<input type=\"".$type."\" name=\"".$name."\" id=\"".$name."\" value=\" ".$value."\" ";
    if($disable)
      $this->content.="disabled=\"disabled\" ";
    if($size)
      $this->content.="style=\"width:".$size."px\" ";
    $this->content.="/><br />";
  }
  public function crtSelect($name,$arr,$title=NULL)
  {
    $sel='';
    foreach($arr as $key)
      $sel.='<option value="'.$key.'">'.$key.'</option>';
    if($title)
      $this->content.=$title;
    $this->content.='<select name="'.$name.'">'.$sel.'</select><br />';
  }
  public function crtTextArea($name, $row, $column, $label=NULL, $text=NULL)
  {
    if($label)
      $this->content.=$label;
    $this->content.='<textarea name="'.$name.'" rows="'.$row.'" cols="'.$column.'">';
    if($text)
      $this->content.=$text;
    $this->content.='</textarea><br />';
  }
  
  public function crtForm($met,$act=NULL)
  {
    if($act)
      $this->form='<form action="'.$act.'" method="'.$met.'">';
    else
      $this->form='<form method="'.$met.'">';
    $this->form.=$this->content."</form>";
  }
}
class myForm extends createForm{
    public function genCaptcha($class=NULL){
        $a=rand(1,50);
        $c=rand(50,100);
        $b=$c-$a;
        $tmp=array(0,1);
        $operator=array_rand($tmp);
        if($operator==0 && $a>$b){
            $captcha[]="Введите результат выражения: $a-$b= "."<input type=text name=captcha class=\"".$class."\"><br />";
            $captcha[]=$a-$b;
        }
        else{
            $captcha[]="Введите результат выражения: $a+$b= "."<input type='text' name='captcha' size='5' class=\"".$class."\"><br />";
            $captcha[]=$a+$b;
        }
        $_SESSION['captcha']=$captcha[1];
        $this->content.=$captcha[0];
    }
    public function myInput($type,$name,$class=NULL,$placeholder=NULL,$value=NULL,$disable=NULL,$label=NULL,$size=NULL)
    {
        if($label)
            $this->content.="<label for=\"".$name."\">".$label."</label> ";
        $this->content.="<input type=\"".$type."\" name=\"".$name."\" class=\"".$class."\" placeholder=\"".$placeholder."\" ";
        if(isset($_SESSION[$name])){
            $value=$_SESSION[$name];
            $this->content.="value=\"".$value."\"";
        }
        if($disable)
            $this->content.="disabled=\"disabled\" ";
        if($size)
            $this->content.="style=\"width:".$size."px\" ";
        $this->content.="/><br />";
    }
    public function myTextArea($name, $class, $row, $column, $label=NULL, $text=NULL)
    {
        if($label)
            $this->content.=$label;
        $this->content.='<textarea name="'.$name.'" class="'.$class.'" rows="'.$row.'" cols="'.$column.'">';
        if($text)
            $this->content.=$text;
        $this->content.='</textarea><br />';
    }
}
