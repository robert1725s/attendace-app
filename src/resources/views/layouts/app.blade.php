<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '勤怠管理')</title>
    <link rel="stylesheet" href="{{ asset('css/common/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common/header.css') }}">
    @yield('css')
</head>

<body @auth @if(!request()->is('verify_email')) class="with-bg" @endif @endauth>
    @include('components.header')

    <main>
        @yield('content')
    </main>
</body>

</html>
