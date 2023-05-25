<?php
/**
 *   Stake iGaming platform
 *   ----------------------
 *   MaintenanceController.php
 * 
 *   @copyright  Copyright (c) FinancialPlugins, All rights reserved
 *   @author     FinancialPlugins <info@financialplugins.com>
 *   @see        https://financialplugins.com
*/

namespace App\Http\Controllers\Admin;

use App\Helpers\CommandManager;
use App\Helpers\Queries\FailedJobQuery;
use App\Helpers\Queries\JobQuery;
use App\Helpers\ReleaseManager;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Services\ArtisanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MaintenanceController extends Controller
{
    const LOG_FILE_EXTENSION = '.log';

    public function index(CommandManager $commandManager, ReleaseManager $releaseManager)
    {
        $latestVersion = data_get($releaseManager->getInfo(), 'app.version');

        $logStorage = Storage::disk('logs');

        return response()->json([
            'current_version'   => config('app.version'),
            'latest_version'    => $latestVersion,
            'update_available'  => $latestVersion && version_compare(config('app.version'), $latestVersion, '<'),
            'system_info'       => [
                ['title' => __('OS'), 'value' => php_uname()],
                ['title' => __('PHP version'), 'value' => PHP_VERSION],
                ['title' => __('Path to PHP'), 'value' => Utils::getPathToPhp()],
                ['title' => __('Installation folder'), 'value' => base_path()],
            ],
            'log_files' => collect($logStorage->files())
                ->filter(function ($path) {
                    return Str::of($path)->endsWith(self::LOG_FILE_EXTENSION);
                })
                ->map(function ($path) use ($logStorage) {
                    return [
                        'name' => Str::of(basename($path))->replace(self::LOG_FILE_EXTENSION, ''),
                        'size' => round($logStorage->size($path) / 1024 / 1024, 2),
                        'isWritable' => File::isWritable($logStorage->path($path)),
                    ];
                })
                ->values(),
            'maintenance_mode'  => app()->isDownForMaintenance(),
            'commands'          => $commandManager->all(),
            'queues'            => [STAKE_QUEUE_MULTIPLAYER_GAMES, 'default'],
            'cron_job'          => Utils::getCronJobCommand()
        ]);
    }

    
    public function down(Request $request)
    {
        Artisan::call('down');

        return $this->successResponse($this->getSuccessMessage());
    }

    
    public function up()
    {
        Artisan::call('up');

        return $this->successResponse($this->getSuccessMessage());
    }

    
    public function migrate()
    {
        set_time_limit(1800);
        ArtisanService::migrateAndSeed();

        return $this->successResponse($this->getSuccessMessage());
    }

    
    public function cache()
    {
        set_time_limit(1800);
        ArtisanService::clearAllCaches();

        return $this->successResponse($this->getSuccessMessage());
    }

    public function command(Request $request, CommandManager $commandManager)
    {
        $command = $commandManager->get($request->command);

        $request->validate([
            'command' => [
                'required',
                Rule::in([$command['class']])
            ]
        ]);

        set_time_limit(1800);

        
        $arguments = collect($request->arguments);
        $params = $arguments->only(array_column($command['arguments'], 'id'))->all();

        
        Artisan::call($command['signature'], $params);

        
        $output = Artisan::output();

        return $this->successResponse(Str::of($output)->trim()->length() > 0 ? $output : $this->getSuccessMessage());
    }

    public function jobs(JobQuery $query)
    {
        return [
            'count' => $query->getRowsCount(),
            'items' => $query->get()
        ];
    }

    public function failedJobs(FailedJobQuery $query)
    {
        return [
            'count' => $query->getRowsCount(),
            'items' => $query->get()
        ];
    }

    public function clearQueue(Request $request)
    {
        Artisan::call('queue:clear', [
            '--queue' => $request->queue,
            '--force' => TRUE
        ]);

        return $this->successResponse($this->getSuccessMessage());
    }

    public function stopQueueWorker()
    {
        Artisan::call('queue:restart');

        return $this->successResponse($this->getSuccessMessage());
    }

    public function getLogFile(Request $request)
    {
        $storage = Storage::disk('logs');
        $file = $request->file . self::LOG_FILE_EXTENSION;

        return [
            'size' => $storage->exists($file) ? round($storage->size($file) / 1024 / 1024, 2) : 0,
            'content' => $storage->exists($file) ? $storage->get($file) : ''
        ];
    }

    public function deleteLogFile(Request $request)
    {
        $storage = Storage::disk('logs');
        $file = $request->file . self::LOG_FILE_EXTENSION;

        if (!$storage->exists($file)) {
            abort(404);
        }

        return $storage->delete($file)
            ? $this->successResponse(__('Log file successfully deleted.'))
            : $this->errorResponse(__('Log file can not be deleted.'));
    }

    public function downloadLogFile(Request $request)
    {
        $file = $request->file . self::LOG_FILE_EXTENSION;

        if (!Storage::disk('logs')->exists($file)) {
            abort(404);
        }

        return response()->download(storage_path(sprintf('logs/%s', $file)));
    }

    protected function getSuccessMessage()
    {
        return __('Operation performed successfully.');
    }
}
