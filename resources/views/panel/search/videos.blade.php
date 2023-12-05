<div class="container mt-5">
    <h1 class="mb-4">Video Results</h1>

    <!-- Video Results -->
    @if(isset($apiResponse['results']) && count($apiResponse['results']) > 0)
        @foreach($apiResponse['results'] as $result)
            <div class="row mb-4">
                <!-- Thumbnail on the left -->
                <div class="col-md-3">
                    <a href="{{ $result['url'] }}" target="_blank">
                        <img alt="{{ $result['title'] }}" src="{{ $result['thumbnail']['original'] }}" class="img-fluid">
                    </a>
                </div>

                <!-- Other information on the right -->
                <div class="col-md-9">
                    <a href="{{ $result['url'] }}" target="_blank" rel="noopener" class="text-decoration-none">
                        @if(isset($result['meta_url']['favicon']))
                            <img alt="🌐" class="favicon" src="{{ $result['meta_url']['favicon'] }}" loading="lazy">
                        @endif
                        <span class="text-muted small">{{ $result['video']['publisher'] ?? '' }}</span>
                        @if(isset($result['video']['creator']))
                         <span class="text-muted small"> <svg xmlns="http://www.w3.org/2000/svg"  fill="none" class="icon" viewBox="0 0 14 16"><path fill="currentColor" fill-rule="evenodd" d="M10.21 7.623a.533.533 0 0 1 0 .754l-3.666 3.667a.533.533 0 1 1-.754-.754L9.08 8 5.79 4.71a.533.533 0 0 1 .754-.754l3.666 3.667Z" clip-rule="evenodd"></path></svg>                        @endif
                        
                         </span> <span class="text-muted small">{{ $result['video']['creator'] ?? '' }}</span>

                        <p>{{ $result['title'] }}</p>
                    </a>
                    @if(isset($result['description']))
                        <p>{{ $result['description'] }}</p>
                    @endif
                    <p>
                        Age: {{ $result['age'] }}
                        @if(isset($result['video']['views']))
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="12" fill="none" viewBox="0 0 13 12">
                                <path fill="currentColor" fill-rule="evenodd" d="M6.5 10.5c-3.897 0-6-3.194-6-4.5s2.103-4.5 6-4.5c3.898 0 6 3.194 6 4.5s-2.102 4.5-6 4.5Zm0-8c-3.392 0-5 2.808-5 3.5 0 .692 1.608 3.5 5 3.5s5-2.808 5-3.5c0-.692-1.608-3.5-5-3.5Zm0 6A2.503 2.503 0 0 1 4 6c0-1.378 1.122-2.5 2.5-2.5S9 4.622 9 6 7.878 8.5 6.5 8.5Zm0-4C5.673 4.5 5 5.173 5 6s.673 1.5 1.5 1.5S8 6.827 8 6s-.673-1.5-1.5-1.5Z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $result['video']['views'] > 1000 ? number_format($result['video']['views'] / 1000, 1) . 'K' : $result['video']['views'] }}
                        @endif
                    </p>
                </div>
            </div>
        @endforeach
    @else
        <p>No results found.</p>
    @endif
</div>
