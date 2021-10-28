<?php

namespace Modules\Transaction\Console;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\Transaction\DTO\TransactionDTO;
use Modules\Transaction\Emails\SendTransactionSuccessEmail;
use Modules\Transaction\Http\Clients\REST\NotifierClient;

class EmailResendCommand extends Command
{
    public User $user;
    public TransactionDTO $transactionDTO;
    public NotifierClient $notifierClient;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:resend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resend pending e-mails.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        User $user,
        TransactionDTO $transactionDTO,
    )
    {
        parent::__construct();
        $this->user = $user;
        $this->transactionDTO = $transactionDTO;
        $this->notifierClient = new NotifierClient();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info("EmailResendCommand::handle request to notifier and send e-mail");
        if ($this->isNotified()) {
            Mail::to($this->user->getAttribute('email'))
                ->send(new SendTransactionSuccessEmail($this->transactionDTO));
        }
    }

    public function isNotified(): bool
    {
        $response = $this->notifierClient->request(
            method: 'POST',
        );
        return $response->status == NotifierClient::STATUS;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
