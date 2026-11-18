<!DOCTYPE html>
<html lang="en">
<head>
	<title>Katalog Produk | Coza Store Kelompok 3</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="{{ asset('frontend-assets/images/icons/favicon.png') }}"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/bootstrap/css/bootstrap.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/fonts/iconic/css/material-design-iconic-font.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/fonts/linearicons-v1.0.0/icon-font.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/animate/animate.css') }}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/css-hamburgers/hamburgers.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/animsition/css/animsition.min.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/select2/select2.min.css') }}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/daterangepicker/daterangepicker.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/slick/slick.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/MagnificPopup/magnific-popup.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/vendor/perfect-scrollbar/perfect-scrollbar.css') }}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/css/util.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('frontend-assets/css/main.css') }}">
<!--===============================================================================================-->
	<style>
		/* ========================================================
		   RESPONSIVE DESIGN OPTIMIZATIONS (MOBILE & DESKTOP)
		   ======================================================== */
		
		/* --- Cart & Wishlist Sidebars --- */
		@media (max-width: 576px) {
			.header-cart {
				width: 100% !important;
				max-width: 100% !important;
				padding-left: 20px !important;
				padding-right: 20px !important;
				padding-top: 25px !important;
			}
			.header-cart-title {
				padding-bottom: 12px !important;
			}
			.header-cart-item-img {
				width: 50px !important;
				margin-right: 12px !important;
			}
			.header-cart-buttons {
				display: flex;
				flex-direction: column;
				gap: 8px;
			}
			.header-cart-buttons button {
				width: 100% !important;
				margin-right: 0 !important;
				margin-bottom: 5px !important;
			}
		}

		/* --- Quick View Modal (Modernized) --- */
		.wrap-modal1 {
			z-index: 9999 !important;
			padding: 24px 16px !important;
			align-items: center;
			justify-content: center;
		}
		.wrap-modal1.show-modal1 {
			display: flex !important;
		}
		.overlay-modal1 {
			background: rgba(15, 23, 42, 0.68) !important;
			backdrop-filter: blur(5px);
		}
		.quickview-card-modern {
			background: #ffffff;
			border-radius: 20px;
			box-shadow: 0 25px 60px -12px rgba(15, 23, 42, 0.35);
			border: 1px solid rgba(226, 232, 240, 0.85);
			overflow: hidden;
			max-width: 960px;
			margin: 0 auto;
			position: relative;
		}
		.quickview-close-btn {
			position: absolute;
			top: 16px;
			right: 16px;
			z-index: 20;
			width: 36px;
			height: 36px;
			border-radius: 50%;
			background: #f1f5f9;
			color: #475569;
			border: 1px solid #e2e8f0;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 1.15rem;
			cursor: pointer;
			transition: all 0.2s ease;
		}
		.quickview-close-btn:hover {
			background: #0f172a;
			color: #ffffff;
			border-color: #0f172a;
			transform: rotate(90deg);
		}
		.quickview-gallery-pane {
			background: #f8fafc;
			padding: 28px;
			display: flex;
			flex-direction: column;
			justify-content: center;
			height: 100%;
			border-right: 1px solid #f1f5f9;
		}
		.modal-img-wrapper {
			display: flex;
			align-items: center;
			justify-content: center;
			min-height: 360px;
			background: #ffffff;
			border: 1px solid #e2e8f0;
			border-radius: 16px;
			padding: 16px;
			box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
		}
		#modal-product-img {
			max-height: 370px;
			max-width: 100%;
			object-fit: contain;
			border-radius: 10px;
			transition: opacity 0.2s ease;
		}
		.quickview-detail-pane {
			padding: 30px 30px 26px 26px;
			max-height: 86vh;
			overflow-y: auto;
		}
		.quickview-detail-pane::-webkit-scrollbar {
			width: 6px;
		}
		.quickview-detail-pane::-webkit-scrollbar-thumb {
			background: #cbd5e1;
			border-radius: 99px;
		}
		#modal-product-name {
			font-size: 1.45rem;
			font-weight: 800;
			color: #0f172a;
			line-height: 1.25;
			padding-right: 34px;
			margin-bottom: 8px;
		}
		#modal-product-price {
			display: inline-block;
			color: #4f46e5;
			font-weight: 800;
			font-size: 1.5rem;
			letter-spacing: -0.02em;
			margin-top: 4px;
		}
		.quickview-desc-box {
			background: #f8fafc;
			border: 1px solid #f1f5f9;
			border-radius: 12px;
			padding: 12px 14px;
			margin: 14px 0;
		}
		.quickview-desc-box p {
			font-size: 0.875rem;
			color: #475569;
			margin-bottom: 0;
			line-height: 1.55;
		}
		.quickview-action-row {
			display: flex;
			align-items: center;
			gap: 12px;
			flex-wrap: wrap;
			margin-top: 16px;
		}
		.quickview-qty-stepper {
			display: inline-flex;
			align-items: center;
			height: 46px;
			border-radius: 12px;
			border: 1px solid #cbd5e1;
			background: #f8fafc;
			overflow: hidden;
			margin: 0 !important;
			width: 126px;
		}
		.quickview-qty-stepper .btn-num-product-down,
		.quickview-qty-stepper .btn-num-product-up {
			width: 38px;
			height: 100%;
			color: #334155;
			transition: background 0.15s ease;
		}
		.quickview-qty-stepper .btn-num-product-down:hover,
		.quickview-qty-stepper .btn-num-product-up:hover {
			background: #e2e8f0;
			color: #0f172a;
		}
		.quickview-qty-stepper .num-product {
			width: 48px;
			height: 100%;
			background: #ffffff;
			border-left: 1px solid #e2e8f0;
			border-right: 1px solid #e2e8f0;
			font-weight: 700;
			color: #0f172a;
			font-size: 0.95rem;
		}
		.quickview-btn-cart {
			flex: 1;
			min-width: 175px;
			height: 46px;
			border-radius: 12px;
			background: linear-gradient(135deg, #4f46e5 0%, #717fe0 100%);
			color: #ffffff;
			font-weight: 700;
			font-size: 0.9rem;
			letter-spacing: 0.02em;
			border: none;
			box-shadow: 0 8px 20px -4px rgba(79, 70, 229, 0.4);
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 8px;
			cursor: pointer;
			transition: all 0.2s ease;
		}
		.quickview-btn-cart:hover {
			transform: translateY(-1px);
			box-shadow: 0 12px 24px -4px rgba(79, 70, 229, 0.5);
			color: #ffffff;
		}
		.quickview-btn-admin {
			width: 100%;
			height: 40px;
			margin-top: 10px;
			border-radius: 10px;
			border: 1px solid #e2e8f0;
			background: #ffffff;
			color: #475569;
			font-weight: 600;
			font-size: 0.83rem;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 6px;
			transition: all 0.2s ease;
		}
		.quickview-btn-admin:hover {
			background: #f8fafc;
			color: #0f172a;
			border-color: #cbd5e1;
		}
		.quickview-reviews-section {
			margin-top: 20px;
			padding-top: 16px;
			border-top: 1px solid #e2e8f0;
		}
		.quickview-review-toggle-btn {
			font-size: 0.78rem;
			font-weight: 600;
			padding: 4px 12px;
			border-radius: 99px;
			border: 1px solid #c7d2fe;
			background: #eef2ff;
			color: #4f46e5;
			transition: all 0.2s ease;
		}
		.quickview-review-toggle-btn:hover {
			background: #4f46e5;
			color: #ffffff;
			border-color: #4f46e5;
		}
		@media (max-width: 768px) {
			.wrap-modal1 {
				padding: 14px 10px !important;
			}
			.quickview-card-modern {
				max-height: 92vh !important;
				overflow-y: auto !important;
				border-radius: 16px !important;
			}
			.quickview-gallery-pane {
				padding: 18px 16px 12px 16px !important;
				border-right: none;
				border-bottom: 1px solid #f1f5f9;
			}
			.modal-img-wrapper {
				min-height: 220px !important;
				max-height: 260px !important;
				padding: 10px !important;
			}
			#modal-product-img {
				max-height: 230px !important;
			}
			.quickview-detail-pane {
				padding: 18px 16px 20px 16px !important;
				max-height: none !important;
				overflow-y: visible !important;
			}
			.quickview-action-row {
				flex-direction: column !important;
				align-items: stretch !important;
			}
			.quickview-qty-stepper {
				width: 100% !important;
				justify-content: space-between;
			}
			.quickview-qty-stepper .num-product {
				flex: 1;
			}
		}

		/* --- Product Grid & Cards --- */
		@media (max-width: 768px) {
			.block2-pic {
				height: 220px !important;
			}
			/* Show Quick View button permanently on mobile touch screens */
			.block2-btn {
				bottom: 10px !important;
				opacity: 0.95 !important;
				transform: translateX(-50%) scale(0.88) !important;
				font-size: 0.8rem !important;
				padding: 0 10px !important;
				height: 34px !important;
			}
			.block2-txt {
				padding-top: 8px !important;
			}
			.product-item-title {
				font-size: 0.92rem !important;
				white-space: nowrap !important;
				overflow: hidden !important;
				text-overflow: ellipsis !important;
				display: block !important;
			}
			.product-item-category {
				font-size: 0.68rem !important;
			}
			.product-item-price {
				font-size: 0.95rem !important;
			}
		}

		@media (max-width: 576px) {
			.block2-pic {
				height: 180px !important;
			}
			.isotope-grid {
				margin-left: -5px !important;
				margin-right: -5px !important;
			}
			.isotope-item {
				padding-left: 5px !important;
				padding-right: 5px !important;
				padding-bottom: 20px !important;
			}
		}

		/* --- Horizontal Category Swiper on Mobile --- */
		@media (max-width: 768px) {
			.filter-tope-group {
				display: flex !important;
				flex-wrap: nowrap !important;
				overflow-x: auto !important;
				-webkit-overflow-scrolling: touch;
				padding-bottom: 8px !important;
				margin-bottom: 15px !important;
				width: 100% !important;
			}
			.filter-tope-group::-webkit-scrollbar {
				height: 3px;
			}
			.filter-tope-group::-webkit-scrollbar-thumb {
				background: #ccc;
				border-radius: 3px;
			}
			.filter-tope-group button {
				white-space: nowrap !important;
				flex-shrink: 0 !important;
				margin-right: 12px !important;
				padding: 4px 10px !important;
				font-size: 0.88rem !important;
			}
		}

		/* --- Slider Responsiveness on Mobile --- */
		@media (max-width: 768px) {
			.item-slick1 {
				height: 60vh !important;
				min-height: 380px !important;
			}
			.item-slick1 .ltext-201 {
				font-size: 2rem !important;
				line-height: 1.2 !important;
				padding-top: 10px !important;
				padding-bottom: 20px !important;
			}
			.item-slick1 .ltext-101 {
				font-size: 1.1rem !important;
			}
			.item-slick1 .size-101 {
				min-width: 130px !important;
				height: 40px !important;
				font-size: 0.85rem !important;
			}
		}

		/* --- Mobile Header Bar --- */
		.wrap-header-mobile {
			border-bottom: 1px solid #f0f0f0;
			background-color: #fff;
		}
		.wrap-header-mobile .logo-mobile img {
			max-height: 28px;
		}

		/* --- User Profile Dropdown Fix --- */
		.js-user-dropdown {
			position: relative !important;
			display: inline-flex !important;
			align-items: center !important;
		}
		.js-user-dropdown .js-user-dropdown-toggle {
			display: inline-flex !important;
			align-items: center !important;
			cursor: pointer;
			user-select: none;
		}
		.js-user-dropdown .user-dropdown-menu {
			display: none;
			position: absolute !important;
			top: calc(100% + 14px) !important;
			right: 0 !important;
			left: auto !important;
			transform: none !important;
			box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
			border: 1px solid #e9ecef !important;
			border-radius: 10px !important;
			min-width: 220px !important;
			background-color: #ffffff !important;
			z-index: 1200 !important;
			margin: 0 !important;
			padding: 8px 0;
		}
		.js-user-dropdown.show > .user-dropdown-menu {
			display: block !important;
			animation: fadeInMenu 0.15s ease-out;
		}
		@keyframes fadeInMenu {
			from { opacity: 0; transform: translateY(-4px); }
			to { opacity: 1; transform: translateY(0); }
		}
		.js-user-dropdown .dropdown-item {
			display: flex;
			align-items: center;
			padding: 9px 16px;
			color: #333 !important;
			font-size: 13px;
			font-family: Poppins-Regular, sans-serif;
			text-decoration: none;
			transition: all 0.2s ease;
		}
		.js-user-dropdown .dropdown-item:hover {
			background-color: #f7f7f9;
			color: #717fe0 !important;
			padding-left: 20px;
		}

		/* --- Checkout Payment Radio Styling --- */
		.checkout-payment-option {
			transition: all 0.2s ease;
			border: 1px solid #dee2e6;
			border-radius: 8px;
			background-color: #fff;
		}
		.checkout-payment-option:hover {
			border-color: #717fe0;
			background-color: #f7f8ff;
		}
		.checkout-payment-option input[type="radio"]:checked ~ span {
			color: #717fe0;
		}

		/* --- Product Variations & Gallery Thumbnails --- */
		.var-pill {
			display: inline-flex;
			align-items: center;
			justify-content: center;
			padding: 5px 14px;
			border-radius: 20px;
			border: 1.5px solid #dcdfe6;
			background: #ffffff;
			color: #333333;
			font-size: 0.82rem;
			font-weight: 500;
			cursor: pointer;
			transition: all 0.2s ease;
			user-select: none;
		}
		.var-pill:hover {
			border-color: #717fe0;
			color: #717fe0;
			background-color: #f8f9ff;
		}
		.var-pill.active {
			border-color: #717fe0 !important;
			background-color: #717fe0 !important;
			color: #ffffff !important;
			box-shadow: 0 2px 8px rgba(113, 127, 224, 0.35);
		}
		.var-pill.disabled {
			opacity: 0.35;
			cursor: not-allowed;
			border-style: dashed;
		}
		.modal-thumb-item {
			width: 60px;
			height: 60px;
			border-radius: 6px;
			overflow: hidden;
			border: 2px solid #e9ecef;
			cursor: pointer;
			transition: all 0.2s ease;
			padding: 2px;
			background: #fff;
		}
		.modal-thumb-item img {
			width: 100%;
			height: 100%;
			object-fit: cover;
			border-radius: 4px;
		}
		.modal-thumb-item:hover,
		.modal-thumb-item.active {
			border-color: #717fe0;
			box-shadow: 0 2px 8px rgba(113, 127, 224, 0.4);
			transform: scale(1.05);
		}

		/* --- Clean Single-Line Desktop Header Navbar --- */
		.limiter-menu-desktop {
			display: flex !important;
			align-items: center !important;
			justify-content: space-between !important;
			flex-wrap: nowrap !important;
			height: 100%;
		}
		.limiter-menu-desktop .logo {
			flex-shrink: 0;
			margin-right: 32px !important;
		}
		.menu-desktop {
			display: flex;
			align-items: center;
			flex: 1 1 auto;
			min-width: 0;
		}
		.menu-desktop .main-menu {
			display: flex !important;
			align-items: center !important;
			flex-wrap: nowrap !important;
			margin: 0;
			padding: 0;
		}
		.menu-desktop .main-menu > li {
			margin: 0 12px 0 0 !important;
			padding: 12px 6px !important;
			flex-shrink: 0;
		}
		.menu-desktop .main-menu > li > a {
			white-space: nowrap !important;
			display: inline-flex;
			align-items: center;
			gap: 5px;
		}
		.wrap-icon-header {
			display: flex !important;
			align-items: center !important;
			flex-wrap: nowrap !important;
			flex-shrink: 0 !important;
			margin-left: auto;
		}
		.wrap-icon-header .icon-header-item,
		.wrap-icon-header .js-user-dropdown {
			flex-shrink: 0;
			white-space: nowrap;
		}

		/* --- Modern Product Card Design --- */
		.product-card-modern {
			background: #ffffff;
			border-radius: 16px;
			border: 1px solid #edf0f5;
			box-shadow: 0 4px 18px rgba(0, 0, 0, 0.035);
			transition: transform 0.28s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.28s ease, border-color 0.28s ease;
			display: flex;
			flex-direction: column;
			height: 100%;
			overflow: hidden;
		}
		.product-card-modern:hover {
			transform: translateY(-6px);
			box-shadow: 0 16px 36px rgba(113, 127, 224, 0.14);
			border-color: rgba(113, 127, 224, 0.3);
		}
		.product-img-modern {
			position: relative;
			width: 100%;
			height: 275px;
			background-color: #f4f6f9;
			overflow: hidden;
		}
		.product-img-modern > img {
			width: 100%;
			height: 100%;
			object-fit: cover;
			transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1);
			cursor: pointer;
		}
		.product-card-modern:hover .product-img-modern > img {
			transform: scale(1.06);
		}
		.badge-gallery-float {
			position: absolute;
			top: 12px;
			left: 12px;
			z-index: 4;
			background: rgba(17, 24, 39, 0.65);
			backdrop-filter: blur(6px);
			-webkit-backdrop-filter: blur(6px);
			color: #ffffff;
			font-size: 11px;
			font-weight: 500;
			padding: 4px 10px;
			border-radius: 20px;
			display: inline-flex;
			align-items: center;
			gap: 5px;
			pointer-events: none;
		}
		.btn-wishlist-float {
			position: absolute;
			top: 12px;
			right: 12px;
			z-index: 5;
			width: 36px;
			height: 36px;
			border-radius: 50%;
			background: rgba(255, 255, 255, 0.92);
			backdrop-filter: blur(6px);
			-webkit-backdrop-filter: blur(6px);
			display: flex;
			align-items: center;
			justify-content: center;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
			transition: all 0.2s ease;
		}
		.btn-wishlist-float:hover {
			transform: scale(1.1);
			background: #ffffff;
			box-shadow: 0 4px 14px rgba(0, 0, 0, 0.14);
		}
		.btn-wishlist-float .btn-addwish-b2 {
			width: 18px;
			height: 18px;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.btn-quickview-modern {
			position: absolute;
			left: 50%;
			bottom: 14px;
			transform: translateX(-50%) translateY(12px);
			opacity: 0;
			z-index: 4;
			background: rgba(255, 255, 255, 0.96);
			backdrop-filter: blur(8px);
			-webkit-backdrop-filter: blur(8px);
			color: #1e293b !important;
			font-size: 12.5px;
			font-weight: 600;
			padding: 9px 20px;
			border-radius: 30px;
			box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
			white-space: nowrap;
			display: inline-flex;
			align-items: center;
			gap: 6px;
			transition: all 0.28s cubic-bezier(0.22, 1, 0.36, 1);
			text-decoration: none !important;
		}
		.product-card-modern:hover .btn-quickview-modern {
			opacity: 1;
			transform: translateX(-50%) translateY(0);
		}
		.btn-quickview-modern:hover {
			background: #717fe0 !important;
			color: #ffffff !important;
		}
		.product-body-modern {
			padding: 15px 16px 16px;
			display: flex;
			flex-direction: column;
			flex: 1 1 auto;
			min-height: 168px;
		}
		.product-meta-row {
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 8px;
		}
		.badge-cat-modern {
			background: #f1f3ff;
			color: #5b69c9;
			font-size: 11px;
			font-weight: 600;
			padding: 3px 10px;
			border-radius: 20px;
			letter-spacing: 0.2px;
		}
		.stock-indicator {
			font-size: 11.5px;
			font-weight: 500;
			display: inline-flex;
			align-items: center;
			gap: 5px;
		}
		.stock-dot {
			width: 6px;
			height: 6px;
			border-radius: 50%;
			display: inline-block;
		}
		.product-title-modern {
			font-size: 15px;
			font-weight: 600;
			color: #1e293b;
			line-height: 1.4;
			margin: 10px 0 8px;
			display: -webkit-box;
			-webkit-line-clamp: 2;
			-webkit-box-orient: vertical;
			overflow: hidden;
			text-decoration: none !important;
			transition: color 0.2s ease;
		}
		.product-title-modern:hover {
			color: #717fe0;
		}
		.chip-var {
			display: inline-flex;
			align-items: center;
			gap: 4px;
			font-size: 11px;
			font-weight: 500;
			color: #475569;
			background: #f8fafc;
			border: 1px solid #e2e8f0;
			padding: 2px 8px;
			border-radius: 6px;
		}
		.chip-var-color {
			color: #5b69c9;
			background: #f5f7ff;
			border-color: #e0e7ff;
		}
		.product-footer-modern {
			margin-top: auto;
			padding-top: 12px;
			border-top: 1px dashed #eef2f6;
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 8px;
		}
		.product-price-modern {
			font-size: 16px;
			font-weight: 700;
			color: #e65540;
			letter-spacing: -0.2px;
		}
		.rating-pill-modern {
			display: inline-flex;
			align-items: center;
			gap: 4px;
			font-size: 12px;
			font-weight: 600;
			color: #334155;
			background: #fffbeb;
			border: 1px solid #fef3c7;
			padding: 3px 8px;
			border-radius: 20px;
		}
		.rating-pill-new {
			font-size: 11px;
			font-weight: 500;
			color: #64748b;
			background: #f1f5f9;
			padding: 3px 8px;
			border-radius: 20px;
		}
		@media (max-width: 576px) {
			.product-img-modern {
				height: 210px;
			}
			.product-body-modern {
				padding: 12px;
			}
			.product-price-modern {
				font-size: 14.5px;
			}
			.btn-quickview-modern {
				display: none;
			}
		}

		/* --- Modern Cart & Wishlist Drawer --- */
		.js-panel-cart .header-cart.cart-drawer-modern,
		.js-panel-wishlist .header-cart.cart-drawer-modern {
			padding: 0 !important;
			display: flex !important;
			flex-direction: column !important;
			height: 100vh !important;
			width: 410px !important;
			max-width: 100% !important;
			background: #f8fafc !important;
		}
		.cart-drawer-header {
			padding: 18px 22px;
			background: #ffffff;
			border-bottom: 1px solid #eef0f5;
			display: flex;
			align-items: center;
			justify-content: space-between;
			width: 100%;
			flex-shrink: 0;
		}
		.cart-drawer-body {
			flex: 1 1 auto;
			overflow-y: auto;
			padding: 16px 20px;
			width: 100%;
		}
		.cart-drawer-footer {
			padding: 16px 22px 22px;
			background: #ffffff;
			border-top: 1px solid #eef0f5;
			box-shadow: 0 -6px 24px rgba(0, 0, 0, 0.05);
			width: 100%;
			flex-shrink: 0;
		}
		.cart-item-card {
			display: flex;
			gap: 12px;
			background: #ffffff;
			border: 1px solid #edf0f5;
			border-radius: 14px;
			padding: 12px;
			margin-bottom: 12px;
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.025);
			transition: all 0.2s ease;
		}
		.cart-item-card:hover {
			border-color: rgba(113, 127, 224, 0.35);
			box-shadow: 0 6px 16px rgba(113, 127, 224, 0.08);
		}
		.cart-item-thumb {
			width: 72px;
			height: 72px;
			border-radius: 10px;
			object-fit: cover;
			background: #f1f5f9;
			flex-shrink: 0;
			border: 1px solid #eef2f6;
		}
		.cart-qty-stepper {
			display: inline-flex;
			align-items: center;
			background: #f1f5f9;
			border-radius: 8px;
			padding: 2px;
			border: 1px solid #e2e8f0;
		}
		.cart-qty-btn {
			width: 24px;
			height: 24px;
			border: none;
			background: #ffffff;
			border-radius: 6px;
			font-weight: 700;
			font-size: 13px;
			color: #334155;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			cursor: pointer;
			transition: all 0.15s ease;
		}
		.cart-qty-btn:hover {
			background: #717fe0;
			color: #ffffff;
		}
		.cart-qty-val {
			min-width: 26px;
			text-align: center;
			font-size: 12.5px;
			font-weight: 600;
			color: #1e293b;
		}
		.btn-cart-trash {
			border: none;
			background: #fff1f2;
			color: #e11d48;
			width: 28px;
			height: 28px;
			border-radius: 8px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			cursor: pointer;
			transition: all 0.15s ease;
			flex-shrink: 0;
		}
		.btn-cart-trash:hover {
			background: #e11d48;
			color: #ffffff;
		}
		.btn-cart-clear {
			padding: 11px 15px;
			border-radius: 12px;
			border: 1.5px solid #e2e8f0;
			background: #ffffff;
			color: #64748b;
			font-size: 13px;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.2s ease;
		}
		.btn-cart-clear:hover {
			border-color: #fda4af;
			color: #e11d48;
			background: #fff1f2;
		}
		.btn-cart-checkout {
			flex: 1 1 auto;
			padding: 11px 18px;
			border-radius: 12px;
			border: none;
			background: linear-gradient(135deg, #717fe0 0%, #5865d6 100%);
			color: #ffffff;
			font-size: 14px;
			font-weight: 600;
			cursor: pointer;
			box-shadow: 0 6px 18px rgba(113, 127, 224, 0.35);
			transition: all 0.2s ease;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			gap: 8px;
		}
		.btn-cart-checkout:hover {
			transform: translateY(-1px);
			box-shadow: 0 8px 22px rgba(113, 127, 224, 0.45);
		}

		/* --- Fix Modal Z-Index & Scrollable Checkout Modal --- */
		.modal-backdrop {
			z-index: 9990 !important;
		}
		.modal {
			z-index: 9999 !important;
		}
		#modalCheckout .modal-dialog {
			max-height: calc(100vh - 30px);
			margin: 15px auto;
		}
		#modalCheckout .modal-content {
			max-height: calc(100vh - 30px);
			display: flex;
			flex-direction: column;
			border-radius: 14px !important;
			overflow: hidden;
			border: none !important;
			box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25) !important;
		}
		#modalCheckout #formCheckout {
			display: flex;
			flex-direction: column;
			flex: 1 1 auto;
			min-height: 0;
			overflow: hidden;
		}
		#modalCheckout .modal-header,
		#modalCheckout .modal-footer {
			flex-shrink: 0;
		}
		#modalCheckout .modal-body {
			flex: 1 1 auto;
			overflow-y: auto;
			padding: 22px 26px !important;
			background: #f8fafc !important;
		}
		#checkout_kode_voucher::placeholder {
			text-transform: none;
			font-size: 12.5px;
		}
		.checkout-input-modern {
			border-radius: 10px !important;
			border: 1.5px solid #e2e8f0 !important;
			padding: 10px 14px !important;
			font-size: 13.5px !important;
			transition: all 0.2s ease;
			background: #ffffff !important;
		}
		.checkout-input-modern:focus {
			border-color: #717fe0 !important;
			box-shadow: 0 0 0 3px rgba(113, 127, 224, 0.14) !important;
		}
		.checkout-card-box {
			background: #ffffff;
			border: 1px solid #edf0f5;
			border-radius: 14px;
			padding: 16px;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
		}
		.checkout-pay-card {
			position: relative;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			padding: 12px 8px;
			border-radius: 12px;
			border: 1.5px solid #e2e8f0;
			background: #ffffff;
			cursor: pointer;
			transition: all 0.2s ease;
			margin-bottom: 0;
			height: 100%;
		}
		.checkout-pay-card:hover {
			border-color: #a5b0f5;
			background: #f8f9ff;
		}
		.checkout-pay-card input[type="radio"] {
			position: absolute;
			opacity: 0;
			pointer-events: none;
		}
		.checkout-pay-card:has(input[type="radio"]:checked),
		.checkout-pay-card.selected {
			border-color: #717fe0;
			background: #f5f7ff;
			box-shadow: 0 4px 12px rgba(113, 127, 224, 0.15);
		}
		.checkout-pay-check {
			position: absolute;
			top: 6px;
			right: 6px;
			width: 16px;
			height: 16px;
			border-radius: 50%;
			background: #717fe0;
			color: #fff;
			font-size: 9px;
			display: none;
			align-items: center;
			justify-content: center;
		}
		.checkout-pay-card:has(input[type="radio"]:checked) .checkout-pay-check,
		.checkout-pay-card.selected .checkout-pay-check {
			display: flex;
		}
		.btn-checkout-submit-modern {
			background: linear-gradient(135deg, #717fe0 0%, #5865d6 100%) !important;
			border: none !important;
			border-radius: 12px !important;
			padding: 10px 24px !important;
			font-weight: 600 !important;
			font-size: 14px !important;
			color: #ffffff !important;
			box-shadow: 0 6px 18px rgba(113, 127, 224, 0.35);
			transition: all 0.2s ease;
		}
		.btn-checkout-submit-modern:hover {
			transform: translateY(-1px);
			box-shadow: 0 8px 22px rgba(113, 127, 224, 0.45);
		}
	</style>
	@include('frontend.partials.responsive-styles')
</head>
<body class="animsition">
	@include('sweetalert::alert')
	
	<!-- Header -->
	<header class="header-v4">
		<!-- Header desktop -->
		<div class="container-menu-desktop">
			<div class="wrap-menu-desktop" style="background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
				<nav class="limiter-menu-desktop container">
					
					<!-- Logo desktop -->		
					<a href="/" class="logo">
						<img src="{{ asset('frontend-assets/images/icons/logo-01.png') }}" alt="IMG-LOGO">
					</a>

					<!-- Menu desktop -->
					<div class="menu-desktop">
						<ul class="main-menu">
							<li>
								<a href="/">Home</a>
							</li>

							<li class="active-menu">
								<a href="/shop">Shop</a>
							</li>

							<li>
								<a href="/lacak-pesanan">Lacak Pesanan</a>
							</li>

							<li>
								<a href="#footer-contact">Hubungi Kami</a>
							</li>
						</ul>
					</div>	

					<!-- Icon header -->
					<div class="wrap-icon-header flex-r-m">
						<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 js-show-modal-search">
							<i class="zmdi zmdi-search"></i>
						</div>

						<div class="icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-cart js-cart-badge pointer" data-notify="0" title="Keranjang Belanja">
							<i class="zmdi zmdi-shopping-cart"></i>
						</div>

						<a href="javascript:void(0);" class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-l-22 p-r-11 icon-header-noti js-show-wishlist js-wishlist-badge pointer" data-notify="0" title="Wishlist">
							<i class="zmdi zmdi-favorite-outline"></i>
						</a>

						@auth
						<div class="js-user-dropdown p-l-18">
							<a href="javascript:void(0);" class="js-user-dropdown-toggle dis-flex align-items-center cl2 hov-cl1 trans-04 pointer" style="color: #333 !important; text-decoration: none; cursor: pointer;">
								@if(auth()->user()->user_image)
									<img src="{{ asset('storage/' . auth()->user()->user_image) }}" alt="{{ auth()->user()->nama }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #cbd5e1;">
								@else
									<i class="zmdi zmdi-account" style="color: #333 !important; font-size: 26px;"></i>
								@endif
								<span class="d-none d-md-inline-block font-weight-bold ml-2 text-dark" style="color: #333 !important; font-size: 14px; max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
									{{ auth()->user()->nama }}
								</span>
								<i class="fa fa-angle-down ml-1 text-muted" style="font-size: 13px;"></i>
							</a>
							<div class="user-dropdown-menu shadow border-0" style="min-width: 255px;">
								<div class="px-3 py-2 border-bottom mb-2 bg-light rounded-top d-flex align-items-center" style="gap: 12px;">
									@if(auth()->user()->user_image)
										<img src="{{ asset('storage/' . auth()->user()->user_image) }}" alt="{{ auth()->user()->nama }}" class="rounded-circle border shadow-sm flex-shrink-0" style="width: 50px; height: 50px; object-fit: cover;">
									@else
										<div class="rounded-circle d-flex align-items-center justify-content-center text-white font-weight-bold flex-shrink-0" style="width: 50px; height: 50px; background: linear-gradient(135deg, #4f46e5, #717fe0); font-size: 18px;">
											{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
										</div>
									@endif
									<div style="min-width: 0; flex: 1; text-align: left;">
										<div class="font-weight-bold text-dark text-truncate" style="font-size: 14px; line-height: 1.25;">{{ auth()->user()->nama }}</div>
										<div class="small text-muted text-truncate" style="font-size: 12px;">{{ auth()->user()->email }}</div>
										<span class="badge {{ auth()->user()->role === 'admin' ? 'badge-danger' : 'badge-primary' }} mt-1 font-weight-normal" style="font-size: 10.5px;">
											{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Member' }}
										</span>
									</div>
								</div>

								@if(in_array(auth()->user()->role, ['admin', 'superadmin']))
								<a class="dropdown-item py-2 px-3 d-flex align-items-center text-dark" href="/dashboard" style="font-size: 13px;">
									<i class="fa-solid fa-gauge-high mr-2 text-secondary" style="width: 18px;"></i> Dashboard Admin
								</a>
								<a class="dropdown-item py-2 px-3 d-flex align-items-center text-dark" href="{{ route('profile.edit') }}" style="font-size: 13px;">
									<i class="fa-solid fa-user-gear mr-2 text-secondary" style="width: 18px;"></i> Profil Admin
								</a>
								@else
								<a class="dropdown-item py-2 px-3 d-flex align-items-center text-dark" href="/pesanan-saya" style="font-size: 13px;">
									<i class="fa-solid fa-shopping-bag mr-2 text-secondary" style="width: 18px;"></i> Pesanan Saya
								</a>
								<a class="dropdown-item py-2 px-3 d-flex align-items-center text-dark" href="{{ route('profile.edit') }}" style="font-size: 13px;">
									<i class="fa-solid fa-user-gear mr-2 text-secondary" style="width: 18px;"></i> Profil Saya
								</a>
								@endif

								<div class="dropdown-divider my-1"></div>
								<a class="dropdown-item py-2 px-3 d-flex align-items-center text-danger font-weight-bold" href="javascript:void(0);" onclick="document.getElementById('logout-form-desktop').submit();" style="font-size: 13px; color: #dc3545 !important;">
									<i class="fa-solid fa-right-from-bracket mr-2 text-danger" style="width: 18px;"></i> Logout
								</a>
								<form id="logout-form-desktop" action="/logout" method="POST" style="display: none;">
									@csrf
								</form>
							</div>
						</div>
						@else
						<div class="p-l-18">
							<a href="/login" class="dis-flex align-items-center cl2 hov-cl1 trans-04 pointer" title="Masuk / Daftar" style="color: #333 !important; text-decoration: none;">
								<i class="zmdi zmdi-account-o" style="color: #333 !important; font-size: 26px;"></i>
								<span class="d-none d-md-inline-block font-weight-bold ml-2 text-dark" style="color: #333 !important; font-size: 14px;">
									Masuk / Daftar
								</span>
							</a>
						</div>
						@endauth
					</div>
				</nav>
			</div>	
		</div>

		<!-- Header Mobile -->
		<div class="wrap-header-mobile">
			<!-- Logo moblie -->		
			<div class="logo-mobile">
				<a href="/frontend"><img src="{{ asset('frontend-assets/images/icons/logo-01.png') }}" alt="IMG-LOGO"></a>
			</div>

			<!-- Icon header -->
			<div class="wrap-icon-header flex-w flex-r-m m-r-15">
				<div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 js-show-modal-search">
					<i class="zmdi zmdi-search"></i>
				</div>

				<div class="icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti js-show-cart js-cart-badge pointer" data-notify="0" title="Keranjang Belanja">
					<i class="zmdi zmdi-shopping-cart"></i>
				</div>

				<a href="javascript:void(0);" class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 icon-header-noti js-show-wishlist js-wishlist-badge pointer" data-notify="0" title="Wishlist">
					<i class="zmdi zmdi-favorite-outline"></i>
				</a>

				@auth
				<div class="js-user-dropdown">
					<a href="javascript:void(0);" class="js-user-dropdown-toggle dis-flex align-items-center cl2 hov-cl1 trans-04 p-r-11 p-l-10 pointer" style="color: #333 !important; text-decoration: none;">
						<i class="zmdi zmdi-account" style="color: #333 !important; font-size: 24px;"></i>
						<i class="fa fa-angle-down ml-1 text-muted" style="font-size: 11px;"></i>
					</a>
					<div class="user-dropdown-menu shadow border-0 py-2" style="min-width: 200px;">
						<div class="px-3 py-2 text-center border-bottom mb-2 bg-light rounded-top">
							<div class="font-weight-bold text-dark text-truncate" style="font-size: 14px;">{{ auth()->user()->nama }}</div>
							<span class="badge {{ auth()->user()->role === 'admin' ? 'badge-danger' : 'badge-primary' }} mt-1 font-weight-normal">
								{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Member' }}
							</span>
						</div>
						@if(in_array(auth()->user()->role, ['admin', 'superadmin']))
						<a class="dropdown-item py-2 px-3 d-flex align-items-center text-dark" href="/dashboard" style="font-size: 13px;">
							<i class="fa-solid fa-gauge-high mr-2 text-secondary" style="width: 18px;"></i> Dashboard Admin
						</a>
						<a class="dropdown-item py-2 px-3 d-flex align-items-center text-dark" href="{{ route('profile.edit') }}" style="font-size: 13px;">
							<i class="fa-solid fa-user-gear mr-2 text-secondary" style="width: 18px;"></i> Profil Admin
						</a>
						@else
						<a class="dropdown-item py-2 px-3 d-flex align-items-center text-dark" href="/pesanan-saya" style="font-size: 13px;">
							<i class="fa-solid fa-shopping-bag mr-2 text-secondary" style="width: 18px;"></i> Pesanan Saya
						</a>
						<a class="dropdown-item py-2 px-3 d-flex align-items-center text-dark" href="{{ route('profile.edit') }}" style="font-size: 13px;">
							<i class="fa-solid fa-user-gear mr-2 text-secondary" style="width: 18px;"></i> Profil Saya
						</a>
						<a class="dropdown-item py-2 px-3 d-flex align-items-center text-dark" href="/lacak-pesanan" style="font-size: 13px;">
							<i class="fa-solid fa-truck mr-2 text-secondary" style="width: 18px;"></i> Lacak Pesanan
						</a>
						@endif
						<div class="dropdown-divider my-1"></div>
						<a class="dropdown-item py-2 px-3 d-flex align-items-center text-danger font-weight-bold" href="javascript:void(0);" onclick="document.getElementById('logout-form-mobile-header').submit();" style="font-size: 13px; color: #dc3545 !important;">
							<i class="fa-solid fa-right-from-bracket mr-2 text-danger" style="width: 18px;"></i> Logout
						</a>
						<form id="logout-form-mobile-header" action="/logout" method="POST" style="display: none;">
							@csrf
						</form>
					</div>
				</div>
				@else
				<a href="/login" class="dis-block icon-header-item cl2 hov-cl1 trans-04 p-r-11 p-l-10 pointer" title="Masuk / Daftar" style="color: #333 !important;">
					<i class="zmdi zmdi-account-o" style="color: #333 !important; font-size: 24px;"></i>
				</a>
				@endauth
			</div>

			<!-- Button show menu -->
			<div class="btn-show-menu-mobile hamburger hamburger--squeeze">
				<span class="hamburger-box">
					<span class="hamburger-inner"></span>
				</span>
			</div>
		</div>


		<!-- Menu Mobile -->
		<div class="menu-mobile">
			<ul class="topbar-mobile">
				<li>
					<div class="left-top-bar">
						Gratis Ongkir untuk Wilayah Tertentu
					</div>
				</li>

				<li>
					<div class="right-top-bar flex-w h-full">
						@auth
							@if(in_array(auth()->user()->role, ['admin', 'superadmin']))
							<a href="/dashboard" class="flex-c-m p-lr-10 trans-04 font-weight-bold" style="color: #717fe0;">
								<i class="fa fa-dashboard mr-1"></i> Admin Panel
							</a>
							@else
							<a href="/pesanan-saya" class="flex-c-m p-lr-10 trans-04 font-weight-bold text-primary">
								<i class="fa fa-shopping-bag mr-1"></i> Pesanan Saya
							</a>
							@endif

						<a href="javascript:void(0)" onclick="document.getElementById('logout-form-mobile').submit();" class="flex-c-m p-lr-10 trans-04 text-danger">
							<i class="fa fa-sign-out mr-1"></i> Logout
						</a>
						<form id="logout-form-mobile" action="/logout" method="POST" style="display: none;">@csrf</form>
						@else
						<a href="/login" class="flex-c-m p-lr-10 trans-04">
							<i class="fa fa-user mr-1"></i> Masuk / Daftar
						</a>
						@endauth
					</div>
				</li>
			</ul>

			<ul class="main-menu-m">
				<li>
					<a href="/frontend">Home</a>
				</li>

				<li>
					<a href="#section-produk" class="js-scroll-katalog" onclick="$('.btn-show-menu-mobile').click();">Shop / Katalog</a>
				</li>

				@auth
					@if(auth()->user()->role === 'user')
					<li>
						<a href="/pesanan-saya">
							<i class="fa fa-shopping-bag mr-2 text-primary"></i> Pesanan Saya
						</a>
					</li>
					@elseif(in_array(auth()->user()->role, ['admin', 'superadmin']))
					<li>
						<a href="/dashboard">
							<i class="fa fa-gauge-high mr-2 text-danger"></i> Panel Admin
						</a>
					</li>
					@endif
				@endauth

				<li>
					<a href="/lacak-pesanan">
						<i class="fa fa-truck mr-2 text-primary"></i> Lacak Pesanan
					</a>
				</li>

				<li>
					<a href="javascript:void(0);" onclick="$('.btn-show-menu-mobile').click(); $('.js-show-cart').first().click();">
						<i class="zmdi zmdi-shopping-cart mr-2"></i> Keranjang Belanja
					</a>
				</li>

				<li>
					<a href="javascript:void(0);" onclick="$('.btn-show-menu-mobile').click(); $('.js-show-wishlist').first().click();">
						<i class="zmdi zmdi-favorite-outline mr-2 text-danger"></i> Wishlist Saya
					</a>
				</li>

				<li>
					<a href="https://wa.me/6281234567890?text=Halo%20Admin%20Viola%2C%20saya%20ingin%20bertanya" target="_blank">
						<i class="fa fa-whatsapp mr-2 text-success"></i> Hubungi Kami (WhatsApp)
					</a>
				</li>
			</ul>
		</div>

		<!-- Modal Search -->
		<div class="modal-search-header flex-c-m trans-04 js-hide-modal-search">
			<div class="container-search-header">
				<button class="flex-c-m btn-hide-modal-search trans-04 js-hide-modal-search">
					<img src="{{ asset('frontend-assets/images/icons/icon-close2.png') }}" alt="CLOSE">
				</button>

				<form class="wrap-search-header flex-w p-l-15">
					<button class="flex-c-m trans-04">
						<i class="zmdi zmdi-search"></i>
					</button>
					<input class="plh3" type="text" name="search" placeholder="Search...">
				</form>
			</div>
		</div>
	</header>

	@include('frontend.partials.drawers')

	<!-- Compact Modern Shop Header -->
	<section class="border-bottom" style="background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%); padding: 26px 0;">
		<div class="container">
			<div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 16px;">
				<div>
					<nav aria-label="breadcrumb">
						<ol class="breadcrumb bg-transparent p-0 mb-1" style="font-size: 12.5px;">
							<li class="breadcrumb-item"><a href="/" class="text-muted">Home</a></li>
							<li class="breadcrumb-item active font-weight-bold text-primary" aria-current="page">Shop</li>
						</ol>
					</nav>
					<h2 class="font-weight-bold text-dark mb-1" style="font-size: 1.75rem; letter-spacing: -0.4px;">
						Katalog Produk
					</h2>
					<p class="text-muted mb-0" style="font-size: 13.5px;">
						Temukan koleksi fashion terbaik dengan filter kategori, harga, dan ketersediaan stok.
					</p>
				</div>
			</div>
		</div>
	</section>

	<!-- Sleek UNIQLO-Style Compact Category Bar + Floating Mega-Menu -->
	@if(isset($mainKategoris) && $mainKategoris->count() > 0)
	<section class="bg-white border-bottom position-relative" id="uniqlo-cat-bar" style="z-index: 90;">
		<div class="container">
			<div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px; min-height: 52px;">
				<div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
					<a href="{{ route('frontend.shop') }}"
					   class="btn btn-sm rounded-pill px-3 text-decoration-none {{ empty($kategori) || $kategori === 'all' ? 'font-weight-bold text-white' : 'text-dark bg-light border' }}"
					   style="font-size: 13px; {{ empty($kategori) || $kategori === 'all' ? 'background: #717fe0; border-color: #717fe0;' : '' }}">
						Semua
					</a>

					@foreach($mainKategoris as $mCat)
					@php $isTabActive = strtolower($kategori ?? '') === strtolower($mCat->nama_kategori); @endphp
					<button type="button"
					        onclick="toggleShopMegaMenu('{{ $mCat->id_kategori }}', this)"
					        class="btn btn-sm rounded-pill px-3 font-weight-bold js-uniqlo-tab {{ $isTabActive ? 'text-white' : 'text-dark bg-light border' }}"
					        style="font-size: 13px; outline: none !important; box-shadow: none !important; {{ $isTabActive ? 'background: #717fe0; border-color: #717fe0;' : '' }}">
						{{ $mCat->nama_kategori }} <i class="fa fa-angle-down ml-1 js-tab-chevron" style="font-size: 12px; transition: transform 0.2s;"></i>
					</button>
					@endforeach
				</div>
			</div>
		</div>

		<!-- Floating Mega-Menu Dropdown (Collapsed by default, opens on tab click) -->
		@foreach($mainKategoris as $mCat)
		<div class="js-uniqlo-subpanel d-none bg-white border-bottom shadow-lg position-absolute w-100"
		     id="uniqlo-subpanel-{{ $mCat->id_kategori }}"
		     style="left: 0; top: 100%; z-index: 1050; border-top: 1px solid #eef0f5;">
			<div class="container py-4">
				<div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
					<div class="d-flex align-items-center" style="gap: 10px;">
						<strong class="text-dark" style="font-size: 15px;">Sub-Kategori {{ $mCat->nama_kategori }}</strong>
						<a href="{{ route('frontend.shop', ['kategori' => $mCat->nama_kategori]) }}" class="btn btn-xs btn-outline-primary rounded-pill px-3 py-1" style="font-size: 11.5px;">
							Lihat Semua {{ $mCat->nama_kategori }} <i class="fa fa-arrow-right ml-1"></i>
						</a>
					</div>
					<button type="button" onclick="closeAllShopMegaMenus()" class="btn btn-sm btn-light rounded-circle border" style="width: 30px; height: 30px; padding: 0;">
						<i class="fa fa-times text-muted"></i>
					</button>
				</div>

				<div class="row" style="margin-left: -6px; margin-right: -6px;">
					@foreach($mCat->children as $sub)
					@php
						$isSubActive = (strtolower($kategori ?? '') === strtolower($mCat->nama_kategori) && strtolower($subKategori ?? '') === strtolower($sub->nama_kategori));
						$subLower = strtolower($sub->nama_kategori);
						$iconClass = 'fa-shirt';
						if (str_contains($subLower, 'luaran') || str_contains($subLower, 'jaket')) $iconClass = 'fa-vest';
						elseif (str_contains($subLower, 'celana') || str_contains($subLower, 'jeans') || str_contains($subLower, 'kulot')) $iconClass = 'fa-socks';
						elseif (str_contains($subLower, 'gaun') || str_contains($subLower, 'rok')) $iconClass = 'fa-person-dress';
						elseif (str_contains($subLower, 'aksesoris') || str_contains($subLower, 'tas')) $iconClass = 'fa-glasses';
						elseif (str_contains($subLower, 'bayi') || str_contains($subLower, 'newborn') || str_contains($subLower, 'bodysuit')) $iconClass = 'fa-baby';
						elseif (str_contains($subLower, 'piyama') || str_contains($subLower, 'loungewear')) $iconClass = 'fa-bed';
						elseif (str_contains($subLower, 'dalaman')) $iconClass = 'fa-layer-group';
					@endphp
					<div class="col-4 col-sm-3 col-md-2 px-1 mb-2">
						<a href="{{ route('frontend.shop', ['kategori' => $mCat->nama_kategori, 'sub_kategori' => $sub->nama_kategori]) }}"
						   class="d-flex flex-column align-items-center text-center p-2 rounded text-decoration-none h-100 {{ $isSubActive ? 'border border-primary' : '' }}"
						   style="transition: all 0.2s ease; border-radius: 12px !important; {{ $isSubActive ? 'background: #eef2ff;' : '' }}"
						   onmouseover="this.style.backgroundColor='#f4f6ff'" onmouseout="this.style.backgroundColor='{{ $isSubActive ? '#eef2ff' : 'transparent' }}'">
							<div class="mb-2 d-flex align-items-center justify-content-center"
							     style="width: 54px; height: 54px; border-radius: 12px; background: #f8fafc; overflow: hidden; border: 1px solid #e2e8f0;">
								@if($sub->gambar)
									<img src="{{ asset('storage/' . $sub->gambar) }}" alt="{{ $sub->nama_kategori }}" style="width: 100%; height: 100%; object-fit: cover;">
								@else
									<i class="fa-solid {{ $iconClass }} text-primary" style="font-size: 20px; opacity: 0.85;"></i>
								@endif
							</div>
							<span class="{{ $isSubActive ? 'font-weight-bold text-primary' : 'text-dark' }}" style="font-size: 11.5px; line-height: 1.3;">
								{{ $sub->nama_kategori }}
							</span>
						</a>
					</div>
					@endforeach
				</div>
			</div>
		</div>
		@endforeach
	</section>
	<script>
		var currentOpenShopPanelId = null;

		function toggleShopMegaMenu(catId, btnEl) {
			var target = document.getElementById('uniqlo-subpanel-' + catId);
			var isAlreadyOpen = (currentOpenShopPanelId === catId && target && !target.classList.contains('d-none'));

			closeAllShopMegaMenus();

			if (!isAlreadyOpen && target) {
				target.classList.remove('d-none');
				currentOpenShopPanelId = catId;
				var chevron = btnEl.querySelector('.js-tab-chevron');
				if (chevron) chevron.style.transform = 'rotate(180deg)';
			}
		}

		function closeAllShopMegaMenus() {
			document.querySelectorAll('.js-uniqlo-subpanel').forEach(function(panel) {
				panel.classList.add('d-none');
			});
			document.querySelectorAll('.js-tab-chevron').forEach(function(icon) {
				icon.style.transform = 'rotate(0deg)';
			});
			currentOpenShopPanelId = null;
		}

		document.addEventListener('click', function(e) {
			var bar = document.getElementById('uniqlo-cat-bar');
			if (bar && !bar.contains(e.target)) {
				closeAllShopMegaMenus();
			}
		});
	</script>
	@endif

	<!-- Modern 2-Column Shop Layout -->
	<section class="py-5" id="section-produk" style="background-color: #f7f9fc;">
		<div class="container">
			<div class="row">
				<!-- Left Sidebar Filter -->
				<div class="col-lg-3 mb-4">
					<div class="bg-white border p-4 shadow-sm" style="border-radius: 16px; border-color: #edf0f5 !important; position: sticky; top: 96px;">
						<div class="d-flex justify-content-between align-items-center pb-3 mb-3 border-bottom">
							<h6 class="font-weight-bold text-dark mb-0" style="font-size: 15px;">
								<i class="fa fa-sliders-h text-primary mr-2"></i> Filter Produk
							</h6>
							@if(!empty($search) || (!empty($kategori) && $kategori !== 'all') || (!empty($subKategori) && $subKategori !== 'all') || ($minHarga !== null && $minHarga !== '') || ($maxHarga !== null && $maxHarga !== '') || $readyStock == '1' || $sort !== 'terbaru')
							<a href="{{ route('frontend.shop') }}" class="small text-danger font-weight-bold" style="font-size: 12px;">Reset</a>
							@endif
						</div>

						<form action="{{ route('frontend.shop') }}" method="GET" id="shopFilterForm">
							<input type="hidden" name="sort" value="{{ $sort }}">
							@if(!empty($subKategori) && $subKategori !== 'all')
								<input type="hidden" name="sub_kategori" value="{{ $subKategori }}">
							@endif

							<!-- Search -->
							<div class="form-group mb-4">
								<label class="small font-weight-bold text-dark mb-2 d-block">Cari Produk</label>
								<div class="input-group input-group-sm">
									<input type="text" name="search" class="form-control" placeholder="Nama baju, kategori..." value="{{ $search }}" style="border-radius: 10px 0 0 10px; border: 1.5px solid #e2e8f0; height: 38px;">
									<div class="input-group-append">
										<button type="submit" class="btn btn-primary px-3" style="background: #717fe0; border-color: #717fe0; border-radius: 0 10px 10px 0;">
											<i class="fa fa-search"></i>
										</button>
									</div>
								</div>
							</div>

							<!-- Categories & Sub-Categories Tree -->
							<div class="form-group mb-4">
								<label class="small font-weight-bold text-dark mb-2 d-block">Kategori & Sub-Kategori</label>
								<div class="list-group list-group-flush">
									<a href="{{ route('frontend.shop', array_filter(['search' => $search, 'sort' => $sort, 'min_harga' => $minHarga, 'max_harga' => $maxHarga, 'ready_stock' => $readyStock])) }}"
									   class="d-flex justify-content-between align-items-center py-2 px-2 rounded mb-1 text-decoration-none {{ empty($kategori) || $kategori === 'all' ? 'font-weight-bold text-primary' : 'text-dark' }}"
									   style="font-size: 13.5px; {{ empty($kategori) || $kategori === 'all' ? 'background: #eef2ff;' : '' }}">
										<span>Semua Produk</span>
										<span class="badge badge-light border text-muted">{{ $allProductsCount ?? 0 }}</span>
									</a>
									@foreach($mainKategoris as $mCat)
									@php $isMainActive = (strtolower($kategori ?? '') === strtolower($mCat->nama_kategori)); @endphp
									<a href="{{ route('frontend.shop', array_filter(['kategori' => $mCat->nama_kategori, 'search' => $search, 'sort' => $sort, 'min_harga' => $minHarga, 'max_harga' => $maxHarga, 'ready_stock' => $readyStock])) }}"
									   class="d-flex justify-content-between align-items-center py-2 px-2 rounded mb-1 text-decoration-none {{ $isMainActive ? 'font-weight-bold text-primary' : 'text-dark' }}"
									   style="font-size: 13.5px; {{ $isMainActive ? 'background: #eef2ff;' : '' }}">
										<span>{{ $mCat->nama_kategori }}</span>
										<span class="badge badge-light border text-muted">{{ $categoryCounts[$mCat->nama_kategori] ?? 0 }}</span>
									</a>
										@if($isMainActive && $mCat->children->isNotEmpty())
										<div class="pl-3 mb-2 border-left ml-2" style="border-color: #d6ddff !important;">
											@foreach($mCat->children as $sub)
											<a href="{{ route('frontend.shop', array_filter(['kategori' => $mCat->nama_kategori, 'sub_kategori' => $sub->nama_kategori, 'search' => $search, 'sort' => $sort, 'min_harga' => $minHarga, 'max_harga' => $maxHarga, 'ready_stock' => $readyStock])) }}"
											   class="d-block py-1 px-2 rounded text-decoration-none {{ strtolower($subKategori ?? '') === strtolower($sub->nama_kategori) ? 'font-weight-bold text-primary' : 'text-muted' }}"
											   style="font-size: 12.5px;">
												{{ $sub->nama_kategori }}
											</a>
											@endforeach
										</div>
										@endif
									@endforeach
								</div>
								@if($kategori && $kategori !== 'all')
									<input type="hidden" name="kategori" value="{{ $kategori }}">
								@endif
							</div>

							<!-- Price Range -->
							<div class="form-group mb-4">
								<label class="small font-weight-bold text-dark mb-2 d-block">Rentang Harga (Rp)</label>
								<div class="d-flex align-items-center" style="gap: 8px;">
									<input type="number" name="min_harga" class="form-control form-control-sm" placeholder="Min" value="{{ $minHarga }}" min="0" style="border-radius: 8px; height: 36px;">
									<span class="text-muted">-</span>
									<input type="number" name="max_harga" class="form-control form-control-sm" placeholder="Maks" value="{{ $maxHarga }}" min="0" style="border-radius: 8px; height: 36px;">
								</div>
							</div>

							<!-- Stock Availability -->
							<div class="form-group mb-2">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="readyStockSwitch" name="ready_stock" value="1" {{ $readyStock == '1' ? 'checked' : '' }} onchange="this.form.submit()">
									<label class="custom-control-label small font-weight-bold text-dark" for="readyStockSwitch">Hanya Stok Tersedia</label>
								</div>
							</div>

							<!-- Active Promo Filter -->
							<div class="form-group mb-4">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="onlyPromoSwitch" name="promo" value="1" {{ ($onlyPromo ?? '') == '1' ? 'checked' : '' }} onchange="this.form.submit()">
									<label class="custom-control-label small font-weight-bold text-danger" for="onlyPromoSwitch">
										<i class="fa fa-bolt mr-1"></i> Sedang Promo
									</label>
								</div>
							</div>

							<button type="submit" class="btn btn-primary btn-block font-weight-bold py-2" style="background: linear-gradient(135deg, #717fe0 0%, #5865d6 100%); border: none; border-radius: 10px; font-size: 13.5px;">
								<i class="fa fa-filter mr-1"></i> Terapkan Filter
							</button>
						</form>
					</div>
				</div>

				<!-- Right Product Grid -->
				<div class="col-lg-9">
					<!-- Top Sort & Count Bar -->
					<div class="bg-white border px-3 py-2 mb-4 shadow-sm d-flex flex-wrap justify-content-between align-items-center" style="border-radius: 14px; border-color: #edf0f5 !important; gap: 10px;">
						<div class="small text-muted">
							Menampilkan <strong class="text-dark">{{ $barang->firstItem() ?? 0 }} - {{ $barang->lastItem() ?? 0 }}</strong> dari <strong class="text-dark">{{ $barang->total() }}</strong> produk
							@if($kategori && $kategori !== 'all')
								<span class="badge badge-primary ml-1" style="background: #717fe0;">{{ $kategori }}</span>
							@endif
							@if(!empty($subKategori) && $subKategori !== 'all')
								<span class="badge badge-info ml-1">{{ $subKategori }}</span>
							@endif
							@if($search !== '')
								<span class="badge badge-light border ml-1">"{{ $search }}"</span>
							@endif
							@if(($onlyPromo ?? '') == '1')
								<span class="badge badge-danger ml-1"><i class="fa fa-bolt mr-1"></i>Sedang Promo</span>
							@endif
						</div>

						<form action="{{ route('frontend.shop') }}" method="GET" class="d-flex align-items-center" style="gap: 8px;">
							@if($kategori && $kategori !== 'all') <input type="hidden" name="kategori" value="{{ $kategori }}"> @endif
							@if(!empty($subKategori) && $subKategori !== 'all') <input type="hidden" name="sub_kategori" value="{{ $subKategori }}"> @endif
							@if($search !== '') <input type="hidden" name="search" value="{{ $search }}"> @endif
							@if($minHarga !== null && $minHarga !== '') <input type="hidden" name="min_harga" value="{{ $minHarga }}"> @endif
							@if($maxHarga !== null && $maxHarga !== '') <input type="hidden" name="max_harga" value="{{ $maxHarga }}"> @endif
							@if($readyStock == '1') <input type="hidden" name="ready_stock" value="1"> @endif
							@if(($onlyPromo ?? '') == '1') <input type="hidden" name="promo" value="1"> @endif

							<label class="small text-muted mb-0 d-none d-sm-inline">Urutkan:</label>
							<select name="sort" class="form-control form-control-sm" onchange="this.form.submit()" style="width: auto; border-radius: 10px; border: 1.5px solid #e2e8f0; height: 34px; font-size: 13px; font-weight: 500;">
								<option value="terbaru" {{ $sort === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
								<option value="termurah" {{ $sort === 'termurah' ? 'selected' : '' }}>Harga: Termurah</option>
								<option value="termahal" {{ $sort === 'termahal' ? 'selected' : '' }}>Harga: Termahal</option>
								<option value="nama" {{ $sort === 'nama' ? 'selected' : '' }}>Nama: A - Z</option>
							</select>
						</form>
					</div>


			<div class="row" id="product-grid">
				@forelse($barang as $index => $item)
					@php
						$catSlug = \Illuminate\Support\Str::slug($item->kategori);
						$defaultImages = [
							'images/product-01.jpg',
							'images/product-02.jpg',
							'images/product-03.jpg',
							'images/product-04.jpg',
							'images/product-05.jpg',
							'images/product-06.jpg',
							'images/product-07.jpg',
							'images/product-08.jpg',
						];
						$imgSrc = $item->gambar ? asset('storage/' . $item->gambar) : asset('frontend-assets/' . $defaultImages[$index % count($defaultImages)]);
					@endphp
					<div class="col-6 col-md-4 mb-4">
						<div class="product-card-modern">
							<!-- Gambar Produk + Floating Actions -->
							<div class="product-img-modern">
								<img src="{{ $imgSrc }}" alt="{{ $item->nama }}" class="js-show-product-modal"
								     data-id="{{ $item->id_barang }}"
								     data-nama="{{ $item->nama }}"
								     data-kategori="{{ $item->kategori }}"
								     data-supplier="{{ $item->supplier }}"
								     data-stok="{{ $item->stok_aktual }}"
								     data-rawprice="{{ $item->harga_final }}"
								     data-harga="Rp {{ number_format($item->harga_final, 0, ',', '.') }}"
								     data-gambar="{{ $imgSrc }}">

								@if($item->is_promo_active)
									<span style="position: absolute; top: 10px; left: 10px; z-index: 5; background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 99px; box-shadow: 0 3px 10px rgba(239,68,68,0.3);">
										<i class="fa fa-bolt mr-1"></i>PROMO -{{ $item->diskon_persen_aktual }}%
									</span>
								@endif

								@if($item->gambars->count() > 0)
									<span class="badge-gallery-float">
										<i class="fa fa-images"></i> +{{ $item->gambars->count() }} Foto
									</span>
								@endif

								<!-- Floating Wishlist Button -->
								<div class="btn-wishlist-float">
									<a href="javascript:void(0);" class="btn-addwish-b2 dis-block pos-relative js-toggle-wishlist"
									   data-id="{{ $item->id_barang }}"
									   data-nama="{{ $item->nama }}"
									   data-kategori="{{ $item->kategori }}"
									   data-rawprice="{{ $item->harga_final }}"
									   data-harga="Rp {{ number_format($item->harga_final, 0, ',', '.') }}"
									   data-gambar="{{ $imgSrc }}"
									   title="Tambah ke Wishlist">
										<img class="icon-heart1 dis-block trans-04" src="{{ asset('frontend-assets/images/icons/icon-heart-01.png') }}" alt="ICON">
										<img class="icon-heart2 dis-block trans-04 ab-t-l" src="{{ asset('frontend-assets/images/icons/icon-heart-02.png') }}" alt="ICON">
									</a>
								</div>

								<!-- Modern Quick View Pill -->
								<a href="javascript:void(0);" 
								   class="btn-quickview-modern js-show-product-modal"
								   data-id="{{ $item->id_barang }}"
								   data-nama="{{ $item->nama }}"
								   data-kategori="{{ $item->kategori }}"
								   data-supplier="{{ $item->supplier }}"
								   data-stok="{{ $item->stok_aktual }}"
								   data-rawprice="{{ $item->harga_final }}"
								   data-harga="Rp {{ number_format($item->harga_final, 0, ',', '.') }}"
								   data-gambar="{{ $imgSrc }}">
									<i class="fa fa-eye"></i> Quick View
								</a>
							</div>

							<!-- Body Card -->
							<div class="product-body-modern">
								<!-- Kategori & Status Stok -->
								<div class="product-meta-row">
									<span class="badge-cat-modern product-item-category">{{ $item->kategori }}</span>
									@if($item->stok_aktual > 0)
										<span class="stock-indicator text-success">
											<span class="stock-dot" style="background: #10b981;"></span> Stok {{ $item->stok_aktual }}
										</span>
									@else
										<span class="stock-indicator text-danger">
											<span class="stock-dot" style="background: #ef4444;"></span> Habis
										</span>
									@endif
								</div>

								<!-- Nama Produk -->
								<a href="javascript:void(0);" 
								   class="product-title-modern product-item-title js-show-product-modal"
								   data-id="{{ $item->id_barang }}"
								   data-nama="{{ $item->nama }}"
								   data-kategori="{{ $item->kategori }}"
								   data-supplier="{{ $item->supplier }}"
								   data-stok="{{ $item->stok_aktual }}"
								   data-rawprice="{{ $item->harga_final }}"
								   data-harga="Rp {{ number_format($item->harga_final, 0, ',', '.') }}"
								   data-gambar="{{ $imgSrc }}">
									{{ $item->nama }}
								</a>

								<!-- Chips Variasi (jika ada) -->
								@if($item->variasis->count() > 0)
								<div class="d-flex align-items-center flex-wrap mb-2" style="gap: 5px;">
									@if($item->ukuran_list->count() > 0)
										<span class="chip-var">
											<i class="fa fa-ruler-horizontal text-muted"></i> {{ $item->ukuran_list->take(4)->implode('/') }}
										</span>
									@endif
									@if($item->warna_list->count() > 0)
										<span class="chip-var chip-var-color">
											<i class="fa fa-palette"></i> {{ $item->warna_list->count() }} Warna
										</span>
									@endif
								</div>
								@endif

								<!-- Footer Card: Harga & Rating -->
								@php
									$avgRat = $item->average_rating;
									$totRev = $item->total_reviews;
								@endphp
								<div class="product-footer-modern">
									<div>
										<span class="product-price-modern product-item-price {{ $item->is_promo_active ? 'text-danger' : '' }}">
											Rp {{ number_format($item->harga_final, 0, ',', '.') }}
										</span>
										@if($item->is_promo_active)
											<div class="small text-muted" style="font-size: 0.72rem; line-height: 1.1;">
												<del>Rp {{ number_format($item->harga, 0, ',', '.') }}</del>
											</div>
										@endif
									</div>

									@if($totRev > 0)
										<span class="rating-pill-modern" title="Rating {{ $avgRat }} dari {{ $totRev }} ulasan">
											<i class="fa fa-star text-warning"></i> {{ $avgRat }} <span class="text-muted font-weight-normal">({{ $totRev }})</span>
										</span>
									@else
										<span class="rating-pill-new">Baru</span>
									@endif
								</div>
							</div>
						</div>
					</div>
				@empty
					<div class="col-12">
						<div class="bg-white border rounded-lg p-5 text-center shadow-sm" style="border-radius: 16px;">
							<i class="zmdi zmdi-search-for" style="font-size: 56px; color: #cbd5e1;"></i>
							<h5 class="font-weight-bold text-dark mt-3">Produk tidak ditemukan</h5>
							<p class="text-muted small mb-4">Coba ubah kata kunci pencarian, rentang harga, atau pilih kategori lain.</p>
							<a href="{{ route('frontend.shop') }}" class="btn btn-primary rounded-pill px-4" style="background: #717fe0; border-color: #717fe0;">
								<i class="fa fa-refresh mr-1"></i> Tampilkan Semua Produk
							</a>
						</div>
					</div>
				@endforelse
			</div>

			<!-- Pagination -->
			@if($barang->hasPages())
			<div class="d-flex justify-content-center mt-4">
				{{ $barang->links('pagination::bootstrap-4') }}
			</div>
			@endif
				</div>
			</div>
		</div>
	</section>


	<!-- Footer -->
	@include('frontend.partials.footer')


	<!-- Back to top -->
	<div class="btn-back-to-top" id="myBtn">
		<span class="symbol-btn-back-to-top">
			<i class="zmdi zmdi-chevron-up"></i>
		</span>
	</div>

	@include('frontend.partials.modals')

	<!-- Midtrans Snap JS (Sandbox Demo) -->
	@if(config('midtrans.is_production'))
		<script type="text/javascript" src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
	@else
		<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
	@endif

<!--===============================================================================================-->
	<script src="{{ asset('frontend-assets/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('frontend-assets/vendor/animsition/js/animsition.min.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('frontend-assets/vendor/bootstrap/js/popper.js') }}"></script>
	<script src="{{ asset('frontend-assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('frontend-assets/vendor/select2/select2.min.js') }}"></script>
	<script>
		$(".js-select2").each(function(){
			$(this).select2({
				minimumResultsForSearch: 20,
				dropdownParent: $(this).next('.dropDownSelect2')
			});
		})
	</script>
<!--===============================================================================================-->
	<script src="{{ asset('frontend-assets/vendor/daterangepicker/moment.min.js') }}"></script>
	<script src="{{ asset('frontend-assets/vendor/daterangepicker/daterangepicker.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('frontend-assets/vendor/slick/slick.min.js') }}"></script>
	<script src="{{ asset('frontend-assets/js/slick-custom.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('frontend-assets/vendor/parallax100/parallax100.js') }}"></script>
	<script>
        $('.parallax100').parallax100();
	</script>
<!--===============================================================================================-->
	<script src="{{ asset('frontend-assets/vendor/MagnificPopup/jquery.magnific-popup.min.js') }}"></script>
	<script>
		$('.gallery-lb').each(function() { // the containers for all your galleries
			$(this).magnificPopup({
		        delegate: 'a', // the selector for gallery item
		        type: 'image',
		        gallery: {
		        	enabled:true
		        },
		        mainClass: 'mfp-fade'
		    });
		});
	</script>
<!--===============================================================================================-->
	<script src="{{ asset('frontend-assets/vendor/isotope/isotope.pkgd.min.js') }}"></script>
<!--===============================================================================================-->
	<script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
	<style>
		.swal2-container.swal2-top-end,
		.swal2-container.swal2-top-right {
			z-index: 99999 !important;
		}
		.swal2-popup.swal2-toast {
			border-radius: 12px !important;
			padding: 12px 16px !important;
			box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12) !important;
			border: 1px solid rgba(0, 0, 0, 0.06) !important;
			overflow: hidden !important;
		}
		.swal2-popup.swal2-toast .swal2-timer-progress-bar {
			background: linear-gradient(90deg, #717fe0, #4f46e5) !important;
			height: 4px !important;
		}
	</style>
	<script>
		// Modern SweetAlert2 Toast (3 detik + animasi loading progress bar di bawah toast)
		window.Toast = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			showCloseButton: true,
			timer: 3000,
			timerProgressBar: true,
			didOpen: function(toast) {
				toast.onmouseenter = Swal.stopTimer;
				toast.onmouseleave = Swal.resumeTimer;
			}
		});

		window.swal = function() {
			var args = Array.prototype.slice.call(arguments);
			if (args.length === 1 && typeof args[0] === 'object' && args[0] !== null) {
				var cfg = args[0];
				if (cfg.buttons) {
					var swal2Cfg = {
						title: cfg.title || '',
						text: cfg.text || '',
						icon: cfg.icon || 'info',
						confirmButtonColor: '#717fe0',
						cancelButtonColor: '#6c757d',
						showCancelButton: Boolean(cfg.buttons.cancel),
						cancelButtonText: (typeof cfg.buttons.cancel === 'string') ? cfg.buttons.cancel : 'Tutup',
						confirmButtonText: (cfg.buttons.confirm && cfg.buttons.confirm.text) ? cfg.buttons.confirm.text : 'OK',
					};
					return Swal.fire(swal2Cfg).then(function(result) {
						return result.isConfirmed;
					});
				}
				return window.Toast.fire({
					title: cfg.title || '',
					text: cfg.text || '',
					icon: cfg.icon || 'info'
				});
			}
			return window.Toast.fire({
				title: args[0] || '',
				text: args[1] || '',
				icon: args[2] || 'info'
			});
		};
	</script>
<!--===============================================================================================-->
	<script src="{{ asset('frontend-assets/vendor/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
	<script>
		$('.js-pscroll').each(function(){
			$(this).css('position','relative');
			$(this).css('overflow','hidden');
			var ps = new PerfectScrollbar(this, {
				wheelSpeed: 1,
				scrollingThreshold: 1000,
				wheelPropagation: false,
			});

			$(window).on('resize', function(){
				ps.update();
			})
		});
	</script>
<!--===============================================================================================-->
	<script>
		window.isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
		window.currentUserId = {{ auth()->check() ? auth()->id() : 'null' }};
	</script>
	<script src="{{ asset('frontend-assets/js/main.js') }}"></script>


	<script>
		// ==================== UTILITY FUNCTIONS ====================
		function formatRupiah(amount) {
			var num = parseInt(amount) || 0;
			return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		}

		function escapeHtml(str) {
			if (!str) return '';
			return String(str)
				.replace(/&/g, '&amp;')
				.replace(/</g, '&lt;')
				.replace(/>/g, '&gt;')
				.replace(/"/g, '&quot;')
				.replace(/'/g, '&#039;');
		}

		// ==================== USER PROFILE DROPDOWN ====================
		$(document).on('click', '.js-user-dropdown-toggle', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var $dropdown = $(this).closest('.js-user-dropdown');
			$('.js-user-dropdown').not($dropdown).removeClass('show');
			$dropdown.toggleClass('show');
		});

		$(document).on('click', function(e) {
			if (!$(e.target).closest('.js-user-dropdown').length) {
				$('.js-user-dropdown').removeClass('show');
			}
		});

		// ==================== STORAGE & STATE ====================
		var CART_KEY = window.isLoggedIn ? ('cozastore_cart_u_' + window.currentUserId) : null;
		var WISHLIST_KEY = window.isLoggedIn ? ('cozastore_wishlist_u_' + window.currentUserId) : null;

		function requireLoginPrompt(actionLabel) {
			swal({
				title: "Login Diperlukan",
				text: "Silakan masuk atau daftar akun terlebih dahulu untuk " + (actionLabel || "melanjutkan") + ".",
				icon: "info",
				buttons: {
					cancel: "Nanti Saja",
					confirm: {
						text: "Masuk / Daftar",
						className: "btn btn-primary"
					}
				}
			}).then(function(willLogin) {
				if (willLogin) {
					window.location.href = '/login';
				}
			});
		}

		@include('frontend.partials.storefront-scripts')

		// Instant search box filter
		$('input[name="search-product"]').on('keyup', function() {
			var keyword = $(this).val().toLowerCase();
			$('.isotope-item').each(function() {
				var title = $(this).find('.product-item-title').text().toLowerCase();
				if (title.indexOf(keyword) > -1) {
					$(this).show();
				} else {
					$(this).hide();
				}
			});
			if (window.jQuery && jQuery.fn.isotope) {
				$('.isotope-grid').isotope('layout');
			}
		});

		// Navbar Shop -> Smooth scroll to Katalog Produk
		$(document).on('click', '.js-scroll-katalog', function(e) {
			var targetEl = document.getElementById('section-produk');
			if (targetEl) {
				e.preventDefault();
				targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
			}
		});

		// Banner click -> Direct smooth scroll to product section
		$(document).on('click', '.item-slick1', function(e) {
			var link = $(this).data('link') || '#section-produk';
			if (link.indexOf('#product-grid') > -1 || link.indexOf('#section-produk') > -1) {
				e.preventDefault();
				var targetEl = document.getElementById('section-produk') || document.getElementById('product-grid');
				if (targetEl) {
					targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
					return;
				}
			}
			window.location.href = link;
		});

		$(document).on('click', '.js-banner-btn', function(e) {
			e.stopPropagation();
			var href = $(this).attr('href');
			if (href && (href.indexOf('#product-grid') > -1 || href.indexOf('#section-produk') > -1)) {
				e.preventDefault();
				var targetEl = document.getElementById('section-produk') || document.getElementById('product-grid');
				if (targetEl) {
					targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
				}
			}
		});

	</script>
</body>
</html>