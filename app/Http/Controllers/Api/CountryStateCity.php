<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class CountryStateCity extends BaseController
{
    public function country(Request $request)
    {
        $country = Country::where('status', 1)
        ->orderByRaw('name COLLATE utf8mb4_unicode_ci')
        ->get();
        return $this->sendResponse($country, 'Country retrieved successfully.');
    }

    public function state(Request $request)
    {
        $country = Country::where('id', $request->countries_id)->where('status', 1)->first();
        if (!is_null($country)) {
            $state = State::select('states.id', 'states.name', 'states.status', 'states.created_at', 'states.updated_at', 'countries.name as country_name')
                            ->join('countries', 'states.countries_id', '=', 'countries.id')
                            ->where('states.status', 1)
                            ->where('countries.status', 1)
                            ->where('states.countries_id', $request->countries_id)
                            ->orderByRaw('states.name COLLATE utf8mb4_unicode_ci')
                            ->get();

            return $this->sendResponse($state, 'state created successfully.');
        }else{
            return $this->sendError('Country not found.');
        }

    }

    public function city(Request $request)
    {
        $state = State::where('id', $request->states_id)->where('status', 1)->first();
        if (!is_null($state)) {

            $city = City::select('cities.id', 'cities.name', 'cities.status', 'cities.created_at', 'cities.updated_at', 'states.name as state_name')
                            ->join('states', 'cities.states_id', '=', 'states.id')
                            ->where('cities.status', 1)
                            ->where('states.status', 1)
                            ->where('cities.states_id', $request->states_id)
                            ->orderByRaw('cities.name COLLATE utf8mb4_unicode_ci')
                            ->get();

            return $this->sendResponse($city, 'City created successfully.');
        }else{
            return $this->sendError('state not found.');
        }
    }
}
