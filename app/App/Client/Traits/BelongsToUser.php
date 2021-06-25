<?php


namespace App\App\Client\Traits;


use App\App\Scopes\UserScope;
use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToUser
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function bootBelongsToUser() : void
    {
        static::addGlobalScope(new UserScope);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
