<?php

namespace WebModularity\LaravelCms\Http\Controllers;

use Illuminate\Http\JsonResponse;
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
            'userAction' => $logUser->userAction->slug,
            'requestMethod' => $logUser->logRequest->requestMethod->method,
            'urlPath' => $logUser->logRequest->urlPath->url_path,
            'queryString' => isset($logUser->logRequest->queryString)
                ? $logUser->logRequest->queryString->query_string : null,
            'isAjax' => $logUser->logRequest->is_ajax ? 'Yes' : 'No',
            'socialProvider' => $logUser->socialProvider->getName(),
            'sessionId' => $logUser->logRequest->session_id,
            'ipAddress' => $logUser->logRequest->ipAddress->ip,
            'user' => $logUser->user->person->email,
            'userAgent' => $logUser->logRequest->userAgent->user_agent
        ]);
    }
}
