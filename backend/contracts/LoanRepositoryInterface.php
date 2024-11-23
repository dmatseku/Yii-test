<?php

namespace backend\contracts;

use backend\models\Loan;

interface LoanRepositoryInterface
{
    /**
     * @param int $amount
     * @param int $term
     * @param string $purpose
     * @param int $income
     * @param int $userId
     * @return Loan
     */
    public function create(int $amount, int $term, string $purpose, int $income, int $userId): Loan;

    /**
     * @param mixed $value
     * @param string $field
     * @return Loan
     */
    public function get(mixed $value, string $field = "id"): Loan;

    /**
     * @param int $userId
     * @return array|Loan[]
     */
    public function getFor(int $userId): array;

    /**
     * @param Loan $loan
     * @return bool
     */
    public function save(Loan $loan): bool;

    /**
     * @param Loan $loan
     * @return bool
     */
    public function delete(Loan $loan): bool;
}