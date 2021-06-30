<?php

namespace App\Enums;

use App\Enums\Traits\HasObjectArray;
use BenSampo\Enum\Enum;

/**
 * @method static static Black()
 * @method static static Borderless()
 * @method static static Gold()
 * @method static static Silver()
 * @method static static White()
 */
final class BorderColor extends Enum
{
    use HasObjectArray;

    const Black         = 'Black';

    const Borderless    = 'Borderless';

    const Gold          = 'Gold';

    const Silver        = 'Silver';

    const White         = 'White';
}
