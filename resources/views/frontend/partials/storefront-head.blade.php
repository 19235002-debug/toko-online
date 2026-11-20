<!DOCTYPE html>
<html lang="id">
<head>
	<title>{{ $pageTitle ?? (store_setting()->nama_toko ?: 'Coza Store') }}</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="icon" type="image/png" href="{{ store_favicon_url() }}"/>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/bootstrap/css/bootstrap.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/fonts/iconic/css/material-design-iconic-font.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/fonts/linearicons-v1.0.0/icon-font.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/animate/animate.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/css-hamburgers/hamburgers.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/animsition/css/animsition.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/select2/select2.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/daterangepicker/daterangepicker.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/slick/slick.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/MagnificPopup/magnific-popup.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/perfect-scrollbar/perfect-scrollbar.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/css/util.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/css/main.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/css/modern-storefront.css') }}">
	@include('frontend.partials.responsive-styles')
</head>

