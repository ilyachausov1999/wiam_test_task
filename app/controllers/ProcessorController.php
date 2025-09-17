<?php

declare(strict_types=1);

namespace app\controllers;

use app\services\LoanProcessorService;
use InvalidArgumentException;
use Throwable;
use Yii;
use yii\base\Module;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use yii\filters\ContentNegotiator;
use yii\web\ServerErrorHttpException;

/**
 * ProcessorController
 *
 * Обрабатывает все запросы в состоянии STATUS_PENDING, принимая решение: одобрить или отклонить.
 */
class ProcessorController extends Controller
{
    /**
     * @var LoanProcessorService
     */
    private LoanProcessorService $processorService;

    /**
     * @param string $id
     * @param Module $module
     * @param LoanProcessorService $processorService
     * @param array $config
     */
    public function __construct($id, $module, LoanProcessorService $processorService, array $config = [])
    {
        $this->processorService = $processorService;
        parent::__construct($id, $module, $config);
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    /**
     * Обработка всех запросов со статусом "STATUS_PENDING"
     *
     * @param int $delay
     * @return array
     * @throws BadRequestHttpException|ServerErrorHttpException
     *
     * @SWG\Get(
     *     path="/processor",
     *     summary="Обработка ожидающих запросов",
     *     tags={"Processor"},
     *     @SWG\Parameter(
     *         name="delay",
     *         in="query",
     *         type="integer",
     *         description="Delay in seconds",
     *         required=false,
     *         default=0,
     *         example=5
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful processing of requests",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="result", type="boolean", example=true)
     *         )
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Incorrect delay parameter",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="result", type="boolean", example=false),
     *             @SWG\Property(property="error", type="string", example="Invalid delay parameter")
     *         )
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Internal server error",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="result", type="boolean", example=false),
     *             @SWG\Property(property="error", type="string", example="Processing error")
     *         )
     *     )
     * )
     */
    public function actionHandle(int $delay = 0): array
    {
        try {
            $result = $this->processorService->processPendingRequests($delay);
            return ['result' => $result];
        } catch (InvalidArgumentException $e) {
            Yii::error("Invalid Argument: " . $e->getMessage());
            throw new BadRequestHttpException($e->getMessage());
        } catch (Throwable $e) {
            Yii::error("Processing Error: " . $e->getMessage());
            throw new ServerErrorHttpException($e->getMessage());
        }
    }
}
