<?php

namespace App\Http\Controllers\auth;

use Carbon\Carbon;
use App\Models\User;
use App\Models\SaleArea;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendForgetPasswordEmailJob;

class AuthController extends Controller
{
    const SANCTUM_TOKEN_NAME = 'sev_acc_app';

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken(self::SANCTUM_TOKEN_NAME)->plainTextToken;
            $success['token_expiration'] = 60 * 60 * 24;
            
            $success['role'] = $user->getRoleNames();
            $success['permissions'] = $user->getPermissionsViaRoles()->pluck('name');


            $success['user'] =  [
                'id' => $user->id,
                'name' =>$user->name,
                'email' =>$user->email,
                'sale_area_id_1' => $user->sale_area_id_1,
                'sale_area_id_2' => $user->sale_area_id_2,
            ];
            return response()->json(['status' => 'success', 'data' => $success]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Please try again']);
        }

    }

    // create new user data
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'password' => 'required',
                'email' => 'required|email',
                'sale_area_id_1' => 'required',
            ]);
            $data = [
                'name' => $request->name,
                'password'=>Hash::make($request->password),
                'email' => $request->email,
                'sale_area_id_1' => $request->sale_area_id_1,
                'sale_area_id_2' => $request->sale_area_id_2,
            ];

            $user=User::create($data);
            $user->assignRole('user');

            return response()->json(['data' => 'success']);
        } catch (\Exception $e) {
            // Other exceptions
            return response()->json(['error' => $e->getMessage()]);
        }

    }

    public function forgetPassword(Request $request)
    {
        
        $request->validate([
            'email' => 'required|exists:users,email',
        ]);

        $token = Str::random(60);

        $password_reset = PasswordReset::where('email', $request->email)->first();

        if (!$password_reset) {
            $password_reset = PasswordReset::create([
                 'email' => $request->email,
                 'token' => $token,
                 'created_at' => Carbon::now()
             ]);
        } else {
            $carbon_expired_at = Carbon::parse($password_reset->created_at)->addMinutes(5);
            $now = Carbon::now();

            if ($carbon_expired_at->lte($now)) {
                $password_reset->update([
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
            }
        }

        $user = User::whereEmail($request->email)->first();

        dispatch(new SendForgetPasswordEmailJob(
            $user,
            $password_reset->token,
            config('app.frontend_url')
        ));

        return response()->json(['success'=>'Password reset email is sent to your Email!'], 200);
        // return response()->json(['success'=>$request->email], 200);
    }

    public function resetPassword(Request $request, $token)
    {
        $request->validate([
            'new_password' => 'required|min:6',
            'confirmed_new_password' => 'required|same:new_password'
        ]);

        $password_reset = PasswordReset::whereToken($token)->first();

        if (!$password_reset) {
            return response()->json(['data' => 'Invalid token!']);
        } else {
            $carbon_expired_at = Carbon::parse($password_reset->created_at)->addMinutes(5);
            $now = Carbon::now();
            if ($carbon_expired_at->lte($now)) {
                return response()->json(['data' => 'Expire token!']);
            }
        }

        // return 'akm';
        $user = User::whereEmail($password_reset->email)->first();
        
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        PasswordReset::whereEmail($user->email)->delete();

        return response()->json(['data'=>'Password is successfully updated!'], 200);
    }

    
    public function logout()
    {
        // Auth::user()->tokens()->delete();
        return response()->json(['status' => 'success', 'message' => 'Logout Successfully']);

    }


    public function getSalesArea()
    {
        $saleAreas=SaleArea::get();
        return response()->json(['data' => $saleAreas]);
    }
}
