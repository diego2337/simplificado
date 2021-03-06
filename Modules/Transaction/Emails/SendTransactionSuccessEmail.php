<?php

namespace Modules\Transaction\Emails;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\Transaction\DTO\TransactionDTO;

class SendTransactionSuccessEmail extends Mailable
{
    use Queueable, SerializesModels;

    public TransactionDTO $transactionDTO;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(TransactionDTO $transactionDTO)
    {
        $this->transactionDTO = $transactionDTO;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        Log::info("SendTransactionSuccessEmail::build build e-mail");
        return $this
                ->from(config('mail.from'))
                ->view('transaction.send-transaction-success');
    }
}
