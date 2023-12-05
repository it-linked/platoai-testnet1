@extends('panel.layout.app')
@section('title', 'Search')

@section('content')
<div class="page-header">
	<div class="container-xl">
		<div class="row g-2 items-center justify-between max-md:flex-col max-md:items-start max-md:gap-4">
			<div class="col">
				<div class="page-pretitle">
					{{ __('User Search') }}
				</div>
				<h2 class="mb-2 page-title">
					{{ __('Welcome') }}, {{ \Illuminate\Support\Facades\Auth::user()->name }}.
				</h2>
			</div>

		</div>
	</div>
</div>
<!-- Page body -->
<div class="page-body">
	<div class="container-xl">
		<div class="row row-deck row-cards">
			<div class="flex items-center lg:-order-1 max-lg:w-full max-lg:fixed max-lg:bottom-16 max-lg:left-0 max-lg:z-50">
				<form class="navbar-search group !me-2 max-lg:hidden max-lg:[&.show]:flex max-lg:[&.collapsing]:flex max-lg:m-0 max-lg:w-full max-lg:!me-0" id="brave-search" autocomplete="off" novalidate>
					<div class="w-full input-icon max-lg:p-3 max-lg:bg-[#fff] max-lg:dark:bg-zinc-800">
						<span class="input-icon-addon">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
								<path stroke="none" d="M0 0h24v24H0z" fill="none" />
								<path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
								<path d="M21 21l-6 -6" />
							</svg>
						</span>
						<input type="search" class="form-control brave-search-input peer max-lg:!rounded-md dark:!bg-zinc-900" id="top_brave_search_word" onkeydown="return event.key != 'Enter';" placeholder="{{__('Search .....')}}" aria-label="">
						<span class="absolute top-1/2 -translate-y-1/2 !end-[20px]">
							<span class="spinner-border spinner-border-sm text-muted hidden group-[.is-searching]:block" role="status"></span>
						</span>

						<input type="hidden" id="search_type" value="{{$slug}}">

					</div>
				</form>
			</div>
		</div>
		<div class="navbar-search-results mt-10 top-[calc(100%+17px)] !start-0 bg-[#fff] shadow-[0_10px_70px_rgba(0,0,0,0.1)] rounded-md w-[100%] max-h-[800px] overflow-y-auto group-[.done-searching]:block dark:!bg-[--tblr-bg-surface] max-lg:top-auto max-lg:bottom-full max-lg:start-0 max-lg:end-0 max-lg:w-auto" id="brave_search_results" style="max-height:800px;z-index: 999;">
			<!-- Search results here -->
			<!--h3 class="m-0 py-[0.75rem] px-3 border-solid border-b border-t-0 border-r-0 border-l-0 border-[--tblr-border-color] text-[1rem] font-medium">{{__('Search results')}}</h3-->
			<div class="brave-search-results-container px-10 py-10"></div>
		</div>
		<div class="row mt-10 flex flex-center" id="pagination-snippet">
			<div id="pagination" class="col-lg-12 flex flex-row"> 
				<a class="btn " id="previous_page" style="display: none;">
					<div class="icon-wrapper mr-10 icon-left">
						<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" class="icon" viewBox="0 0 14 14">
							<path fill-rule="evenodd" d="M.19 7a.8.8 0 0 1 .247-.578l6.26-6a.8.8 0 1 1 1.108 1.156L2.98 6.2h10.01a.8.8 0 0 1 0 1.6H2.98l4.824 4.622a.8.8 0 1 1-1.107 1.156l-6.261-6A.8.8 0 0 1 .19 7z" clip-rule="evenodd"></path>
						</svg></div> Previous
				</a>
				<div id="search_page_no" class="text-gray text-sm mx-4" style="display: none;"></div> 
				<a class="btn svelte-6lprow" id="next_page" style="display: none;" >Next <div class="icon-wrapper ml-10 icon-right"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" class="icon" viewBox="0 0 16 16">
							<path fill="currentColor" fill-rule="evenodd" d="M10.866 8.556H3.222a.556.556 0 1 1 0-1.112h7.644l-3.771-3.48a.556.556 0 0 1 .754-.817l4.52 4.173a.926.926 0 0 1 0 1.36l-4.52 4.173a.556.556 0 0 1-.754-.817l3.77-3.48Z" clip-rule="evenodd"></path>
					</svg></div></a>
			</div>
		</div>
	</div>
</div>
@endsection