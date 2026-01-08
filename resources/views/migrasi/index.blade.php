<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Database Migration Generator</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .select2-container .select2-selection--single {
            height: 42px;
            border-radius: 0.5rem;
            border-color: #d1d5db;
        }

        .select2-selection__rendered {
            line-height: 42px !important;
        }
    </style>
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-4xl px-6">
        <div class="bg-white rounded-xl shadow-lg p-6">

            <!-- Header -->
            <div class="flex items-center gap-3 mb-6">
                <!-- Database Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6c0 1.657 3.582 3 8 3s8-1.343 8-3M4 6v6c0 1.657 3.582 3 8 3s8-1.343 8-3V6M4 12v6c0 1.657 3.582 3 8 3s8-1.343 8-3v-6" />
                </svg>

                <h1 class="text-2xl font-semibold text-slate-800">
                    Database Migration Generator
                </h1>
            </div>

            <!-- Database Select -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-sm text-slate-600 mb-1 block">Database</label>
                    <select id="dbSelect" class="w-full">
                        <option value="">Pilih Database</option>
                        @foreach ($databases as $db)
                            <option value="{{ $db->Database }}">{{ $db->Database }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm text-slate-600 mb-1 block">Tabel</label>
                    <select id="tableSelect" class="w-full">
                        <option value="">Pilih Tabel</option>
                    </select>
                </div>
            </div>

            <!-- Result -->
            <div class="mt-6">
                <label class="text-sm text-slate-600 mb-2 block">Hasil Migration</label>
                <pre id="result" class="bg-slate-900 text-green-400 rounded-lg p-4 text-sm overflow-x-auto min-h-[200px]"></pre>
            </div>

            <!-- Actions -->
            <div class="flex flex-wrap gap-3 mt-6">
                <button id="copyBtn"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition">
                    <!-- Copy Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 16h8M8 12h8m-6 8h6a2 2 0 002-2V8a2 2 0 00-2-2h-6a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Copy Script
                </button>

                <a id="generateAllBtn" href="#"
                    class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-lg transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Generate All
                </a>
            </div>

        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $('#dbSelect, #tableSelect').select2();

        $('#dbSelect').change(function() {
            let database = $(this).val();
            $('#tableSelect').html('<option>Loading...</option>');

            $.post('/migrasi/tables', {
                _token: "{{ csrf_token() }}",
                database: database
            }, function(res) {
                let html = '<option value="">Pilih Tabel</option>';
                res.forEach(t => html += `<option value="${t}">${t}</option>`);
                $('#tableSelect').html(html);
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

        $('#generateAllBtn').click(function(e) {
            e.preventDefault();

            let database = $('#dbSelect').val();

            if (!database) {
                alert('Pilih database terlebih dahulu!');
                return;
            }

            let url = `/migrasi/all/${database}`;

            window.open(url, '_blank');
        });
    </script>

</body>

</html>
