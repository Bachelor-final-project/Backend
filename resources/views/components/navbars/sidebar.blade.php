@props(['activePage'])
@php
    $navItems = [
            [
                'name' => __("Dashboard"),
                'icon' => 'dashboard',
                'route' => route('dashboard')
            ],
            [
                'name' => __("Users"),
                'icon' => 'group',
                'route' => route('user.index')
            ],
            [
                'name' => __("Beneficiaries"),
                'icon' => 'group',
                'route' => route('beneficiary.index')
            ],
            [
                'name' => __("Beneficiaries"),
                'icon' => 'group',
                'route' => route('beneficiary.index')
            ],
            [
                'name' => __("Beneficiaries"),
                'icon' => 'group',
                'route' => route('beneficiary.index')
            ],
            [
                'name' => __("Beneficiaries"),
                'icon' => 'group',
                'route' => route('beneficiary.index')
            ],
            [
                'name' => __("Beneficiaries"),
                'icon' => 'group',
                'route' => route('beneficiary.index')
            ],
            [
                'name' => __("Beneficiaries"),
                'icon' => 'group',
                'route' => route('beneficiary.index')
            ],
            [
                'name' => __("Beneficiaries"),
                'icon' => 'group',
                'route' => route('beneficiary.index')
            ],
            [
                'name' => __("Beneficiaries"),
                'icon' => 'group',
                'route' => route('beneficiary.index')
            ],
        ];
@endphp
<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0 d-flex text-wrap align-items-center" href=" {{ route('dashboard') }} ">
            <img src="{{ asset('assets') }}/img/logo-ct.png" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-2 font-weight-bold text-white">{{ __('Welcom, ')." ". auth()->user()?->name}}</span>
        </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto  max-height-vh-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @foreach ($navItems as $navItem)
            <li class="nav-item">
                <a class="nav-link text-white"
                    href="{{ $navItem['route'] }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">{{ $navItem['icon'] }}</i>
                    </div>
                    <span class="nav-link-text ms-1">{{ $navItem['name'] }}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</aside>

@push("js")
<script>
   console.log("start app.js")
    $(this).ready(function () {
        console.log("start ready app.js")
        var str=location.href.toLowerCase();
        $(".nav-link").each(function() {
            if (this.href.toLowerCase() == str) {
                $(".nav-link").removeClass("active bg-gradient-primary");
                $(this).addClass("active bg-gradient-primary");
                console.log(this.href.toLowerCase());
                console.log(str.indexOf(this.href.toLowerCase()));
            }
        });
    });
</script>
@endpush
