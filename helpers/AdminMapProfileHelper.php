<?php
namespace common\service\mapprofile\helpers;

use common\helpers\GeometryFormatHelper;
use common\models\mapProfile\MapProfile;
use common\models\mapProfile\MapProfileForm;
use common\service\mapprofile\interfaces\iAdminMapProfile;
use common\service\mapprofile\MapProfileService;

class AdminMapProfileHelper implements iAdminMapProfile
{

    public function update(MapProfile $mapProfile, MapProfileForm $mapProfileForm)
    {
        if ($mapProfile->geometry === GeometryFormatHelper::formatPolygon($mapProfileForm->polygon,
                GeometryFormatHelper::FORMAT_RAW_TO_PSQL)
        ) {
            $mapProfile->mp_name = $mapProfileForm->mp_name;
            $mapProfile->update();
        } else {
            $mapProfile = MapProfileService::getInstance()->updateMapProfile($mapProfile, $mapProfileForm);
        }

        return $mapProfile;
    }

    public function delete(MapProfile $mapProfile)
    {
        $mapProfile->delete();
        return $mapProfile;
    }
}