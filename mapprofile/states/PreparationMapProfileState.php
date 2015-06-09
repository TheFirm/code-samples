<?php
namespace frontend\service\mapprofile\states;

use common\models\mapProfile\MapProfile;
use common\models\mapProfile\MapProfileForm;
use common\service\mapprofile\interfaces\iAdminMapProfile;
use common\service\mapprofile\BaseMapProfileState;
use yii\web\ServerErrorHttpException;

class PreparationMapProfileState extends BaseMapProfileState implements iAdminMapProfile
{

    public function update(MapProfile $mapProfile, MapProfileForm $mapProfileForm)
    {
        throw new ServerErrorHttpException('Map Profile is in preparation. You can not update this Map Profile.');
    }

    public function delete(MapProfile $mapProfile)
    {
        throw new ServerErrorHttpException('Map Profile is in preparation. You can not delete this Map Profile.');
    }
}