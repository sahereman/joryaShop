<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('users.edit', [
            'user' => $user,
        ]);
    }

    public function update(UserRequest $request, User $user, ImageUploadHandler $imageUploadHandler)
    {

        $this->authorize('update', $user);

        $data = $request->only('name', 'avatar', 'email', 'password');

        if ($request->hasFile('avatar'))
        {
            $data['avatar'] = $imageUploadHandler->uploadOriginal($request->avatar);
        }

        if ($request->has('password') && $user->password != $data['password'])
        {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.edit', $user->id);
    }

}
