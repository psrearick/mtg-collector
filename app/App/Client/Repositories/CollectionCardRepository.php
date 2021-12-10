<?php

namespace App\App\Client\Repositories;

use App\App\Base\Repository;
use App\Domain\Cards\Actions\GetComputed;
use App\Domain\Cards\Models\Card;
use App\Domain\Collections\Models\Collection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CollectionCardRepository extends Repository
{
//    public string $class = 'App\Domain\Collections\Models\Collection';
//
//    public string $table = 'card_collections';
//
//    /**
//     * @param Collection $collection
//     * @param Card $card
//     * @param bool $foil
//     * @return bool
//     */
//    public function detachCollectionCard(Collection $collection, Card $card, bool $foil) : bool
//    {
//        DB::table('card_collections')
//            ->where('collection_id', $collection->id)
//            ->where('card_id', $card->id)
//            ->where('foil', $foil)
//            ->delete();
//
//        return true;
//    }
//
//    /**
//     * @param Collection $collection
//     * @param Card $card
//     * @param bool $foil
//     * @return object
//     */
//    public function getCollectionCard(Collection $collection, Card $card, bool $foil) : ?object
//    {
//        return $collection->cards()
//            ->where('cards.id', $card->id)
//            ->where('foil', $foil)
//            ->first();
//    }
//
//    /**
//     * @param Request $request
//     * @return Response
//     */
//    public function updateCollectionCard(Request $request) : response
//    {
//        $change = $request['change'];
//        if (!$change) {
//            // No change data was provided
//            return response('No change');
//        }
//
//        if (!array_key_exists('quantity', $change)) {
//            $change['quantity'] = false;
//        }
//
//        if (!array_key_exists('change', $change)) {
//            $change['change'] = false;
//        }
//
//        if ($change['change'] == 0 && !$change['quantity']) {
//            // No change was provided... how did we get here?
//            return response('No change');
//        }
//
//        // Get records
//        $collection     = Collection::find($request['collection']);
//        $card           = Card::find($change['id']);
//        $collectionCard = $this->getCollectionCard($collection, $card, $change['foil']);
//        $computed       = new GetComputed($card);
//        $computedCard   = $computed->add('priceNormal')->add('priceFoil')->get();
//
//        if (!$collectionCard && $change['change'] && $change['change'] < 1) {
//            // The change must be positive
//            return response(null);
//        }
//
//        if (!$collectionCard && $change['quantity'] && $change['quantity'] < 1) {
//            // The quantity must be positive
//            return response(null);
//        }
//
//        if (!$collectionCard) {
//            // Add card to collection
//
//            $date_added = Carbon::now();
//            if (array_key_exists('date_added', $change)) {
//                $date_added = $change['date_added'] ?: $date_added;
//            }
//            $collection->cards()->attach($card->id, [
//                'price_when_added'  => $change['foil'] ? $computedCard->priceFoil : $computedCard->priceNormal,
//                'foil'              => $change['foil'],
//                'description'       => null,
//                'condition'         => null,
//                'quantity'          => $change['quantity'] ?: $change['change'],
//                'date_added'        => $date_added,
//            ]);
//            $collectionCard = $this->getCollectionCard($collection, $card, $change['foil']);
//
//            return response(['message' => 'Card added to collection',
//                'collectionCard'       => $collectionCard->pivot->toArray(), ]);
//        }
//
//        if (!$change['change'] && $change['quantity'] == 0) {
//            // Remove card from collection
//            $this->detachCollectionCard($collection, $card, $change['foil']);
//
//            return response(['message' => 'Card removed from collection']);
//        }
//
//        if ($change['change'] && $collectionCard->pivot->quantity + $change['change'] < 1) {
//            // Remove card from collection
//            $this->detachCollectionCard($collection, $card, $change['foil']);
//
//            return response(['message' => 'Card removed from collection']);
//        }
//
//        // update relation quantity and price
////        $quantity       = $collectionCard->pivot->quantity;
////        $price          = $collectionCard->pivot->price_when_added;
////        $cost           = $quantity * $price;
////        $increase       = $change['change'] > 0;
////        $currentPrice   = $change['foil'] ? $card->price_foil : $card->price_normal;
////        $newCost        = $increase ? $cost + $currentPrice : $cost - $currentPrice;
//        $newQuantity    = $collectionCard->pivot->quantity + $change['change'];
////        $newPrice       = $newCost / $newQuantity;
//
//        $newPrice = $change['foil'] ? $computedCard->priceFoil : $computedCard->priceNormal;
//
//        $changed = [
//            'quantity'          => $newQuantity,
//            'price_when_added'  => $newPrice,
//        ];
//
//        if ($change['quantity']) {
//            $changed = [
//                'quantity' => $change['quantity'],
//            ];
//        }
//
//        if (array_key_exists('date_added', $change) && $change['date_added']) {
//            $changed['data_added'] = $change['data_added'];
//        }
//
//        DB::table('card_collections')
//            ->where('collection_id', $collection->id)
//            ->where('card_id', $card->id)
//            ->where('foil', $change['foil'])
//            ->update($changed);
//        $collectionCard = $this->getCollectionCard($collection, $card, $change['foil']);
//
//        return response(['message' => 'Card quantity was updated',
//            'collectionCard'       => $collectionCard->pivot->toArray(), ]);
//    }
}
