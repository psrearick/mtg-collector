<?php


namespace App\App\Client\Repositories;


use App\Domain\Cards\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CardsRepository extends Repository
{
    public function getAllCardsPaginatedFromQuery(int $perPage, Request $request) : LengthAwarePaginator
    {
        $cards      = [];
        $query      = $request->q;
        if ($query) {
            $page    = $request->page ? (int) $request->page : null;
            $results = $this->searchQueryWith(Card::search($query), 'set')->get();
            $cards = $results->paginate($perPage, $results->count(), $page)->withQueryString();
        }
        return $cards ?: Card::with('set')->whereNotNull('name')->orderBy('name')->paginate($perPage);
    }

    public function searchQueryWith($query, string $with)
    {
        return $query->query(function ($builder) use ($with) {
            $builder->with($with);
        });
    }

    public function searchCardsStartingWith(string $card, array $sets): Collection
    {
        $q = Card::select('cards.*')
        ->where('cards.name', 'LIKE', $card . '%');
        if ($sets) {
            $q->whereIn('sets.id', $sets)
                ->leftJoin('sets', 'sets.id', '=', 'cards.set_id');
        }
        return $q->with('set')->limit(50)->get();
    }
}
