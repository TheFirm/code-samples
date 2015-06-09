<?php
namespace frontend\service\mapprofile;

use common\service\mapprofile\interfaces\iAgentMapProfile;
use common\service\mapprofile\interfaces\iTeamManagerMapProfile;

interface iMapProfileClientService extends iTeamManagerMapProfile, iAgentMapProfile
{

}