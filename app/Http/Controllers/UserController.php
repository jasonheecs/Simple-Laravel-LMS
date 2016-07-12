<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Gate;
use App\Http\Requests;
use App\User;
use App\Role;
use App\Uploaders\ImageUploader;
use App\Uploaders\AvatarUploader;

class UserController extends Controller
{
    /**
     * Display a listing of Users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        auth_check('index', User::class);

        $users = User::all();
        $users->load('roles');

        // generate file path to thumbnail version of avatars
        $users = $users->each(function ($user) {
            if ($user->avatar) {
                $path_parts = pathinfo($user->avatar);
                $user->avatar = $path_parts['dirname'] . '/' .$path_parts['filename'] .
                                config('constants.thumbnail_suffix') . '.' . $path_parts['extension'];
            }
        });

        // return index view without caching the page
        return response()->view('users.index', [
            'users' => $users
        ])->header('cache-control', 'no-store,no-cache,must-revalidate')
          ->header('pragma', 'no-cache')
          ->header('expires', '0');
    }

    /**
     * Show the form for creating a new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        auth_check('store', User::class);

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
        auth_check('store', User::class, $request);

        if ($request->has('save')) {
            return $this->createNewUser($request);
        } elseif ($request->has('cancel')) {
            return $this->cancelCreateNewUser($request);
        }
    }

    /**
     * Display the specified User.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        auth_check('index', $user);

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
        auth_check('update', $user, $request);

        $this->validate($request, [
            'name' => 'required',
            'email'  => 'required|email|unique:users,email,' . $user->id
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->company = $request->company;
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
        auth_check('destroy', $user);

        $user->delete();

        flash('User deleted', 'success');

        return redirect()->route('users.index');
    }

    private function createNewUser(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->company = $request->company;

        //  If avatar is specified, check if the avatar is uploaded successfully. If so,
        //  move the avatar from the uploads/tmp dir to the uploads/users dir
        if ($request->has('avatar')) {
            $avatarTmpFile = $this->transferTmpAvatar($request->avatar);
            if ($avatarTmpFile) {
                $user->save(); //save user so that we can get an id

                $filename = 'user_' . $user->id;
                $avatarTmpFilePath = public_path(config('constants.upload_dir.tmp')) . $avatarTmpFile;
                $avatarUploader = new AvatarUploader($avatarTmpFilePath);

                $uploadedFile = $avatarUploader->upload(
                    $filename,
                    public_path(config('constants.upload_dir.users')),
                    150,
                    150,
                    false,
                    true
                );

                $user->setAvatar(url(config('constants.upload_dir.users'). $uploadedFile));
                \File::delete($avatarTmpFilePath);
            }
        } else {
            $user->save();
        }

        if ($request->has('isSuperAdmin') && $request->isSuperAdmin == 'on') {
            $user->addRole(Role::getSuperAdminRole());
        }
        if ($request->has('isAdmin') && $request->isAdmin == 'on') {
            $user->addRole(Role::getAdminRole());
        }

        flash('User created', 'success');

        return redirect()->route('users.index');
    }

    private function cancelCreateNewUser(Request $request)
    {
        // delete any temporary uploaded user avatar image file
        if ($request->has('avatar')) {
            \File::delete(public_path(config('constants.upload_dir.tmp')) . basename($request->avatar));
        }

        return redirect()->route('users.index');
    }

    /**
     * Sets whether the user is an administrator / super admin
     * @param Request \Illuminate\Http\Request  $request
     * @param User    \App\User  $user
     */
    public function setAdminStatus(Request $request, User $user)
    {
        auth_check('setAdminStatus', $user, $request);

        $status = '';

        if ($request->isSuperAdmin) {
            $user->toggleRole(Role::getSuperAdminRole());
            $status = $user->is('superadmin') ?
            'User is now a Super Administrator' : 'User is no longer a Super Administrator';
        } else {
            $user->toggleRole(Role::getAdminRole());
            $status = $user->is('admin') ?
            'User is now an Administrator' : 'User is no longer an Administrator';
        }

        if ($request->ajax()) {
            return response()->json(['response' => $status]);
        }

        return back();
    }

    /**
     * Handles uploading of avatar image file.
     * If $user_id is 0, means that the user model is a temporary one
     * (most likely one made during the create() view before saving the model)
     * If user model is temporary ($user_id = 0), upload the file to a temporary directory first.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $user_id
     * @return JSON   JSON response
     */
    public function upload(Request $request, $user_id)
    {
        auth_check('update', User::class, $request);

        if ($user_id == 0) {
            $imageUploader = new ImageUploader($request->file('files')[0]);
            $response = $this->uploadToTmp($imageUploader);
        } else {
            $avatarUploader = new AvatarUploader($request->file('files')[0]);
            $filename = 'user_' . $user_id;
            $uploadedFile = $avatarUploader->upload(
                $filename,
                public_path(config('constants.upload_dir.users')),
                150,
                150,
                true
            );

            if ($uploadedFile) {
                $user = User::find($user_id);
                $user->setAvatar(url(config('constants.upload_dir.users'). $uploadedFile));

                $response = AvatarUploader::formatResponse(url(config('constants.upload_dir.users'). $uploadedFile));
            } else {
                echo 'Image Upload Error!';
                $response = AvatarUploader::getErrorResponse();
            }
        }

        return json_encode($response);
    }

    /**
     * Upload avatar image file to the Users tmp directory
     * Used as an ajax endpoint for the jQuery file upload plugin
     * @param  \Illuminate\Http\UploadedFile $file
     * @param  \App\Uploaders\ImageUploader $imageUploader
     * @return array          Response Array containing the directory path of the uploaded file
     */
    private function uploadToTmp($imageUploader)
    {
        $filename = time().'-'.'user_' . generate_random_str(20);
        $uploadedFile = $imageUploader->upload(
            $filename,
            public_path(config('constants.upload_dir.tmp')),
            150,
            150,
            true
        );

        if ($uploadedFile) {
            $response = ImageUploader::formatResponse(url(config('constants.upload_dir.tmp') . $uploadedFile));
        } else {
            echo 'Image Upload Error!';
            $response = ImageUploader::getErrorResponse();
        }

        return $response;
    }

    /**
     * Read the avatar image url string during the user creation process and save it as a png file
     * in the uploads/tmp dir.
     * Converts an image from a url (e.g.: api.adorable.io/avatars) into a png file
     * @param  string $imageUrl  Url of the avatar image file
     * @return string/boolean    If image url is valid, returns filename of png file
     *                           If image url is invalid, returns false
     */
    private function transferTmpAvatar($imageUrl)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $imageUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);

        $imagedata = imagecreatefromstring($data);

        if ($imagedata !== false) {
            $imageUploader = new ImageUploader($imagedata);
            $filename = time().'-'.'user_avatar_' . generate_random_str(20);
            $uploadedFile = $imageUploader->upload(
                $filename,
                public_path(config('constants.upload_dir.tmp')),
                0,
                0,
                false,
                true
            );

            if (!$uploadedFile) {
                return false;
            }

            // delete tmp avatar file (if any)
            \File::delete(public_path(config('constants.upload_dir.tmp')) . basename($imageUrl));
            imagedestroy($imagedata);

            return $uploadedFile;
        }

        return false;
    }
}
