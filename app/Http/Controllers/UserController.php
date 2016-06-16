<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Role;
use App\ImageUploader;

use Intervention\Image\ImageManagerStatic as Image;

class UserController extends Controller
{
    /**
     * Display a listing of Users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        $users->load('roles');

        return view('users.index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created User in storage.
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

        if ($request->has('avatar')) {
            $this->transferTmpAvatar($request->avatar, $user);
        }
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
     * Display the specified User.
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
     * Update the specified User in storage.
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
     * Remove the specified User from storage.
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
        $imageUploader = new ImageUploader($request->file('files')[0]);

        if ($user_id == 0) {
            $response = $this->uploadToTmp($imageUploader);
        } else {
            $filename = 'user_' . $user_id;
            $uploadedFile = $imageUploader->upload($filename, public_path(config('constants.upload_dir.users')), 150, 150, true);

            if ($uploadedFile) {
                $user = User::find($user_id);
                $user->setAvatar(url(config('constants.upload_dir.users'). $uploadedFile));

                $response = ImageUploader::formatResponse(url(config('constants.upload_dir.users'). $uploadedFile));
            } else {
                echo 'Image Upload Error!';
                $response = ImageUploader::getErrorResponse();
            }
        }

        return json_encode($response);
    }

    /**
     * Upload file to the Users tmp directory
     * @param  \Illuminate\Http\UploadedFile $file
     * @param  \App\ImageUploader $imageUploader
     * @return [Array]            Response Array containing the directory path of the uploaded file
     */
    private function uploadToTmp($imageUploader)
    {
        $filename = time().'-'.'user_' . generate_random_str(20);
        $uploadedFile = $imageUploader->upload($filename, public_path(config('constants.upload_dir.tmp')), 150, 150, true);

        if ($uploadedFile) {
            $response = ImageUploader::formatResponse(url(config('constants.upload_dir.tmp') . $uploadedFile));
        } else {
            echo 'Image Upload Error!';
            $response = ImageUploader::getErrorResponse();
        }

        return $response;
    }

    private function transferTmpAvatar($imageUrl, User $user)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$imageUrl); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);

        $imagedata = imagecreatefromstring($data);

        if ($imagedata !== false) {
            $imageUploader = new ImageUploader($imagedata);
            $filename = 'user_' . $user->id;
            $uploadedFile = $imageUploader->upload($filename, public_path(config('constants.upload_dir.users')), 0, 0, false, true);

            if ($uploadedFile) {
                $user->avatar = url(config('constants.upload_dir.users'). $uploadedFile);
                $user->save();

                // delete tmp avatar file
                \File::delete(public_path(config('constants.upload_dir.tmp')) . basename($imageUrl));
            }

            imagedestroy($imagedata);
        }
    }
}
