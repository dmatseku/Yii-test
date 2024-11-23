<?php

namespace backend\models\Repositories;

use backend\contracts\LoanRepositoryInterface;
use backend\models\Loan;

class LoanRepository implements LoanRepositoryInterface
{
    /**
     * @param int $amount
     * @param int $term
     * @param string $purpose
     * @param int $income
     * @param int $userId
     * @return Loan
     */
    public function create(int $amount, int $term, string $purpose, int $income, int $userId): Loan
    {
        return new Loan(['amount' => $amount, 'term' => $term, 'purpose' => $purpose, 'income' => $income, 'status' => Loan::STATUSES['pending'], 'user_id' => $userId]);
    }

    /**
     * @param mixed $value
     * @param string $field
     * @return Loan
     */
    public function get(mixed $value, string $field = "id"): Loan
    {
        return Loan::findOne([$field => $value]);
    }

    /**
     * @param int $userId
     * @return array|Loan[]
     */
    public function getFor(int $userId): array
    {
        return Loan::findAll(['user_id' => $userId]);
    }

    /**
     * @param Loan $loan
     * @return bool
     * @throws \yii\db\Exception
     */
    public function save(Loan $loan): bool
    {
        return $loan->save();
    }

    /**
     * @param Loan $loan
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(Loan $loan): bool
    {
        return $loan->delete();
    }
}