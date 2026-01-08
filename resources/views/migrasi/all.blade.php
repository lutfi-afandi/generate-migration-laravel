<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All Migration Scripts</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        pre {
            background: #111;
            color: #00ff90;
            padding: 15px;
            border-radius: 8px;
            font-size: 14px;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container py-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>All Migration Generator</h3>
            <a href="/migrasi" class="btn btn-outline-primary btn-sm">Back</a>
        </div>

        @foreach ($output as $table => $script)
            <div class="card mb-4 shadow-sm">
                <div class="card-header fw-bold">
                    {{ $table }}
                </div>
                <div class="card-body">
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-2">
                            <button class="btn btn-sm btn-outline-primary copy-btn">Copy</button>
                        </div>

                        <pre class="migration-code">{{ $script }}</pre>
                    </div>

                </div>
            </div>
        @endforeach

    </div>

</body>
<script>
    document.querySelectorAll('.copy-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const code = this.closest('.card-body').querySelector('.migration-code').innerText;

            navigator.clipboard.writeText(code).then(() => {
                this.innerText = 'Copied!';
                this.classList.replace('btn-outline-primary', 'btn-success');

                setTimeout(() => {
                    this.innerText = 'Copy';
                    this.classList.replace('btn-success', 'btn-outline-primary');
                }, 1500);
            });
        });
    });
</script>

</html>
