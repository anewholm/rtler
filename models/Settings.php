<?php

namespace Acorn\Rtler\Models;

use Model;


/**
 * It shifts the controller from right to left
 *
 * @package Acorn\Rtler
 * @author Jaber Rasul
 */
class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'acorn_rtler';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}
