<?php
namespace App\Services\V1;
use App\Models\TruckDriverModel;
use Illuminate\Support\Facades\Hash;
use App\Services\ApiException;
use App\Models\DumpTruckModel;
class TruckDriver {
    
    private $RESULT = null;
    public function __construct($method, $request)
    {
        $this->$method($request);
    }

    private function add($req){
       $td = new TruckDriverModel();
       $td->username = $req->username;
       $td->password = Hash::make($req->password);
       $td->name = $req->name;
       $td->license = $req->licensenum;
       $td->contact = $req->contact;
       $td->address = $req->address;
       $td->save();

       $this->RESULT = ['add' ,'Truck Driver Successfully Added', $td];
    }

    private function delete($req){
        
       $check = TruckDriverModel::where('td_id', $req->id)->first();

       if(!$check){
         throw new ApiException(ApiException::NO_DATA_FOUND);
       }

       $truck = DumpTruckModel::where('td_id', $req->id)->get();
       if($truck->count() > 0){
         foreach($truck as $t){
            $t->update(['td_id' => '0']);
         }
       }

       $check->delete();
        $this->RESULT = ['delete', 'Truck Driver is Successfully Deleted', null];
     }

    private function list($req){
        $truck = TruckDriverModel::all();
        
        $this->RESULT = ['list', 'List of all truck drivers', $truck];
    }

    private function update($req){
        $update = TruckDriverModel::where('td_id', $req->id)->first();

        if(!$update){
            throw new ApiException(ApiException::NO_DATA_FOUND);
        }

        $update->update([
             'name'=> $req->name,
             'username'=>$req->username,
             'password'=>Hash::make($req->password),
             'license'=>$req->licensenum,
             'contact'=>$req->contact,
             'address'=>$req->address
        ]);

        $this->RESULT = ['update', 'Update Truck Driver', $update];
    }

    private function details($req){
      $check = TruckDriverModel::where('td_id', $req->id)->first();

      if(!$check){
        throw new ApiException(ApiException::NO_DATA_FOUND);
      }

      $truck = DumpTruckModel::where('td_id', $req->id)->first();
      
      $check->truck = $truck;

      $this->RESULT = ['details', 'Show truck details', $check];
    }

    public function getResult(){
        return $this->RESULT;
    }
}