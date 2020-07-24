<?php

/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

namespace Scarce\EntityForm;

use JsonSerializable;

class Button implements JsonSerializable{


    public $data;

    public function __construct(string $name)
    {
        $this->data = [
            "button_name" => $name,
            "data" => null,
            "mode" => 0,
            "text" => "",
            "type" => 1,
        ];
    }

    public function jsonSerialize()
    {
        return $this->data;
    }
}
