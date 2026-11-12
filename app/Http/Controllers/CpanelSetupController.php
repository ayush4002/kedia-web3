<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CpanelSetupController extends Controller
{
    private function verifyKey(Request $request): bool
    {
        $expectedKey = env('CPANEL_SETUP_KEY', 'kedia2026_cpanel_setup');
        return $request->query('key') === $expectedKey || $request->input('key') === $expectedKey;
    }

    public function index(Request $request)
    {
        if (!$this->verifyKey($request)) {
            return response('Unauthorized access. Provide the valid ?key= parameter configured in .env', 403)
                ->header('Content-Type', 'text/plain');
        }

        // Diagnostics
        $phpVersion = phpversion();
        $requiredExtensions = ['pdo_mysql', 'openssl', 'mbstring', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath', 'fileinfo', 'curl'];
        $extensionStatus = [];
        foreach ($requiredExtensions as $ext) {
            $extensionStatus[$ext] = extension_loaded($ext);
        }

        $writablePaths = [
            'storage' => is_writable(storage_path()),
            'storage/framework' => is_writable(storage_path('framework')),
            'storage/framework/views' => is_writable(storage_path('framework/views')),
            'storage/framework/sessions' => is_writable(storage_path('framework/sessions')),
            'storage/framework/cache' => is_writable(storage_path('framework/cache')),
            'storage/logs' => is_writable(storage_path('logs')),
            'bootstrap/cache' => is_writable(base_path('bootstrap/cache')),
        ];

        $dbConnected = false;
        $dbError = null;
        $dbTables = 0;
        try {
            DB::connection()->getPdo();
            $dbConnected = true;
            $dbTables = count(DB::select('SHOW TABLES'));
        } catch (\Exception $e) {
            $dbError = $e->getMessage();
        }

        return view('pages.cpanel-setup', compact('phpVersion', 'extensionStatus', 'writablePaths', 'dbConnected', 'dbError', 'dbTables'));
    }

    public function runAction(Request $request)
    {
        if (!$this->verifyKey($request)) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        $action = $request->input('action');
        $output = '';

        try {
            switch ($action) {
                case 'migrate':
                    Artisan::call('migrate', ['--force' => true]);
                    $output = Artisan::output() ?: 'Database migrations executed successfully.';
                    break;

                case 'cache':
                    Artisan::call('config:cache');
                    Artisan::call('route:cache');
                    Artisan::call('view:cache');
                    $output = "Application configurations, routes, and views compiled and cached for production.";
                    break;

                case 'clear':
                    Artisan::call('optimize:clear');
                    $output = Artisan::output() ?: 'All application caches cleared.';
                    break;

                case 'storage_link':
                    if (function_exists('symlink')) {
                        try {
                            Artisan::call('storage:link');
                            $output = Artisan::output() ?: 'Storage symlink created.';
                        } catch (\Exception $e) {
                            $output = 'Symlink creation failed: ' . $e->getMessage();
                        }
                    } else {
                        // Fallback mirror directory for cPanel hosts with symlink() disabled
                        $target = storage_path('app/public');
                        $link = public_path('storage');
                        if (!file_exists($link)) {
                            File::copyDirectory($target, $link);
                            $output = 'Symlink function is disabled by host; storage directory successfully copied to public/storage.';
                        } else {
                            $output = 'public/storage already exists.';
                        }
                    }
                    break;

                default:
                    $output = 'Unknown action specified.';
            }

            return redirect()->route('cpanel.setup', ['key' => $request->input('key')])
                ->with('success', $output);
        } catch (\Exception $e) {
            return redirect()->route('cpanel.setup', ['key' => $request->input('key')])
                ->with('error', 'Action failed: ' . $e->getMessage());
        }
    }
}

