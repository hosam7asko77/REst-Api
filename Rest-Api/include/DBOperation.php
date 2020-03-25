<?php
/**
 *
 */
class DbOperation
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
  public function userLogin($email,$password){
    if($this->isEmailExist($email)){
      $hashed_password= $this->getUserPasswordByEmail($email);
      if(password_verify($password,$hashed_password)){
        return 201;

      }else {

        return 202;
      }
    }else {
      return 203;
    }

  }
  public function getUserPasswordByEmail($email)
{
$sql=$this->con->prepare("SELECT s_password FROM student WHERE s_email=? ");
$sql->bind_param("s",$email);
$sql->execute();
$sql->bind_result($password);
$sql->fetch();
return $password;

}
public function getUserByEmail($email)
{
$sql=$this->con->prepare("SELECT s_id,s_name, s_course, s_email, s_password, s_age FROM student WHERE s_email=? ");
$sql->bind_param("s",$email);
$sql->execute();
$sql->bind_result($id,$name,$course,$email,$password,$age);
$sql->fetch();
$user=array();
$user['id']=$id;
$user['name']=$name;
$user['course']=$course;
$user['email']=$email;
$user['password']=$password;
$user['age']=$age;
return $user;
}
function getAll(){
$sql=$this->con->prepare("SELECT s_id,s_name, s_course, s_email, s_password, s_age FROM student");
$sql->execute();
$sql->bind_result($id,$name,$course,$email,$password,$age);
  $users=array();
while ($sql->fetch()) {
  $user=array();
  $user['id']=$id;
  $user['name']=$name;
  $user['course']=$course;
  $user['email']=$email;
  $user['password']=$password;
  $user['age']=$age;
array_push($users,$user);
}
  return $users;
}
public function addImage($name1,$name2)
{
    $sql=$this->con->prepare("INSERT INTO image(profile, background)VALUES(?,?)");
    $sql->bind_param("ss",$name1,$name2);
    if($sql->execute()){
      return 200;
    }else {
      return 404;
    }

}

}

 ?>
