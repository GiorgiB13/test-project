<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetUsersByCountryRequest;
use App\Models\Company;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CountryController extends Controller
{
    public function users(GetUsersByCountryRequest $request)
    {
        $country = Country::where('name', $request->get('country_name'))->first();

        if (!$country) {
            return response(['message' => 'Country not found'], 404);
        }

        $companies = $country->companies()->pluck('id');

        $users = DB::table('company_user')->whereIn('company_id', $companies)->pluck('user_id')->all();

        if (empty($users)) {
            return response(['message' => 'No users in this country'], 404);
        }
        $users = User::whereIn('id', $users)->get();
        $data = $users->map(function ($user) {
            return [
                'name' => $user->name,
                'companies' => $user->companies->map(function ($item) {
                    return [
                        'name' => $item->name,
                        'date_at' => $item->pivot->date_at
                    ];
                })
            ];
        });

        return response(['users' => $data], 200);
    }

}
