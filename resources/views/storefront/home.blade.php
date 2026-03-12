@extends('layouts.storefront')

@section('title', \App\Models\Setting::getValue('meta_title', \App\Models\Setting::getValue('site_name', 'مكتبة الصديق')))

@section('content')

@include('storefront.partials.hero-slider')
@include('storefront.partials.special-offers')
@include('storefront.partials.featured-categories')
@include('storefront.partials.featured-products')
@include('storefront.partials.latest-products')
@include('storefront.partials.why-choose-us')
@include('storefront.partials.location-contact')

@endsection
