<?php

namespace common\models\Repositories;

use common\contracts\LoanRepositoryInterface;
use common\models\Loan;
use PhpParser\Node\Stmt\Label;

class LoanRepository implements LoanRepositoryInterface
{
    public function create($amount, $term, $purpose, $income, $userId)
    {
        return new Loan(['amount' => $amount, 'term' => $term, 'purpose' => $purpose, 'income' => $income, 'status' => Loan::STATUSES['pending'], 'user_id' => $userId]);
    }

    public function get($value, $field = "id")
    {
        return Loan::findOne([$field => $value]);
    }

    public function getFor($userId)
    {
        return Loan::findAll(['user_id' => $userId]);
    }

    public function save(Loan $loan)
    {
        $loan->save();
    }

    public function delete(Loan $loan)
    {
        $loan->delete();
    }
}