<?php
namespace App\Services\V1;
use App\Models\Accounts;
use Illuminate\Support\Facades\Hash;
class AdminAccount{
    private $RESULT = null;

    public function __construct($method, $request)
    {
        $this->$method($request);
    }

    private function update($request){
        $account = Accounts::where('acc_id', $request->id)->first();

        $account->update([
            'acc_name'=> $request->name,
            'acc_username'=> $request->username,
        ]);

        $this->RESULT = ['update', 'Account Profile is successfully updated', 'null'];
    }

    private function changepass($request){
        $account = Accounts::where('acc_id', $request->id)->first();

        if(Hash::check($request->currentPass, $account->acc_password)){
            $account->update([
                'acc_password'=> Hash::make($request->newPass),
            ]);

            $this->RESULT = ['changepass', 'Password is Successfully Updated', 'none'];
        }else{
            $this->RESULT = ['changepass', 'Fail: Entered Current Password is invalid', 'none'];
        }
    }   

    public function getResult(){
        return $this->RESULT;
    }
}