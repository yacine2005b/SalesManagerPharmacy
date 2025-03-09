<div>
    <h1>Products</h1>
    @foreach ($products as $product)
    <div>
      <p>{{$product->name}}</p>
      <p>{{$product->description}}</p>
    </div>
  @endforeach
  </div>