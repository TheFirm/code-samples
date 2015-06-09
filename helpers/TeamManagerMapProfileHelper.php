<?php
namespace common\service\mapprofile\helpers;

use common\models\mapProfile\MapProfile;
use common\models\mapProfile\MapProfileAgent;
use common\models\mapProfile\MapProfileTeam;
use common\models\User;
use common\service\mapprofile\interfaces\iTeamManagerMapProfile;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class TeamManagerMapProfileHelper implements iTeamManagerMapProfile
{

    public function addToList(MapProfile $mapProfile)
    {
        if (MapProfileTeam::find()->where(['map_profile_id' => $mapProfile->id])->count()) {
            throw new ServerErrorHttpException("Map Profile already added to list.");
        }

        $mapProfileTeam = new MapProfileTeam();
        $mapProfileTeam->map_profile_id = $mapProfile->id;
        $mapProfileTeam->team_id = $this->getUserTeamId();
        $mapProfileTeam->save();

        return $mapProfileTeam;
    }

    public function removeFromList(MapProfile $mapProfile)
    {
        $this->beforeRemoveFromList($mapProfile);
        /**
         * @var $mapProfileTeam MapProfileTeam
         */
        $mapProfileTeam = MapProfileTeam::findOne([
            'map_profile_id' => $mapProfile->id,
            'team_id' => $this->getUserTeamId()
        ]);
        $mapProfileTeam->delete();

        return $mapProfileTeam;
    }

    public function assign(MapProfile $mapProfile, $userId)
    {
        $this->beforeAssign($mapProfile, $userId);

        $mapProfileAgent = MapProfileAgent::findOne([
            'map_profile_id' => $mapProfile->id,
            'user_id' => $userId
        ]);

        if (!$mapProfileAgent) {
            $mapProfileAgent = new MapProfileAgent();
            $mapProfileAgent->map_profile_id = $mapProfile->id;
            $mapProfileAgent->user_id = $userId;
            $mapProfileAgent->status = MapProfileAgent::NOT_STARTED;
            $mapProfileAgent->save();
        }

        return $mapProfileAgent;

    }

    public function unassign(MapProfile $mapProfile, $userId)
    {
        $this->isUserAvailableForWork($userId);
        if (!MapProfileAgent::find()->where(['map_profile_id' => $mapProfile->id])->count()) {
            throw new ServerErrorHttpException("Map Profile not assigned.");
        }

        /**
         * @var $mapProfileAgent MapProfileAgent
         */
        $mapProfileAgent = MapProfileAgent::findOne([
            'map_profile_id' => $mapProfile->id,
            'user_id' => $userId
        ]);
        $mapProfileAgent->delete();

        return $mapProfileAgent;
    }

    private function beforeRemoveFromList(MapProfile $mapProfile)
    {
        /**
         * @var MapProfileAgent $mapProfileAgent
         */
        $mapProfileAgent = MapProfileAgent::findOne(['map_profile_id' => $mapProfile->id]);
        if ($mapProfileAgent) {
            $this->getAgentByMapProfile($mapProfileAgent);
            if ($mapProfileAgent->status == MapProfileAgent::NOT_STARTED) {
                $mapProfileAgent->delete();
            } else {
                throw new ServerErrorHttpException("You can't remove from list this MapProfile. User is currently working on a Map Profile.");
            }
        }
    }

    private function beforeAssign(MapProfile $mapProfile, $userId)
    {
        $this->isUserAvailableForWork($userId);

        /**
         * @var MapProfileAgent $mapProfileAgent
         */
        $mapProfileAgent = MapProfileAgent::findOne(['map_profile_id' => $mapProfile->id]);
        if ($mapProfileAgent) {
            $this->removeAssignment($mapProfileAgent, $userId);
        }
    }

    private function removeAssignment(MapProfileAgent $mapProfileAgent, $userId)
    {
        $assignedUser = $this->getAgentByMapProfile($mapProfileAgent);
        /**
         * @var User $user
         */
        $user = User::findOne($userId);
        if ($user->temp_team_id != $this->getUserTeamId()) {
            throw new ServerErrorHttpException("You can not assign user from another team.");
        }

        if ($userId != $assignedUser->id) {
            $mapProfileAgent->delete();
        }
    }

    private function isUserAvailableForWork($userId)
    {
        $user = User::find()
            ->where(['id' => $userId])
            ->andFilterWhere(["!=", "role", User::ROLE_ADMIN])
            ->one();

        if (!$user) {
            throw new NotFoundHttpException("User not found.");
        }

        return true;
    }

    private function getAgentByMapProfile(MapProfileAgent $mapProfileAgent)
    {
        /**
         * @var User $user
         */
        $user = $mapProfileAgent->getUser()->one();
        if ($user->temp_team_id != $this->getUserTeamId()) {
            throw new ServerErrorHttpException("Map Profile used by another team.");
        }

        return $user;
    }

    private function getUserTeamId()
    {
        if (!isset(\Yii::$app->user->identity->temp_team_id)) {
            throw new ServerErrorHttpException('User team not found.');
        }
        return \Yii::$app->user->identity->temp_team_id;
    }
}