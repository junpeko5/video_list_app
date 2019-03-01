<?php
namespace App\Utils;

use Symfony\Component\Security\Core\Security;
use App\Entity\Video;

class VideoForNoValidSubscription {
    public $isSubscriptionValid = false;
    public function __construct(Security $security)
    {
        $user = $security->getUser();
        if($user && $user->getSubscription() != null)
        {
            $payment_status = $user->getSubscription()->getPaymentStatus();
            // 支払済かつ、登録時間が現在よりも後の場合は視聴可能
            $valid = new \DateTime() < $user->getSubscription()->getValidTo();
            if($payment_status != null && $valid)
            {
                $this->isSubscriptionValid = true;
            }
        }
    }

    public function check()
    {
        if($this->isSubscriptionValid)
        {
            return null;
        }
        else
        {
            static $video = Video::videoForNotLoggedInOrNoMembers;
            return $video;
        }
    }
}
