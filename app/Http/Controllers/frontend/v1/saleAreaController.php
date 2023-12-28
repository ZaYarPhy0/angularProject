<?php

namespace App\Http\Controllers\frontend\v1;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\SaleArea;
use Illuminate\Http\Request;

class saleAreaController extends Controller
{
    public function getSaleArea(Request $request)
    {
        $query = SaleArea::select('sale_areas.name as saleAreaName', 'regions.name as regionName', 'sale_areas.id as id')
        ->leftJoin('regions', 'regions.id', 'sale_areas.region_id');
        if(!is_null($request->search)) {
            $search_query = "%$request->search%";

            $query = $query->where(function ($q) use ($search_query) {
                $q->where('regions.name', 'LIKE', $search_query)
                ->orWhere('sale_areas.name', 'LIKE', $search_query);
            });

        }


        $data=$query->orderBy('sale_areas.created_at', 'desc')
        ->get();
        return response()->json(['data'=>$data]);
    }

    // delete sale area
    public function deleteSaleArea($id)
    {
        try {
            $sale = SaleArea::find($id);

            if ($sale) {
                $sale->delete();
                return response()->json(['message' => 'Sale Area deleted successfully','status'=>1]);
            } else {
                return response()->json(['message' => 'Sale Area not found','status'=>0], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting Sale Area','status'=>0, 'error' => $e->getMessage()], 500);
        }

    }

    // get regions
    public function getRegions()
    {
        $regions = Region::orderBy('created_at', 'desc')->get();
        return response()->json(['data'=>$regions]);
    }

    // create sale area
    public function createSaleArea(Request $request)
    {
        try {
            $validate=$request->validate([
                'name' => 'required',
                'region_id' => 'required',
            ]);
            SaleArea::create($validate);

            return response()->json(['data' => 'success']);
        } catch (\Exception $e) {
            // Other exceptions
            return response()->json(['error' => $e->getMessage()]);
        }

    }
    // create region
    public function createRegion(Request $request)
    {
        try {
            $validate=$request->validate([
                'name' => 'required',
            ]);
            Region::create($validate);

            return response()->json(['data' => 'success']);
        } catch (\Exception $e) {
            // Other exceptions
            return response()->json(['error' => $e->getMessage()]);
        }

    }
}
