<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Payment Failed</title>
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 text-center">
                <div class="display-6 mb-2">❌ Payment Failed</div>
                <div class="text-muted">Payment was not completed. Please try again.</div>

                <hr class="my-4">

                <div class="small text-muted">
                    Status: {{ request('status') }}
                    <br>
                    Subscription ID: {{ request('subscription_id') ?? request()->route('subscription') }}
                    <br>
                    Reference ID: {{ request('reference_id') ?? request()->route('reference_id') }}
                </div>

                <a href="/" class="btn btn-dark mt-3">Back to Home</a>
            </div>
        </div>
    </div>
</body>

</html>
