<?php
namespace common\service\mapprofile\helpers;

use common\models\mapProfile\MapProfile;
use common\models\mapProfile\MapProfileAgent;
use common\service\mapprofile\interfaces\iAgentMapProfile;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class AgentMapProfileHelper implements iAgentMapProfile
{

    public function start(MapProfile $mapProfile)
    {

        $mapProfileAgent = $this->beforeStart($mapProfile);

        if ($mapProfileAgent->status != MapProfileAgent::STARTED) {
            $mapProfileAgent->status = MapProfileAgent::STARTED;
            $mapProfileAgent->save();
        }

        return $mapProfileAgent;
    }

    public function complete(MapProfile $mapProfile)
    {

        $mapProfileAgent = $this->beforeComplete($mapProfile);

        if ($mapProfileAgent->status != MapProfileAgent::COMPLETED) {
            $mapProfileAgent->status = MapProfileAgent::COMPLETED;
            $mapProfileAgent->save();
        }

        return $mapProfileAgent;
    }

    private function beforeStart(MapProfile $mapProfile)
    {
        $isStartedMapProfiles = MapProfileAgent::find()->where([
                "user_id" => \Yii::$app->user->id,
                "status" => MapProfileAgent::STARTED]
        )->count();

        if ($isStartedMapProfiles) {
            throw new ServerErrorHttpException("You can not start Map Profile. You have uncompleted Map Profile.");
        }

        /**
         * @var $mapProfileAgent MapProfileAgent
         */
        $mapProfileAgent = MapProfileAgent::findOne([
            'map_profile_id' => $mapProfile->id,
            'user_id' => \Yii::$app->user->id
        ]);

        if (!$mapProfileAgent) {
            throw new NotFoundHttpException("You can not start not assigned Map Profile.");
        }

        return $mapProfileAgent;
    }

    private function beforeComplete(MapProfile $mapProfile)
    {
        /**
         * @var $mapProfileAgent MapProfileAgent
         */
        $mapProfileAgent = MapProfileAgent::findOne([
            'map_profile_id' => $mapProfile->id,
            'user_id' => \Yii::$app->user->id
        ]);

        if (!$mapProfileAgent) {
            throw new NotFoundHttpException("You can not complete not assigned Map Profile.");
        }

        return $mapProfileAgent;
    }
}