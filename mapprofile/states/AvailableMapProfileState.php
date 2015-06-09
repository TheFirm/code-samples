<?php
namespace frontend\service\mapprofile\states;

use common\service\mapprofile\interfaces\iTeamManagerMapProfile;
use common\service\mapprofile\BaseMapProfileState;
use common\models\mapProfile\MapProfile;
use yii\web\ServerErrorHttpException;

class AvailableMapProfileState extends BaseMapProfileState implements iTeamManagerMapProfile
{

    public function addToList(MapProfile $mapProfile)
    {
        return $this->getTeamManagerHelper()->addToList($mapProfile);
    }

    public function removeFromList(MapProfile $mapProfile)
    {
        throw new ServerErrorHttpException("You need add Map Profile to list first.");
    }

    public function assign(MapProfile $mapProfile, $userId)
    {
        throw new ServerErrorHttpException("You need add Map Profile to list first.");
    }

    public function unassign(MapProfile $mapProfile, $userId)
    {
        throw new ServerErrorHttpException("You need assign Map Profile first.");
    }

}