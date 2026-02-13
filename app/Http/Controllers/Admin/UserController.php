<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DataTables;
use Carbon\Carbon;

class UserController extends AdminThemeController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data  = User::select('users.*', 'cities.name as city_name', 'states.name as state_name', 'countries.name as country_name')
                ->leftJoin('cities', 'users.city', '=', 'cities.id')
                ->leftJoin('states', 'users.state', '=', 'states.id')
                ->leftJoin('countries', 'users.country', '=', 'countries.id')->orderBy('id', 'DESC');
            return Datatables::of($data)
                ->addIndexColumn()
                ->filterColumn('city_name', function ($query, $keyword) {
                    $query->where('cities.name', 'like', "%{$keyword}%");
                })
                ->filterColumn('state_name', function ($query, $keyword) {
                    $query->where('states.name', 'like', "%{$keyword}%");
                })
                ->filterColumn('contact_number', function ($query, $keyword) {
                    $query->where('users.contact_number', 'like', "%{$keyword}%");
                })
                ->filterColumn('name', function ($query, $keyword) {
                    $query->where('users.name', 'like', "%{$keyword}%");
                })
                ->filterColumn('email', function ($query, $keyword) {
                    $query->where('users.email', 'like', "%{$keyword}%");
                })
                ->addColumn('action', function ($data) {
                    // $action = '<a href="'.route('user.create').'" class="btn btn-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Create"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                    $action = '<a href="' . route('user.show', $data->id) . '" class="btn btn-info btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Create"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                    return $action;
                })
                ->editColumn('status', function ($data) {

                    if ($data->status == 1) {
                        $switch = '<div class="row">
                                        <div class="col-4 p-0">Inactive</div>
                                        <div class="col-3 p-0"><div class="form-check form-switch"><input class="form-check-input status-switch" type="checkbox" checked value="1" data-action="' . route("user.status") . '" data-id="' . $data->id . '"></div></div>
                                        <div class="col-3 p-0">Active</div>
                                    </div>';
                    } else {
                        $switch = '<div class="row">
                                        <div class="col-4 p-0">Inactive</div>
                                        <div class="col-3 p-0"><div class="form-check form-switch"><input class="form-check-input status-switch" type="checkbox" data-action="' . route("user.status") . '" data-id="' . $data->id . '"></div></div>
                                        <div class="col-3 p-0">Active</div>
                                    </div>';
                    }

                    return $switch;
                })
                ->editColumn('created_at', function ($data) {
                    $created_at = Carbon::parse($data->created_at)->format('d/m/Y');
                    return $created_at;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'min:8|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'min:8',
        ]);

        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        notificationMsg('success', 'user created sucessfully.');
        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::select('users.*', 'cities.name as city_name', 'states.name as state_name', 'countries.name as country_name')
            ->leftJoin('cities', 'users.city', '=', 'cities.id')
            ->leftJoin('states', 'users.state', '=', 'states.id')
            ->leftJoin('countries', 'users.country', '=', 'countries.id')
            ->where('users.id', $id)
            ->first();

        return view('admin.user.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $input = $request->all();

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email'
        ]);

        if (!empty($input['password'])) {
            $input['password'] = bcrypt($input['password']);
        } else {
            $input['password'] = $user->password;
        }

        // $user->update($input);

        notificationMsg('success', 'user updated sucessfully.');
        return redirect()->route('user.index');
    }

    public function statusUpdate(Request $request)
    {
        $user = User::find($request->id);
        if (!is_null($user)) {
            $user->update(['status' => $request->status]);
        }

        $status = $request->status == 1 ? 'activated' : 'inactivated';
        notificationMsg('success', 'user ' . $status . ' sucessfully.');

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        notificationMsg('success', 'user removed sucessfully.');
        return redirect()->route('user.index');
    }
}
