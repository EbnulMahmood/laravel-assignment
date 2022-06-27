@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Variant</th>
                            <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <th scope="row">{{ $product->id }}</th>
                                <td>{{ $product->title }} Created at: 
                                    {{ $product->created_at->diffForHumans() }}</td>
                                <td>{{ $product->description }}</td>
                                @php
                                    $num_page = 40;
                                    $variants = DB::table('product_variants')
                                        ->where('product_id', $product->id)
                                        ->orderBy('variant', 'ASC')
                                        ->paginate($num_page);

                                    $product_variant_prices = DB::table('product_variant_prices')
                                        ->where('product_id', $product->id)
                                        ->paginate($num_page);
                                @endphp
                                <td>
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    @foreach($product_variant_prices as $variant_price)
                                                        @php
                                                            $variantOne = App\Models\ProductVariant::find($variant_price->product_variant_one);
                                                            $variantTwo = App\Models\ProductVariant::find($variant_price->product_variant_two);
                                                            $variantThree = App\Models\ProductVariant::find($variant_price->product_variant_three);
                                                        @endphp
                                                        {{ $variantOne->variant }}\ 
                                                        {{ $variantTwo->variant }}\ 
                                                        {{ $variantThree ? $variantThree->variant : '' }}
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($product_variant_prices as $variant_price)
                                                        Price: {{ $variant_price->price }}
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @foreach($product_variant_prices as $variant_price)
                                                        InStock: {{ $variant_price->stock }}
                                                    @endforeach
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <a type="button">show more...</a>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-success">Edit</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="bg-light d-flex justify-content-between">
                        <div>
                            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }}
                            out of {{$products->total()}} entries
                        </div>
                        <div>
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
