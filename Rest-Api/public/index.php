<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require_once "../include/DBOperation.php";
$app = AppFactory::create();

$app->setBasePath("/Slim/Rest-Api/Rest-Api/public");
  $app->post('/createUser', function (Request $request, Response $response, array $args) {
  if(!haveEmptyParameters(array('name','course','email','password','age'),$response)){
      $request_data=$request->getParsedBody();
      $name=$request_data['name'];
      $course=$request_data['course'];
      $email=$request_data['email'];
      $password=$request_data['password'];
      $age=$request_data['age'];
          $db=new DbOperation;
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
    }
    return $response
                  ->withHeader('Content_type','application/json')
                  ->withStatus(422);

});
$app->post('/userLogin', function (Request $request, Response $response, array $args) {
	if (!haveEmptyParameters(array('password','email'),$response)) {
		$request_data=$request->getParsedBody();
		$password=$request_data['password'];
		$email=$request_data['email'];
		//$pass=password_hash($password,PASSWORD_DEFAULT);
		$db = new DbOperation;
		$result=$db->userLogin($email,$password);
		if($result == 201){
		$user=$db->getUserByEmail($email);
		$response_data=array();
		$response_data['error']=false;
		$response_data['message']='User Login Successfully';
		$response_data['user']=$user;
		$response->getBody()->write(json_encode($response_data));
		return $response
									->withHeader('Content-type','application/json')
									->withStatus(200);
	}elseif ($result == 202) {

		$message=array();
		$message['error']=true;
		$message['message']='User Not Exist'.$password.' '.$email;
		$response->getBody()->write(json_encode($message));
		return $response
									->withHeader('Content-type','application/json')
									->withStatus(200);
		}elseif ($result == 203) {
			$message=array();
			$message['error']=true;
			$message['message']='Invalid credential';
			$response->getBody()->write(json_encode($message));
			return $response
										->withHeader('Content-type','application/json')
										->withStatus(200);
	}
  return $response
                ->withHeader('Content-type','application/json')
                ->withStatus(200);
	}
  return $response
                ->withHeader('Content-type','application/json')
                ->withStatus(404);
});

$app->get('/getAll', function (Request $request, Response $response, array $args) {
		$db = new DbOperation;
		$users=$db->getAll();
		$response_data=array();
		$response_data['error']=false;
		$response_data['users']=$users;
		$response->getBody()->write(json_encode($response_data));
		return $response
									->withHeader('Content-type','application/json')
									->withStatus(201);
});
 function haveEmptyParameters($required_params,$response){
  $error=false;
  $error_params='';
  $request_params=$_REQUEST;
  foreach ($required_params as $param) {
    if(!isset($request_params[$param]) || strlen($request_params[$param])<=0){
    $error=true;
    $error_params .=$param .',';
  }
}
  if ($error) {
    $error_detail=array();
    $error_detail['error']=true;
    $error_detail['message']='require param :'.substr($error_params,0,-1).' are missing or empty';
    $response->getBody()->write(json_encode($error_detail));
  }

  return $error;
}


$app->run();
