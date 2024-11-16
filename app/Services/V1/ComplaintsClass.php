<?php

namespace App\Services\V1;

use App\Models\Complaints;
use App\Services\ApiException;
use App\Models\Zones;

class ComplaintsClass
{
    private $RESULT = null;

    public function __construct($method, $request)
    {
        $this->$method($request);
    }

    private function submit($request)
    {

        $attachment = $request->file('attachment');

        if (!$attachment) {
            throw new ApiException(ApiException::NO_IMAGE);
        }
        if (!in_array($attachment->getClientOriginalExtension(), ['jpg', 'jpeg', 'png'])) {
            throw new ApiException(ApiException::INVALID_PIC_TYPE);
        }

        if ($attachment->getSize() > 10485760) {
            throw new ApiException(ApiException::LARGE_IMAGE);
        }

        $url = "https://semysms.net/api/3/sms.php";

        $phone = $request->contact;
        $msg = 'Hi, we have received your complaint and want to assure you that we are working to resolve it as quickly as possible. Thank you for your patience and understanding.';
        $device = '348599';
        $token = '25599652cf0f719e20d5f63db090219a';


        $data = array(
            "phone" => $phone,
            "msg" => $msg,
            "device" => $device,
            "token" => $token
        );

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $output = curl_exec($curl);
        curl_close($curl);


        $comp = new Complaints();
        $comp->comp_name = $request->comp_name;
        $comp->comp_email = $request->email;
        $comp->comp_contact = $request->contact;
        $comp->comp_nature = $request->nature;
        $comp->comp_remarks = $request->remarks;
        $comp->comp_zone = $request->zone;
        $comp->comp_status = 0;
        $comp->save();

        $fileName = "Complaint" . $comp->comp_id . "." . $attachment->getClientOriginalExtension();
        $attachment->move(public_path('ComplaintAssets/'), $fileName);

        $updateComp = Complaints::where('comp_id', $comp->comp_id)->first();
        $updateComp->update([
            'comp_image' => $fileName
        ]);

        $this->RESULT = ['submit', 'Complaint Successfully Submitted', 'null'];
    }

    private function list($request)
    {
        $complaints = Complaints::join('zones', 'zones.zone_id', '=', 'complaints.comp_zone')
            ->select('complaints.*', 'zones.zone_name')
            ->get();

        $this->RESULT = ['list', 'Complaint List', $complaints];
    }

    private function remove($request)
    {
        $comp = Complaints::where('comp_id', $request->comp_id)->first();
        $comp->delete();

        $this->RESULT = ['Remove Complaint', 'Complaint Successfully Deleted', 'null'];
    }

    private function update($request)
    {
        $comp = Complaints::where('comp_id', $request->comp_id);

        $comp->update(['comp_status' => $request->status]);

        $this->RESULT = ['Update Complaint Status', 'Complaint is Successfully Updated', 'null'];
    }

    private function getzone($req)
    {
        $zone = Zones::all();

        $this->RESULT = ['getzone', "Zone Fetched", $zone];
    }

    public function getResult()
    {
        return $this->RESULT;
    }
}
