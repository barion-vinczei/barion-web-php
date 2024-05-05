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
 
namespace Barion\Models\Error;

use Barion\Helpers\JSON;

class ApiErrorModel
{
    public ?string $Title;
    public ?string $ErrorCode;
    public ?string $Description;
    public ?string $HappenedAt;
    public ?string $AuthData;
    public ?string $EndPoint;
    public ?string $PaymentId;

    function __construct()
    {
        $this->Title = null;
        $this->ErrorCode = null;
        $this->Description = null;
        $this->HappenedAt = null;
        $this->AuthData = null;
        $this->EndPoint = null;
        $this->PaymentId = null;
    }

    /** @param array<object> $json */
    public function fromJson(array $json) : void
    {
        if (!empty($json)) {
            $this->ErrorCode = JSON::getString($json, 'ErrorCode');
            $this->Title = JSON::getString($json, 'Title');
            $this->Description = JSON::getString($json, 'Description');
            $this->HappenedAt = JSON::getString($json, 'HappenedAt');
            $this->AuthData = JSON::getString($json, 'AuthData');
            $this->EndPoint = JSON::getString($json, 'EndPoint');
            
            if (array_key_exists('PaymentId', $json)) {
                $this->PaymentId = JSON::getString($json, 'PaymentId');
            }
        }
    }
}