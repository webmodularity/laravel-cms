<?php

namespace WebModularity\LaravelCms\Http\Controllers;

use Illuminate\Http\JsonResponse;
use WebModularity\LaravelCms\Http\Requests\StoreUserSocialLogin;
use WebModularity\LaravelContact\Address;
use WebModularity\LaravelContact\Person;
use WebModularity\LaravelContact\Phone;
use WebModularity\LaravelCms\DataTables\UserDataTable;
use WebModularity\LaravelUser\LogUser;
use WebModularity\LaravelUser\User;
use WebModularity\LaravelCms\Http\Requests\StoreUser;
use WebModularity\LaravelContact\Http\Controllers\SyncsInputToPerson;
use WebModularity\LaravelUser\UserSocialProvider;

class UserController extends Controller
{
    use SyncsInputToPerson;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('wmcms::users.index');
    }

    /**
     * Display trashed listings.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function recycle(UserDataTable $recycleDataTable)
    {
        $recycleDataTable->recycle = true;
        return $recycleDataTable->before(function ($dataTable) {
            return $dataTable->onlyTrashed();
        })->render('wmcms::users.recycle');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('wmcms::users.create', ['recentlyAdded' => User::orderBy('id', 'desc')->limit(10)->get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreUser  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUser $request)
    {
        $person = Person::firstOrCreate(request(['email']), request(['first_name', 'middle_name', 'last_name']));
        // Address
        $this->syncAddressToPerson($person);
        // Phones
        $this->syncPhonesToPerson($person);
        // User
        $password = !empty(request('password')) ? bcrypt(request('password')) : null;
        $person->user()->create([
            'role_id' => request('role_id'),
            'avatar_url' => request('avatar_url'),
            'password' => $password
        ]);
        session()->flash('success', "You have created ".$person->email.".");
        return redirect()->route('users.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $user->load([
            'person',
            'person.addresses',
            'person.phones'
        ]);
        $primaryAddress = $user->person->addresses->first(function ($value, $key) {
            return $value->pivot->address_type_id === Address::TYPE_PRIMARY;
        });
        $phones = [
            'mobile' => $user->person->phones->first(function ($value, $key) {
                return $value->pivot->phone_type_id === Phone::TYPE_MOBILE;
            }),
            'office' => $user->person->phones->first(function ($value, $key) {
                return $value->pivot->phone_type_id === Phone::TYPE_OFFICE;
            }),
            'fax' => $user->person->phones->first(function ($value, $key) {
                return $value->pivot->phone_type_id === Phone::TYPE_FAX;
            })
        ];
        $userLogs = LogUser::where('user_id', $user->id)
            ->limit(50)
            ->get();
        return view('wmcms::users.edit', compact(['user', 'primaryAddress', 'phones', 'userLogs']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StoreUser  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUser $request, User $user)
    {
        $user->update(request(['role_id', 'avatar_url']));
        $user->person->update(request(['email', 'first_name', 'middle_name', 'last_name']));
        // Address
        $this->syncAddressToPerson($user->person);
        // Phones
        $this->syncPhonesToPerson($user->person);
        session()->flash('success', "You have updated ".$user->person->email.".");
        return redirect()->route('users.edit', ['id' => $user->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return JsonResponse
     */
    public function destroy(User $user)
    {
        if ($user->delete()) {
            return $this->sendJsonSuccessResponse("You have successfully deleted " . $user->person->email . ".");
        } else {
            return $this->sendJsonFailureResponse("Failed to delete " . $user->person->email . ".");
        }
    }

    /**
     * Un-delete the specified resource from the recycle bin.
     *
     * @param  User $recycledUser
     * @return JsonResponse
     */
    public function restore(User $recycledUser)
    {
        if ($recycledUser->restore()) {
            return $this->sendJsonSuccessResponse("You have successfully restored " . $recycledUser->person->email . ".");
        } else {
            return $this->sendJsonFailureResponse("Failed to restore " . $recycledUser->person->email . ".");
        }
    }

    /**
     * Permanently delete the specified resource.
     *
     * @param  User $recycledUser
     * @return JsonResponse
     */
    public function permaDelete(User $recycledUser)
    {
        if ($recycledUser->forceDelete()) {
            return $this->sendJsonSuccessResponse("You have permanently deleted " . $recycledUser->person->email . ".");
        } else {
            return $this->sendJsonFailureResponse("Failed to permanently delete " . $recycledUser->person->email . ".");
        }
    }

    /**
     * Attach specified social login to User
     *
     * @param  int $userId
     * @return JsonResponse
     */
    public function attachSocialLogin($userId, StoreUserSocialLogin $request)
    {
        $user = User::find($userId);
        $socialProvider = UserSocialProvider::find(request('social_provider_id'));
        if (!is_null($user) && !is_null($socialProvider)) {
            $user->socialProviders()->attach($socialProvider, request(['uid', 'email', 'avatar_url']));
            return $this->sendJsonSuccessResponse("".$socialProvider->getName()." social login has been 
             added to " . $user->person->email . ".");
        }

        return $this->sendJsonFailureResponse('Failed to link Social Login.');
    }

    /**
     * Detach specified social login from User
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function detachSocialLogin($userId, $id)
    {
        $user = User::find($userId);
        $socialProvider = UserSocialProvider::find($id);
        if (!is_null($user) && !is_null($socialProvider) && $user->socialProviders()->detach($socialProvider) > 0) {
            return $this->sendJsonSuccessResponse("".$socialProvider->getName()." social login has been 
             removed from " . $user->person->email . ".");
        }

        return $this->sendJsonFailureResponse('Failed to unlink Social Login.');
    }
}
