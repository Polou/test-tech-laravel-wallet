<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController
{
    public function __invoke(Request $request)
    {
        $userWallet = $request->user()->wallet;

        $transactions = $userWallet ? $userWallet->transactions()->with('transfer')->orderByDesc('id')->get() : [];
        $balance = $userWallet ? $userWallet->balance : 0;

        return view('dashboard', compact('transactions', 'balance'));
    }
}
