<?php
/**
 *
 */
class BbOperation
{
  private $con;
  function __construct()
  {
     require_once dirname(__FILE__).'/DBConnect.php';
     $db=new DBConnect;
     $this->con=$db->connect();
  }

public function createUser($name,$course,$email,$password,$age){
  if(!$this->isEmailExist($email)){
    $sql=$this->con->prepare("INSERT INTO student(s_name, s_course, s_email, s_password, s_age)VALUES(?,?,?,?,?)");
    $sql->bind_param("sssss",$name,$course,$email,$password,$age);
    if($sql->execute()){
      return 200;
    }else {
      return 404;
    }
  }else {
    return 401;
  }
}
  public function isEmailExist($email){
    $sql=$this->con->prepare("SELECT * FROM student WHERE s_email=?");
    $sql->bind_param("s",$email);
    $sql->execute();
    $sql->store_result();
    return $sql->num_rows  > 0;
  }
}

 ?>
