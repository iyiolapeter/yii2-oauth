<?php

namespace pso\yii2\oauth\events;

use yii\base\Event;

class AutocompleteEvent extends Event
{
    public $type;
    public $response = [];
}