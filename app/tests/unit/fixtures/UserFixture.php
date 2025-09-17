<?php

declare(strict_types=1);

namespace tests\unit\fixtures;

use yii\test\ActiveFixture;

class UserFixture extends ActiveFixture
{
    public $modelClass = 'app\models\User';
    public $dataFile = '@tests/unit/fixtures/data/user.php';
}