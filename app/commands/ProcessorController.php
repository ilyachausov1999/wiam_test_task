<?php

declare(strict_types=1);

namespace app\commands;

use app\services\LoanProcessorService;
use InvalidArgumentException;
use Throwable;
use Yii;
use yii\base\Module;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * ProcessorController
 *
 * Обрабатывает все запросы в состоянии ОЖИДАНИЯ, принимая решение: одобрить или отклонить.
 * Возможен ручной запуск или например по крону
 *
 * php yii processor/index
 *
 */
class ProcessorController extends Controller
{
    /**
     * @var LoanProcessorService
     */
    private LoanProcessorService $loanProcessorService;

    /**
     *
     * @param string $id
     * @param Module $module
     * @param LoanProcessorService $loanProcessorService
     * @param array $config
     */
    public function __construct($id, $module, LoanProcessorService $loanProcessorService, array $config = [])
    {
        $this->loanProcessorService = $loanProcessorService;
        parent::__construct($id, $module, $config);
    }

    /**
     * Обрабатывает все запросы со статусом "ОЖИДАЮЩИЙ".
     *
     * @param integer $delay Задержка в секундах для обработки каждого запроса.
     * @return int код выполнения
     */
    public function actionIndex(int $delay = 1): int
    {
        try {
            $this->loanProcessorService->processPendingRequests($delay);
        } catch (InvalidArgumentException $e) {
            Yii::error("Invalid Argument: " . $e->getMessage());
            return ExitCode::DATAERR;
        } catch (Throwable $e) {
            Yii::error("Processing Error: " . $e->getMessage());
            return ExitCode::UNSPECIFIED_ERROR;
        }
        return ExitCode::OK;
    }
}
