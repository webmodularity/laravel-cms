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
use WebModularity\LaravelCms\DataTables\UserRecycleDataTable;
use WebModularity\LaravelUser\User;

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
        return view('users.create', ['recentlyAdded' => User::orderBy('id', 'desc')->limit(10)->get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePerson  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePerson $request)
    {
        $person = Person::create(request(['email', 'first_name', 'middle_name', 'last_name']));
        // Address
        $this->syncPrimaryAddress($person);
        // Phones
        $this->syncPhones($person);
        session()->flash('success', "You have created ".$person->email.".");
        return redirect()->route('people.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Person  $person
     * @return \Illuminate\Http\Response
     */
    public function edit(Person $person)
    {
        $person->load([
            'addresses',
            'phones',
            'userInvitations',
            'userInvitations.socialProvider',
            'userInvitations.role'
        ]);
        $primaryAddress = $person->addresses->first(function ($value, $key) {
            return $value->pivot->address_type_id === Address::TYPE_PRIMARY;
        });
        $phones = [
            'mobile' => $person->phones->first(function ($value, $key) {
                return $value->pivot->phone_type_id === Phone::TYPE_MOBILE;
            }),
            'office' => $person->phones->first(function ($value, $key) {
                return $value->pivot->phone_type_id === Phone::TYPE_OFFICE;
            }),
            'fax' => $person->phones->first(function ($value, $key) {
                return $value->pivot->phone_type_id === Phone::TYPE_FAX;
            })
        ];
        $equipmentRequests = EquipmentRequest::where('person_id', $person->id)->get();

        return view('people.edit', compact(['person', 'primaryAddress', 'phones', 'equipmentRequests']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StorePerson  $request
     * @param  Person  $person
     * @return \Illuminate\Http\Response
     */
    public function update(StorePerson $request, Person $person)
    {
        $person->update(request(['email', 'first_name', 'middle_name', 'last_name']));
        // Address
        $this->syncPrimaryAddress($person);
        // Phones
        $this->syncPhones($person);
        session()->flash('success', "You have updated ".$person->email.".");
        return redirect()->route('people.edit', ['id' => $person->id]);
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

    protected function syncPrimaryAddress($person)
    {
        $addressInput = (array) request('address');
        $addressId = [];
        if (isset($addressInput['street']) && !empty($addressInput['street'])) {
            $address = Address::firstOrCreate($addressInput);
            if (!is_null($address)) {
                $addressId[$address->id] = ['address_type_id' => Address::TYPE_PRIMARY];
            }
        }
        $person->addresses()->sync($addressId);
    }

    protected function syncPhones(Person $person)
    {
        $phoneIds = [];
        foreach ((array) request('phones') as $phoneKey => $phoneValue) {
            if (!is_null(Phone::splitFull($phoneValue))) {
                $phone = Phone::firstOrCreate(Phone::splitFull($phoneValue));
                if (!is_null($phone)) {
                    $phoneIds[$phone->id] = [
                        'phone_type_id' => constant(Phone::class . '::TYPE_' . strtoupper($phoneKey))
                    ];
                }
            }
        }
        $person->phones()->sync($phoneIds);
    }
}
