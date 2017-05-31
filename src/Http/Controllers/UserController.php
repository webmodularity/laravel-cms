<?php

namespace WebModularity\LaravelCms\Http\Controllers;

use Illuminate\Http\JsonResponse;
use WebModularity\LaravelCms\Http\Requests\StoreUserSocialLogin;
use WebModularity\LaravelContact\Address;
use WebModularity\LaravelContact\Person;
use WebModularity\LaravelContact\Phone;
use WebModularity\LaravelCms\DataTables\UserDataTable;
use WebModularity\LaravelCms\DataTables\UserRecycleDataTable;
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
     * @return \Illuminate\Http\Response
     */
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('wmcms::users.index');
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
        if ($user->delete() && $user->person->delete()) {
            return $this->sendJsonSuccessResponse("You have successfully deleted " . $user->person->email . ".");
        } else {
            return $this->sendJsonFailureResponse("Failed to delete " . $user->person->email . ".");
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

    /**
     * Display trashed listings.
     *
     * @return \Illuminate\Http\Response
     */
    public function recycle(UserRecycleDataTable $dataTable)
    {
        return $dataTable->render('wmcms::users.recycle');
    }

    /**
     * Un-delete the specified resource from the recycle bin.
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function restore($id)
    {
        $user = User::onlyTrashed()->find($id);
        if (!is_null($user) && $user->restore()) {
            return $this->sendJsonSuccessResponse("You have successfully restored " . $user->person->email . ".");
        } else {
            return $this->sendJsonFailureResponse();
        }
    }

    /**
     * Permanently delete the specified resource.
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function permaDelete($id)
    {
        $person = Person::onlyTrashed()->findOrFail($id);
        if ($this->deleteChecks($person) !== false) {
            return $this->deleteChecks($person);
        }
        if ($person->forceDelete()) {
            return $this->sendJsonSuccessResponse("You have permanently deleted " . $person->email . ".");
        } else {
            $this->sendJsonFailureResponse();
        }
    }

    protected function deleteChecks($person)
    {
        // User
        if (!is_null($person->user)) {
            return $this->sendJsonFailureResponse($person->email . ' is associated with one or more '
                . 'User Profiles. Please remove the associated User before deleting.');
        }

        return false;
    }
}
