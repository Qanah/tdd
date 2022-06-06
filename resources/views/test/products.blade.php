<ul>
    @foreach($products as $product)
        <li>{{ $product->code }} : {{ $product->name }} (${{number_format($product->price, 2)}}) <a href="{{ route('scan-product', $product->code) }}">Scan</a></li>
    @endforeach
</ul>

Total = {{ $total }}
