<?php
namespace App\Services\V1;
use App\Models\Accounts;
use App\Models\TruckDriverModel;
use Illuminate\Support\Facades\Hash;
class Auth {


     /*
    *@Author: Rheyan John Blanco
    *@Date: August, 12, 2024
    *@Description: Auth Class handles all authentication request of the users
    *@Params: $method- determines what action users wants to do, $data- the data used to process the request
    */

    private $username;
    private $password;
    private $method;
    private $token;
    private $userType;
    private $request;
    public function __construct($method = null, $data = null)
    {
      $this->request = $data;
      if($method == 'login'){
        $this->username = $data->username;
        $this->password = $data->password;
        $this->method = $method;
      }
      if($method == 'info'){
        $this->token = $data->token;
        $this->userType = $data->type;
      }
    }


        /*
    *Author: Rheyan John Blanco
    *Date: August, 12, 2024
    *Description: Main Function to authenticate users
    */
    public function auth()
    {
        $acc = Accounts::where('acc_username', $this->username)->first();
        if($acc){
          if(Hash::check($this->password, $acc->acc_password)){
            $token = $this->genAuthToken();
            while($acc->acc_token == $token){
                $token = $this->genAuthToken();
            }
            $acc->update(['acc_token'=> $token]);
            session(['access_token' => $token]);
            return $this->parseResult('success', $token);
          }else{
            return $this->parseResult('fail', 'Invalid Password');
          }
        }else{
            return $this->parseResult('fail', 'No Account Found');
        }

    }

    public function authdriver(){
        $driver = TruckDriverModel::where('username', $this->username)->first();

        if($driver){
            if(Hash::check($this->password, $driver->password)){
                $token = $this->genAuthToken();
                while($driver->access_token == $token){
                    $token = $this->genAuthToken();
                }
                $driver->update(['access_token'=> $token]);
                session(['access_token' => $token]);
                return $this->parseResult('success', $token);
            }else{
                return $this->parseResult('fail', 'Invalid Password');
            }
        }else{
            return $this->parseResult('fail', 'No Account Found');
        }
    }


    private function parseResult($status, $result){
        return [
           'status'=>$status,
           'method'=>$this->method,
           'result'=>$result
        ];
    }

    /*
    *Author: Rheyan John Blanco
    *Date: August, 12, 2024
    *Description: Generate Token for authenticated User
    *Params: $length - to determine how long the token is and 32 is the default value
    */
    private function genAuthToken($length = 32){
      $bytes = random_bytes($length);
      $token = bin2hex($bytes);

      return $token;
    }
    
    public function getAccessToken(){
      if($this->request->userType == 'admin' ){
        return session('access_token');
      }
      return session('driver_access_token');
    }


    public function getUserInfo(){
      if($this->userType == 'admin'){
        $account = Accounts::where('acc_token', $this->token)->first();

        return $account;
      }

      $driver = TruckDriverModel::where('access_token', $this->token)->first();

      return $driver;
    }
    /*
    *Author: Rheyan John Blanco
    *Date: August, 12, 2024
    *Description: Check Session if there is an api_token exist means user is authenticated
    */
    public function checkAuth($req){
      return $req->session()->has('access_token');
    }
}
