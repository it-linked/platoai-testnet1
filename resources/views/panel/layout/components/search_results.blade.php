@if(count($template_search)>0)
<ul class="m-0 p-0 list-none font-medium">
    @foreach($template_search as $item)
    <li class="p-2 px-3 border-solid border-b border-t-0 border-r-0 border-l-0 border-[--tblr-border-color] last:border-b-0 transition-colors hover:bg-slate-50  dark:hover:!bg-[rgba(255,255,255,0.05)]">
        @if($item->type == 'text')
            <a href="{{route('dashboard.user.openai.generator.workbook', $item->slug)}}" class="d-flex align-items-center text-heading !no-underline">
				<span class="avatar w-[43px] h-[43px] [&_svg]:w-[20px] [&_svg]:h-[20px] relative !me-2" style="background: {{$item->color}}">
					@if ( $item->image !== 'none' )
					{!! html_entity_decode($item->image) !!}
					@endif
					@if($item->active == 1)
						<span class="badge bg-green !w-[9px] !h-[9px]"></span>
					@else
						<span class="badge bg-red !w-[9px] !h-[9px]"></span>
					@endif
				</span>
                {{$item->title}}
				<small class="ml-auto text-muted opacity-75">{{__('Template')}}</small>
            </a>
        @elseif($item->type == 'image')
            <a href="{{route('dashboard.user.openai.generator', $item->slug)}}" class="d-flex align-items-center text-heading !no-underline">
				<span class="avatar w-[43px] h-[43px] [&_svg]:w-[20px] [&_svg]:h-[20px] relative !me-2" style="background: {{$item->color}}">
					@if ( $item->image !== 'none' )
					{!! html_entity_decode($item->image) !!}
					@endif
					@if($item->active == 1)
						<span class="badge bg-green !w-[9px] !h-[9px]"></span>
					@else
						<span class="badge bg-red !w-[9px] !h-[9px]"></span>
					@endif
				</span>
                {{$item->title}}
				<small class="ml-auto text-muted opacity-75">{{__('Template')}}</small>
            </a>
        @elseif($item->type == 'audio')
            <a href="{{route('dashboard.user.openai.generator', $item->slug)}}" class="d-flex align-items-center text-heading !no-underline">
				<span class="avatar w-[43px] h-[43px] [&_svg]:w-[20px] [&_svg]:h-[20px] relative !me-2" style="background: {{$item->color}}">
					@if ( $item->image !== 'none' )
					{!! html_entity_decode($item->image) !!}
					@endif
					@if($item->active == 1)
						<span class="badge bg-green !w-[9px] !h-[9px]"></span>
					@else
						<span class="badge bg-red !w-[9px] !h-[9px]"></span>
					@endif
				</span>
                {{$item->title}}
				<small class="ml-auto text-muted opacity-75">{{__('Template')}}</small>
            </a>
        @elseif($item->type == 'code')
            <a href="{{route('dashboard.user.openai.generator.workbook', $item->slug)}}" class="d-flex align-items-center text-heading !no-underline">
				<span class="avatar w-[43px] h-[43px] [&_svg]:w-[20px] [&_svg]:h-[20px] relative !me-2" style="background: {{$item->color}}">
					@if ( $item->image !== 'none' )
					{!! html_entity_decode($item->image) !!}
					@endif
					@if($item->active == 1)
						<span class="badge bg-green !w-[9px] !h-[9px]"></span>
					@else
						<span class="badge bg-red !w-[9px] !h-[9px]"></span>
					@endif
				</span>
                {{$item->title}}
				<small class="ml-auto text-muted opacity-75">{{__('Template')}}</small>
            </a>
        @endif
    </li>
    @endforeach
</ul>
@endif
@if(count($ai_chat_search)>0)
    <ul class="m-0 p-0 list-none font-medium">
        @foreach($ai_chat_search as $item)
            <li class="p-2 px-3 border-solid border-b border-t-0 border-r-0 border-l-0 border-[--tblr-border-color] last:border-b-0 transition-colors hover:bg-slate-50  dark:hover:!bg-[rgba(255,255,255,0.05)]">
                <a href="{{LaravelLocalization::localizeUrl(route('dashboard.user.openai.chat.chat', $item->slug))}}" class="d-flex align-items-center text-heading !no-underline">
                    <div class="avatar w-[43px] h-[43px] [&_svg]:w-[20px] [&_svg]:h-[20px] relative !me-2" style="background: {{$item->color}}">
						@if ( $item->image !== 'none' )
							<img src="/{{ $item->image }}" width="100%" class="rounded">
                        @else
                            {{$item->short_name}}
                        @endif
                    </div>
                    {{$item->name}}
                    <small class="ml-auto text-muted opacity-75">{{__('Plato Agent/')}} {{$item->name}} GPT </small>
                </a>
            </li>
        @endforeach
    </ul>
@endif
@if(count($workbook_search)>0)
    <hr class="border-[2px]">
    <h3 class="m-0 py-[0.75rem] px-3 border-solid border-b border-t-0 border-r-0 border-l-0 border-[--tblr-border-color] text-[1rem] font-medium">{{__('Workbooks')}}</h3>
    <ul class="m-0 p-0 list-none">
        @foreach($workbook_search as $item)
        <li class="p-2 px-3 border-solid border-b border-t-0 border-r-0 border-l-0 border-[--tblr-border-color] last:border-b-0 transition-colors hover:bg-slate-50  dark:hover:!bg-[rgba(255,255,255,0.05)]">
			<a href="{{route('dashboard.user.openai.generator.workbook', $item->slug)}}" class="d-flex align-items-center text-heading !no-underline">
				<span class="avatar w-[43px] h-[43px] [&_svg]:w-[20px] [&_svg]:h-[20px] relative !me-2" style="background: {{$item->color}}">
					@if ( $item->image !== 'none' )
					{!! html_entity_decode($item->image) !!}
					@endif
				</span>
				{{$item->title}}
				<small class="ml-auto text-muted opacity-75">{{__('Workbook')}}</small>
			</a>
		</li>
        @endforeach
    </ul>
@endif
@if(count($verical_search)>0)
    <hr class="border-[2px]">
    <h3 class="m-0 py-[0.75rem] px-3 border-solid border-b border-t-0 border-r-0 border-l-0 border-[--tblr-border-color] text-[1rem] font-medium">{{__('Verticals')}}</h3>
    <ul class="m-0 p-0 list-none">
        @foreach($verical_search as $item)
        <li class="p-2 px-3 border-solid border-b border-t-0 border-r-0 border-l-0 border-[--tblr-border-color] last:border-b-0 transition-colors hover:bg-slate-50  dark:hover:!bg-[rgba(255,255,255,0.05)]">
			<a href="{{ route('dashboard.user.vertical.name', $item->slug) }}" class="d-flex align-items-center text-heading !no-underline">
				<span class="avatar w-[43px] h-[43px] [&_svg]:w-[20px] [&_svg]:h-[20px] relative !me-2">
					@if ( $item->icon !== 'none' )
						<img src="/{{ $item->icon }}"  width="100%" class="rounded">
					@endif
				</span>
				{{ $item->name }}
				<small class="ml-auto text-muted opacity-75">{{ __('Plato AI /') }} {{ $item->name }} Intelligence</small>
			</a>
		</li>
        @endforeach
    </ul>
@endif

@if(count($blog_search)>0)
    <hr class="border-[2px]">
    <h3 class="m-0 py-[0.75rem] px-3 border-solid border-b border-t-0 border-r-0 border-l-0 border-[--tblr-border-color] text-[1rem] font-medium">{{__('Blogs')}}</h3>
    <ul class="m-0 p-0 list-none">
        @foreach($blog_search as $item)
        <li class="p-2 px-3 border-solid border-b border-t-0 border-r-0 border-l-0 border-[--tblr-border-color] last:border-b-0 transition-colors hover:bg-slate-50  dark:hover:!bg-[rgba(255,255,255,0.05)]">
			<a href="{{ url('/blog', $item->slug) }}" class="d-flex align-items-center text-heading !no-underline">
				<span class="avatar w-[43px] h-[43px] [&_svg]:w-[20px] [&_svg]:h-[20px] relative !me-2">B</span>
				{{ $item->title }}
				<small class="ml-auto text-muted opacity-75">{{__('Blog')}}</small>
			</a>
		</li>
        @endforeach
    </ul>
@endif

@if(count($publication_search)>0)
    <hr class="border-[2px]">
    <h3 class="m-0 py-[0.75rem] px-3 border-solid border-b border-t-0 border-r-0 border-l-0 border-[--tblr-border-color] text-[1rem] font-medium">{{__('Publications')}}</h3>
    <ul class="m-0 p-0 list-none">
        @foreach($publication_search as $item)
        <li class="p-2 px-3 border-solid border-b border-t-0 border-r-0 border-l-0 border-[--tblr-border-color] last:border-b-0 transition-colors hover:bg-slate-50  dark:hover:!bg-[rgba(255,255,255,0.05)]">
			<a href="{{ route('dashboard.publication.externalSite', ['slug' => $item->slug]) }}#{{$item->slug}}" class="d-flex align-items-center text-heading !no-underline">
				<span class="avatar w-[43px] h-[43px] [&_svg]:w-[20px] [&_svg]:h-[20px] relative !me-2">B</span>
				{{ $item->title }}
				<small class="ml-auto text-muted opacity-75">{{__('Publications')}}</small>
			</a>
		</li>
        @endforeach
    </ul>
@endif

@if(count($defix_search)>0)
    <hr class="border-[2px]">
    <h3 class="m-0 py-[0.75rem] px-3 border-solid border-b border-t-0 border-r-0 border-l-0 border-[--tblr-border-color] text-[1rem] font-medium">{{__('Defix Gateway')}}</h3>
    <ul class="m-0 p-0 list-none">
        @foreach($defix_search as $item)
        <li class="p-2 px-3 border-solid border-b border-t-0 border-r-0 border-l-0 border-[--tblr-border-color] last:border-b-0 transition-colors hover:bg-slate-50  dark:hover:!bg-[rgba(255,255,255,0.05)]">
			<a href="{{ route('dashboard.defix.externalSite', [$item->parent->slug,$item->slug]) }}#{{$item->slug}}" class="d-flex align-items-center text-heading !no-underline">
				<span class="avatar w-[43px] h-[43px] [&_svg]:w-[20px] [&_svg]:h-[20px] relative !me-2">B</span>
				{{ $item->title }}
				<small class="ml-auto text-muted opacity-75">{{__('Defix Gateway')}}</small>
			</a>
		</li>
        @endforeach
    </ul>
@endif

@if(isset($result) and $result=='null')
	<div class="p-6 font-medium text-center text-heading">
		<h3 class="mb-2">{{__('No results.')}}</h3>
		<p class="opacity-70">{{__('Please try with another word.')}}</p>
	</div>
@endif
