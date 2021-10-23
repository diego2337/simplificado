<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Controllers;

use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Response as StatusCode;
use Modules\Transaction\Http\Requests\TransactionRequest;
use Modules\Transaction\Services\TransactionService;
use Modules\Transaction\DTO\TransactionDTO;

class TransactionController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function transaction(TransactionRequest $request)
    {
        $validatedRequest = $request->validated();
        $transactionDto = new TransactionDTO([
            'value' => $validatedRequest['value'],
            'payer' => $validatedRequest['payer'],
            'payee' => $validatedRequest['payee'],
        ]);
        try {
            return Response::json($this->transactionService->transaction($transactionDto), StatusCode::HTTP_OK);
        } catch (Exception $e) {
            return Response::json($e, $e->getCode());
        }
    }
}
