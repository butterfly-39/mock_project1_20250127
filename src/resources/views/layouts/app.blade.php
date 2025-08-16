<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @auth
        <meta name="auth-check" content="true">
    @endauth
    @yield('css')
</head>

<body>
    <div class="app {{ Request::is('mypage*') ? 'mypage' : (Request::is('index*') ? 'index' : (Request::is('purchase*') ? 'purchase' : '')) }}">
        <header class="header">
            <h1 class="header__heading">
                <a href="/">
                    <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH" class="header__logo">
                </a>
            </h1>
            @unless(Request::is('buyers/chat*') || Request::is('sellers/chat*'))
            <ul class="header-nav">
                @if (Auth::check())
                <li class="header-nav__item--search">
                    <form class="header-search" action="/" method="get">
                        <div class="header-search__container">
                            @if(request()->has('tab'))
                                <input type="hidden" name="tab" value="{{ request()->get('tab') }}">
                            @endif
                            <input type="text" name="query" class="header-search__input" value="{{ request()->get('query') }}" placeholder="なにをお探しですか？">
                        </div>
                    </form>
                </li>
                <li class="header-nav__item">
                    <form class="header-logout" action="/logout" method="post">
                        @csrf
                        <button type="submit" class="header-nav__link">ログアウト</button>
                    </form>
                </li>
                <li class="header-nav__item">
                    <a class="header-nav__link" href="/mypage">マイページ</a>
                </li>
                <li class="header-nav__item">
                    <a class="header-nav__button" href="/sell">出品</a>
                </li>
                @else
                <li class="header-nav__item">
                    <a class="header-nav__link" href="/login">ログイン</a>
                </li>
                <li class="header-nav__item">
                    <a class="header-nav__link" href="/mypage">マイページ</a>
                </li>
                <li class="header-nav__item">
                    <a class="header-nav__button" href="/sell">出品</a>
                </li>
                @endif
            </ul>
            @endunless
            @yield('link')
        </header>
        <div class="content">
            @yield('content')
        </div>
    </div>
    @yield('scripts')
</body>

</html>