<?php

namespace app\services;

interface LoanProcessorServiceInterface
{
    /**
     * Обрабатываем все заявки со статусом PENDING
     *
     * @param int $delay задержка обработки в секундах
     */
    public function processPendingRequests(int $delay = 0): bool;
}