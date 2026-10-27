@extends('layouts.app')

@section('title', 'Сторінку не знайдено')

@section('content')
  <section class="status-page">
    <div class="status-page__visual" aria-hidden="true">
      <span>4</span>
      <span class="status-page__zero">0</span>
      <span>4</span>
    </div>
    <p class="status-page__eyebrow">Цієї речі тут немає</p>
    <h1 class="status-page__title">Сторінку не знайдено</h1>
    <p class="status-page__text">
      Перевірте адресу або поверніться до каталогу, щоб обрати інше оголошення.
    </p>
    <div class="status-page__actions">
      <a class="button" href="{{ route('catalog.index') }}">До каталогу</a>
      <a class="status-page__link" href="{{ route('home') }}">На головну</a>
    </div>
  </section>
@endsection
