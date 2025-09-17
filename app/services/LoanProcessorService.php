<?php

declare(strict_types=1);

namespace app\services;

use app\models\Enums\LoanStatusEnum;
use app\models\Request;
use Exception;
use InvalidArgumentException;
use Yii;

class LoanProcessorService implements LoanProcessorServiceInterface
{
    /**
     * @param int $delay
     * @return bool
     */
    public function processPendingRequests(int $delay = 0): bool
    {
        if ($delay < 0) {
            throw new InvalidArgumentException("The delay parameter must be a non-negative integer");
        }

        $processed = false;
        $pendingRequests = Request::find()->where(['status' => LoanStatusEnum::PENDING->value])->all();

        /** @var Request $request */
        foreach ($pendingRequests as $request) {
            try {
                if ($this->processSingleRequest($request, $delay)) {
                    $processed = true;
                }
            } catch (Exception $e) {
                Yii::error("Error processing request {$request->id}: " . $e->getMessage());
                continue;
            }
        }

        return $processed;
    }

    /**
     * @param Request $request
     * @param int $delay
     * @return bool
     * @throws \yii\db\Exception
     */
    private function processSingleRequest(Request $request, int $delay = 0): bool
    {
        sleep($delay);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $sql = Request::find()
                    ->where(['id' => $request->id, 'status' => LoanStatusEnum::PENDING->value])
                    ->createCommand()
                    ->getRawSql() . ' FOR UPDATE NOWAIT';

            $lockedRequest = Yii::$app->db->createCommand($sql)->queryOne();

            if (!$lockedRequest) {
                $transaction->rollBack();
                return false;
            }

            $loanRequest = Request::findOne($request->id);
            if (!$loanRequest || $loanRequest->status !== LoanStatusEnum::PENDING->value) {
                $transaction->rollBack();
                return false;
            }

            $hasApproved = Request::find()
                ->where([
                    'user_id' => $loanRequest->user_id,
                    'status' => LoanStatusEnum::APPROVED->value
                ])
                ->andWhere(['!=', 'id', $loanRequest->id])
                ->exists();

            $isApproved = !$hasApproved && (rand(1, 100) <= 10);

            $loanRequest->status = $isApproved ? LoanStatusEnum::APPROVED->value : LoanStatusEnum::DECLINED->value;
            $loanRequest->processed_at = time();

            if (!$loanRequest->save()) {
                throw new Exception('Failed to save loan request');
            }

            $transaction->commit();
            return true;

        } catch (Exception $e) {
            $transaction->rollBack();

            if (str_contains($e->getMessage(), 'could not obtain lock') || str_contains($e->getMessage(), 'LOCK')) {
                return false;
            }

            throw $e;
        }
    }
}