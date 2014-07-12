<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11.07.14
 * Time: 21:16
 */
class dbDataProcessing {
    private $host='localhost';
    private $dbName='pdd';
    private $user='root';
    private $password='7233992';
    public $DBH;

    public function __construct(){

        try{
            $this->DBH=new PDO("mysql:host=$this->host;dbname=$this->dbName", $this->user, $this->password);

        }
        catch(PDOException $e){
                echo $e->getMessage();
        }
    }

    public function saveData(array $formData){
        $tmp=$this->DBH->prepare("INSERT INTO messages(time,user_ip,user_browser,user_name,user_email,user_message) value (:time,:user_ip,:user_browser,:user_name,:user_email,:user_message)");
        $tmp->execute($formData);

    }
/*
    public function getPageData ($formData){


        $this->DBH->prepare("SELECT * FROM messages WHERE id<);
    }
*/
}