<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class ResetEmailCodeValidRule implements Rule
{
    protected $is_expired = true;
    protected $email = '';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
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
        if (Cache::has('reset_email_code-' . $this->email)) {
            $this->is_expired = false;
            return Cache::get('reset_email_code-' . $this->email) == $value;
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
            return trans('basic.users.Email_verification_code_expired');
        }
        return trans('basic.users.Wrong_email_verification_code');
    }
}
