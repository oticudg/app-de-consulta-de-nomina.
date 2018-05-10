@extends('layouts.base.app')

@section('end')
{{-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. --}}
<body class="hold-transition {{ config('frontend.theme') }} layout-top-nav">
	{{-- Site wrapper --}}
	<div id="app" class="wrapper">

		@include('layouts.partials.frontend.header')

		{{-- Full Width Column --}}
		<div class="content-wrapper">
			<div class="container">
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
			</div> {{-- /.container --}}
		</div> {{-- /.content-wrapper --}}
		@include('layouts.partials._partials.footer')
	</div> {{-- ./wrapper --}}
</body>
@endsection