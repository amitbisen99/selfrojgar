<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ImageUpload;
use Illuminate\Support\Facades\Validator;
use App\Models\City;
use App\Models\State;
use App\Models\Country;

class ProfileController extends BaseController
{
    public function profileByUser($id)
    {
        $user = User::find($id);

        $city = City::where('id', $user->city)->value('name') ?? NULL;
        $country = Country::where('id', $user->country)->value('name') ?? NULL;
        $state = State::where('id', $user->state)->value('name') ?? NULL;

        unset($user->city, $user->country, $user->state);

        $user['city_name'] = $city;
        $user['country_name'] = $country;
        $user['state_name'] = $state;

        if (!is_null($user)) {
            return $this->sendResponse($user, 'User retrieved successfully.');
        }else{
            return $this->sendError('User not found.');
        }
    }

    public function profileUpdate(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email, '.$id,
        ], [
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
        ]);
   
        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), []);
        }

        if (isset($input['profile_pic']) && !empty($input['profile_pic'])) {
            $input['profile_pic'] = ImageUpload::upload('uploads/profile/', $request->profile_pic);
        }
        
        $user = User::find($id);

        if (!is_null($user)) {
            $user->update($input);
            $city = City::where('id', $user->city)->value('name') ?? NULL;
            $country = Country::where('id', $user->country)->value('name') ?? NULL;
            $state = State::where('id', $user->state)->value('name') ?? NULL;

            unset($user->city, $user->country, $user->state);

            $user['city_name'] = $city;
            $user['country_name'] = $country;
            $user['state_name'] = $state;
            return $this->sendResponse($user, 'Profile Updated successfully.');
        }else{
            return $this->sendError('User not found.');
        }
   
    }
}
