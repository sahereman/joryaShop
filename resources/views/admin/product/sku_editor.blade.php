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
    <div class="box-body table-responsive no-padding">
        <form id="sku_editor_form" role="form" method="POST" enctype="multipart/form-data" action="{{ route('admin.products.sku_editor_store', ['product' => $product->id]) }}">
            {{ csrf_field() }}
            <table class="table table-hover">

                <tr>
                    @if($product->skus->first())
                        <th>Photo</th>
                        @foreach($product->skus->first()->attr_value_options as $option)
                            <th>{{$option['attr']['name']}}</th>
                        @endforeach
                        <th>Delta Price</th>
                        <th>Stock</th>
                        <th>Stock Increment</th>
                        <th>Stock Decrement</th>
                    @endif
                </tr>

                @foreach($product->skus as $sku)
                    <tr>
                        <td>
                            {{--<input type="file" id="skus[{{ $sku->id }}][photo]" name="skus[{{ $sku->id }}][photo]" value="{{ $sku->photo }}">--}}
                            <img src="{{ $sku->photo_url }}" style="max-width:60px;max-height:200px" class="img img-thumbnail">
                        </td>
                        @foreach($sku->attr_values as $value)
                            <td>{{$value['value']}}</td>
                        @endforeach
                        <td>
                            <input class="form-control" type="text" id="skus[{{ $sku->id }}][delta_price]" name="skus[{{ $sku->id }}][delta_price]" value="{{ $sku->delta_price }}">
                        </td>
                        <td>
                            <input class="form-control" type="text" id="skus[{{ $sku->id }}][stock]" name="skus[{{ $sku->id }}][stock]" value="{{ $sku->stock }}">
                        </td>
                        <td>
                            <input class="form-control" type="text" id="skus[{{ $sku->id }}][stock_increment]" name="skus[{{ $sku->id }}][stock_increment]" value="0">
                        </td>
                        <td>
                            <input class="form-control" type="text" id="skus[{{ $sku->id }}][stock_decrement]" name="skus[{{ $sku->id }}][stock_decrement]" value="0">
                        </td>
                    </tr>
                @endforeach

            </table>
            <button type="submit" class="btn btn-primary btn-lg btn-block">提交</button>
        </form>
    </div>
</div>

<script type="text/javascript">
    //
</script>
