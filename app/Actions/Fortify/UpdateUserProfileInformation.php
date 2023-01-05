<?php

namespace App\Actions\Fortify;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Carbon\Carbon;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        $messages = []; //empty to use the default messages
        $attributes = [
            'usr_apellidos' => 'Apellidos',
            'usr_num_documento' => 'Número de documento de identidad',
        ];
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'usr_apellidos' => ['required', 'string', 'max:255'],
            'usr_num_documento' => ['required', 'string', 'max:8', Rule::unique('users')->ignore($user->id)],
            'usr_fecha_nacimiento' => ['nullable', 'before_or_equal:'.Carbon::now('-05:00')->subYears(18)->format('Y-m-d')],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ], $messages, $attributes)->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
                'usr_apellidos' => $input['usr_apellidos'],
                'usr_num_documento' => $input['usr_num_documento'],
                'usr_fecha_nacimiento' => $input['usr_fecha_nacimiento'],
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    protected function updateVerifiedUser($user, array $input)
    {
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
            'usr_apellidos' => $input['usr_apellidos'],
            'usr_num_documento' => $input['usr_num_documento'],
            'usr_fecha_nacimiento' => $input['usr_fecha_nacimiento'],
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
