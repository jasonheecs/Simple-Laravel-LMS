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

    /**
     * Generates the right unauthorized response / redirect based on the request type (ajax or not)
     * @param  \Illuminate\Http\RedirectResponse $redirect - redirect action
     * @param  \Illuminate\Http\Request $request - optional request, default is null
     * @param  string $message - message to be flashed to status
     * @return \Illuminate\Http\RedirectResponse  redirect action
     */
    protected function unauthorizedResponse($redirect, $request = null, $message = 'You do not have permission to access this page') {
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
