{{-- @auth --}}
<footer class="main-footer">
	<div class="row">
		<div class="pull-right hidden-xs">
			<strong>Copyright &copy; {{ date('Y') }}. {!! config('frontend.credits') !!}</strong>. All rights reserved.
		</div>
		<div class="col-sm-3">
			<b>Version</b> {{ config('frontend.version') }} | 
			Time:<b>{{ round(microtime(true) - LARAVEL_START, 6) }}s</b>
		</div>  
	</div>
</footer>
{{-- @endauth --}}
