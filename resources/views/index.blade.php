@include('includes.header')

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h1 class="my-4">Главная</h1>
            <p class="lead">Выберите категорию</p>
        </div>
        @foreach ($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h2 class="card-title">{{ $product->name }}</h2>
                        <h4 class="card-desc">{{ $product->description }}</h4>
                        <h3 class="card-desc">Цена: {{ $product->price }}</h3>
                        <h3>Количество: {{ $product->count }}</h3>
                        @auth
                            @php
                                $inBasket = \App\Models\Basket::where('user_id', auth()->id())
                                    ->where('product_id', $product->id)
                                    ->first();
                            @endphp
                            @if ($inBasket)
                                <div style="display: flex; align-items: center;">
                                    <form action="{{ route('cart.decrease', $product) }}" method="POST" style="margin-right: 10px;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">-</button>
                                    </form>
                                    {{ $inBasket->count }}
                                    <form action="{{ route('cart.increase', $product) }}" method="POST" style="margin-left: 10px;">
                                        @csrf
                                        <button type="submit" class="btn btn-success">+</button>
                                    </form>
                                </div>
                            @else
                                <form action="{{ route('cart.add', $product) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Добавить в корзину</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@include('includes.footer')
