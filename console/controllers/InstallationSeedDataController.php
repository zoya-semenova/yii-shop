<?php

declare(strict_types=1);


namespace console\controllers;

use common\models\User;
use common\models\UserProfile;
use Yii;
use yii\console\Controller;
use yii\db\Exception;

class InstallationSeedDataController extends Controller
{
    /**
     * @return void
     * @throws \yii\base\Exception
     * @throws Exception
     */
    public function actionSeedUsers(): void
    {
        $usersData = [
            [
                'webmaster',
                'webmaster@example.com',
                Yii::$app->getSecurity()->generatePasswordHash('webmaster'),
                Yii::$app->getSecurity()->generateRandomString(),
                Yii::$app->getSecurity()->generateRandomString(40),
                User::STATUS_ACTIVE,
                time(),
                time()
            ],
            [
                'manager',
                'manager@example.com',
                Yii::$app->getSecurity()->generatePasswordHash('manager'),
                Yii::$app->getSecurity()->generateRandomString(),
                Yii::$app->getSecurity()->generateRandomString(40),
                User::STATUS_ACTIVE,
                time(),
                time()
            ],
            [
                'user',
                'user@example.com',
                Yii::$app->getSecurity()->generatePasswordHash('user'),
                Yii::$app->getSecurity()->generateRandomString(),
                Yii::$app->getSecurity()->generateRandomString(40),
                User::STATUS_ACTIVE,
                time(),
                time()
            ],
        ];
        $userTable = User::tableName();

        Yii::$app->db->createCommand()->batchInsert($userTable, [
            'username',
            'email',
            'password_hash',
            'auth_key',
            'access_token',
            'status',
            'created_at',
            'updated_at'
        ], $usersData)->execute();

        foreach (User::find()->each(10) as $user) {
            Yii::$app->db->createCommand()->insert(UserProfile::tableName(), [
                'user_id' => $user->id,
                'locale' => Yii::$app->sourceLanguage
            ])->execute();
        }
    }
}
