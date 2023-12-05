<div class="container mt-5">
    <h1 class="mb-4">API Results</h1>

    <div class="row">
        <!-- API Results (3/4 of the screen) -->
        <div class="col-md-9" style="column-count: 3; column-gap: 20px;">
            @if(isset($apiResponse['results']) && count($apiResponse['results']) > 0)
                <div class="masonry">
                    @foreach($apiResponse['results'] as $result)
                        <div class="masonry-item mb-4" style="break-inside: avoid-column;">
                            <a href="{{ $result['url'] }}" target="_blank" class="card-link">
                                <div class="card">
                                    <img src="{{ $result['thumbnail']['src'] }}" class="card-img-top" alt="{{ $result['title'] }}">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ substr($result['title'], 0, 40) }}</h5>
                                        <p class="card-text">Source: {{ $result['source'] }}</p>
                                        <!-- You can include additional properties here -->
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p>No results found.</p>
            @endif
        </div>

        <!-- Additional Info (1/4 of the screen) -->
        <div class="col-md-3">
            <div class="card">
                @if(count($apiResponse['results']) > 0)
                    <!-- Show details of the first image in the right section -->
                    @php $firstResult = $apiResponse['results'][0]; @endphp
                    <img src="{{ $firstResult['thumbnail']['src'] }}" class="card-img-top" alt="{{ $firstResult['title'] }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $firstResult['title'] }}</h5>
                        <p class="card-text">Source: {{ $firstResult['source'] }}</p>
                        <!-- You can include additional details here -->
                    </div>
                @else
                    <p>No results found.</p>
                @endif
            </div>
        </div>
    </div>
</div>
