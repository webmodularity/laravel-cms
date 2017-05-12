<?php

namespace WebModularity\LaravelCms\Http\Controllers;

/**
use App\Equipment\EquipmentRequest;
use WebModularity\LaravelContact\Person;
use App\Branch;
use App\Http\Requests\StoreBranch;
use Illuminate\Http\JsonResponse;
**/
use WebModularity\LaravelContact\Address;
use WebModularity\LaravelContact\Person;
use WebModularity\LaravelContact\Phone;
use WebModularity\LaravelCms\DataTables\UserDataTable;
use WebModularity\LaravelCms\DataTables\UserRecycleDataTable;
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
     * Display trashed listings.
     *
     * @return \Illuminate\Http\Response
     */
    public function recycle(UserRecycleDataTable $dataTable)
    {
        return $dataTable->render('wmcms::users.recycle');
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
     * @param  Person  $person
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

        return view('wmcms::users.edit', compact(['user', 'primaryAddress', 'phones']));
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
     * @param  Person $person
     * @return JsonResponse
     */
    public function destroy(Person $person)
    {
        if ($this->deleteChecks($person) !== false) {
            return $this->deleteChecks($person);
        }

        if ($person->delete()) {
            return $this->sendJsonSuccessResponse("You have successfully deleted " . $person->email . ".");
        } else {
            return $this->sendJsonFailureResponse();
        }
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
             removed from " . $user->person->email . " user account.");
        }

        $this->sendJsonFailureResponse('Failed to unlink Social Login.');
    }

    /**
     * Detach specified social login from User
     *
     * @param  int $userId
     * @return JsonResponse
     */
    public function attachSocialLogin($userId)
    {
        $user = User::find($userId);
        return $this->sendJsonSuccessResponse($user->id);
        $socialProvider = UserSocialProvider::find($id);
        if (!is_null($user) && !is_null($socialProvider) && $user->socialProviders()->detach($socialProvider) > 0) {
            return $this->sendJsonSuccessResponse("".$socialProvider->getName()." social login has been 
             removed from " . $user->person->email . " user account.");
        }

        $this->sendJsonFailureResponse('Failed to unlink Social Login.');
    }

    /**
     * Un-delete the specified resource from the recycle bin.
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function restore($id)
    {
        $person = Person::onlyTrashed()->findOrFail($id);
        if ($person->restore()) {
            return $this->sendJsonSuccessResponse("You have successfully restored " . $person->email . ".");
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
        // User Invitations
        if ($person->userInvitations->count() > 0) {
            return $this->sendJsonFailureResponse(
                $person->email
                . ' is associated with (' . $person->userInvitations->count()
                . ') User Invitation(s). Please remove those invitation(s) before deleting.'
            );
        }
        // User
        if (!is_null($person->user)) {
            return $this->sendJsonFailureResponse(
                $person->email
                . ' is associated with one or more User Profiles. Please remove that user before deleting.'
            );
        }

        return false;
    }
}
