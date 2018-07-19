@if (count($errors) > 0)
    <center>
        <h4>有错误发生：</h4>
        <ul>
            @foreach ($errors->all() as $error)
                <li> {{ $error }}</li>
            @endforeach
        </ul>
    </center>
@endif