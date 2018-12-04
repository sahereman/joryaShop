{{--TODO ...--}}

<h3>DEMO PAGE:</h3>

route: articles/{slug}
<br>
<br>
route('articles.show', ['slug' => $slug]);
<br>
<br>
GET articles.show:
<br>
<br>
show content by slug ...
<br>
<br>
CONTENT:
<br>
<br>
{{ $content }}
