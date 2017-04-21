<?php

namespace WebModularity\LaravelCms\Http\Controllers;

use Illuminate\View\View;
use WebModularity\LaravelCms\DataTables\UserLogDataTable;
use WebModularity\LaravelUser\LogUser;

class UserLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index(UserLogDataTable $userLogDataTable)
    {
        return $userLogDataTable->render('wmcms::user-log.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  LogUser  $logUser
     * @return View
     */
    public function show(LogUser $logUser)
    {
        dd($logUser);
        $recentUserLogs = LogUser::where('user_id', $logUser->user_id)
            ->orderBy('log_users.created_at', 'desc')
            ->limit(10)
            ->get();
        return view('wmcms::user-log.show')->with('logUser', $logUser)->with('userLogs', $recentUserLogs);
    }
}
