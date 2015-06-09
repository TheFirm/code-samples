<?php
namespace frontend\service\mapprofile\states;

use common\service\mapprofile\interfaces\iAgentMapProfile;
use common\service\mapprofile\interfaces\iTeamManagerMapProfile;
use common\service\mapprofile\BaseMapProfileState;
use common\models\mapProfile\MapProfile;
use yii\web\ServerErrorHttpException;

class CompletedMapProfileState extends BaseMapProfileState implements iAgentMapProfile, iTeamManagerMapProfile
{

    public function addToList(MapProfile $mapProfile)
    {
        throw new ServerErrorHttpException("You can not add to list completed Map Profile.");
    }

    public function removeFromList(MapProfile $mapProfile)
    {
        return $this->getTeamManagerHelper()->removeFromList($mapProfile);
    }

    public function assign(MapProfile $mapProfile, $userId)
    {
        throw new ServerErrorHttpException("You can not assign completed Map Profile.");
    }

    public function unassign(MapProfile $mapProfile, $userId)
    {
        return $this->getTeamManagerHelper()->unassign($mapProfile, $userId);
    }

    public function start(MapProfile $mapProfile)
    {
        return $this->getAgentHelper()->start($mapProfile);
    }

    public function complete(MapProfile $mapProfile)
    {
        throw new ServerErrorHttpException("Map Profile already completed.");
    }
}