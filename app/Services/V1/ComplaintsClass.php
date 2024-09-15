<?php
namespace App\Services\V1;
use App\Models\Complaints;

class ComplaintsClass{
    private $RESULT = null;

    public function __construct($method, $request)
    {
        $this->$method($request);
    }

    private function submit($request){
        $comp = new Complaints();
        $comp->comp_name = $request->comp_name;
        $comp->comp_email = $request->email;
        $comp->comp_contact = $request->contact;
        $comp->comp_nature = $request->nature;
        $comp->comp_remarks = $request->remarks;
        $comp->comp_status = 0;
        $comp->save();

        $this->RESULT = ['submit', 'Complaint Successfully Submitted', 'null'];
    }

    private function list($request){
        $complaints = Complaints::all();

        $this->RESULT = ['list', 'Complaint List', $complaints];
    }

    private function remove($request){
        $comp = Complaints::where('comp_id', $request->comp_id)->first();
        $comp->delete();

        $this->RESULT = ['delete', 'Complaint Successfully Deleted', 'null'];
    }
    private function details(){}
    private function update($request){
        $comp = Complaints::where('comp_id', $request->comp_id);

        $comp->update(['comp_status'=>$request->status]);

        $this->RESULT = ['update', 'Complaint is Successfully Updated', 'null'];
    }
    public function getResult(){
        return $this->RESULT;
    }
}