<!DOCTYPE html>
<html lang="en">
<head>
	<title>Coza Store | Kelompok 3</title>
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
	<header>
		<!-- Header desktop -->
		<div class="container-menu-desktop">
			<div class="wrap-menu-desktop">
				<nav class="limiter-menu-desktop container">
					
					<!-- Logo desktop -->		
					<a href="/" class="logo">
						<img src="{{ asset('frontend-assets/images/icons/logo-01.png') }}" alt="IMG-LOGO">
					</a>

					<!-- Menu desktop -->
					<div class="menu-desktop">
						<ul class="main-menu">
							<li class="active-menu">
								<a href="/">Home</a>
							</li>

							<li>
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
					<a href="/shop">Shop / Katalog</a>
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

	<!-- Slider -->
	<section class="section-slide">
		<div class="wrap-slick1">
			<div class="slick1">
				@php
					$animations = [
						['down' => 'fadeInDown', 'up' => 'fadeInUp', 'btn' => 'zoomIn'],
						['down' => 'rollIn', 'up' => 'lightSpeedIn', 'btn' => 'slideInUp'],
						['down' => 'rotateInDownLeft', 'up' => 'rotateInUpRight', 'btn' => 'rotateIn'],
					];
				@endphp

				@forelse($banners as $index => $banner)
					@php
						$anim = $animations[$index % count($animations)];
						$imgUrl = str_starts_with($banner->gambar, 'banners/')
							? asset('storage/' . $banner->gambar)
							: (file_exists(public_path('storage/' . $banner->gambar)) ? asset('storage/' . $banner->gambar) : asset('frontend-assets/' . $banner->gambar));
						$linkUrl = $banner->link ?: '/frontend#product-grid';
					@endphp
					<div class="item-slick1" data-link="{{ $linkUrl }}" style="background-image: url({{ $imgUrl }}); cursor: pointer;">
						<div class="container h-full">
							<div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
								@if($banner->subjudul)
								<div class="layer-slick1 animated visible-false" data-appear="{{ $anim['down'] }}" data-delay="0">
									<span class="ltext-101 cl2 respon2 font-weight-bold" style="text-shadow: 0 1px 3px rgba(255,255,255,0.7);">
										{{ $banner->subjudul }}
									</span>
								</div>
								@endif
									
								<div class="layer-slick1 animated visible-false" data-appear="{{ $anim['up'] }}" data-delay="800">
									<h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1 font-weight-bold" style="text-shadow: 0 1px 4px rgba(255,255,255,0.7);">
										{{ $banner->judul }}
									</h2>
								</div>
									
								<div class="layer-slick1 animated visible-false" data-appear="{{ $anim['btn'] }}" data-delay="1600">
									<a href="{{ $linkUrl }}" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 js-banner-btn">
										Shop Now
									</a>
								</div>
							</div>
						</div>
					</div>
				@empty
					<div class="item-slick1" style="background-image: url({{ asset('frontend-assets/images/slide-01.jpg') }});">
						<div class="container h-full">
							<div class="flex-col-l-m h-full p-t-100 p-b-30 respon5">
								<div class="layer-slick1 animated visible-false" data-appear="fadeInDown" data-delay="0">
									<span class="ltext-101 cl2 respon2">Women Collection</span>
								</div>
								<div class="layer-slick1 animated visible-false" data-appear="fadeInUp" data-delay="800">
									<h2 class="ltext-201 cl2 p-t-19 p-b-43 respon1">NEW SEASON</h2>
								</div>
								<div class="layer-slick1 animated visible-false" data-appear="zoomIn" data-delay="1600">
									<a href="/frontend#product-grid" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04">Shop Now</a>
								</div>
							</div>
						</div>
					</div>
				@endforelse
			</div>
		</div>
	</section>


	<!-- Banner -->
	<div class="sec-banner bg0 p-t-80 p-b-50">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
					<!-- Block1 -->
					<div class="block1 wrap-pic-w">
						<img src="{{ asset('frontend-assets/images/banner-01.jpg') }}" alt="IMG-BANNER">

						<a href="/frontend#product-grid" class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
							<div class="block1-txt-child1 flex-col-l">
								<span class="block1-name ltext-102 trans-04 p-b-8">
									Women
								</span>

								<span class="block1-info stext-102 trans-04">
									Spring 2018
								</span>
							</div>

							<div class="block1-txt-child2 p-b-4 trans-05">
								<div class="block1-link stext-101 cl0 trans-09">
									Shop Now
								</div>
							</div>
						</a>
					</div>
				</div>

				<div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
					<!-- Block1 -->
					<div class="block1 wrap-pic-w">
						<img src="{{ asset('frontend-assets/images/banner-02.jpg') }}" alt="IMG-BANNER">

						<a href="/frontend#product-grid" class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
							<div class="block1-txt-child1 flex-col-l">
								<span class="block1-name ltext-102 trans-04 p-b-8">
									Men
								</span>

								<span class="block1-info stext-102 trans-04">
									Spring 2018
								</span>
							</div>

							<div class="block1-txt-child2 p-b-4 trans-05">
								<div class="block1-link stext-101 cl0 trans-09">
									Shop Now
								</div>
							</div>
						</a>
					</div>
				</div>

				<div class="col-md-6 col-xl-4 p-b-30 m-lr-auto">
					<!-- Block1 -->
					<div class="block1 wrap-pic-w">
						<img src="{{ asset('frontend-assets/images/banner-03.jpg') }}" alt="IMG-BANNER">

						<a href="/frontend#product-grid" class="block1-txt ab-t-l s-full flex-col-l-sb p-lr-38 p-tb-34 trans-03 respon3">
							<div class="block1-txt-child1 flex-col-l">
								<span class="block1-name ltext-102 trans-04 p-b-8">
									Accessories
								</span>

								<span class="block1-info stext-102 trans-04">
									New Trend
								</span>
							</div>

							<div class="block1-txt-child2 p-b-4 trans-05">
								<div class="block1-link stext-101 cl0 trans-09">
									Shop Now
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	@if(isset($promoProducts) && $promoProducts->count() > 0)
	<!-- Promo Spesial Showcase -->
	<section class="py-4" style="background: linear-gradient(135deg, #fff1f2 0%, #fef2f2 55%, #fff7ed 100%); border-top: 1px solid #ffe4e6; border-bottom: 1px solid #ffe4e6;">
		<div class="container">
			<div class="d-flex flex-wrap justify-content-between align-items-center mb-3" style="gap: 10px;">
				<div>
					<span class="badge badge-danger px-2 py-1 mb-1" style="font-size: 11px; border-radius: 99px;"><i class="fa fa-bolt mr-1"></i> PROMO TERBATAS</span>
					<h4 class="font-weight-bold text-dark mb-0" style="font-size: 1.4rem;">Promo Spesial Hari Ini</h4>
				</div>
				<a href="{{ route('frontend.shop', ['promo' => 1]) }}" class="btn btn-sm btn-outline-danger font-weight-bold px-3" style="border-radius: 99px;">
					Lihat Semua Promo <i class="fa fa-arrow-right ml-1"></i>
				</a>
			</div>

			<div class="row">
				@foreach($promoProducts as $pItem)
					@php
						$pImg = $pItem->gambar ? asset('storage/' . $pItem->gambar) : asset('frontend-assets/images/product-01.jpg');
					@endphp
					<div class="col-6 col-md-4 col-lg-3 mb-3">
						<div class="product-card-modern h-100">
							<div class="product-img-modern">
								<img src="{{ $pImg }}" alt="{{ $pItem->nama }}" class="js-show-product-modal"
								     data-id="{{ $pItem->id_barang }}"
								     data-nama="{{ $pItem->nama }}"
								     data-kategori="{{ $pItem->kategori }}"
								     data-supplier="{{ $pItem->supplier }}"
								     data-stok="{{ $pItem->stok_aktual }}"
								     data-rawprice="{{ $pItem->harga_final }}"
								     data-harga="Rp {{ number_format($pItem->harga_final, 0, ',', '.') }}"
								     data-gambar="{{ $pImg }}">

								<span style="position: absolute; top: 10px; left: 10px; z-index: 5; background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 99px; box-shadow: 0 3px 10px rgba(239,68,68,0.3);">
									<i class="fa fa-bolt mr-1"></i>PROMO -{{ $pItem->diskon_persen_aktual }}%
								</span>

								<a href="javascript:void(0);"
								   class="btn-quickview-modern js-show-product-modal"
								   data-id="{{ $pItem->id_barang }}"
								   data-nama="{{ $pItem->nama }}"
								   data-kategori="{{ $pItem->kategori }}"
								   data-supplier="{{ $pItem->supplier }}"
								   data-stok="{{ $pItem->stok_aktual }}"
								   data-rawprice="{{ $pItem->harga_final }}"
								   data-harga="Rp {{ number_format($pItem->harga_final, 0, ',', '.') }}"
								   data-gambar="{{ $pImg }}">
									<i class="fa fa-eye"></i> Quick View
								</a>
							</div>
							<div class="product-body-modern">
								<div class="product-meta-row">
									<span class="badge-cat-modern">{{ $pItem->kategori }}</span>
									@if($pItem->promo_selesai)
										<span class="text-danger" style="font-size: 11px; font-weight: 600;">
											<i class="fa fa-clock"></i> s/d {{ $pItem->promo_selesai->format('d M') }}
										</span>
									@endif
								</div>
								<a href="javascript:void(0);"
								   class="product-title-modern js-show-product-modal"
								   data-id="{{ $pItem->id_barang }}"
								   data-nama="{{ $pItem->nama }}"
								   data-kategori="{{ $pItem->kategori }}"
								   data-supplier="{{ $pItem->supplier }}"
								   data-stok="{{ $pItem->stok_aktual }}"
								   data-rawprice="{{ $pItem->harga_final }}"
								   data-harga="Rp {{ number_format($pItem->harga_final, 0, ',', '.') }}"
								   data-gambar="{{ $pImg }}">
									{{ $pItem->nama }}
								</a>
								<div class="product-footer-modern">
									<div>
										<span class="product-price-modern text-danger">Rp {{ number_format($pItem->harga_final, 0, ',', '.') }}</span>
										<div class="small text-muted" style="font-size: 0.72rem; line-height: 1.1;">
											<del>Rp {{ number_format($pItem->harga, 0, ',', '.') }}</del>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</section>
	@endif

	<!-- Product -->
	<section class="bg0 p-t-23 p-b-140" id="section-produk" style="scroll-margin-top: 85px;">
		<div class="container">
			<div class="p-b-10">
				<h3 class="ltext-103 cl5 font-weight-bold">
					Katalog Produk
				</h3>
			</div>

			<div class="flex-w flex-sb-m p-b-52">
				<div class="flex-w flex-l-m filter-tope-group m-tb-10">
					<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5 how-active1" data-filter="*">
						Semua Produk
					</button>

					@foreach($categories as $cat)
						@php $catSlug = \Illuminate\Support\Str::slug($cat); @endphp
						<button class="stext-106 cl6 hov1 bor3 trans-04 m-r-32 m-tb-5" data-filter=".cat-{{ $catSlug }}">
							{{ $cat }}
						</button>
					@endforeach
				</div>

				<div class="flex-w flex-c-m m-tb-10">
					<div class="flex-c-m stext-106 cl6 size-104 bor4 pointer hov-btn3 trans-04 m-r-8 m-tb-4 js-show-filter">
						<i class="icon-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-filter-list"></i>
						<i class="icon-close-filter cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
						 Filter
					</div>

					<div class="flex-c-m stext-106 cl6 size-105 bor4 pointer hov-btn3 trans-04 m-tb-4 js-show-search">
						<i class="icon-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-search"></i>
						<i class="icon-close-search cl2 m-r-6 fs-15 trans-04 zmdi zmdi-close dis-none"></i>
						Search
					</div>
				</div>
				
				<!-- Search product -->
				<div class="dis-none panel-search w-full p-t-10 p-b-15">
					<div class="bor8 dis-flex p-l-15">
						<button class="size-113 flex-c-m fs-16 cl2 hov-cl1 trans-04">
							<i class="zmdi zmdi-search"></i>
						</button>

						<input class="mtext-107 cl2 size-114 plh2 p-r-15" type="text" name="search-product" placeholder="Search">
					</div>	
				</div>

				<!-- Filter -->
				<div class="dis-none panel-filter w-full p-t-10">
					<div class="wrap-filter flex-w bg6 w-full p-lr-40 p-t-27 p-lr-15-sm">
						<div class="filter-col1 p-r-15 p-b-27">
							<div class="mtext-102 cl2 p-b-15">
								Sort By
							</div>

							<ul>
								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Default
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Popularity
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Average rating
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04 filter-link-active">
										Newness
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Price: Low to High
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										Price: High to Low
									</a>
								</li>
							</ul>
						</div>

						<div class="filter-col2 p-r-15 p-b-27">
							<div class="mtext-102 cl2 p-b-15">
								Price
							</div>

							<ul>
								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04 filter-link-active">
										All
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										$0.00 - $50.00
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										$50.00 - $100.00
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										$100.00 - $150.00
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										$150.00 - $200.00
									</a>
								</li>

								<li class="p-b-6">
									<a href="#" class="filter-link stext-106 trans-04">
										$200.00+
									</a>
								</li>
							</ul>
						</div>

						<div class="filter-col3 p-r-15 p-b-27">
							<div class="mtext-102 cl2 p-b-15">
								Color
							</div>

							<ul>
								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #222;">
										<i class="zmdi zmdi-circle"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04">
										Black
									</a>
								</li>

								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #4272d7;">
										<i class="zmdi zmdi-circle"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04 filter-link-active">
										Blue
									</a>
								</li>

								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #b3b3b3;">
										<i class="zmdi zmdi-circle"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04">
										Grey
									</a>
								</li>

								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #00ad5f;">
										<i class="zmdi zmdi-circle"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04">
										Green
									</a>
								</li>

								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #fa4251;">
										<i class="zmdi zmdi-circle"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04">
										Red
									</a>
								</li>

								<li class="p-b-6">
									<span class="fs-15 lh-12 m-r-6" style="color: #aaa;">
										<i class="zmdi zmdi-circle-o"></i>
									</span>

									<a href="#" class="filter-link stext-106 trans-04">
										White
									</a>
								</li>
							</ul>
						</div>

						<div class="filter-col4 p-b-27">
							<div class="mtext-102 cl2 p-b-15">
								Tags
							</div>

							<div class="flex-w p-t-4 m-r--5">
								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Fashion
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Lifestyle
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Denim
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Streetstyle
								</a>

								<a href="#" class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
									Crafts
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row isotope-grid" id="product-grid">
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
					<div class="col-6 col-sm-6 col-md-4 col-lg-3 p-b-30 isotope-item cat-{{ $catSlug }}">
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
					<div class="col-12 text-center p-t-50 p-b-50">
						<i class="zmdi zmdi-info-outline" style="font-size: 48px; color: #aaa;"></i>
						<h4 class="stext-104 cl4 m-t-15 font-weight-bold">Belum ada produk di database</h4>
						<p class="stext-105 cl6 m-t-5">Silakan masuk ke panel admin untuk menambahkan produk baru.</p>
						@auth
							<a href="/barang/create" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 m-t-15 d-inline-flex">Tambah Produk Sekarang</a>
						@else
							<a href="/login" class="flex-c-m stext-101 cl0 size-101 bg1 bor1 hov-btn1 p-lr-15 trans-04 m-t-15 d-inline-flex">Login Admin</a>
						@endauth
					</div>
				@endforelse
			</div>

			<!-- Lihat Semua Produk -->
			<div class="flex-c-m flex-w w-full p-t-45">
				<a href="{{ route('frontend.shop') }}" class="flex-c-m stext-101 cl0 size-103 bg1 bor1 hov-btn1 p-lr-25 trans-04" style="background: #717fe0; border-color: #717fe0; text-decoration: none; gap: 8px;">
					<span>Lihat Semua Produk ({{ $totalProduk ?? count($barang) }})</span>
					<i class="fa fa-arrow-right"></i>
				</a>
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