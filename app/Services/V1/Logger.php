<?php
namespace App\Services\V1;
use App\Models\ActivityLogs;
use App\Models\Accounts;
class Logger{
    public static function save($data, $req, $auths = true){
        $act = new ActivityLogs();

        if($auths){
            $account = Accounts::where('acc_token', session('access_token'))->first();
            $user_id = $account->acc_id;
        }else{
            $user_id = null;
        }
        $act->user_id = $user_id;
        $act->action = $data[0];
        $act->result = $data[1];
        $act->route = $req->url();

        $act->save();
    }

    public static function list(){
        $act = ActivityLogs::join('accounts', 'accounts.acc_id', '=', 'activity_logs.user_id')->select('activity_logs.*', 'accounts.acc_name')->get();

        return $act;
    }
}