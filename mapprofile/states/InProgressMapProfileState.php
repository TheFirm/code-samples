<?php
namespace frontend\service\mapprofile\states;

use common\service\mapprofile\interfaces\iAgentMapProfile;
use common\service\mapprofile\interfaces\iTeamManagerMapProfile;
use common\service\mapprofile\BaseMapProfileState;
use common\models\mapProfile\MapProfile;
use yii\web\ServerErrorHttpException;

class InProgressMapProfileState extends BaseMapProfileState implements iAgentMapProfile, iTeamManagerMapProfile
{

    public function start(MapProfile $mapProfile)
    {
        throw new ServerErrorHttpException("Map Profile already started.");
    }

    public function complete(MapProfile $mapProfile)
    {
        return $this->getAgentHelper()->complete($mapProfile);
    }

    public function addToList(MapProfile $mapProfile)
    {
        throw new ServerErrorHttpException("This MapProfile already in list.");
    }

    public function removeFromList(MapProfile $mapProfile)
    {
        throw new ServerErrorHttpException("You can't remove from list this MapProfile. User is currently working on a Map Profile.");
    }

    public function assign(MapProfile $mapProfile, $userId)
    {
        throw new ServerErrorHttpException("You can't assign this user. Other user is currently working on a Map Profile.");
    }

    public function unassign(MapProfile $mapProfile, $userId)
    {
        throw new ServerErrorHttpException("You can't unassign this user. This user is currently working on a Map Profile.");
    }
}