<?php

namespace common\contracts;

use common\models\Loan;

interface LoanRepositoryInterface
{
    public function create($amount, $term, $purpose, $income, $userId);

    public function get($value, $field = "id");

    public function getFor($userId);

    public function save(Loan $loan);

    public function delete(Loan $loan);
}