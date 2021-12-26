<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\Domain\Users\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserProfileInformationController extends Controller
{
    public function update(Request $request) : RedirectResponse
    {
        User::find($request->get('id'))->update([
            'name'  => $request->get('name'),
            'email' => $request->get('email'),
        ]);

        return $request->wantsJson()
        ? new JsonResponse('', 200)
        : back()->with('status', 'profile-information-updated');
    }
}
