<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\LoanRequestForm;
use app\services\LoanService;
use Exception;
use Yii;
use yii\rest\Controller;
use yii\web\Response;

/**
 * RequestController
 */
class RequestController extends Controller
{
    private LoanService $loanService;

    public function __construct($id, $module, LoanService $loanService, array $config = [])
    {
        $this->loanService = $loanService;
        parent::__construct($id, $module, $config);
    }

    /**
     * Configuring behavior to return JSON responses
     *
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        return $behaviors;
    }

    /**
     *
     * @return array
     *
     * @SWG\Post(
     *     path="/requests",
     *     summary="Создать новый запрос",
     *     tags={"Requests"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(
     *             type="object",
     *             required={"user_id", "amount", "term"},
     *             @SWG\Property(
     *                 property="user_id",
     *                 type="integer",
     *                 description="User ID",
     *                 example=4
     *             ),
     *             @SWG\Property(
     *                 property="amount",
     *                 type="integer",
     *                 description="Сумма займа",
     *                 example=3000
     *             ),
     *             @SWG\Property(
     *                 property="term",
     *                 type="integer",
     *                 description="Срок займа",
     *                 example=30
     *             )
     *         )
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Request successfully created",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="result", type="boolean", example=true),
     *             @SWG\Property(property="id", type="integer", example=123)
     *         )
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Incorrect input data",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="result", type="boolean", example=false),
     *             @SWG\Property(property="error", type="string", example="User Id is invalid.")
     *         )
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Incorrect input data",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="result", type="boolean", example=false),
     *             @SWG\Property(property="error", type="string", example="Loan creation error.")
     *         )
     *     )
     * )
     */
    public function actionCreate(): array
    {
        $request = Yii::$app->request->post();
        $validator = new LoanRequestForm();
        $validator->load($request, '');

        if (!$validator->validateData()) {
            Yii::$app->response->statusCode = 400;
            return [
                'result' => false,
                'errors' => $validator->getErrorsAsArray(),
            ];
        }

        try {
            $dto = $validator->getDto();
            $loanResult = $this->loanService->createLoanRequest($dto);

            if ($loanResult->isSuccess()) {
                Yii::$app->response->statusCode = 201;
                return [
                    'result' => $loanResult->isSuccess(),
                    'id' => $loanResult->getResult()->id,
                ];
            }

            Yii::$app->response->statusCode = 400;
            return ['result' => false];

        } catch (Exception $e) {
            Yii::error("Loan creation error: " . $e->getMessage());
            Yii::$app->response->statusCode = 500;
            return ['result' => false];
        }
    }
}