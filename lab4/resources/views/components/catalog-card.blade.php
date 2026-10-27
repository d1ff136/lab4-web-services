@props(['product', 'highlight' => false])

@php
  $price = number_format((float) $product->price, 0, ',', ' ').' '.$product->currency;
@endphp

<article class="catalog-card {{ $highlight ? 'catalog-card--featured' : '' }}">
  <div class="catalog-card__media">
    @if ($product->status === 'featured')
      <span class="catalog-card__badge">Рекомендовано</span>
    @endif
    <img
      class="catalog-card__image"
      src="{{ asset($product->image) }}"
      width="400"
      height="400"
      loading="lazy"
      alt="{{ $product->title }}"
    />
  </div>
  <div class="catalog-card__body">
    <p class="catalog-card__eyebrow">{{ $product->category->name }} · {{ $product->operation }}</p>
    <h3 class="catalog-card__title">{{ $product->title }}</h3>
    <p class="catalog-card__description">{{ $product->short_description }}</p>
    <div class="catalog-card__footer">
      <strong class="catalog-card__price">{{ $price }}</strong>
      <a class="catalog-card__link" href="{{ route('catalog.show', $product->slug) }}"
        >Переглянути <span aria-hidden="true">↗</span></a
      >
    </div>
  </div>
</article>