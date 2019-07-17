@if ($messages)
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        @foreach ($messages as $message)
            <h4>
                <i class="icon fa fa-ban"></i>
                {{ $message[0] }}
            </h4>
            <p></p>
            @break
        @endforeach
    </div>
@endif
<div class="box">
    <form id="sku_editor_form" role="form" method="POST" enctype="multipart/form-data"
          action="{{ route('admin.products.sku_editor_store', ['product' => $product->id]) }}">
        {{ csrf_field() }}
        <div class="box-body">
            <div class="container-fluid">
                @foreach($product->skus as $sku)
                    <div class="row">
                        <label>ID</label>
                        <span>{{ $sku->id }}</span>
                        {{--<input type="hidden" name="skus[{{ $sku->id }}][id]" value="{{ $sku->id }}">--}}
                        <label>Attributes</label>
                        <span>{{ $sku->attr_value_string }}</span>
                        {{--<label for="skus[{{ $sku->id }}][photo]">Photo</label>
                        <input type="file" id="skus[{{ $sku->id }}][photo]" name="skus[{{ $sku->id }}][photo]"
                               value="{{ $sku->photo }}">
                        <img src="{{ $sku->photo_url }}"/>--}}
                        <label for="skus[{{ $sku->id }}][price]">Price</label>
                        <input type="text" id="skus[{{ $sku->id }}][price]" name="skus[{{ $sku->id }}][price]"
                               value="{{ $sku->price }}">
                        <label for="skus[{{ $sku->id }}][stock]">Stock</label>
                        <input type="text" id="skus[{{ $sku->id }}][stock]" name="skus[{{ $sku->id }}][stock]"
                               value="{{ $sku->stock }}">
                        {{--<label for="skus[{{ $sku->id }}][sales]">Sales</label>
                        <input type="text" id="skus[{{ $sku->id }}][sales]" name="skus[{{ $sku->id }}][sales]"
                               value="{{ $sku->sales }}">--}}
                        <label for="skus[{{ $sku->id }}][stock_increment]">Stock Increment</label>
                        <input type="text" id="skus[{{ $sku->id }}][stock_increment]" name="skus[{{ $sku->id }}][stock_increment]">
                        <label for="skus[{{ $sku->id }}][stock_decrement]">Stock Decrement</label>
                        <input type="text" id="skus[{{ $sku->id }}][stock_decrement]" name="skus[{{ $sku->id }}][stock_decrement]">
                    </div>
                @endforeach
            </div>
        </div>
        <button type="submit">提交</button>
    </form>
</div>
<script type="text/javascript">
    //
</script>
