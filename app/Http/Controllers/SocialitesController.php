<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Overtrue\LaravelSocialite\Socialite;

class SocialitesController extends Controller
{
    protected $supportedSocialites = [
        'facebook'
    ];

    protected function isAuthorized(string $socialite)
    {
        if (!in_array($socialite, $this->supportedSocialites)) {
            throw new InvalidRequestException("Socialite {$socialite} is not supported yet");
        }
    }

    // GET: Socialite Login Url
    public function login(Request $request, string $socialite)
    {
        $this->isAuthorized($socialite);
        return Socialite::driver($socialite)->redirect();
    }

    // POST: Socialite Callback Url
    public function callback(Request $request, string $socialite)
    {
        try {
            $user_info = Socialite::driver($socialite)->user();
        } catch (\Exception $e) {
            return redirect()->route('socialites.login', ['socialite' => $socialite]);
        }
        $user = $this->findOrCreateUser($user_info, $socialite);
        Auth::login($user);
        return redirect()->route('root');
    }

    protected function findOrCreateUser($user_info, string $socialite)
    {
        /*$this->isAuthorized($socialite);
        $user = User::where([
            'socialite' => $socialite,
            'socialite_id' => $user_info->id
        ])->first();

        if (!$user) {
            $user = User::create([
                'name' => $user_info->name,
                'email' => $user_info->email,
                'socialite' => $socialite,
                'socialite_id' => $user_info->id
            ]);
        }*/

        if (!in_array($socialite, $this->supportedSocialites) && $socialite == 'facebook') {
            $user = User::where([
                'facebook' => $user_info->id
            ])->first();

            if (!$user) {
                $user = User::create([
                    'name' => $user_info->name,
                    'email' => $user_info->email,
                    'facebook' => $user_info->id
                ]);
            }
            return $user;
        } else {
            throw new InvalidRequestException("Socialite {$socialite} is not supported yet");
        }
    }
}
