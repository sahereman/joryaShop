<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class RegisterSmsCodeSendableRule implements Rule
{
    protected $country_code = 86;

    /**
     * Create a new rule instance.
     * @param integer $country_code
     * @return void
     */
    public function __construct($country_code)
    {
        $this->country_code = $country_code;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !Cache::has('register_sms_code_sent-' . $this->country_code . '-' . $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('basic.users.Sms_verification_code_sent_already');
    }
}
