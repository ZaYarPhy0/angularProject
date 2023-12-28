<?php

namespace App\Http\Controllers\frontend\v1;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    // get all user datas
    public function getUserData(Request $request)
    {
        $query = User::select('users.*')->with('roles', 'firstSale', 'secondSale')
            ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id');

        if (!$request->user()->hasAnyRole(['Superadmin', 'Admin', 'User'])) {
            $query = $query->where('users.id', '!=', $request->user()->id)
                ->where(function ($query) use ($request) {
                    $query->where(function ($query) use ($request) {
                        $query->where('users.sale_area_id_1', $request->user()->sale_area_id_1)
                            ->orWhere('users.sale_area_id_2', $request->user()->sale_area_id_1);
                    })
                    ->orWhere(function ($query) use ($request) {
                        $query->where('users.sale_area_id_1', $request->user()->sale_area_id_2)
                            ->orWhere('users.sale_area_id_2', $request->user()->sale_area_id_2);
                    });
                });
        }


        if ($request->search != '') {
            $search_query = "%$request->search%";

            $query = $query->searchBySaleName($request->search)
                ->orWhere(function ($q) use ($search_query) {
                    $q->where('users.name', 'LIKE', $search_query)
                        ->orWhere('users.email', 'LIKE', $search_query)
                        ->orWhere('users.id', 'LIKE', $search_query)
                        ->orWhere('roles.name', 'LIKE', $search_query);
                });
        }

        $user = $query->orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $user]);
    }


    // delete user account
    public function deleteUser($id)
    {
        try {
            $user = User::find($id);

            if ($user) {
                $user->delete();
                return response()->json(['message' => 'User deleted successfully','status'=>1]);
            } else {
                return response()->json(['message' => 'User not found','status'=>0], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting user','status'=>0, 'error' => $e->getMessage()], 500);
        }

    }


    //get user roles
    public function getRoles()
    {
        $roles = Role::get();
        return response()->json(['data'=>$roles]);
    }

    //update user roles
    public function updateUserRoles(Request $request)
    {
        $roleId = Role::find($request->roleId);
        $originId = Role::find($request->originId);

        if (!$roleId) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        $user = User::find($request->userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->removeRole($originId);
        $user->assignRole($roleId);

        return response()->json(['success' => 'ok', 'role' => $roleId], 200);
    }
}
