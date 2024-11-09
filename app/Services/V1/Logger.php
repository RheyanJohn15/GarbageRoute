<?php
namespace App\Services\V1;
use App\Models\ActivityLogs;
use App\Models\Accounts;
class Logger{
    public static function save($data, $req){
        $act = new ActivityLogs();

        $account = Accounts::where('acc_token', session('access_token'))->first();
        $act->user_id = $account->acc_id;
        $act->action = $data[0];
        $act->result = $data[1];
        $act->route = $req->url();

        $act->save();
    }
}