<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserAdminController extends Controller
{

    const FOLDER = 'admin.user_admin';
    const URL = 'user-admin';
    const TITLE = 'User Admin';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $pageTitle = 'User Admin';

        $adminLogin = auth()->guard('admin')->user();
        $admins = Admin::where('id', '!=', $adminLogin->id)->latest()->get();

        return view(self::FOLDER . '.index', compact('pageTitle', 'admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $pageTitle = 'User Admin';
        $roles = Role::get();
        
        return view(self::FOLDER . '.create', compact('pageTitle', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdminRequest $request)
    {
        //
        $input = $request->all();

        $role = $request->role;

        unset($input['role']);

        $admin = Admin::create($input);

        $admin->assignRole($role);

        return redirect()->route('admin.user-admin.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin, $id)
    {
        //
        $pageTitle = 'User Admin';

        $admin = Admin::findOrFail($id);
        $roles = Role::get();
        
        return view(self::FOLDER . '.edit', compact('pageTitle', 'admin', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdminRequest $request, Admin $admin, $id)
    {
        //
        $input = $request->all();

        $admin = Admin::findOrFail($id);

        $admin->syncRoles($request->role);

        unset($input['role']);
        
        $admin->update($input);

        return redirect()->route('admin.user-admin.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
    }
}
