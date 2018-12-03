{{--TODO ...--}}

route: articles/{slug}
route('articles.show', ['slug' => $slug]);
GET articles.show:
show content by slug ...

CONTENT:

{{ $content }}
