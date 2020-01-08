<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require_once "../include/DBOperation.php";
$app = AppFactory::create();

$app->setBasePath("/projects/My-Api/public");
$app->post('/createUser', function (Request $request, Response $response, array $args) {
  //if(!haveEmptyParameters(array('name','course','email','password','age'),$response)){
      $request_data=$request->getParsedBody();
      $name=$request_data['name'];
      $course=$request_data['course'];
      $email=$request_data['email'];
      $password=$request_data['password'];
      $age=$request_data['age'];
          $db=new BbOperation;
          $pass=password_hash($password,PASSWORD_DEFAULT);
          $rs=$db->createUser($name,$course,$email,$pass,$age);
          if($rs==200){
            $message=array();
            $message['error']=false;
            $message['message']='User Created Successfuly';
            $response->getBody()->write(json_encode($message));
            return $response
                          ->withHeader('Content_type','application/json')
                          ->withStatus(201);
          }
          elseif($rs==401){
            $message=array();
            $message['error']=true;
            $message['message']="User Exist";
            $response->getBody()->write(json_encode($message));
            return $response
                          ->withHeader('Content_type','application/json')
                          ->withStatus(422);
          }
          elseif($rs==404){
            $message=array();
            $message['error']=true;
            $message['messae']='some eroor is occurred';
            $response->getBody()->write(json_encode($message));
            return $response
                          ->withHeader('Content_type','application/json')
                          ->withStatus(422);
          }
          return $response
                        ->withHeader('Content_type','application/json')
                        ->withStatus(422);
    //}


    return $response;
});
 function haveEmptyParameters($required_params,$response){
  $error=false;
  $error_params='';
  $required_params=$_REQUEST;
  foreach ($required_params as $param) {
    if(!isset($required_params[$param])||strlen($required_params[$param])<=0){
    $error=true;
    $error_params .=$param .',';
  }
}
  if ($error) {
    $error_detail=array();
    $error_detail['error']=true;
    $error_detail['message']='require param '.substr($error_params,0,-3).'are missing or empty';
    $response->getBody()->write(json_encode($error_detail));
  }

  return $error;
}

$app->run();
