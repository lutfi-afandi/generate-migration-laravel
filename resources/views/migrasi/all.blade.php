<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>All Migration Scripts</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 min-h-screen">

    <div class="max-w-6xl mx-auto px-6 py-8">

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <!-- Stack Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-600 flex-shrink-0" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4m16 0H4" />
                </svg>

                <!-- Title & Subtitle -->
                <div class="flex flex-col">
                    <h1 class="text-2xl font-semibold text-slate-800 leading-tight">
                        All Migration Generator
                    </h1>
                    <p class="text-sm text-slate-500">
                        Database: <span class="font-medium text-slate-700">{{ $database }}</span>
                    </p>
                </div>
            </div>


            <a href="/migrasi"
                class="inline-flex items-center gap-2 text-sm px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-200 transition">
                <!-- Arrow Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </div>

        <!-- List -->
        @foreach ($output as $table => $script)
            <div class="bg-white rounded-xl shadow mb-6 overflow-hidden">

                <!-- Card Header -->
                <div class="flex items-center justify-between px-5 py-3 border-b">
                    <div class="flex items-center gap-2 text-slate-700 font-medium">
                        <!-- Table Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M3 6h18M3 14h18M3 18h18" />
                        </svg>
                        {{ $table }}
                    </div>

                    <button
                        class="copy-btn inline-flex items-center gap-1.5 text-sm px-3 py-1.5 rounded-md border border-indigo-500 text-indigo-600 hover:bg-indigo-50 transition">
                        <!-- Copy Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 16h8M8 12h8m-6 8h6a2 2 0 002-2V8a2 2 0 00-2-2h-6a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Copy
                    </button>
                </div>

                <!-- Code -->
                <pre class="migration-code bg-slate-900 text-green-400 p-5 text-sm overflow-x-auto">
{{ $script }}
            </pre>
            </div>
        @endforeach

    </div>

    <script>
        document.querySelectorAll('.copy-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const code = this.closest('.rounded-xl').querySelector('.migration-code').innerText;

                navigator.clipboard.writeText(code).then(() => {
                    this.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                    Copied
                `;
                    this.classList.remove('border-indigo-500', 'text-indigo-600');
                    this.classList.add('border-emerald-500', 'text-emerald-600');

                    setTimeout(() => {
                        this.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 16h8M8 12h8m-6 8h6a2 2 0 002-2V8a2 2 0 00-2-2h-6a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Copy
                    `;
                        this.classList.remove('border-emerald-500', 'text-emerald-600');
                        this.classList.add('border-indigo-500', 'text-indigo-600');
                    }, 1500);
                });
            });
        });
    </script>

</body>

</html>
