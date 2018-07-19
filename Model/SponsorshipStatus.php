<?php

namespace ParainageSimple\Model;

use ParainageSimple\Model\Base\SponsorshipStatus as BaseSponsorshipStatus;

class SponsorshipStatus extends BaseSponsorshipStatus
{
    const CODE_INVITATION_SENT = "invitation_sent";
    const CODE_INVITATION_ACCEPTED = "invitation_accepted";
}
