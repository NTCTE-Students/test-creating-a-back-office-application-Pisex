<?php

namespace App\Orchid\Screens\Orders;

use App\Models\Order;
use App\Models\User;
use App\Orchid\Layouts\Orders\OrdersEditLayout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Select;

class OrdersEditScreen extends Screen
{
    public $order;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Order $order): iterable
    {
        return [
            'order' => $order,
            'products' => $order->products,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Edit order';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Update')
                ->icon('note')
                ->method('Update')
                ->canSee($this->order->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('Remove')
                ->canSee($this->order->exists),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Relation::make('order.user_id')
                    ->title('Author')
                    ->fromModel(User::class, 'name'),
                Select::make('order.status')
                    ->options([
                        'paid'   => 'Оплачено',
                        'done' => 'Готово',
                    ])
                    ->title('Status'),
            ]),
            (new OrdersEditLayout()) -> title('Products')
        ];
    }

    public function Update(Request $request)
    {
        $this->order->fill($request->get('order'))->save();

        Alert::info('You have successfully updated order.');

        return redirect()->route('platform.orders.list');
    }

    public function Remove()
    {
        $this->order->delete();

        Alert::info('You have successfully deleted the order.');

        return redirect()->route('platform.orders.list');
    }
}
