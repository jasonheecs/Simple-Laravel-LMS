<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    protected function unauthorizedResponse($redirect, $message = 'You do not have permission to access this page', $request = null) {
        if ($request && $request->ajax()) {
            return response()->json(['response' => $message], 401);
        } else {
            if (strlen($message)) {
                flash($message, 'danger');
            }
        }

        return $redirect;
    }
}
