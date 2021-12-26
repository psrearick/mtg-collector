<?php

namespace App\App\Client\Traits;

use App\App\Scopes\UserScope;
use App\App\Scopes\UserScopeNotShared;
use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToUser
{
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function bootBelongsToUser() : void
    {
        $userScope = self::USERSCOPE;

        if ($userScope !== 'notShared') {
            static::addGlobalScope(new UserScope);

            return;
        }

        static::addGlobalScope(new UserScopeNotShared);
    }
}
