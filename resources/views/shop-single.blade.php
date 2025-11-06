@extends('layouts.app')

@section('content')
    <section class="bg-light">
        <div class="container pb-5">
            <div class="row">
                {{-- Product Image --}}
                <div class="col-lg-5 mt-5">
                    <div class="card mb-3">
                        <img class="card-img img-fluid" src="{{ $product->image_url }}" alt="{{ $product->name }}"
                            style="object-fit: cover;">
                    </div>
                </div>

                {{-- Product Details --}}
                <div class="col-lg-7 mt-5">
                    <div class="card">
                        <div class="card-body">
                            {{-- Product Name --}}
                            <h1 class="h2">{{ $product->name }}</h1>

                            {{-- Product Price --}}
                            <p class="h3 py-2">${{ number_format($product->price, 2) }}</p>

                            {{-- Rating (Static placeholder) --}}
                            <p class="py-2">
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-warning"></i>
                                <i class="fa fa-star text-secondary"></i>
                                <span class="list-inline-item text-dark">Rating 4.8 | 36 Comments</span>
                            </p>

                            {{-- Category --}}
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <h6>Category:</h6>
                                </li>
                                <li class="list-inline-item">
                                    <p class="text-muted">
                                        <strong>{{ $product->category->name ?? 'Uncategorized' }}</strong>
                                    </p>
                                </li>
                            </ul>

                            {{-- Description --}}
                            <h6>Description:</h6>
                            <p>{{ $product->description ?? 'No description available.' }}</p>

                            {{-- Add to Cart Form --}}
                            <form action="{{ route('cart.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">

                                <div class="row pb-3">
                                    <div class="col d-grid">
                                        <button type="submit" class="btn btn-success btn-lg" name="submit"
                                            value="addtocart">
                                            Add To Cart
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
