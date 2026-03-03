@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@php
    // Determine dashboard URL based on user type
    if (auth()->check() && auth()->user()->isShop()) {
        $dashboard_url = route('loja.dashboard');
    } else {
        $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home');
        if (config('adminlte.use_route_url', false)) {
            $dashboard_url = $dashboard_url ? route($dashboard_url) : '';
        } else {
            $dashboard_url = $dashboard_url ? url($dashboard_url) : '';
        }
    }
@endphp

<a href="{{ $dashboard_url }}"
    @if($layoutHelper->isLayoutTopnavEnabled())
        class="navbar-brand {{ config('adminlte.classes_brand') }}"
    @else
        class="brand-link {{ config('adminlte.classes_brand') }}"
    @endif>

    {{-- Small brand logo --}}
    <img src="{{ asset(config('adminlte.logo_img', 'vendor/adminlte/dist/img/AdminLTELogo.png')) }}"
         alt="{{ config('adminlte.logo_img_alt', 'AdminLTE') }}"
         class="{{ config('adminlte.logo_img_class', 'brand-image img-circle elevation-3') }}"
         style="opacity:.8">

    {{-- Brand text --}}
    <span class="brand-text font-weight-light {{ config('adminlte.classes_brand_text') }}">
        {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
    </span>

</a>
