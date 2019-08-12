<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<div class="main">
    <div class="header" style="width: 1200px;margin: auto;height: 50px;border-bottom: 1px solid #ccc;padding: 20px 40px;">
        <img src="{{ asset('img/logo2.png') }}" alt="Lyricalhair">
    </div>
    <div class="content" style="width: 600px;margin: auto;text-align: left">
        <h4>Hello,</h4>
        <p>I love this product on <a href="https://www.lyricalhair.com/" style="text-decoration: underline;color: #0000cc">LYRICALHAIR.COM</a> and thought you might too!</p>
        <img src="{{ $product->photos[0] }}" alt="lyricalhair.com">
        <a href="{{ route('seo_url', ['slug' => $product->slug]) }}" style="text-decoration: underline;color: #0000cc;display: block;">{{ $product->name_en }}</a>
        <p style="font-size: 14px;">{{ $product->description_en }}</p>
        <img src="{{ asset('img/star-5.png') }}" alt="" style="width: 20%;">
        <p>{{ \App\Models\ExchangeRate::$symbolMap['USD'] }} {{ $product->price }}</p>
        <a href="{{ route('seo_url', ['slug' => $product->slug]) }}">{{ route('seo_url', ['slug' => $product->slug]) }}</a>
    </div>
</div>
</body>
</html>
