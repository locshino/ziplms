<?php

return [
    /*
     * The name of the database table that will be used to store one-time passwords.
     * You can change this if you want to use a different table name.
     */
    'table_name' => 'one_time_passwords',

    /*
     * one-time passwords should be consumed within this number of minutes
     */
    'default_expires_in_minutes' => (int) env('OTP_EXPIRES_IN_MINUTES', 2),

    /*
     * When this setting is active, we'll delete all previous one-time passwords for
     * a user when generating a new one
     */
    'only_one_active_one_time_password_per_user' => env('OTP_ONLY_ONE_ACTIVE_PER_USER', true),

    /*
     * When this option is active, we'll try to ensure that the one-time password can only
     * be consumed on the platform where it was requested on
     */
    'enforce_same_origin' => env('OTP_ENFORCE_SAME_ORIGIN', true),

    /*
     * This class is responsible to enforce that the one-time password can only be consumed on
     * the platform it was requested on.
     *
     * If you do not wish to enforce this, set OTP_ENFORCE_SAME_ORIGIN to false or this value to
     * Spatie\OneTimePasswords\Support\OriginInspector\DoNotEnforceOrigin
     */
    'origin_enforcer' => Spatie\OneTimePasswords\Support\OriginInspector\DefaultOriginEnforcer::class,

    /*
     * This class generates a random password
     */
    'password_generator' => Spatie\OneTimePasswords\Support\PasswordGenerators\NumericOneTimePasswordGenerator::class,

    /*
     * By default, the password generator will create a password with
     * this number of digits
     */
    'password_length' => (int) env('OTP_PASSWORD_LENGTH', 6),

    /*
     * The Livewire component will redirect successfully authenticated users
     * to this URL.
     */
    'redirect_successful_authentication_to' => env('OTP_REDIRECT_TO', '/'),

    /*
     * These values are used to rate limit the number of attempts
     * that may be made to consume a one-time password.
     */
    'rate_limit_attempts' => [
        'max_attempts_per_user' => (int) env('OTP_RATE_LIMIT_MAX_ATTEMPTS', 5),
        'time_window_in_seconds' => (int) env('OTP_RATE_LIMIT_TIME_WINDOW', 60),
    ],

    /*
     * The model uses to store one-time passwords
     */
    'model' => Spatie\OneTimePasswords\Models\OneTimePassword::class,

    /*
     * The notification used to send a one-time password to a user
     */
    'notification' => Spatie\OneTimePasswords\Notifications\OneTimePasswordNotification::class,

    /*
     * These class are responsible for performing core tasks regarding one-time passwords.
     * You can customize them by creating a class that extends the default, and
     * by specifying your custom class name here.
     */
    'actions' => [
        'create_one_time_password' => Spatie\OneTimePasswords\Actions\CreateOneTimePasswordAction::class,
        'consume_one_time_password' => Spatie\OneTimePasswords\Actions\ConsumeOneTimePasswordAction::class,
    ],
];
