<?php

namespace WebModularity\LaravelCms\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use WebModularity\LaravelCms\DataTables\LogUserDataTable;
use WebModularity\LaravelUser\LogUser;

class LogUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index(LogUserDataTable $logUserDataTable)
    {
        return $logUserDataTable->render('wmcms::log-user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  LogUser  $logUser
     * @return JsonResponse
     */
    public function show(LogUser $logUser)
    {
        return response()->json([
            'createdAt' => $logUser->created_at->format('m/d/Y h:i:sa'),
            $logUser->userAction->slug
        ]);
    }
}
