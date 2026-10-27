@extends('layouts.app')

@section('title', 'Каталог')

@section('content')
  <section class="catalog-intro" aria-labelledby="catalog-title">
    <div class="catalog-intro__heading">
      <p class="catalog-intro__eyebrow">Одяг · {{ $products->total() }} оголошень</p>
      <h1 class="catalog-intro__title" id="catalog-title">
        Оберіть одяг
        <em>на свій смак.</em>
      </h1>
    </div>
    <p class="catalog-intro__text">
      Шукайте за назвою чи описом, обирайте категорію та спосіб угоди, сортуйте за
      датою або ціною.
    </p>
  </section>

  <form class="filter-form" method="get" action="{{ route('catalog.index') }}" aria-label="Фільтри каталогу">
    @if ($errors->any())
      <div class="form-errors" role="alert">
        <ul class="form-errors__list">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <label class="search-field filter-form__wide">
      <span class="search-field__label">Пошук</span>
      <input class="search-field__control" type="search" name="q" maxlength="100"
        value="{{ old('q', $filters->q) }}" placeholder="Назва або короткий опис" />
    </label>

    <label class="search-field">
      <span class="search-field__label">Категорія</span>
      <select class="search-field__control" name="category">
        <option value="">Усі категорії</option>
        @foreach ($categories as $category)
          <option value="{{ $category->name }}" @selected($filters->category === $category->name)>{{ $category->name }}</option>
        @endforeach
      </select>
    </label>

    <label class="search-field">
      <span class="search-field__label">Спосіб угоди</span>
      <select class="search-field__control" name="operation">
        <option value="">Усі способи</option>
        @foreach ($operations as $operation)
          <option value="{{ $operation }}" @selected($filters->operation === $operation)>{{ $operation }}</option>
        @endforeach
      </select>
    </label>

    <label class="search-field">
      <span class="search-field__label">Сортування</span>
      <select class="search-field__control" name="sort">
        <option value="newest" @selected($filters->sort === 'newest')>Спочатку нові</option>
        <option value="price_asc" @selected($filters->sort === 'price_asc')>Від дешевших</option>
        <option value="price_desc" @selected($filters->sort === 'price_desc')>Від дорожчих</option>
      </select>
    </label>

    <label class="search-field">
      <span class="search-field__label">На сторінці</span>
      <select class="search-field__control" name="per_page">
        @foreach ([9, 12, 24] as $size)
          <option value="{{ $size }}" @selected($filters->perPage === $size)>{{ $size }}</option>
        @endforeach
      </select>
    </label>

    <div class="filter-form__actions">
      <button class="button" type="submit">Знайти</button>
      <a class="filter-form__reset" href="{{ route('catalog.index') }}">Скинути фільтри</a>
    </div>
  </form>

  <section class="catalog-results" aria-label="Результати каталогу">
    <p class="catalog-results__count"><strong>{{ $products->total() }}</strong> результатів</p>

    @if ($products->isEmpty())
      <div class="empty-state">
        <h2 class="empty-state__title">Нічого не знайдено</h2>
        <p>Змініть умови пошуку або скиньте фільтри.</p>
        <a class="empty-state__link" href="{{ route('catalog.index') }}">Скинути фільтри</a>
      </div>
    @else
      <div class="catalog-grid catalog-grid--wide">
        @foreach ($products as $product)
          <x-catalog-card :product="$product" />
        @endforeach
      </div>
      {{ $products->links('vendor.pagination.bem') }}
    @endif
  </section>
@endsection