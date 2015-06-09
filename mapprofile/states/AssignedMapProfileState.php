<?php
namespace frontend\service\mapprofile\states;

use common\service\mapprofile\interfaces\iAgentMapProfile;
use common\service\mapprofile\interfaces\iTeamManagerMapProfile;
use common\service\mapprofile\BaseMapProfileState;
use common\models\mapProfile\MapProfile;
use yii\web\ServerErrorHttpException;

class AssignedMapProfileState extends BaseMapProfileState implements iAgentMapProfile, iTeamManagerMapProfile
{

    public function start(MapProfile $mapProfile)
    {
        return $this->getAgentHelper()->start($mapProfile);
    }

    public function complete(MapProfile $mapProfile)
    {
        throw new ServerErrorHttpException("Can not complete Map Profile before start.");
    }

    public function addToList(MapProfile $mapProfile)
    {
        throw new ServerErrorHttpException("This Map Profile already assigned.");
    }

    public function removeFromList(MapProfile $mapProfile)
    {
        return $this->getTeamManagerHelper()->removeFromList($mapProfile);
    }

    public function assign(MapProfile $mapProfile, $userId)
    {
        return $this->getTeamManagerHelper()->assign($mapProfile, $userId);
    }

    public function unassign(MapProfile $mapProfile, $userId)
    {
        return $this->getTeamManagerHelper()->unassign($mapProfile, $userId);
    }
}