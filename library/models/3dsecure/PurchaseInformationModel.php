<?php

/**
 * Copyright 2016 Barion Payment Inc. All Rights Reserved.
 * <p/>
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * <p/>
 * http://www.apache.org/licenses/LICENSE-2.0
 * <p/>
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Barion\Models\ThreeDSecure;

use Barion\Helpers\JSON;

use Barion\Enumerations\ThreeDSecure\{
    AvailabilityIndicator,
    DeliveryTimeframeType,
    PurchaseType,
    ReOrderIndicator,
    ShippingAddressIndicator
};

class PurchaseInformationModel implements \Barion\Interfaces\IBarionModel
{
    public DeliveryTimeframeType $DeliveryTimeframe;
    public ?string $DeliveryEmailAddress;
    public ?string $PreOrderDate;
    public AvailabilityIndicator $AvailabilityIndicator;
    public ReOrderIndicator $ReOrderIndicator;
    public ?string $RecurringExpiry;
    public ?string $RecurringFrequency;
    public ShippingAddressIndicator $ShippingAddressIndicator;
    public ?object $GiftCardPurchase;
    public PurchaseType $PurchaseType;
    public ?string $PurchaseDate;

    function __construct()
    {
        $this->DeliveryTimeframe = DeliveryTimeframeType::Unspecified;
        $this->DeliveryEmailAddress = null;
        $this->PreOrderDate = null;
        $this->AvailabilityIndicator = AvailabilityIndicator::Unspecified;
        $this->ReOrderIndicator = ReOrderIndicator::Unspecified;
        $this->RecurringExpiry = null;
        $this->RecurringFrequency = null;
        $this->ShippingAddressIndicator = ShippingAddressIndicator::Unspecified;
        $this->GiftCardPurchase = null;
        $this->PurchaseType = PurchaseType::Unspecified;
        $this->PurchaseDate = null;
    }

    public function fromJson(array $json) : void
    {
        if (!empty($json)) {
            $this->DeliveryTimeframe = DeliveryTimeframeType::from(JSON::getString($json, 'DeliveryTimeframe') ?? '');
            $this->DeliveryEmailAddress = JSON::getString($json, 'DeliveryEmailAddress');
            $this->PreOrderDate = JSON::getString($json, 'PreOrderDate');
            $this->AvailabilityIndicator = AvailabilityIndicator::from(JSON::getString($json, 'AvailabilityIndicator') ?? '');
            $this->ReOrderIndicator = ReOrderIndicator::from(JSON::getString($json, 'ReOrderIndicator') ?? '');
            $this->RecurringExpiry = JSON::getString($json, 'RecurringExpiry');
            $this->RecurringFrequency = JSON::getString($json, 'RecurringFrequency');
            $this->ShippingAddressIndicator = ShippingAddressIndicator::from(JSON::getString($json, 'ShippingAddressIndicator') ?? '');
            $this->GiftCardPurchase = JSON::getObject($json, 'GiftCardPurchase');
            $this->PurchaseType = PurchaseType::from(JSON::getString($json, 'PurchaseType') ?? '');
            $this->PurchaseDate = JSON::getString($json, 'PurchaseDate');
        }
    }
}