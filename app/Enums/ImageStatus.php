<?php

namespace App\Enums;

use App\Enums\Traits\HasObjectArray;
use BenSampo\Enum\Enum;

/**
 * @method static static Missing()
 * @method static static Placeholder()
 * @method static static Lowres()
 * @method static static HighresScan()
 */
final class ImageStatus extends Enum
{
    use HasObjectArray;

    const HighresScan    = 'High Resolution Scan';

    const Lowres        = 'Low Resolution';

    const Missing       =   'Missing';

    const Placeholder   =   'Placeholder';
}
