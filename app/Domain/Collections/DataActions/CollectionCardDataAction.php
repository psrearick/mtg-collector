<?php

namespace App\Domain\Collections\DataActions;

use App\Domain\Base\DataAction;
use App\Domain\Cards\Models\Card;
use App\Domain\Collections\Models\Collection;
use App\Exceptions\UpdateCollectionCardException;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Domain\Collections\DataActions\UpdateCollectionCardQuantity;

class CollectionCardDataAction extends DataAction
{
    private UpdateCollectionCardQuantity $updateCollectionCardQuantity;

    public function __construct(UpdateCollectionCardQuantity $updateCollectionCardQuantity)
    {
        $this->updateCollectionCardQuantity = $updateCollectionCardQuantity;
    }

    public function execute(array $request) : array
    {
        if (!$this->isValidRequest($request)) {
            throw new UpdateCollectionCardException('Invalid request');
        }
            
        return $this->updateCollectionCardQuantity->execute($request);
    }

    private function isValidRequest(array $request) : bool
    {
        if (!$request['id']) {
            return false;
        }

        if (!isset($request['change']) && !isset($request['quantity'])) {
            return false;
        }

        if (!$request['collection']) {
            return false;
        }

        return true;
    }
}
