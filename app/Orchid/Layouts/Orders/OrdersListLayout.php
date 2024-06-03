<?php

namespace App\Orchid\Layouts\Orders;

use App\Models\Order;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;

class OrdersListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'orders';

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
                ->render(function (Order $order) {
                    return Link::make($order->user->name)
                        ->route('platform.orders.edit', $order);
                }),
            TD::make('status', __('Status'))
                ->sort()
                ->filter(Input::make())
                ->render(function (Order $order) {
                    return $order->status;
                }),
        ];
    }
}
