<?php

namespace App\Http\Controllers\frontend\v1;

use App\Models\Brand;
use App\Models\SaleArea;
use App\Models\RemarkField;
use App\Models\welcomeData;
use Illuminate\Http\Request;
use App\Models\InstallProcess;
use App\Models\ApplicantResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WelcomeDataController extends Controller
{
    public function getWelcomeData(Request $request)
    {
        $query=WelcomeData::select('welcome_data.*', 'sale_areas.name as saleAreaName', 'regions.name as regionName', 'users.name as saName', 'brands.name as brandName')->with('installProcess', 'remarkField', 'applicantResponse')
        ->leftJoin('sale_areas', 'sale_areas.id', 'welcome_data.sale_area_id')
        ->leftJoin('regions', 'regions.id', 'welcome_data.region_id')
        ->leftJoin('brands', 'brands.id', 'welcome_data.brand_id')
        ->leftJoin('users', 'users.id', 'welcome_data.user_id');

        $searchQuery = $request->search ? "%$request->search%" : null;

        $query = $query->when($searchQuery, function ($query) use ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->orwhere('users.name', 'LIKE', $searchQuery)
                ->orWhere('regions.name', 'LIKE', $searchQuery)
                ->orWhere('sale_areas.name', 'LIKE', $searchQuery)
                ->orWhere('welcome_data.application_id', 'LIKE', $searchQuery)
                ->orWhere('welcome_data.phone_no', 'LIKE', $searchQuery)
                ->orWhere('welcome_data.contract_no', 'LIKE', $searchQuery)
                ->orWhere('brands.name', 'LIKE', $searchQuery);
            });
        });


        if (!is_null($request->startDate) && !is_null($request->endDate)) {
            $query->whereDate('welcome_data.created_at', '>=', $request->startDate)
               ->whereDate('welcome_data.created_at', '<=', $request->endDate);

        } elseif(!is_null($request->startDate) && is_null($request->endDate)) {
            $query->whereDate('welcome_data.created_at', $request->startDate);
        } elseif(!is_null($request->endDate) && is_null($request->startDate)) {
            $query->whereDate('welcome_data.created_at', $request->endDate);
        }

        if (!is_null($request->installStatus)) {
            switch ($request->installStatus) {
                case '1':
                    $query = $query->where('welcome_data.install_process_id', '1');
                    break;

                case '2':
                    $query = $query->where('welcome_data.install_process_id', '2');
                    break;

                case '3':
                    $query = $query->where('welcome_data.install_process_id', '3');
                    break;

                case '4':
                    $query = $query->where('welcome_data.install_process_id', '4');
                    break;
            }
        }
        if (!is_null($request->remarkStatus)) {
            switch ($request->remarkStatus) {
                case '1':
                    $query = $query->where('welcome_data.remark_field_id', '1');
                    break;

                case '2':
                    $query = $query->where('welcome_data.remark_field_id', '2');
                    break;

                case '3':
                    $query = $query->where('welcome_data.remark_field_id', '3');
                    break;

                case '4':
                    $query = $query->where('welcome_data.remark_field_id', '4');
                    break;

                case '5':
                    $query = $query->where('welcome_data.remark_field_id', '5');
                    break;

                case '6':
                    $query = $query->where('welcome_data.remark_field_id', '6');
                    break;

                case '7':
                    $query = $query->where('welcome_data.remark_field_id', '7');
                    break;

                case '8':
                    $query = $query->where('welcome_data.remark_field_id', '8');
                    break;

                case '9':
                    $query = $query->where('welcome_data.remark_field_id', '9');
                    break;
            }
        }

        if (!$request->user()->hasAnyRole(['Superadmin', 'Admin','LSL'])) {
            $query = $query->where('welcome_data.user_id', $request->user()->id);
        }

        if (!$request->user()->hasAnyRole(['Superadmin', 'Admin','User'])) {
            $query = $query->where('welcome_data.sale_area_id', $request->user()->sale_area_id_1)
            ->orWhere('welcome_data.sale_area_id', $request->user()->sale_area_id_2);
        }


        $data=$query->orderBy('welcome_data.created_at', 'desc')->get();
        return response()->json(['data' => $data]);
    }

    public function createWelcomeData(Request $request)
    {
        try {
            $request->validate([
                'application_id' => 'required',
                'brand_id' => 'required',
                'install_process_id' => 'required',
                'remark_field_id' => 'required',
                'applicant_response_id' => 'required',
            ]);

            $saleArea = SaleArea::find($request->sale_area_id);
            if($saleArea) {
                $regionId = $saleArea->region_id;

            } else {
                $regionId = null;
            }

            $data=[
                'brand_id'=>$request->brand_id,
                'region_id'=>$regionId,
                'user_id'=>$request->user_id,
                'sale_area_id'=>$request->sale_area_id,
                'install_process_id'=>$request->install_process_id,
                'remark_field_id'=>$request->remark_field_id,
                'applicant_response_id'=>$request->applicant_response_id,
                'application_id'=>$request->application_id,
                'version'=>$request->version,
                'contract_no'=>$request->contract_no,
                'phone_no'=>$request->phone_no,
                'remark'=>$request->remark
            ];
            welcomeData::create($data);

            return response()->json(['data' => 'success']);
        } catch (\Exception $e) {
            // Other exceptions
            return response()->json(['error' => $e->getMessage()]);
        }

    }
    // delete welcome data
    public function deleteWelcomeData($id)
    {
        try {
            $data = welcomeData::find($id);

            if ($data) {
                $data->delete();
                return response()->json(['message' => 'welcome data deleted successfully','status'=>1]);
            } else {
                return response()->json(['message' => 'welcome data not found','status'=>0], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting welcome data','status'=>0, 'error' => $e->getMessage()], 500);
        }

    }

    // get brand data
    public function getBrandData()
    {
        $brand=Brand::all();
        return response()->json(['data' => $brand]);
    }

    // get install process data
    public function getInstallProcessData()
    {
        $install=InstallProcess::all();
        return response()->json(['data' => $install]);
    }
    // get all remark field data
    public function getAllRemarkFieldData()
    {
        $remark=RemarkField::all();
        return response()->json(['data' => $remark]);
    }

    // get remark field data
    public function getRemarkData($id)
    {
        $remark =RemarkField::where('install_id', $id)->get();
        return response()->json(['data' => $remark]);
    }
    // get remark field data
    public function getResponseData($id)
    {
        $response =ApplicantResponse::where('install_id', $id)->get();
        return response()->json(['data' => $response]);
    }
}
