<?php

namespace WebModularity\LaravelCms\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function setCollapseSidebar()
    {
        $toggleState = (bool) request('state', false);
        session(['collapse-sidebar' => $toggleState]);
        return response()->json();
    }

    protected function sendJsonFailureResponse($error = null)
    {
        return response()->json($error, 422);
    }

    protected function sendJsonSuccessResponse($successMessage = '')
    {
        return response()->json($successMessage);
    }
}
