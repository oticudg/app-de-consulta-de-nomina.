@extends('layouts.base.app')

@section('head')
<link href="{{ asset('/adminlte/css/backend.css?version=' . config('frontend.version')) }}" rel="stylesheet">
@yield('css')
@endsection

@section('footer')
<script src="{{ asset('/adminlte/js/backend.js?version='.config('frontend.version')) }}"></script>
@yield('js')
@endsection

@section('end')
<body class="hold-transition {{ config('frontend.theme') }} sidebar-mini">
    {{-- Site wrapper --}}
    <div id="app" class="wrapper">

        @include('layouts.partials.backend.header')

        @include('layouts.partials.backend.sidebar')

        {{-- Content Wrapper. Contains page content --}}
        <div class="content-wrapper">
            {{-- Content Header (Page header) --}}
            <section class="content-header">
                <h1>
                    @yield('page-title')
                    <small>@yield('page-subtitle')</small>
                </h1>
                @yield('breadcrumbs')
            </section>

            {{-- Main content --}}
            <section class="content">

                @yield('content')

            </section> {{-- /.content --}}
        </div> {{-- /.content-wrapper --}}

        @include('layouts.partials._partials.footer')
    </div> {{-- ./wrapper --}}
</body>
@endsection