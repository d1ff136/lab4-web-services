<!doctype html>
<html lang="uk">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#f2efe7" />
    <title>@yield('title', 'Головна') — Гардероб</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
    <script src="{{ asset('js/partners.js') }}" defer></script>
  </head>
  <body class="page">
    <a class="skip-link" href="#main-content">Перейти до вмісту</a>

    <header class="site-header">
      <a class="site-header__brand" href="{{ route('home') }}">
        <span class="site-header__mark" aria-hidden="true">Г</span>
        <span>Гардероб</span>
      </a>
      <nav class="site-nav" aria-label="Головна навігація">
        <a
          class="site-nav__link {{ request()->routeIs('home') ? 'site-nav__link--active' : '' }}"
          href="{{ route('home') }}"
          @if (request()->routeIs('home')) aria-current="page" @endif
          >Головна</a
        >
        <a
          class="site-nav__link {{ request()->routeIs('catalog.*') ? 'site-nav__link--active' : '' }}"
          href="{{ route('catalog.index') }}"
          @if (request()->routeIs('catalog.*')) aria-current="page" @endif
          >Каталог</a
        >
      </nav>
      <div class="site-header__tools">
        <x-notification-center />
      </div>
    </header>

    <main class="page__main" id="main-content">
      @yield('content')
    </main>

    @yield('after-main')

    <footer class="site-footer">
      <p class="site-footer__text">Могилін Владислав Олександрович · Група 6.1224</p>
      <p class="site-footer__meta">
        Лабораторна робота 4 · Варіант 7 · тема Market Grid · Laravel + MySQL
      </p>
    </footer>
  </body>
</html>