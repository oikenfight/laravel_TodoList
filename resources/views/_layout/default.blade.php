<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    {{--<title>{{ $metadata->page_title }}</title>--}}
    <title>Todo List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <style>
        {{-- インラインのCSSブロック。各ページで追記ができる --}}
        @section('inline-style')
            footer {
            margin-bottom: 5em;
            }
        @show
        {{-- セクションにこの場所を展開させたい場合、@showを指定する --}}
    </style>

</head>

<body>
    {{-- ここに各ページの内容が展開される --}}
    @yield('content')

    {{-- 'app/views/partials/footer.php'をインクルード --}}
    {{--@include('partials.footer')--}}


    <script src="{{ asset("https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js") }}"></script>
    <script src="{{ asset("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js") }}" defer="defer"></script>
    <script type="text/javascript">
        {{-- インラインのjavascriptブロック。各ページで追記可能 --}}
        @section('inline-script')
        @show
        {{-- セクションをこの場所に展開させたい場合、@showを指定する --}}

    </script>

</body>
</html>