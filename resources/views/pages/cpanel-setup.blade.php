@extends('layouts.app')

@section('title', 'cPanel Production Setup & Diagnostics | Kedia Builders')

@section('content')
<section class="py-12 bg-[#FAF8F5] min-h-screen">
    <div class="max-w-4xl mx-auto px-6 sm:px-10 lg:px-12 space-y-8">
        
        <!-- Header -->
        <div class="bg-white p-8 rounded-3xl border border-[#0E1520]/10 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-mono uppercase tracking-widest text-emerald-600 font-bold">Production Ready</span>
                <h1 class="font-serif text-3xl font-bold text-[#0E1520] mt-1">cPanel Deployment & Diagnostics</h1>
                <p class="text-sm text-[#0E1520]/60 mt-1">System health, environment checks, and one-click artisan utilities for shared hosting.</p>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-mono font-bold bg-[#0E1520] text-[#D4E845]">
                    PHP {{ $phpVersion }}
                </span>
            </div>
        </div>

        <!-- System Checks Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Database Connection Card -->
            <div class="bg-white p-6 rounded-3xl border border-[#0E1520]/10 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="font-serif text-lg font-bold text-[#0E1520]">Database Status</h2>
                    <span class="px-2.5 py-1 rounded-full text-xs font-mono font-bold {{ $dbConnected ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                        {{ $dbConnected ? 'CONNECTED' : 'DISCONNECTED' }}
                    </span>
                </div>
                @if($dbConnected)
                    <div class="text-xs space-y-1 text-[#0E1520]/75">
                        <div>Database connection established successfully via PDO.</div>
                        <div class="font-mono font-bold text-emerald-600">{{ $dbTables }} tables detected in schema.</div>
                    </div>
                @else
                    <div class="p-3 rounded-xl bg-red-50 text-red-700 text-xs font-mono break-all">
                        {{ $dbError }}
                    </div>
                    <p class="text-xs text-[#0E1520]/60">Check DB_HOST (usually 'localhost' on cPanel), DB_DATABASE, DB_USERNAME, and DB_PASSWORD in your .env file.</p>
                @endif
            </div>

            <!-- Directory Permissions Card -->
            <div class="bg-white p-6 rounded-3xl border border-[#0E1520]/10 shadow-sm space-y-4">
                <h2 class="font-serif text-lg font-bold text-[#0E1520]">Storage Writable Checks</h2>
                <div class="grid grid-cols-1 gap-2 text-xs">
                    @foreach($writablePaths as $path => $status)
                        <div class="flex items-center justify-between py-1 border-b border-[#0E1520]/5">
                            <span class="font-mono text-[#0E1520]/80">{{ $path }}</span>
                            <span class="font-mono font-bold {{ $status ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $status ? 'WRITABLE' : 'PERMISSION DENIED' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Required Extensions Card -->
            <div class="bg-white p-6 rounded-3xl border border-[#0E1520]/10 shadow-sm space-y-4 md:col-span-2">
                <h2 class="font-serif text-lg font-bold text-[#0E1520]">Required PHP Extensions</h2>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-xs">
                    @foreach($extensionStatus as $ext => $loaded)
                        <div class="p-3 rounded-2xl border flex items-center justify-between {{ $loaded ? 'bg-emerald-50/50 border-emerald-200 text-emerald-900' : 'bg-red-50 border-red-200 text-red-900' }}">
                            <span class="font-mono font-semibold">{{ $ext }}</span>
                            <span>{{ $loaded ? '✓' : '✗' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- One-Click cPanel Maintenance Actions -->
        <div class="bg-white p-8 rounded-3xl border border-[#0E1520]/10 shadow-sm space-y-6">
            <h2 class="font-serif text-xl font-bold text-[#0E1520]">One-Click Artisan Actions</h2>
            <p class="text-xs text-[#0E1520]/60">Run production maintenance tasks directly from this page without needing SSH terminal access.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Run Migrations -->
                <form action="{{ route('cpanel.action') }}" method="POST">
                    @csrf
                    <input type="hidden" name="key" value="{{ request('key') }}">
                    <input type="hidden" name="action" value="migrate">
                    <button type="submit" class="w-full p-4 rounded-2xl bg-[#0E1520] hover:bg-[#162132] text-white text-xs font-bold transition-all text-left space-y-1 shadow">
                        <div class="text-[#D4E845] font-mono">⚡ DATABASE</div>
                        <div>Run Migrations</div>
                        <div class="text-[10px] text-white/50 font-normal">migrate --force</div>
                    </button>
                </form>

                <!-- Build Production Cache -->
                <form action="{{ route('cpanel.action') }}" method="POST">
                    @csrf
                    <input type="hidden" name="key" value="{{ request('key') }}">
                    <input type="hidden" name="action" value="cache">
                    <button type="submit" class="w-full p-4 rounded-2xl bg-[#0E1520] hover:bg-[#162132] text-white text-xs font-bold transition-all text-left space-y-1 shadow">
                        <div class="text-emerald-400 font-mono">🚀 PERFORMANCE</div>
                        <div>Compile Caches</div>
                        <div class="text-[10px] text-white/50 font-normal">config, route &amp; view</div>
                    </button>
                </form>

                <!-- Clear Caches -->
                <form action="{{ route('cpanel.action') }}" method="POST">
                    @csrf
                    <input type="hidden" name="key" value="{{ request('key') }}">
                    <input type="hidden" name="action" value="clear">
                    <button type="submit" class="w-full p-4 rounded-2xl bg-[#0E1520] hover:bg-[#162132] text-white text-xs font-bold transition-all text-left space-y-1 shadow">
                        <div class="text-amber-400 font-mono">🧹 REFRESH</div>
                        <div>Clear All Caches</div>
                        <div class="text-[10px] text-white/50 font-normal">optimize:clear</div>
                    </button>
                </form>

                <!-- Storage Symlink -->
                <form action="{{ route('cpanel.action') }}" method="POST">
                    @csrf
                    <input type="hidden" name="key" value="{{ request('key') }}">
                    <input type="hidden" name="action" value="storage_link">
                    <button type="submit" class="w-full p-4 rounded-2xl bg-[#0E1520] hover:bg-[#162132] text-white text-xs font-bold transition-all text-left space-y-1 shadow">
                        <div class="text-blue-400 font-mono">🔗 MEDIA</div>
                        <div>Storage Symlink</div>
                        <div class="text-[10px] text-white/50 font-normal">storage:link / mirror</div>
                    </button>
                </form>

            </div>
        </div>

        <!-- Quick Deployment Notes -->
        <div class="bg-[#0E1520] text-white p-6 sm:p-8 rounded-3xl space-y-3">
            <h3 class="font-serif text-lg font-bold text-[#D4E845]">cPanel Quick Deployment Instructions</h3>
            <ol class="list-decimal list-inside text-xs text-white/80 space-y-2 leading-relaxed">
                <li>Create your MySQL database and user in cPanel <strong>MySQL Databases</strong>, then assign all privileges to the user.</li>
                <li>Import <code>database/cpanel_import.sql</code> via cPanel <strong>phpMyAdmin</strong>.</li>
                <li>Copy <code>.env.cpanel.example</code> to <code>.env</code> and update your database credentials and <code>APP_URL</code>.</li>
                <li>Upload the files to your server (or deploy via cPanel Git Version Control).</li>
                <li>Visit <code>https://yourdomain.com/cpanel-setup?key=kedia2026_cpanel_setup</code> to verify diagnostics and compile caches!</li>
            </ol>
        </div>

    </div>
</section>
@endsection

