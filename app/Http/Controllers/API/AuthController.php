<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Package;
use App\Models\PackageAllot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function makeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:245', 'unique:users'],
            'package' => ['required', 'exists:packages,id'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();
            $errorArray = [];

            foreach ($errors->keys() as $key) {
                $errorArray[$key] = $errors->first($key);
            }

            $code = '403';
            $message = 'Validation failed';
            $data = $errorArray;

        } else {

            try {

                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);

                $package = Package::where('id', $request->package)->first();
                PackageAllot::create([
                    'limit' => $package->limit,
                    'package_id' => $package->id,
                    'user_id' => $user->id,
                ]);

                $code = 201;
                $message = "Account has been created successfully.";
                $data = [
                    'name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'package_limit' => $package->limit,
                    // 'password' => $request->password
                ];

            } catch (\Illuminate\Database\QueryException $e) {
                $code = 500; // Use a generic server error code for database errors
                $message = 'Database error: ' . $e->getMessage();
                $data = null;
            } catch (\Exception $e) {
                $code = 500; // General error code
                $message = 'An error occurred: ' . $e->getMessage();
                $data = null;
            }
        }

        return response()->json([
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
