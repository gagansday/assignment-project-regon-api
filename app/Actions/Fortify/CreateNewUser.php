<?php

namespace App\Actions\Fortify;

use App\Helpers\GusRegonApi;
use App\Mail\NeedVerificationMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Mail;

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
        $company = $this->validate($input);

        $user = User::create([
            'email' => $input['email'],
            'nip_number' =>  $input['nip_number'],
            'password' => Hash::make($input['password']),
        ]);

        $user->company()->create($company);

        return $user;
    }

    public function validate($input)
    {
        Validator::make($input, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'nip_number' => ['required', 'string', 'min:10', 'max:10'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();

        $company = (new GusRegonApi())->searchNIP($input['nip_number']);

        if (!$company || !count(array_filter($company['pkd'], function ($pdk) {
            return $pdk['code'] === '6920Z';
        })))
            $this->registrationFailed($input);

        return $company;
    }
    public function registrationFailed($input)
    {
        $this->sendMailToOffice($input);
        $this->customValidationError();
    }

    public function sendMailToOffice($input)
    {
        Mail::to('gagansday@gmail.com')->send(new NeedVerificationMail($input));
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
