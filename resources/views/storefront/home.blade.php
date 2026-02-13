@extends('layouts.storefront')

@section('title', 'متجر الصديق - El-Sedeek Store | Your Trusted Shop for Educational Toys & School Supplies')

@section('content')

@include('storefront.partials.hero-slider')
@include('storefront.partials.featured-categories')
@include('storefront.partials.featured-products')
@include('storefront.partials.latest-products')
@include('storefront.partials.why-choose-us')
@include('storefront.partials.location-contact')

@endsection
