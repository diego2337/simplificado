<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Modules\Transaction\Http\Requests\TransactionRequest;
use Modules\Transaction\Services\TransactionService;
use Modules\Transaction\DTO\TransactionDTO;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TransactionController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function transaction(TransactionRequest $request): JsonResponse
    {
        $validatedRequest = $request->validated();
        try {
            $transactionDto = new TransactionDTO(
                value: $validatedRequest['value'],
                payer: $validatedRequest['payer'],
                payee: $validatedRequest['payee'],
            );
            Log::info("TransactionController::transaction request \n", [ 'request' => $request ]);
            return Response::json(['message' => $this->transactionService->transaction($transactionDto)], ResponseAlias::HTTP_OK);
        } catch (Exception $e) {
            Log::error("TransactionController::transaction error \n", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return Response::json(
                ['error' => $e->getMessage()],
                $e->getCode() != 0 ? $e->getCode() : ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
