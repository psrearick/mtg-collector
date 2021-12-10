<?php

namespace App\Enums;

use App\Enums\Traits\HasObjectArray;
use BenSampo\Enum\Enum;

/**
 * @method static static Common()
 * @method static static Uncommon()
 * @method static static Rare()
 * @method static static Special()
 * @method static static Mythic()
 * @method static static Bonus()
 */
final class Rarity extends Enum
{
    use HasObjectArray;

    const Bonus         = 'Bonus';

    const Common        = 'Common';

    const Mythic        = 'Mythic';

    const Rare          = 'Rare';

    const Special       = 'Special';

    const Uncommon      = 'Uncommon';
}
