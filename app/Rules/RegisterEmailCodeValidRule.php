<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Cache;

class RegisterEmailCodeValidRule implements Rule
{
    protected $is_expired = true;
    protected $code = '';

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($code)
    {
        $this->code = $code;
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
        if(Cache::has('register_email_code-'.$value)){
            $this->is_expired = false;
            return Cache::get('register_email_code-' . $value) == $this->code;
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
        if($this->is_expired){
            return '邮箱验证码已过期';
        }
        return '邮箱验证码错误';
    }
}
