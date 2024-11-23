<?php

namespace backend\services;

use backend\contracts\LoanRepositoryInterface;

class LoanService
{
    /**
     * @param LoanRepositoryInterface $loanRepository
     */
    public function __construct(
        protected LoanRepositoryInterface $loanRepository,
    ) {

    }

    /**
     * @param $loanId
     * @return float|int
     */
    public function predictAmountOfMonthsForPayment($loanId): float|int
    {
        $loan = $this->loanRepository->get($loanId);

        return $loan->amount / $loan->income;
    }
}