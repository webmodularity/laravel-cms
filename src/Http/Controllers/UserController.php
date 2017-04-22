<?php

namespace WebModularity\LaravelCms\Http\Controllers;

/**
use App\Equipment\EquipmentRequest;
use App\Http\Requests\StorePerson;
use WebModularity\LaravelContact\Person;
use App\Branch;
use App\Http\Requests\StoreBranch;
use Illuminate\Http\JsonResponse;
**/
use WebModularity\LaravelContact\Address;
use WebModularity\LaravelContact\Phone;
use WebModularity\LaravelCms\DataTables\UserDataTable;
//use WebModularity\LaravelCms\DataTables\PersonRecycleDataTable;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('wmcms::users.index');
    }
}
