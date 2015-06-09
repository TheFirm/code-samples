<?php
namespace frontend\service\mapprofile\states;

use common\service\mapprofile\interfaces\iTeamManagerMapProfile;
use common\service\mapprofile\BaseMapProfileState;
use common\models\mapProfile\MapProfile;
use yii\web\ServerErrorHttpException;

class InListMapProfileState extends BaseMapProfileState implements iTeamManagerMapProfile
{

    public function addToList(MapProfile $mapProfile)
    {
        throw new ServerErrorHttpException("Map Profile already in list.");
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