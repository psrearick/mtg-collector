<?php


namespace App\App\Client\Repositories;


use App\Domain\Cards\Models\Card;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CardRepository
{
    public Builder $card;

    public function __construct(?Card $card = null)
    {
        $this->card = $card ? $card->newQuery() : Card::query();
    }

    /**
     * Set the select of the query
     *
     * @param array|string[] $columns
     * @return Builder
     */
    public function select(array $columns = ['*']) : Builder
    {
        return $this->card = $this->card->select($columns);
    }

    /**
     * Add to the query a where on the specified field compared to the provided value
     *
     * @param string $field
     * @param string $value
     * @return Builder
     */
    public function equals(string $field, string $value) : Builder
    {
        return $this->card = $this->card->where('cards.' . $field, '=', $value);
    }

    /**
     * Limit results to those within the specified sets
     *
     * @param array $sets
     * @return Builder
     */
    public function filterOnSets(array $sets) : Builder
    {
        return $this->card =  $this->card->whereIn('sets.id', $sets)
            ->leftJoin('sets', 'sets.id', '=', 'cards.set_id');
    }

    /**
     * get results
     *
     * @return Collection
     */
    public function get() : Collection
    {
        return $this->card->get();
    }

    /**
     * Query the specified field for terms starting with the provided value
     *
     * @param string $field
     * @param string $value
     * @return Builder
     */
    public function startsWith(string $field, string $value) : Builder
    {
        return $this->card =  $this->card->where('cards.' . $field, 'LIKE', $value . '%');
    }

    /**
     * Filter out records that are only available in an online format
     *
     * @return Builder
     */
    public function withoutOnline() : Builder
    {
        return $this->card =  $this->card->where('cards.isOnlineOnly', '=', false);
    }

    /**
     * Eager loading
     *
     * @param array $relations
     * @return Builder
     */
    public function with(array $relations) : Builder
    {
        return $this->card = $this->card->with($relations);
    }

    /**
     * Lazy Eager Loading
     *
     * @param array $attributes
     * @return Builder
     */
    public function loadAttribute(array $attributes) : Builder
    {
        foreach ($attributes as $attribute) {
            $key   = $attribute;
            $value = $attribute;

            if (is_array($attribute)) {
                $key   = $attribute['key'];
                $value = $attribute['value'];
            }

            foreach ($this->card as $item) {
                $item->{$key} = $item->{$value};
            }
        }

        return $this->card;
    }
}
