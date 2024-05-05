<?php

namespace Barion\Enumerations\ThreeDSecure;

enum DeliveryTimeframeType : string
{
    case Unspecified = "";
    case ElectronicDelivery = "ElectronicDelivery";
    case SameDayShipping = "SameDayShipping";
    case OvernightShipping = "OvernightShipping";
    case TwoDayOrMoreShipping = "TwoDayOrMoreShipping";
}

?>