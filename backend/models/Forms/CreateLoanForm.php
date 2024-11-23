<?php

namespace backend\models\Forms;

use backend\contracts\LoanRepositoryInterface;
use backend\contracts\UserRepositoryInterface;
use backend\models\Loan;
use Yii;
use yii\base\Model;

class CreateLoanForm extends Model
{
    public int $amount;
    public int $term;
    public string $purpose;
    public int $income;

    /**
     * @param UserRepositoryInterface $userRepository
     * @param LoanRepositoryInterface $loanRepository
     * @param $config
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected LoanRepositoryInterface $loanRepository,
        $config = []
    ) {
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function formName(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
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

    /**
     * @return Loan|false
     */
    public function createLoan(): Loan|false
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