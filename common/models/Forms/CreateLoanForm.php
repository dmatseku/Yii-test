<?php

namespace common\models\Forms;

use common\contracts\LoanRepositoryInterface;
use common\contracts\UserRepositoryInterface;
use Yii;
use yii\base\Model;

class CreateLoanForm extends Model
{
    public $amount;
    public $term;
    public $purpose;
    public $income;

    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected LoanRepositoryInterface $loanRepository,
        $config = []
    ) {
        parent::__construct($config);
    }

    public function formName()
    {
        return '';
    }

    public function rules()
    {
        return [
            ['amount', 'integer', 'min' => 1],
            ['amount', 'required'],

            ['term', 'integer', 'min' => 1],
            ['term', 'required'],

            ['purpose', 'string', 'min' => 3],
            ['purpose', 'required'],
            ['purpose', 'trim'],

            ['income', 'integer', 'min' => 1],
            ['income', 'required'],
        ];
    }

    public function createLoan()
    {
        if ($this->validate() && !Yii::$app->user->isGuest) {
            $loan = $this->loanRepository->create($this->amount, $this->purpose, $this->term, $this->income, Yii::$app->user->id);

            $this->loanRepository->save($loan);
            return $loan;
        } else {
            return false;
        }
    }
}