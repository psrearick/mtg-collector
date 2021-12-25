<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\Domain\Users\Actions\UpdateUserPassword;
use App\Domain\Users\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class UserPasswordController extends Controller
{
    private UpdateUserPassword $updateUserPassword;

    public function __construct(UpdateUserPassword $updateUserPassword)
    {
        $this->updateUserPassword = $updateUserPassword;
    }

    public function update(Request $request) : RedirectResponse
    {
        $this->updateUserPassword->execute($request->user(), $request->all());

        return $request->wantsJson()
        ? new JsonResponse('', 200)
        : back()->with('status', 'password-updated');
    }
}