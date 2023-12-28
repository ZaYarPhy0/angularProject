<?php

namespace App\Http\Controllers\frontend\v1;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    // get brand data
    public function getBrand(Request $request)
    {
        if ($request->search!='') {
            $search_query = "%$request->search%";

            $brands = Brand::where(function ($q) use ($search_query) {
                $q->where('name', 'LIKE', $search_query);
            })->orderBy('created_at', 'desc')->get();
        } else {
            $brands=Brand::orderBy('created_at', 'desc')->get();
        }

        return response()->json(['data'=>$brands]);
    }

    // create brand data
    public function createBrand(Request $request)
    {
        try {
            $validate=$request->validate([
                'name' => 'required',
            ]);
            Brand::create($validate);

            return response()->json(['data' => 'success']);
        } catch (\Exception $e) {
            // Other exceptions
            return response()->json(['error' => $e->getMessage()]);
        }

    }
    // delete brand data
    public function deleteBrand($id)
    {
        try {
            $brand = Brand::find($id);

            if ($brand) {
                $brand->delete();
                return response()->json(['message' => 'Brand deleted successfully','status'=>1]);
            } else {
                return response()->json(['message' => 'Brand not found','status'=>0], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting Brand','status'=>0, 'error' => $e->getMessage()], 500);
        }

    }
}
