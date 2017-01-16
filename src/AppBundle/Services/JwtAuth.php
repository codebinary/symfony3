<?php 

namespace AppBundle\Services;

use Firebase\JWT\JWT;

class JwtAuth{

	public $manager;

	public function __construct($manager){
		$this->manager = $manager;
	}

	//Método para la validación
	public function signup($email, $password, $getHash = NULL){

		//Clave secrete
		$key = "clave-secreta";

		//Consulta para comprobar los datos
		//Esto es como decir SELECT * FROM USER WHERE EMAIL = $EMAIL AND PASSWORD = $PASSWORD
		$user = $this->manager->getRepository('BackendBundle:User')->findOneBy(
				array(
					"email" => $email,
					"password" => $password
				)
			);

		$signup = false;

		//si devuelve un objeto
		if (is_object($user)) {
			$signup = true;
		}

		if ($signup == true) {

			$token = array(
				"sub" 		=> $user->getId(),
				"email" 	=> $user->getEmail(),
				"name" 		=> $user->getName(),
				"surname" 	=> $user->getSurname(),
				"password" 	=> $user->getPassword(),
				"image" 	=> $user->getImage(),
				"iat" 		=> time(),//Cuando se ha creado el token
				"exp" 		=> time() + (7 * 24 * 60 * 60)//Fecha de expiración
			);	

			//codificamos los datos
			$jwt = JWT::encode($token, $key, "HS256");

			$decode = JWT::decode($jwt, $key, array('HS256'));	

			//Datos en limpio
			if ($getHash != null) {
				return $jwt;
			}else{
				return $decode;
			}

			//return array("status" => "success", "data" => "Login success !!");
		}else{
			return array("status" => "error", "data" => "Login failed !!");
		}
	}




}