@php
	$brandSetting = store_setting();
	$brandLogo = store_logo_url();
	$brandName = $brandSetting->nama_toko ?: 'Coza Store';
@endphp
@if($brandLogo)
	<img src="{{ $brandLogo }}" alt="{{ $brandName }}" style="max-height: {{ $maxHeight ?? '36px' }}; width: auto; object-fit: contain;">
@else
	<span style="font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif; font-weight: 800; font-size: {{ $fontSize ?? '1.25rem' }}; letter-spacing: 0.04em; color: #0f172a; white-space: nowrap; text-transform: uppercase;">
		{{ $brandName }}
	</span>
@endif

