<?php

namespace Sokeio\Support\LogView;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use SplFileInfo;

class LogViewManager
{
    private static $instance;
    public static function inst()
    {
        return self::$instance ?? (self::$instance = new self());
    }
    public $files = [];
    public $filePath = '';
    public function __construct()
    {
        $directory = storage_path('logs');

        $this->files = collect(File::allFiles($directory))
            ->sortByDesc(function (SplFileInfo $file) {
                return $file->getMTime();
            })->values()->map(function ($item) {
                return [
                    'name' => $item->getFilename(),
                    'path' => $item->getRealPath(),
                    'date' => $item->getMTime()
                ];
            });
    }
    public function readLogs()
    {
        if (empty($this->files)) {
            return [];
        }
        $logs = [];
        if ($this->filePath == '') {
            $this->filePath = $this->files[0]['path'];
        }
        $logPattern = '/^\[(.*?)\] (\S+): (.*)$/';
        $file = fopen($this->filePath, 'r');
        if (!$file) {
            return [];
        }
        $logCurrent = [];
        $stackTrace = [];
        $stackTraceLine = 0;
        while (($line = fgets($file)) !== false) {
            if (preg_match($logPattern, $line, $matches)) {

                $date = $matches[1];
                $level = $matches[2];
                $message = $matches[3];
                $levelType = count(explode('.', $level)) > 1 ? explode('.', $level)[1] : $level;
                try {
                    $date = Carbon::parse($date);

                    if ($date && !empty($logCurrent)) {
                        $logCurrent['stacktrace'] = $stackTrace;
                        $logCurrent['stackTraceLine'] = $stackTraceLine;
                        $logs[] = $logCurrent;
                        $logCurrent = [];
                        $stackTrace = [];
                        $stackTraceLine = 0;
                    }
                    $logCurrent = [
                        'date' => $date,
                        'dateText' => $date->format('y-m-d H:i:s'),
                        'level' => $level,
                        'levelType' => str($levelType)->lower(),
                        'message' => $message,
                        'stacktrace' => $stackTrace,
                        'stackTraceLine' => $stackTraceLine
                    ];
                } catch (\Throwable $e) {
                    $stackTrace[] = $line;
                    $stackTraceLine++;
                }
            } elseif (!empty($logCurrent)) {
                $stackTrace[] = $line;
                $stackTraceLine++;
            }
        }
        fclose($file);
        if (!empty($logCurrent)) {
            $logCurrent['stacktrace'] = $stackTrace;
            $logCurrent['stackTraceLine'] = $stackTraceLine;
            $logs[] = $logCurrent;
            $logCurrent = [];
            $stackTrace = [];
            $stackTraceLine = 0;
        }

        return $logs;
    }
}
