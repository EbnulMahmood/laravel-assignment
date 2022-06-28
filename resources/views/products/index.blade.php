@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" name="title" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control">
                        <optgroup label="Color">
                        @foreach ($colorVariants as $variant)
                            <option value="{{ $variant->id }}">{{ $variant->variant }}</option>
                        @endforeach
                        <optgroup label="Size">
                        @foreach ($sizeVariants as $variant)
                            <option value="{{ $variant->id }}">{{ $variant->variant }}</option>
                        @endforeach
                        <optgroup label="Style">
                        @foreach ($styleVariants as $variant)
                            <option value="{{ $variant->id }}">{{ $variant->variant }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" name="price_to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($products as $key => $product)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $product->title }} <br> Created at : {{ $product->created_at->diffForHumans() }}</td>
                        <td>{{ $product->description }}</td>
                        <td>
                            @php
                                $variants = DB::table('product_variants')
                                    ->where('product_id', $product->id)
                                    ->orderBy('variant', 'ASC')
                                    ->get();
            
                                $product_variant_prices = DB::table('product_variant_prices')
                                    ->where('product_id', $product->id)
                                    ->get();
                            @endphp
                            <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">

                                <dt class="col-sm-3 pb-0">
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
                                </dt>
                                <dd class="col-sm-9">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4 pb-0">
                                            @foreach($product_variant_prices as $variant_price)
                                                Price : {{ number_format($variant_price->price,2) }}
                                            @endforeach
                                        </dt>
                                        <dd class="col-sm-8 pb-0">
                                            @foreach($product_variant_prices as $variant_price)
                                                InStock : {{ number_format($variant_price->stock,2) }}
                                            @endforeach
                                        </dd>
                                    </dl>
                                </dd>
                            </dl>
                            <button onclick="$('#variant').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('product.edit', $product) }}" class="btn btn-success">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <p>Showing {{ $products->firstItem() }} to {{ $products->lastItem() }}
                        out of {{$products->total()}} entries</p>
                </div>
                <div class="col-md-2">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection
