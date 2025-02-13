<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\WalletTransactionType;
use App\Exceptions\InsufficientBalance;
use App\Models\User;
use App\Models\WalletTransfer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

readonly class PerformWalletTransfer
{
    public function __construct(protected PerformWalletTransaction $performWalletTransaction) {}

    /**
     * @throws InsufficientBalance
     */
    public function execute(User $sender, User $recipient, int $amount, string $reason, bool $isRecurrent = false, Carbon $start = null, Carbon $end = null, int $freq = 0): WalletTransfer
    {
        return DB::transaction(function () use ($sender, $recipient, $amount, $reason, $isRecurrent, $start, $end, $freq) {
            $baseTransfer = [
                'amount' => $amount,
                'source_id' => $sender->wallet->id,
                'target_id' => $recipient->wallet->id,
            ];
            if($isRecurrent){
                $baseTransfer['is_reccuring'] = $isRecurrent;
                $baseTransfer['start_date'] = $start;
                $baseTransfer['end_date'] = $end;
                $baseTransfer['frequency'] = $freq;
            }

            $transfer = WalletTransfer::create();

            $this->performWalletTransaction->execute(
                wallet: $sender->wallet,
                type: WalletTransactionType::DEBIT,
                amount: $amount,
                reason: $reason,
                transfer: $transfer
            );

            $this->performWalletTransaction->execute(
                wallet: $recipient->wallet,
                type: WalletTransactionType::CREDIT,
                amount: $amount,
                reason: $reason,
                transfer: $transfer
            );

            return $transfer;
        });
    }
}
