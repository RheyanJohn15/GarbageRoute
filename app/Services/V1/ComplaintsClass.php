<?php
namespace App\Services\V1;
use App\Models\Complaints;
use App\Services\ApiException;

class ComplaintsClass{
    private $RESULT = null;

    public function __construct($method, $request)
    {
        $this->$method($request);
    }

    private function submit($request){

        $attachment = $request->file('attachment');

        if(!in_array($attachment->getClientOriginalExtension(), ['jpg', 'jpeg', 'png'])){
            throw new ApiException(ApiException::INVALID_PIC_TYPE);
        }

        if($attachment->getSize() > 10485760){
            throw new ApiException(ApiException::LARGE_IMAGE);
        }

        $comp = new Complaints();
        $comp->comp_name = $request->comp_name;
        $comp->comp_email = $request->email;
        $comp->comp_contact = $request->contact;
        $comp->comp_nature = $request->nature;
        $comp->comp_remarks = $request->remarks;
        $comp->comp_status = 0;
        $comp->save();

        $fileName = "Complaint". $comp->comp_id. ".". $attachment->getClientOriginalExtension();
        $attachment->move(public_path('ComplaintAssets/'), $fileName);

        $updateComp = Complaints::where('comp_id', $comp->comp_id)->first();
        $updateComp->update([
            'comp_image' => $fileName 
        ]);
        
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

    private function update($request){
        $comp = Complaints::where('comp_id', $request->comp_id);

        $comp->update(['comp_status'=>$request->status]);

        $this->RESULT = ['update', 'Complaint is Successfully Updated', 'null'];
    }
    public function getResult(){
        return $this->RESULT;
    }
}