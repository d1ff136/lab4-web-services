@extends('layouts.app')

@section('title', 'Головна')

@section('content')
  <section class="hero">
    <div class="hero__content">
      <p class="hero__eyebrow">Одяг · продаж від власників</p>
      <h1 class="hero__title">
        Одяг, який
        <em>вже підібраний.</em>
      </h1>
      <p class="hero__text">
        «Гардероб» — каталог оголошень про продаж одягу: сорочки, куртки, костюми та
        комплекти з розміром, матеріалом і кольором в одному місці та прямим контактом
        із продавцем.
      </p>
      <div class="hero__actions">
        <a class="button" href="{{ route('catalog.index') }}">Переглянути каталог</a>
      </div>
      <dl class="hero-stats">
        <div class="hero-stats__item">
          <dt class="hero-stats__value">{{ $total }}</dt>
          <dd class="hero-stats__label">оголошень</dd>
        </div>
        <div class="hero-stats__item">
          <dt class="hero-stats__value">{{ $categories->count() }}</dt>
          <dd class="hero-stats__label">категорії</dd>
        </div>
      </dl>
    </div>

    <form class="search-panel" action="{{ route('catalog.index') }}" method="get">
      @if ($errors->any())
        <div class="form-errors" role="alert">
          <ul class="form-errors__list">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="search-panel__fields">
        <label class="search-field search-field--wide">
          <span class="search-field__label">Пошук</span>
          <input class="search-field__control" type="search" name="q" maxlength="100"
            value="{{ old('q') }}" placeholder="Назва або опис" />
        </label>

        <label class="search-field">
          <span class="search-field__label">Категорія</span>
          <select class="search-field__control" name="category">
            <option value="">Усі категорії</option>
            @foreach ($categories as $category)
              <option value="{{ $category->name }}">{{ $category->name }}</option>
            @endforeach
          </select>
        </label>

        <label class="search-field">
          <span class="search-field__label">Спосіб угоди</span>
          <select class="search-field__control" name="operation">
            <option value="">Усі способи</option>
            @foreach ($operations as $operation)
              <option value="{{ $operation }}">{{ $operation }}</option>
            @endforeach
          </select>
        </label>
      </div>

      <button class="search-panel__submit" type="submit">
        Знайти <span aria-hidden="true">→</span>
      </button>
      <p class="search-panel__note">Фільтри передаються до каталогу через рядок запиту.</p>
    </form>
  </section>

  <section class="section" aria-labelledby="featured-title">
    <div class="section__heading">
      <div>
        <p class="section__eyebrow">Добірка</p>
        <h2 class="section__title" id="featured-title">Рекомендовані оголошення</h2>
      </div>
      <a class="section__link" href="{{ route('catalog.index') }}"
        >Усі {{ $total }} оголошень <span aria-hidden="true">→</span></a
      >
    </div>

    <div class="catalog-grid catalog-grid--home">
      @foreach ($featured as $product)
        <x-catalog-card :product="$product" :highlight="$loop->first" />
      @endforeach
    </div>
  </section>
@endsection

@section('after-main')
  <section class="partners" aria-labelledby="partners-title">
    <div class="partners__heading">
      <div>
        <p class="partners__eyebrow">Співпраця</p>
        <h2 class="partners__title" id="partners-title">Наші партнери</h2>
      </div>
      <p class="partners__text">
        Компанії, які допомагають перевіряти пропозиції, організовувати отримання та
        підтримувати якість сервісу.
      </p>
    </div>
    <div class="partners__slider" data-partners-slider>
      <button class="partners__control" type="button" data-action="partners-previous"
        aria-label="Показати попередні логотипи" aria-controls="partners-viewport" disabled>
        <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true">
          <path d="m15 5-7 7 7 7" fill="none" stroke="currentColor" stroke-linecap="round"
            stroke-linejoin="round" stroke-width="2" />
        </svg>
      </button>
      <div class="partners__viewport" id="partners-viewport" data-partners-viewport
        role="region" aria-label="Логотипи партнерів" tabindex="0">
        <div class="partners__track">
          @foreach ([['nova', 'NOVA'], ['orbit', 'ORBIT'], ['vector', 'VECTOR'], ['form', 'FORMA'], ['pulse', 'PULSE'], ['nexus', 'NEXUS']] as [$logo, $name])
            <div class="partner-logo">
              <svg class="partner-logo__icon" width="76" height="76" aria-hidden="true">
                <use href="{{ asset('images/partner-logos.svg') }}#logo-{{ $logo }}"></use>
              </svg>
              <span class="partner-logo__name">{{ $name }}</span>
            </div>
          @endforeach
        </div>
      </div>
      <button class="partners__control" type="button" data-action="partners-next"
        aria-label="Показати наступні логотипи" aria-controls="partners-viewport">
        <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true">
          <path d="m9 5 7 7-7 7" fill="none" stroke="currentColor" stroke-linecap="round"
            stroke-linejoin="round" stroke-width="2" />
        </svg>
      </button>
    </div>
  </section>
@endsection