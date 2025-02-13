<?php

declare(strict_types=1);

namespace App\Models;

use App\Mail\BalanceLow;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Mail;

class Wallet extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::updated(function (Wallet $wallet) {
            if($wallet->balance < 1000){
                Mail::to($wallet->user)
                    ->send(new BalanceLow($wallet));
            }
        });
    }

    /**
     * @return BelongsTo<User>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<WalletTransaction>
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }
}
