        <div class="border p-2 rounded-2 mb-2">
            <h3 class="card-title">{{ $product['name'] }}</h3>
            <p class="card-text">
                <strong>Product Id:</strong> {{ $product['id'] }}
            </p>
            <p class="card-text">
                <strong>Description:</strong> {{ $product['description'] }}
            </p>
            <p class="card-text">
                <strong>Framework:</strong> {{ $product['framework'] }}
            </p>
            <p class="card-text">
                <strong>Version:</strong> {{ $product['version'] }}
            </p>
            <p class="card-text">
                <strong>Author:</strong> {{ $product['author'] }}
            </p>
            <p class="card-text">
                <strong>URL:</strong> <a href="{{ $product['url'] }}">{{ $product['url'] }}</a>
            </p>
            <p class="card-text">
                <strong>Email:</strong> {{ $product['email'] }}
            </p>
            @if ($isLicensed)
                <p class="card-text bg-success text-bg-success p-2">
                    <strong>Product activated with license Key:</strong> {{ $licenseKey }} </br>
                    <strong> Activated at: </strong> {{ $licenseInfo['activated_at']??'' }}
                </p>
            @endif
        </div>
