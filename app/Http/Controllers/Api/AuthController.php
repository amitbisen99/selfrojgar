<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Jobs\SendEmailJob;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPassword;
use Carbon\Carbon;
use App\Models\User;

use App\Models\Advertisement;
use App\Models\Artist;
use App\Models\Business;
use App\Models\Education;
use App\Models\Experience;
use App\Models\FranchiseBusiness;
use App\Models\Job;
use App\Models\JobApply;
use App\Models\OnDemandService;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\Property;
use App\Models\Tourism;
use App\Models\WholeSellProduct;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'contact_number' => 'required|unique:users,contact_number',
            'password' => ['required', 'string', 'min:8'],
            'c_password' => 'required|same:password',
        ], [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'contact_number.required' => 'The contact number field is required.',
            'contact_number.unique' => 'The contact number has already been taken.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'password.required' => 'The password field is required.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.regex' => 'The password must contain at least one lowercase letter, one uppercase letter, and one number.',
            'c_password.required' => 'The confirm password field is required.',
            'c_password.same' => 'The confirm password and password must match.',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), []);
        }

        $input = $request->all();
        $input['password'] = bcrypt(strtolower($input['password']));
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['user'] =  User::where('email', $input['email'])->first();

        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'password.required' => 'The password field is required.',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), []);
        }

        // Attempt to authenticate the user
        if (Auth::attempt(['email' => $request->email, 'password' => strtolower($request->password)])) {
            $user = Auth::user();

            if ($user->status != 1) {
                return $this->sendError('Your account is inactive. Please contact administrator.', [], 403);
            }

            $payment = $user->payment;
            if ($request->firebase_token) {
                $user->update(['firebase_token' => $request->firebase_token]);
            }
            $currentDateTime = Carbon::now();

            // Subscription is active
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['user'] = $user;

            return $this->sendResponse($success, 'User login successfully.');
            // if (!is_null($payment)) {
            //     if ($payment->start_date && $payment->start_date) {
            //         $subscriptionStartDate = Carbon::parse($payment->start_date);
            //         $subscriptionEndDate = Carbon::parse($payment->end_date);

            //         if ($currentDateTime->between($subscriptionStartDate, $subscriptionEndDate)) {
            //             // Subscription is active
            //             $success['token'] = $user->createToken('MyApp')->accessToken;
            //             $success['user'] = $user;

            //             return $this->sendResponse($success, 'User login successfully.');
            //         } else {
            //             // Subscription is inactive
            //             return $this->sendError('Your subscription is not active.');
            //         }
            //     } else {
            //         // Subscription dates are not set
            //         return $this->sendError('Subscription details are missing.', [], 402);
            //     }
            // } else {
            //     // Subscription dates are not set
            //     return $this->sendError('Subscription details are missing.', [], 402);
            // }
        } else {
            return $this->sendError('email and password is not valid.', [], 403);
        }
    }

    public function socialLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ], [
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), []);
        }

        $user = User::where('email', $request->email)->first();

        if (is_null($user)) {
            $input = $request->all();
            $input['password'] = bcrypt($input['email']);
            $user = User::create($input);

            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['user'] = $user;

            return $this->sendResponse($success, 'User login successfully.');
        }

        if ($user) {
            if ($user->status != 1) {
                return $this->sendError('Your account is inactive. Please contact administrator.', [], 403);
            }
            Auth::login($user);

            $payment = $user->payment;
            if ($request->firebase_token) {
                $user->update(['firebase_token' => $request->firebase_token]);
            }
            $currentDateTime = Carbon::now();

            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['user'] = $user;

            return $this->sendResponse($success, 'User login successfully.');
            // if (!is_null($payment)) {
            //     if ($payment->start_date && $payment->end_date) {
            //         $subscriptionStartDate = Carbon::parse($payment->start_date);
            //         $subscriptionEndDate = Carbon::parse($payment->end_date);

            //         if ($currentDateTime->between($subscriptionStartDate, $subscriptionEndDate)) {
            //             // Subscription is active
            //             $success['token'] = $user->createToken('MyApp')->accessToken;
            //             $success['user'] = $user;

            //             return $this->sendResponse($success, 'User login successfully.');
            //         } else {
            //             // Subscription is inactive
            //             return $this->sendError('Your subscription is not active.');
            //         }
            //     } else {
            //         // Subscription dates are not set
            //         return $this->sendError('Subscription details are missing.', [], 402);
            //     }
            // } else {
            //     // No payment record
            //     return $this->sendError('Subscription details are missing.', [], 402);
            // }
        } else {
            return $this->sendError('User not found.', [], 404);
        }
    }

    /**
     * Laravel Passport User Login  API Function
     */

    public function logout(Request $request)
    {
        try {
            $token = auth()->user()->token();
            $token->revoke();
            return $this->sendResponse([], 'User logout successfully.');
        } catch (Exception $e) {
            return $this->sendError("User not found!", []);
        }
    }

    public function forgotPassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!is_null($user)) {
            $pass = $this->randomPassword();

            $mailData = [
                'user' => $user,
                'pass' => $pass,
            ];

            Mail::to($user->email)->send(new ForgotPassword($mailData));
            $user->update(['password' => bcrypt($pass)]);
            // $details = [
            //             'mailType' => 'forgotPassword',
            //             'users' => [$user->email],
            //             'mailData' => ['user' => $user],
            //         ];


            // SendEmailJob::dispatch($details);
        } else {
            return $this->sendError('user not found.');
        }
    }

    public function changePassword(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],
            'c_password' => 'required|same:password',
            'current_password' => 'required',
        ], [
            'current_password.required' => 'The current password field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'password.required' => 'The password field is required.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.regex' => 'The password must contain at least one lowercase letter, one uppercase letter, and one number.',
            'c_password.required' => 'The confirm password field is required.',
            'c_password.same' => 'The confirm password and password must match.',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), []);
        }

        // Retrieve the user by email
        $user = User::where('email', $request->email)->first();

        // Check if user exists
        if (!$user) {
            return $this->sendError('user not found.');
        }

        // Verify the current password
        if (!Hash::check(strtolower($request->current_password), $user->password)) {
            return $this->sendError('The current password is incorrect.');
        }

        // Update the user's password
        $user->password = Hash::make(strtolower($request->password));
        $user->save();
        return $this->sendResponse([], 'Password changed successfully.');
    }

    function randomPassword()
    {
        $number = '1234567890';
        $pass = array(); // Initialize as an array
        $numberLength = strlen($number) - 1; // Length of $number minus 1

        // Add exactly 8 digits from $number
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $numberLength);
            $pass[] = $number[$n];
        }

        return implode($pass); // Convert array to string
    }


    public function tokens(Request $request)
    {
        $users = User::select('id', 'name', 'firebase_token')->get();

        return $this->sendResponse($users, 'Tokens retrieved successfully.');
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ], [
            'user_id.required' => 'The user id field is required.',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), []);
        }
        if (!empty($request->user_id)) {
            $user = User::find($request->user_id);

            if (!is_null($user)) {
                Advertisement::where('user_id', $request->user_id)->delete();
                Artist::where('user_id', $request->user_id)->delete();
                Business::where('user_id', $request->user_id)->delete();
                Education::where('user_id', $request->user_id)->delete();
                Experience::where('user_id', $request->user_id)->delete();
                FranchiseBusiness::where('user_id', $request->user_id)->delete();
                Job::where('user_id', $request->user_id)->delete();
                JobApply::where('user_id', $request->user_id)->delete();
                OnDemandService::where('user_id', $request->user_id)->delete();
                Payment::where('user_id', $request->user_id)->delete();
                Product::where('user_id', $request->user_id)->delete();
                ProductRating::where('user_id', $request->user_id)->delete();
                Property::where('user_id', $request->user_id)->delete();
                Tourism::where('user_id', $request->user_id)->delete();
                WholeSellProduct::where('user_id', $request->user_id)->delete();

                $user->delete();

                return $this->sendResponse([], 'User Account deleted successfully.');
            } else {
                return $this->sendResponse([], 'User Account not found!.');
            }
        }
    }
}
