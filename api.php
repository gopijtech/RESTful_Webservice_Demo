<?php
  /**
   * Simple RESTful API webservice script example
   * ==============================================================================
   * 
   * @version v1.0: api.php 2016/04/13
   * @copyright Copyright (c) 2016 Sagar Deshmukh
   * @author Sagar Deshmukh <sagarsdeshmukh91@gmail.com>
   * 
   * ==============================================================================
   *
   */
 
	require_once("Rest.inc.php");
	require_once("database_configuration.php");

	class API extends REST{

		public function __construct()
		{
			parent::__construct();				// Init parent contructor
		}

		/*
		 * Public method for access api.
		 * This method dynmically call the method based on the query string
		 *
		 */
		public function processApi()
		{
			$func = strtolower(trim(str_replace("/","",$_REQUEST['action'])));

			if ((int)method_exists($this,$func) > 0)
			{
				$this->$func();
			}
			else
			{
				$this->response('',404); // If the method not exist with in this class, response would be "Page not found".
			}
		}

		/* 
		 *	Insert new User into Database system
		 *  method : GET
		 *  data   : json data
		 *  Output : json data
		 */
		
		private function add_user()
		{
			// You can even make more secure your webservice by resticting user request as per request type
			/*if ($this->get_request_method() != "POST")
			{
				$arr_res = array();
				$arr_res['error']  = array("msg" => "Invalid request method");
				$arr_res['result'] = array('status' => "Failed");
				$this->response($this->json($arr_res), 406);
			}*/

			$data = $this->convert_json_to_array($this->_request['data']);

			$id       = $data['id'];
			$name     = $data['name'];
			$address  = $data['address'];
			$country  = $data['country'];
			$dob      = $data['dob'];
			$username = $data['username'];
			$password = $data['password'];

			// Input validations
			if (empty($id))
			{
				$arr_res=array();
				$arr_res['error']  = array("msg" => "User id not found");
				$arr_res['result'] = array('status' => "Failed");
				$this->response($this->json($arr_res), 400);
			}
			
			if (empty($name))
			{
				$arr_res=array();
				$arr_res['error']  = array("msg" => "User Name value not found");
				$arr_res['result'] = array('status' => "Failed");
				$this->response($this->json($arr_res), 400);
			}
					
			if (empty($address))
			{
				$arr_res=array();
				$arr_res['error']  = array("msg" => "Address value not found");
				$arr_res['result'] = array('status' => "Failed");
				$this->response($this->json($arr_res), 400);
			}
			
			if (empty($country))
			{
				$arr_res=array();
				$arr_res['error']  = array("msg" => "Country value not found");
				$arr_res['result'] = array('status' => "Failed");
				$this->response($this->json($arr_res), 400);
			}
			
			if (empty($dob))
			{
				$arr_res=array();
				$arr_res['error']  = array("msg" => "DOB value not found");
				$arr_res['result'] = array('status' => "Failed");
				$this->response($this->json($arr_res), 400);
			}

			if (empty($username))
			{
				$arr_res=array();
				$arr_res['error']  = array("msg" => "'username' not found");
				$arr_res['result'] = array('status' => "Failed");
				$this->response($this->json($arr_res), 400);
			}
			
			if (empty($password))
			{
				$arr_res=array();
				$arr_res['error']  = array("msg" => "'password' not found");
				$arr_res['result'] = array('status' => "Failed");
				$this->response($this->json($arr_res), 400);
			}

			if (!empty($id) && !empty($name) && !empty($address) && !empty($country) && !empty($dob) && !empty($username) && !empty($password))
			{
				$query = "SELECT id FROM user WHERE username = '".$username."'";
				$result = mysql_query($query);
				if (mysql_num_rows($result)>0)
				{
					$user_data = mysql_fetch_array($result) ;                
					$user_id = $user_data["id"];
					
					$arr_res = array();
					$arr_res['error']  = array("msg" => "User already exist in database. id = ".$user_id);
					$arr_res['result'] = array('status' => "Failed");
					$this->response($this->json($arr_res), 200);
				}

				$insert_query = "INSERT INTO user (id, name, address, country, dob, username, password) values ('".$id."','".$name."','".$address."','".$country."','".$dob."','".$username."','".$password."')";
				
				mysql_query($insert_query);				
				$user_id = mysql_insert_id();

				$str_array = array('status' => "Success" ,'id' => $user_id);
				
				$arr_res = array();
				$arr_res['error']  = $error;
				$arr_res['result'] = $str_array;


				$this->response($this->json($arr_res), 200);
			}
			
			// If invalid inputs "Bad Request" status message and reason
			$error = array("msg" => "Invalid input parameter");
			
			$arr_res = array();
			$arr_res['error']  = $error;
			$arr_res['result'] = array('status' => "Failed");
			$this->response($this->json($arr_res), 400);
		}
		
		
		/* 
		 *	Delete User from database
		 *  method : POST
		 *  data   : json data
		 *  Output : json data
		 */
		
		private function delete_user()
		{
			/*if ($this->get_request_method() != "POST")
			{
				$arr_res = array();
				$arr_res['error']  = array("msg" => "Invalid request method");
				$arr_res['result'] = array('status' => "Failed");
				$this->response($this->json($arr_res), 406);
			}*/

			$data = $this->convert_json_to_array($this->_request['data']);

			$id = $data['id'];

			// Input validations
			if (empty($id))
			{
				$arr_res=array();
				$arr_res['error']  = array("msg" => "User id not found");
				$arr_res['result'] = array('status' => "Failed");
				$this->response($this->json($arr_res), 400);
			}
			else
			{
				$select_query = "SELECT id FROM user WHERE id = '".$id."'";
				$result = mysql_query($select_query);
				if (mysql_num_rows($result) != 1)
				{
					$arr_res = array();
					$arr_res['error']  = array("msg" => "User not exist in database. id = ".$id);
					$arr_res['result'] = array('status' => "Failed");
					$this->response($this->json($arr_res), 200);
				}

				// Delete User from database
				$delete_query = "DELETE FROM user WHERE id = '".$vm_id."'";
				mysql_query($delete_query);
				
				$str_array = array('status' => "Success");
				
				$arr_res = array();
				$arr_res['error']  = $error;
				$arr_res['result'] = $str_array;

				$this->response($this->json($arr_res), 200);
			}
			
			// If invalid inputs "Bad Request" status message and reason
			$error = array("msg" => "Invalid input parameter");
			
			$arr_res = array();
			$arr_res['error']  = $error;
			$arr_res['result'] = array('status' => "Failed");
			$this->response($this->json($arr_res), 400);
		}
		
		/*
		 *	Decode JSON into array
		*/
		private function convert_json_to_array($json)
		{		 
			return json_decode($json,true);
		}
		
		/*
		 *	Encode array into JSON
		*/
		private function json($data)
		{
			if (is_array($data))
			{
				return json_encode($data);
			}
		}
	}
	
	// Initiate Library
	$api = new API;
	$api->processApi();
?>