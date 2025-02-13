<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Date;
use Illuminate\Validation\Rule;

class SendMoneyReccuringRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'recipient_email' => [
                'required',
                'email',
                Rule::exists(User::class, 'email')->whereNot('id', $this->user()->id),
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'reason' => [
                'required',
                'string',
                'max:255',
            ],
            'start_date' => [
                'required',
                'date',
            ],
            'end_date' => [
                'required',
                'date',
            ],
            'frequency' => [
                'required',
                'integer',
            ],
        ];
    }

    public function getRecipient(): User
    {
        return User::where('email', '=', $this->input('recipient_email'))->firstOrFail();
    }

    public function getAmountInCents(): int
    {
        return (int) ceil($this->float('amount') * 100);
    }

    public function getFormattedDate(string $date): Carbon
    {
        return Carbon::createFromFormat('Y-m-d', $date);
    }
}
