<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\PerformWalletTransfer;
use App\Exceptions\InsufficientBalance;
use App\Http\Requests\SendMoneyReccuringRequest;
use App\Http\Requests\SendMoneyRequest;

class SendMoneyController
{
    public function __invoke(SendMoneyRequest $request, PerformWalletTransfer $performWalletTransfer)
    {
        $recipient = $request->getRecipient();

        try {
            $performWalletTransfer->execute(
                sender: $request->user(),
                recipient: $recipient,
                amount: $request->getAmountInCents(),
                reason: $request->input('reason'),
            );

            return redirect()->back()
                ->with('money-sent-status', 'success')
                ->with('money-sent-recipient-name', $recipient->name)
                ->with('money-sent-amount', $request->getAmountInCents());
        } catch (InsufficientBalance $exception) {
            return redirect()->back()->with('money-sent-status', 'insufficient-balance')
                ->with('money-sent-recipient-name', $recipient->name)
                ->with('money-sent-amount', $request->getAmountInCents());
        }
    }

    public function reccuring(SendMoneyReccuringRequest $request, PerformWalletTransfer $performWalletTransfer)
    {
        $recipient = $request->getRecipient();

        try {
            $performWalletTransfer->execute(
                sender: $request->user(),
                recipient: $recipient,
                amount: $request->getAmountInCents(),
                reason: $request->input('reason'),
                isRecurrent: true,
                start: $request->getFormattedDate($request->input('start_date')),
                end: $request->getFormattedDate($request->input('start_date')),
                freq: (int) $request->input('frequency'),
            );

            return redirect()->back()
                ->with('money-sent-status', 'success')
                ->with('money-sent-recipient-name', $recipient->name)
                ->with('money-sent-amount', $request->getAmountInCents());
        } catch (InsufficientBalance $exception) {
            return redirect()->back()->with('money-sent-status', 'insufficient-balance')
                ->with('money-sent-recipient-name', $recipient->name)
                ->with('money-sent-amount', $request->getAmountInCents());
        }
    }
}
