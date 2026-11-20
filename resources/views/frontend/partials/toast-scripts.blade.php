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
				if (cfg.buttons !== false && (cfg.buttons || cfg.button)) {
					var swal2Cfg = {
						title: cfg.title || '',
						text: cfg.text || '',
						icon: cfg.icon || 'info',
						confirmButtonColor: '#717fe0',
						cancelButtonColor: '#6c757d',
						showCancelButton: Boolean(cfg.buttons && cfg.buttons.cancel),
						cancelButtonText: (cfg.buttons && typeof cfg.buttons.cancel === 'string') ? cfg.buttons.cancel : 'Tutup',
						confirmButtonText: (cfg.buttons && cfg.buttons.confirm && cfg.buttons.confirm.text) ? cfg.buttons.confirm.text : 'OK',
					};
					return Swal.fire(swal2Cfg).then(function(result) {
						return result.isConfirmed;
					});
				}
				if (cfg.buttons === false) {
					return Swal.fire({
						title: cfg.title || '',
						text: cfg.text || '',
						icon: cfg.icon || 'info',
						showConfirmButton: false,
						allowOutsideClick: false,
						allowEscapeKey: false
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

		window.swal.close = function() {
			if (typeof Swal !== 'undefined' && typeof Swal.close === 'function') {
				Swal.close();
			}
		};
	</script>

