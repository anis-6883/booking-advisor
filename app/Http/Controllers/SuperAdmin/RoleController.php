<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Auth::guard('super_admin')->check()) {

            $roles = Role::orderBy('id', 'DESC')->get();

            if($request->ajax()) {
                return DataTables::of($roles)
                ->addColumn('created_at', function($role) {
                    return date("M d, Y H:i:s", strtotime($role->created_at));
                })
                ->addColumn('action', function($role) {
                    return '<div class="dropdown">
                                <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="' . route('super_admin.roles.edit', $role->id) . '" class="dropdown-item">
                                            <i class="fas fa-edit"></i>
                                                Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="' . route('super_admin.roles.destroy', $role->id) . '" method="post" class="ajax-delete">'
                                            . csrf_field() 
                                            . method_field('DELETE') 
                                            . '<button type="button" class="btn-remove dropdown-item">
                                                    <i class="fas fa-trash-alt"></i>
                                                        Delete
                                                </button>
                                        </form>
                                    </li>
                                </ul>
                        </div>';
                })
                ->rawColumns(['created_at', 'action'])
                ->make(true);
            }
            return view('super_admins.roles.index');
        }
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $all_permissions = Permission::all();
        $permission_groups = Permission::select('group_name')->distinct()->get();
        return view("super_admins.roles.create", compact('permission_groups', 'all_permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'required|string|max:127|unique:roles,name',
            'permissions' => 'required',
            'permissions.*' => 'required',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        $role = new Role();
        $role->name = $request->role_name;
        $role->save();

        for ($i=0; $i < count($request->permissions); $i++) { 
            $role_permission = new RolePermission();
            $role_permission->role_id = $role->id;
            $role_permission->permission_id = $request->permissions[$i];
            $role_permission->save();
        }

        DB::commit();

        return redirect('super-admin/roles')->with('success', 'Information has been added sucessfully!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permission_groups = Permission::select('group_name')->distinct()->get();
        $all_permissions = Permission::all();
        return view("super_admins.roles.edit", compact('role', 'permission_groups', 'all_permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // 'role_name' => [
            //     'required',
            //     'string',
            //     'max:127',
            //     Rule::unique('roles', 'name')->ignore($id),
            // ],
            'role_name' => 'required|string|unique:roles,name,' . $id,
            'permissions' => 'required',
            'permissions.*' => 'required',
        ]);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        $role = Role::findOrFail($id);
        $role->name = $request->role_name;
        $role->save();

        RolePermission::where('role_id', $id)->delete();
        
        for ($i=0; $i < count($request->permissions); $i++) { 
            $role_permission = new RolePermission();
            $role_permission->role_id = $role->id;
            $role_permission->permission_id = $request->permissions[$i];
            $role_permission->save();
        }

        DB::commit();

        return redirect('super-admin/roles')->with('success', 'Information has been updated sucessfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $permission = Role::findOrFail($id);
        RolePermission::where('role_id', $id)->delete();
        $permission->delete();

        if (!$request->ajax()) {
            return back()->with('success', 'Information has been deleted!');
        } else {
            return response()->json(['result' => 'success', 'message' => 'Information has been deleted sucessfully']);
        }
    }
}
