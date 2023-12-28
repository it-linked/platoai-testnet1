<!doctype html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
@include('panel.layout.head')
<body class="group/body">
	<script src="/assets/js/tabler-theme.min.js"></script>
	<script src="/assets/js/navbar-shrink.js"></script>

	<div id="app-loading-indicator" class="fixed top-0 left-0 right-0 z-[99] opacity-0 transition-opacity">
		<div class="progress [--tblr-progress-height:3px]">
			<div class="progress-bar progress-bar-indeterminate bg-[--tblr-primary] before:[animation-timing-function:ease-in-out] dark:bg-white"></div>
		</div>
	</div>

	<div class="page">
		<!-- Navbar -->
		@if(view()->exists('panel.admin.custom.layout.panel.header'))
			@include('panel.admin.custom.layout.panel.header')
		@else
			@include('panel.layout.header')
		@endif

		<div class="page-wrapper overflow-hidden">
			<!-- Updater -->
            @if($good_for_now)
			@yield('content')
            @elseif(!$good_for_now and Route::currentRouteName()!= 'dashboard.admin.settings.general')
                @include('vendor.installer.magicai_c4st_Act')
            @else
                @yield('content')
            @endif
			@include('panel.layout.footer')
		</div>
	</div>

	@include('panel.layout.scripts')

	@if(\Session::has('message'))
	<script>
		toastr.{{\Session::get('type')}}('{{\Session::get('message')}}')
	</script>
	@endif

	@yield('script')
	<script src="/assets/js/frontend.js"></script>

	@if($setting->dashboard_code_before_body != null)
        {!!$setting->dashboard_code_before_body!!}
    @endif

	@auth()
		@if(\Illuminate\Support\Facades\Auth::user()->type == 'admin')
			<script src="/assets/js/panel/update-check.js"></script>
		@endif
	@endauth

	<template id="typing-template">
		<div class="lqd-typing !inline-flex !items-center !rounded-full !py-2 !px-3 !gap-3 !bg-[#efd8fc] !text-[--lqd-heading-color] !text-xs !leading-none !font-medium">
			{{__('Typing')}}
			<div class="lqd-typing-dots !flex !items-center !gap-1">
				<span class="lqd-typing-dot !w-1 !h-1 !rounded-full"></span>
				<span class="lqd-typing-dot !w-1 !h-1 !rounded-full"></span>
				<span class="lqd-typing-dot !w-1 !h-1 !rounded-full"></span>
			</div>
		</div>
	</template>

	<script>window.gtranslateSettings = {"default_language":"en","native_language_names":true,"detect_browser_language":true,"wrapper_selector":".language_dropdown","switcher_horizontal_position":"right","switcher_vertical_position":"bottom","float_switcher_open_direction":"bottom","alt_flags":{"en":"usa"}}</script>
	<script src="https://cdn.gtranslate.net/widgets/latest/float.js" defer></script>

	<script>
		document.addEventListener('DOMContentLoaded', function () {

			var imgElements = document.querySelectorAll('.gt-current-lang img');

			imgElements.forEach(function (img) {
				// Get the image source from an existing attribute (e.g., 'src')
				var currentSrc = img.getAttribute('src');

				// Set the 'data-gt-lazy-src' attribute to the existing 'src' value
				img.setAttribute('data-gt-lazy-src', currentSrc);
			});


			function updateLanguageInURL(selectedLang) {
				var currentUrl = window.location.href;
				var domain = window.location.protocol + '//' + window.location.host + '/';
				var currentLangRegEx = /\/[a-z]{2}(-[a-zA-Z]{2})?($|\/)/i;
				var isCurrentLanguageInUrl = currentLangRegEx.test(currentUrl);
				var newUrl = currentUrl.replace(currentLangRegEx, '/');
				var selectedLangCode = selectedLang.split('-')[0];
				newUrl = domain + selectedLangCode + '/' + newUrl.split(domain)[1];

				// Update the URL without reloading the page
				window.history.pushState({ path: newUrl }, '', newUrl);
			}

			// Function to handle language change
			function handleLanguageChange(event) {
				event.preventDefault();
				var selectedLang = this.getAttribute('data-gt-lang');
				updateLanguageInURL(selectedLang);
			}
			
			// Get all language links
			var languageLinks = document.querySelectorAll('.gt_float_switcher a[data-gt-lang]');

			languageLinks.forEach(function (link) {
				link.addEventListener('click', handleLanguageChange);
			});

		});
	</script>

</body>
</html>
