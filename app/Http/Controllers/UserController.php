<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.index', [
            'users' => User::all(),
            'roles' => Role::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'required',
            'email'  => 'required|email'
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        if ($request->ajax()) {
            return response()->json(['response' => 'User Updated']);
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        flash('User deleted', 'success');

        return redirect()->route('users.index');
    }

    /**
     * Sets whether the user is an administrator / super admin
     * @param Request \Illuminate\Http\Request  $request
     * @param User    \App\User  $user
     */
    public function setAdminStatus(Request $request, User $user)
    {
        $status = '';

        if ($request->isSuperAdmin) {
            $user->toggleRole(Role::getSuperAdminRole());
            $status = $user->is('superadmin') ? 'User is now a Super Administrator' : 'User is no longer a Super Administrator';
        } else {
            $user->toggleRole(Role::getAdminRole());
            $status = $user->is('admin') ? 'User is now an Administrator' : 'User is no longer an Administrator';
        }

        if ($request->ajax()) {
            return response()->json(['response' => $status]);
        }

        return back();
    }

    /**
     * Handles uploading of avatar image file
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user_id
     * @return JSON   JSON response
     */
    public function upload(Request $request, $user_id)
    {
        $imageUploader = new \App\ImageUploader($request);
        $file = $imageUploader->getFile();
        $filename = 'user_' . $user_id . '.' . $file->guessExtension();

        $upload_success = $imageUploader->upload($filename, public_path() . '/uploads/users/', 150, 150, true);

        if ($upload_success) {
            $user = User::find($user_id);
            $user->setAvatar(url('/uploads/users/'. $filename));

            $response = ['files' => [['url' => url('/uploads/users/'. $filename)]]];
        } else {
            echo 'Image Upload Error!';
            $response = ['files' => [['url' => url('/uploads/error.png')]]];
        }

        return json_encode($response);
    }
}
