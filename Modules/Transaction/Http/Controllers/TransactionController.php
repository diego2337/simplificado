<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
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
            return Response::json($this->transactionService->transaction($transactionDto), ResponseAlias::HTTP_OK);
        } catch (Exception $e) {
            return Response::json(
                $e->getMessage(),
                $e->getCode() != 0 ?: ResponseAlias::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
