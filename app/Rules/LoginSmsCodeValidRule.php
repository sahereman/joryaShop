<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Cache;

class LoginSmsValidRule implements Rule
{
    protected $is_expired = true;
    protected $country_code = 86;
    protected $phone_number;

    /**
     * Create a new rule instance.
     *
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
        if (Cache::has('login_sms_code-' . $this->country_code . '-' . $this->phone_number)) {
            $this->is_expired = false;
            return Cache::get('login_sms_code-' . $this->country_code . '-' . $this->phone_number) == $value;
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
            return '短信验证码已过期';
        }
        return '短信验证码错误';
    }
}
