<?php

namespace App\Http\Controllers\frontend\v1;

use App\Models\welcomeData;
use Illuminate\Http\Request;
use App\Exports\WelcomeDataExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportWelcomeData(Request $request)
    {
        try {
            $query=WelcomeData::select('welcome_data.*', 'sale_areas.name as saleAreaName', 'regions.name as regionName', 'users.name as saName', 'brands.name as brandName', 'install_processes.name as installProcessName', 'remark_fields.name as remarkFieldName', 'applicant_responses.name as applicantResponseName')
        ->leftJoin('sale_areas', 'sale_areas.id', 'welcome_data.sale_area_id')
        ->leftJoin('regions', 'regions.id', 'welcome_data.region_id')
        ->leftJoin('brands', 'brands.id', 'welcome_data.brand_id')
        ->leftJoin('install_processes', 'install_processes.id', 'welcome_data.install_process_id')
        ->leftJoin('remark_fields', 'remark_fields.id', 'welcome_data.remark_field_id')
        ->leftJoin('applicant_responses', 'applicant_responses.id', 'welcome_data.applicant_response_id')
        ->leftJoin('users', 'users.id', 'welcome_data.user_id');

            $searchQuery = $request->search ? "%$request->search%" : null;

            $query = $query->when($searchQuery, function ($query) use ($searchQuery) {
                $query->where(function ($q) use ($searchQuery) {
                    $q->orwhere('users.name', 'LIKE', $searchQuery);
                    $q->orWhere('regions.name', 'LIKE', $searchQuery);
                    $q->orWhere('sale_areas.name', 'LIKE', $searchQuery);
                    $q->orWhere('welcome_data.application_id', 'LIKE', $searchQuery);
                    $q->orWhere('welcome_data.phone_no', 'LIKE', $searchQuery);
                    $q->orWhere('brands.name', 'LIKE', $searchQuery);
                });
            });
            
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
                        $query = $query->where('welcome_data.remark_field_id', '4');
                        break;

                    case '6':
                        $query = $query->where('welcome_data.remark_field_id', '4');
                        break;

                    case '7':
                        $query = $query->where('welcome_data.remark_field_id', '4');
                        break;

                    case '8':
                        $query = $query->where('welcome_data.remark_field_id', '4');
                        break;

                    case '9':
                        $query = $query->where('welcome_data.remark_field_id', '4');
                        break;
                }
            }

            if (!$request->user()->hasAnyRole(['Superadmin', 'Admin','User'])) {
                $query = $query->where('welcome_data.sale_area_id', $request->user()->sale_area_id_1)
                ->orWhere('welcome_data.sale_area_id', $request->user()->sale_area_id_2);
            }



            if (!is_null($request->startDate) && !is_null($request->endDate)) {
                $query->whereDate('welcome_data.created_at', '>=', $request->startDate)
                   ->whereDate('welcome_data.created_at', '<=', $request->endDate);

            } elseif(!is_null($request->startDate) && is_null($request->endDate)) {
                $query->whereDate('welcome_data.created_at', $request->startDate);
            } elseif(!is_null($request->endDate) && is_null($request->startDate)) {
                $query->whereDate('welcome_data.created_at', $request->endDate);
            }

            // $data=$query->orderBy('created_at', 'desc')->get();


            $chunkedData = [];
            $query->orderBy('welcome_data.created_at', 'desc')->chunk(100, function ($datas) use (&$chunkedData) {
                foreach ($datas as $data) {
                    $chunkedData[] = $data;
                }
            });

            return Excel::download(new WelcomeDataExport(collect($chunkedData)), 'welcomeData.xlsx');

        } catch (\Exception $e) {
            return response()->json(array('error' => 'error'), 500);
        }
    }

}
