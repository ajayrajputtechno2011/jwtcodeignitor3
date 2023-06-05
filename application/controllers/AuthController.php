<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthController extends CI_Controller {


    public function __construct(){
        parent:: __construct();
        $this->load->database();
        $this->load->helper('verifyAuthToken');
        // $this->load->model('Webservice_model');
        
      }
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		echo 'hello test';
	}

    public function login()
    {
      $jwt = new JWT();

      $email = $this->input->get_post('email');
      $password = $this->input->get_post('password');
      $q= $this->db->query("SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$password'")->result();
       if($q)
       {
        $JwtSecretKey = "Mysecretwordsshere";
        $li = $q[0];
      $data = array(
       'userId'=>$li->id,
       'email'=>$li->email,
        // 'userType'=>'admin'
  );
     $token = $jwt->encode($data,$JwtSecretKey,'HS256');
    //  echo $token;
     $response = [
        'token' =>$token,
        'user'=>$q[0],
        'status' => true,
        'message' => 'success'
    ];
    echo json_encode($response); die;

       }
       else
       {
         echo 'user not found';
       }
  
     
  
    }
    

	public function getUsers(){

        
        $head = $this->input->get_request_header('Authorization');
    
        if($head==null)
        {
            $response =
             [
                'status' => false,
                'message' => 'Please Enter token'
             ];
            echo json_encode($response); die;  
        }
        
        $data2 = explode(" ", $head);
        $token = $data2[1];
         try
            {
                $token = verifyAuthToken($token);
                if($token)
                { 
                    $id = $this->input->get_post('id');
            $q= $this->db->query("SELECT * FROM `users` WHERE `id` = '$id'")->result();
            if($q)
            {
            $response = [
                'user'=>$q[0],
                'status' => true,
                'message' => 'success'
            ];
        }
        else
        {
            $response = [
                'user'=>(object)[],
                'status' => false,
                'message' => 'data not found'
            ];
        }
            echo json_encode($response); 
                }
            }
        catch (Exception $e) {
            $response = [
                'status' => false,
                'message' => 'Invalid token'
            ];
            echo json_encode($response);
        }
		
		
				}
}
