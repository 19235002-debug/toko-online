@if (Session::has('alert.config') || Session::has('alert.delete'))
    @if (config('sweetalert.animation.enable'))
        <link rel="stylesheet" href="{{ config('sweetalert.animatecss') }}">
    @endif

    @if (config('sweetalert.theme') != 'default')
        <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-{{ config('sweetalert.theme') }}" rel="stylesheet">
    @endif

    @if (config('sweetalert.alwaysLoadJS') === false && config('sweetalert.neverLoadJS') === false)
        <script src="{{ $cdn ?? asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    @endif

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
        .swal2-popup .swal2-file[style*="display: none"],
        .swal2-popup.swal2-toast .swal2-file {
            display: none !important;
        }
    </style>

    <script>
        @if (Session::has('alert.delete'))
            document.addEventListener('click', function(event) {
                if (event.target.matches('[data-confirm-delete]')) {
                    event.preventDefault();
                    Swal.fire({!! Session::pull('alert.delete') !!}).then(function(result) {
                        if (result.isConfirmed) {
                            var form = document.createElement('form');
                            form.action = event.target.href;
                            form.method = 'POST';
                            form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                }
            });
        @endif

        @if (Session::has('alert.config'))
            (function() {
                var alertCfg = {!! Session::pull('alert.config') !!};
                var toastCfg = Object.assign({}, alertCfg, {
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
                Swal.fire(toastCfg);
            })();
        @endif
    </script>
@endif
