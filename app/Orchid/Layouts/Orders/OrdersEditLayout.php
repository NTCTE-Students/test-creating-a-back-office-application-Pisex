<?php

namespace App\Orchid\Layouts\Orders;

use App\Models\Order;
use App\Models\Product;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;

class OrdersEditLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'products';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('user', __('User'))
                ->sort()
                ->filter(Input::make())
                ->render(function (Product $product) {
                    return $product->name;
                }),
            TD::make('count', __('Count'))
                ->sort()
                ->filter(Input::make())
                ->render(function (Product $product) {
                    return $product->count;
                }),
        ];
    }
}
