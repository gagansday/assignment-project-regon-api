<?php

namespace App\Actions\Fortify;

use App\Helpers\GusRegonApi;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'nip_number' => ['required', 'string', 'min:10', 'max:10'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();

        $nipNumber = $input['nip_number'];

        $company = (new GusRegonApi())->searchNIP($nipNumber);

        if (!$company) $this->customValidationError();

        $user = User::create([
            'email' => $input['email'],
            'nip_number' => $nipNumber,
            'password' => Hash::make($input['password']),
        ]);

        $user->company()->create($company);

        return $user;
    }

    public function customValidationError()
    {
        Validator::make(
            [],
            ['nip_number' => 'required'],
            ['required' => 'Verification is ongoing. We will contact your office to confirm the data.',]
        )->validate();
    }
}
