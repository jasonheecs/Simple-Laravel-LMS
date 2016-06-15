<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Role;
use App\ImageUploader;
use Storage;

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
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        if ($request->has('isSuperAdmin') && $request->isSuperAdmin == 'on') {
            $user->addRole(Role::getSuperAdminRole());
        }
        if ($request->has('isAdmin') && $request->isAdmin == 'on') {
            $user->addRole(Role::getAdminRole());
        }

        flash('User created', 'success');

        return redirect()->route('users.show', $user->id);
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
     * Handles uploading of avatar image file.
     * If $user_id is 0, means that the user model is a temporary one (most likely one made during the create() view before saving the model)
     * If user model is temporary ($user_id = 0), upload the file to a temporary directory first.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user_id
     * @return JSON   JSON response
     */
    public function upload(Request $request, $user_id)
    {
        $imageUploader = new ImageUploader($request);
        $file = $imageUploader->getFile();

        if ($user_id == 0) {
            $response = $this->uploadToTmp($file, $imageUploader);
        } else {
            $filename = 'user_' . $user_id;
            $uploadedFile = $imageUploader->upload($filename, public_path('/uploads/users/'), 150, 150, true);

            if ($uploadedFile) {
                $user = User::find($user_id);
                $user->setAvatar(url('/uploads/users/'. $uploadedFile));

                $response = ImageUploader::formatResponse('/uploads/users/'. $uploadedFile);
            } else {
                echo 'Image Upload Error!';
                $response = ImageUploader::formatResponse('/uploads/error.png');
            }
        }

        return json_encode($response);
    }

    /**
     * Upload file to the Users tmp directory
     * @param  [type] $file          [description]
     * @param  \App\ImageUploader $imageUploader
     * @return [Array]            Response Array containing the directory path of the uploaded file
     */
    private function uploadToTmp($file, $imageUploader)
    {
        // create tmp directory if it does not exist
        $tmp_dir = '/public/tmp/user';
        if (!file_exists(storage_path('app/' . $tmp_dir))) {
            $directory = Storage::makeDirectory($tmp_dir, 0775, true);
        }

        $filename = time().'-'.'user_' . generate_random_str(20);
        $uploadedFile = $imageUploader->upload($filename, $tmp_dir, 150, 150, true);

        if ($uploadedFile) {
            $response = ImageUploader::formatResponse('/uploads/users/tmp/'. $uploadedFile);
        } else {
            echo 'Image Upload Error!';
            $response = ImageUploader::formatResponse('/uploads/error.png');
        }

        return $response;
    }
}
