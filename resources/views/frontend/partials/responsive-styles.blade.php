<style>
	/* Global Responsive Guard (Desktop, Tablet, Mobile) */
	html, body {
		overflow-x: hidden;
		max-width: 100%;
	}

	/* Touch devices & Tablet/Mobile: show Quick View button cleanly */
	@media (hover: none), (max-width: 991.98px) {
		.btn-quickview-modern {
			opacity: 0.96 !important;
			transform: translateX(-50%) translateY(0) !important;
			bottom: 10px !important;
			padding: 6px 14px !important;
			font-size: 12px !important;
		}
	}

	/* Tablet Breakpoint (768px - 991px) */
	@media (max-width: 991.98px) {
		.header-v4 .container-menu-desktop,
		.container-menu-desktop {
			display: none !important;
		}
		.wrap-header-mobile {
			display: flex !important;
			align-items: center;
			justify-content: space-between;
			position: sticky;
			top: 0;
			z-index: 1000;
		}
		.product-img-modern {
			height: 230px !important;
		}
		#modalCheckout .modal-dialog {
			max-width: calc(100% - 24px) !important;
			margin: 12px auto !important;
		}
		.js-uniqlo-subpanel {
			max-height: 70vh;
			overflow-y: auto;
		}
	}

	/* Mobile Breakpoint (<= 767px) */
	@media (max-width: 767.98px) {
		.product-img-modern {
			height: 200px !important;
		}
		.product-body-modern {
			padding: 12px !important;
		}
		.product-title-modern {
			font-size: 13.5px !important;
		}
		.product-price-modern {
			font-size: 14px !important;
		}
		.cart-drawer-modern {
			width: 100% !important;
			max-width: 380px !important;
		}
		.orders-hero-modern,
		.profile-hero-modern {
			padding: 20px 0 !important;
			margin-bottom: 20px !important;
		}
		.orders-hero-modern h2,
		.profile-hero-modern h2 {
			font-size: 1.4rem !important;
		}
		.order-filter-pills {
			flex-wrap: nowrap !important;
			overflow-x: auto;
			-webkit-overflow-scrolling: touch;
			padding-bottom: 6px;
		}
		.order-filter-pill {
			white-space: nowrap;
			flex-shrink: 0;
		}
		.order-header,
		.order-body,
		.order-footer {
			padding: 14px 16px !important;
		}
		.profile-card-modern {
			padding: 18px 16px !important;
		}
		.track-hero-modern {
			padding: 28px 0 54px 0 !important;
		}
		.footer-modern {
			padding: 36px 0 22px 0 !important;
		}
		.footer-bottom-bar {
			justify-content: center !important;
			text-align: center;
		}
	}

	/* Small Mobile Breakpoint (<= 575px) */
	@media (max-width: 575.98px) {
		.product-img-modern {
			height: 175px !important;
		}
		.item-row {
			flex-wrap: wrap;
			gap: 10px !important;
		}
		.item-img {
			width: 56px !important;
			height: 56px !important;
		}
		#modalCheckout .modal-body {
			padding: 14px !important;
		}
		#modalCheckout .modal-footer {
			flex-direction: column-reverse;
			gap: 8px;
		}
		#modalCheckout .modal-footer button {
			width: 100%;
		}
		.checkout-card-box {
			padding: 14px !important;
		}
	}

	/* Storefront Desktop Sticky Header Navbar Polish */
	.container-menu-desktop {
		height: 70px !important;
	}

	.wrap-menu-desktop,
	.fix-menu-desktop .wrap-menu-desktop {
		position: fixed !important;
		top: 0 !important;
		left: 0 !important;
		right: 0 !important;
		width: 100% !important;
		height: 70px !important;
		background-color: rgba(255, 255, 255, 0.96) !important;
		backdrop-filter: blur(12px);
		-webkit-backdrop-filter: blur(12px);
		border-bottom: 1px solid #e2e8f0 !important;
		box-shadow: 0 4px 18px rgba(15, 23, 42, 0.06) !important;
		z-index: 1000 !important;
	}

	/* Quick View Title Alignment & Disabled/Muted Out-of-Stock Variants */
	.quickview-info-col {
		justify-content: flex-start !important;
		padding-top: 28px !important;
	}

	.var-pill.is-unavailable,
	.var-pill:disabled {
		opacity: 0.45 !important;
		background-color: #f1f5f9 !important;
		color: #94a3b8 !important;
		border: 1px dashed #cbd5e1 !important;
		text-decoration: line-through !important;
		cursor: not-allowed !important;
		pointer-events: none !important;
		box-shadow: none !important;
	}

	.quickview-btn-cart:disabled {
		background: #cbd5e1 !important;
		color: #64748b !important;
		cursor: not-allowed !important;
		box-shadow: none !important;
		transform: none !important;
	}
</style>

