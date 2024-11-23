<?php

namespace common\services;

use common\contracts\LoanRepositoryInterface;

class LoanService
{
    public function __construct(
        protected LoanRepositoryInterface $loanRepository,
    ) {

    }
    public function predictAmountOfMonthsForPayment($loanId)
    {
        $loan = $this->loanRepository->get($loanId);

        return $loan->amount / $loan->income;
    }
}