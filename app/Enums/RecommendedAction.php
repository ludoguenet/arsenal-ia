<?php

namespace App\Enums;

enum RecommendedAction: string
{
    case RequestMoreInformation = 'request_more_information';
    case Troubleshoot = 'troubleshoot';
    case OfferReplacement = 'offer_replacement';
    case ReviewRefund = 'review_refund';
    case ManualInspection = 'manual_inspection';
    case Other = 'other';
}
