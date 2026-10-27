@extends('layouts.app')

@section('title', $product->title)

@section('content')
  <nav class="breadcrumbs" aria-label="Хлібні крихти">
    <a class="breadcrumbs__link" href="{{ route('catalog.index') }}">Каталог</a>
    <span class="breadcrumbs__separator" aria-hidden="true">/</span>
    <span aria-current="page">{{ $product->title }}</span>
  </nav>

  <article class="listing">
    <div class="listing__media">
      <img
        class="listing__image"
        src="{{ asset($product->image) }}"
        width="400"
        height="400"
        fetchpriority="high"
        alt="{{ $product->title }}"
      />
    </div>

    <div class="listing__content">
      <p class="listing__eyebrow">{{ $product->category->name }} · {{ $product->operation }}</p>
      <h1 class="listing__title">{{ $product->title }}</h1>
      <p class="listing__lead">{{ $product->description }}</p>
      <strong class="listing__price">
        {{ number_format((float) $product->price, 0, ',', ' ') }} {{ $product->currency }}
      </strong>

      <dl class="attribute-list">
        @foreach ($product->attributes as $name => $value)
          <div class="attribute-list__item">
            <dt class="attribute-list__name">{{ $name }}</dt>
            <dd class="attribute-list__value">{{ $value }}</dd>
          </div>
        @endforeach
        <div class="attribute-list__item">
          <dt class="attribute-list__name">Опубліковано</dt>
          <dd class="attribute-list__value">{{ $product->published_at->format('Y-m-d') }}</dd>
        </div>
      </dl>

      <section class="contact-card" id="contact" aria-labelledby="contact-title">
        <div>
          <p class="contact-card__eyebrow">Продавець</p>
          <h2 class="contact-card__title" id="contact-title">{{ $product->contact_name }}</h2>
          <p class="contact-card__line">{{ $product->contact_phone }}</p>
          <a class="contact-card__link" href="mailto:{{ $product->contact_email }}"
            >{{ $product->contact_email }}</a
          >
        </div>
        <a
          class="button button--light"
          href="mailto:{{ $product->contact_email }}"
          data-action="request"
          data-endpoint="/api/requests"
          data-item="{{ $product->slug }}"
          >Замовити примірку</a
        >
      </section>
    </div>
  </article>
@endsection