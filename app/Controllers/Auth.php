<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

class Auth extends BaseController
{
    use ResponseTrait;
    public function index()
    {


        date_default_timezone_set("UTC");
        $t = microtime(true);
        $micro = sprintf("%03d", ($t - floor($t)) * 1000);
        $timestamp=date($micro);
        $string_value = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', 8)), 8, 8).rand(1000,1000000);
        $tokenid = sha1(base64_encode(sha1(base64_decode($string_value) . $timestamp, true)));
        echo $tokenid;
        // $uri=$this->request->getUri();
        // $urlsegment=$uri->getSegment(1);
        // $ip=trim($this->request->getServer('Server_addr'));
        // print_r($urlsegment);
        die;
        $user = new UserModel();
        $data = $user->orderBy('user_id', 'dsce')->findAll();
        $response=[
            'Error'=>[
                'errorCode'=>'00',
                'errorMessage'=>'',
            ],
            'result' => $data
        ];
        return $this->respond($response);
    }



    // save new product info
    public function create()
    {
        // get posted JSON
        //$json = json_decode(file_get_contents("php://input", true));
        $user = new UserModel();
        $json = $this->request->getJSON();
        // $json =  json_decode(file_get_contents("php://input"));
        // print_r($json);
        // die;
        $name = $json->name;
        $email = $json->email;
        $phone = $json->phone;

        $data = array(
            'user_name' => $name,
            'user_email' => $email,
            'user_phone' => $phone
        );

        $user->insert($data);


        $response = array(
            'status'   => 201,
            'messages' => array(
                'success' => 'user created successfully'
            )
        );

        return $this->respondCreated($response);
    }

    public function createuser()
    {



        $user = new UserModel();
        $data = [
            'user_name' =>  $this->request->getPost('name'),
            'user_email' => $this->request->getPost('email'),
            'user_phone' => $this->request->getPost('phone'),

        ];
        $reponse = [];
        if (($data)) {
            if ($user->insert($data)) {
                $response = [
                    'status' => '200',
                    'message' => 'record inserted successfully',
                ];
            } else {
                $response = [
                    'status' => '404',
                    'message' => 'fail in data insertion',
                ];
            }
            return $this->respondCreated($response);
        } else {
            $response = [
                'status' => '404',
                'message' => 'fail in data insertion',
            ];
        }
        return $this->respond($response);
    }
    public function showSingleuserList($userid)
    {
        $response = [];
        $user = new UserModel();
        $data = $user->where("user_id", $userid)->find();
        if (!($data)) {
            $response = [
                'status' => '404',
                'message' => 'data not found',
            ];
        } else {
            $response = [
                "status" => '200',
                'message' => 'data retrieved successfully',
                "singleuser" => $data,
            ];
        }
        // print_r($response);
        // die;
        return $this->respond($response);
        // print_r($data);
        // die;
    }
    public function updateuserlist($userid)
    {
        $user = new UserModel();
        $response = [];

        // print_r($userid);
        // die;
        $data = $user->where('user_id', $userid)->find();
        if ($data) {
            $response = [
                'status' => '200',
                'message' => 'display data in postman',
                'data' => $data,

            ];
        } else {
            $reponse = [
                'status' => '404',
                'message' => 'data not found',
            ];
        }
        return $this->respond($response);
    }
    public function updatedatastore()
    {
        $user = new UserModel();
        $json = $this->request->getJSON();
        $id = $json->user_id;
        $name = $json->user_name;
        $email = $json->user_email;
        $phone = $json->user_phone;

        $data = [
            'user_name' => $name,
            'user_email' => $email,
            'user_phone' => $phone,
        ];


        $response = [];
        if ($user->where('user_id', $id)->set($data)->update()) {
            $response = [
                'status' => '200',
                'message' => 'update user data',
            ];
        }
        return $this->respondUpdated($response);
    }

    public function deletesinglelist($userid)
    {
        $reponse = [];
        $user = new UserModel();
        $data = $user->where('user_id', $userid)->find();
        $userdata = (json_encode($data));



        if ($user->delete($userdata)) {
            $response = [
                'status' => '200',
                'message' => 'user delete from the record set',
            ];
        } else {
            $response = [
                'status' => '404',
                'message' => 'user deleted successfully',
            ];
        }
        return $this->respondDeleted($response);
    }
}
