<?php

namespace App\Domain\Collections\DataActions;

use App\Domain\Base\DataAction;
use App\Domain\Cards\Models\Card;
use App\Domain\Collections\Models\Collection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CollectionCardDataAction extends DataAction
{
    /**
     * Create a new card for a collection
     *
     * @param array $query
     *      collection => (App\Domain\Collections\Models\Collection) collection model
     *      card_id    => (int) id of card to retrieve
     *      foil       => (bool) if the card is foil
     *      price      => (?float)current price
     *      quantity   => (?int) quantity of collection to add
     *      date_added => (?date string) date the card was added
     * @return Card
     */
    public function createCollectionCard(array $query) : Card
    {
        return $query['collection']->cards()->attach($query['card_id'], [
            'price_when_added'  => array_key_exists('price', $query)
                                        ? $query['price']
                                        : null,
            'foil'              => $query['foil'],
            'quantity'          => $query['quantity'],
            'date_added'        => array_key_exists('date', $query)
                                        ? $query['date']
                                        : null,
        ]);
    }

    /**
     * Retrieve a card record from a collection relation
     *
     * @param array $query
     *      collection => (App\Domain\Collections\Models\Collection) collection model
     *      card_id    => (int) id of card to retrieve
     *      foil       => (bool) if the card is foil
     *      date       => The date the card was added
     * @return Card|null
     */
    public function getCollectionCard(array $query) : ?Card
    {
        return $query['collection']->cards()
            ->where('cards.id', '=', $query['card_id'])
            ->where('foil', '=', $query['foil'])
            ->where('date_added', '=', $query['date_added'])
            ->first();
    }

    /**
     * update the quantity
     * The new quantity is $request->input('quantity')
     *
     * @param Request $request
     * @return Response
     */
    public function setQuantity(Request $request) : Response
    {
        return response('set '
            . $request->input('id') . ': '
            . $request->input('quantity'));
    }

    public function updateCollectionCard(Request $request) : Response
    {
//        "change" => 1 or "quantity" => 5
//        "id" => 4288
//        "foil" => false
//        "collection" => 3
//        "date" = 2021-06-25

//        if (!$request->input('id')) {
//            return response('no record found');
//        }

        if ($request->input('change')) {
            return $this->recordQuantityChange($request);
        }

        if ($request->input('quantity') || $request->input('quantity') === 0) {
            return $this->setQuantity($request);
        }

        return response('No change');
    }

    /**
     * Update the quantity
     * The quantity has changed by $request->input('change')
     *
     * @param Request $request
     * @return Response
     */
    private function recordQuantityChange(Request $request) : Response
    {
        $date            = $request->filled('date')
                                ? $request->input('date') : Carbon::now();
        $collection      = Collection::find($request->input('collection'));
        $collectionCard  = $this->getCollectionCard([
            'collection' => $collection,
            'card_id'    => $request->input('id'),
            'foil'       => $request->input('foil'),
            'date_added' => $date,
        ]);

        return response('changed '
            . $request->input('id') . ': '
            . $request->input('change'));
    }
}
