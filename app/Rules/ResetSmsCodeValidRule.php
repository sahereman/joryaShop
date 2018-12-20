<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class ResetSmsCodeValidRule implements Rule
{
    protected $is_expired = true;
    protected $country_code = 86;
    protected $phone_number;

    /**
     * Create a new rule instance.
     * @param integer $country_code
     * @param integer $phone_number
     * @return void
     */
    public function __construct($country_code, $phone_number)
    {
        $this->country_code = $country_code;
        $this->phone_number = $phone_number;
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
        if (Cache::has('reset_sms_code-' . $this->country_code . '-' . $this->phone_number)) {
            $this->is_expired = false;
            return Cache::get('reset_sms_code-' . $this->country_code . '-' . $this->phone_number) == $value;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if ($this->is_expired) {
            return trans('basic.users.Sms_verification_code_expired');
        }
        return trans('basic.users.Wrong_sms_verification_code');
    }
}
