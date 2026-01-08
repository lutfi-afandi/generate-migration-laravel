<!doctype html>
<html>

<head>
    <title>Migration Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        pre {
            background: #111 !important;
            color: #00ff90 !important;
            padding: 15px !important;
            border-radius: 8px !important;
            font-size: 14px !important;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container py-5">
        <h3 class="mb-4">Database Migration Generator</h3>

        <div class="form-group">
            <label for="">-Pilih Database-</label>
            <select id="dbSelect" class="form-control mb-3">
                <option value="">-- Pilih Database --</option>
                @foreach ($databases as $db)
                    <option value="{{ $db->Database }}">{{ $db->Database }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mt-2">
            <label for="">-Pilih Tabel-</label>
            <select id="tableSelect" class="form-control mb-3">
                <option value="">-- Pilih Tabel --</option>
            </select>
        </div>

        <pre id="result" class="form-control mb-3 mt-3 migration-code"></pre>

        <button id="copyBtn" class="btn btn-primary">Copy Script</button>
        <a href="/migrasi/all" class="btn btn-danger ">All</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- <script>
        $('#tableSelect').select2();

        $('#tableSelect').change(function() {
            let table = $(this).val();

            if (!table) return;

            $.post("{{ route('migrasi.generate') }}", {
                _token: "{{ csrf_token() }}",
                table: table
            }, function(res) {
                $('#result').html(res.script);
            });
        });

        $('#copyBtn').click(function() {
            let text = $('#result').text();

            if (!text) {
                alert('Tidak ada script untuk di-copy!');
                return;
            }

            navigator.clipboard.writeText(text).then(function() {
                alert('Migration copied!');
            }).catch(function() {
                alert('Gagal copy!');
            });
        });
    </script> --}}

    <script>
        $('#dbSelect, #tableSelect').select2();

        $('#dbSelect').change(function() {
            let database = $(this).val();

            $('#tableSelect').html('<option value="">Loading...</option>');

            $.post('/migrasi/tables', {
                _token: "{{ csrf_token() }}",
                database: database
            }, function(res) {
                let opt = '<option value="">-- Pilih Tabel --</option>';
                res.forEach(t => opt += `<option value="${t}">${t}</option>`);
                $('#tableSelect').html(opt);
            });
        });

        $('#tableSelect').change(function() {
            let table = $(this).val();
            let database = $('#dbSelect').val();

            if (!table || !database) return;

            $.post("{{ route('migrasi.generate') }}", {
                _token: "{{ csrf_token() }}",
                database: database,
                table: table
            }, function(res) {
                $('#result').text(res.script);
            });
        });

        $('#copyBtn').click(function() {
            navigator.clipboard.writeText($('#result').text());
            alert('Migration copied!');
        });
    </script>


</body>

</html>
