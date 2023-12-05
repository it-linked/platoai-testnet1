<div class="container mt-5">
    <h1 class="mb-4">Web Search Results</h1>

    <div class="row">
        <!-- Web Search Results (3/4 of the screen) -->
        <div class="col-md-9">
            @if(count($apiResponse['results']) > 0)
                @foreach($apiResponse['results'] as $result)
                    <div class="card mb-4">
                        <a href="{{ $result['url'] }}" target="_blank" class="card-link">
                            <div class="card-body">
                                <div class="d-flex flex-row">
                                    <img src="{{ $result['meta_url']['favicon'] }}" alt="Logo">
                                    <h2 class="px-4">{{ $result['title'] }}</h2>
                                </div>
                                <p>{!! $result['description'] !!}</p>
                                <!-- You can include additional properties here -->
                            </div>
                        </a>
                    </div>
                @endforeach
            @else
                <p>No results found.</p>
            @endif
        </div>

        <!-- Additional Info (1/4 of the screen) -->
        <div class="col-md-3">
            @if(count($apiResponse['results']) > 0)
                <!-- Show details of the first web search result in the right section -->
                @php $firstResult = $apiResponse['results'][0]; @endphp
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $firstResult['title'] }}</h5>
                        <p>{!! $firstResult['description'] !!}</p>
                        <!-- You can include additional details here -->
                    </div>
                </div>
            @else
                <p>No results found.</p>
            @endif
        </div>
    </div>
</div>
