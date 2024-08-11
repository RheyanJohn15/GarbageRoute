<?php
namespace App\Services;
use App\Models\Accounts;
use Illuminate\Support\Facades\Hash;
class Auth {
    
    private $username;
    private $password;
    private $method;
    public function __construct($method, $data)
    {
      if($method == 'login'){
        $this->username = $data->username;
        $this->password = $data->password;
        $this->method = $method;
      }
    }
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

    private function genAuthToken($length = 32){
      $bytes = random_bytes($length);
      $token = bin2hex($bytes);
    
      return $token;
    }
}