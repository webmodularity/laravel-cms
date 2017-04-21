<?php

namespace WebModularity\LaravelCms\Http\Controllers;

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
     * @return View
     */
    public function show(LogUser $logUser)
    {
        $recentUserLogs = LogUser::where('user_id', $logUser->user_id)
            ->orderBy('log_users.created_at', 'desc')
            ->limit(10)
            ->get();
        return view('wmcms::log-user.show')->with('logUser', $logUser)->with('userLogs', $recentUserLogs);
    }
}
