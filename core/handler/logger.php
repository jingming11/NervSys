<?php

/**
 * Logger Handler
 *
 * Copyright 2016-2018 秋水之冰 <27206617@qq.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace core\handler;

class logger
{
    //Log level
    public static $log_level = 0;

    //Log file path
    public static $file_path = ROOT . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR;

    /**
     * Log
     *
     * @param string $level
     * @param string $message
     * @param array  $context
     */
    public static function log(string $level, string $message, array $context = []): void
    {
        self::$level($message, $context);
    }

    /**
     * Log emergency
     *
     * @param string $message
     * @param array  $context
     */
    private static function emergency(string $message, array $context = []): void
    {
        array_unshift($context, strtoupper(__FUNCTION__), $message);
        self::save(__FUNCTION__, $context);

        unset($message, $context);
    }

    /**
     * Log alert
     *
     * @param string $message
     * @param array  $context
     */
    private static function alert(string $message, array $context = []): void
    {
        array_unshift($context, strtoupper(__FUNCTION__), $message);
        self::save(__FUNCTION__, $context);

        unset($message, $context);
    }

    /**
     * Log critical
     *
     * @param string $message
     * @param array  $context
     */
    private static function critical(string $message, array $context = []): void
    {
        array_unshift($context, strtoupper(__FUNCTION__), $message);
        self::save(__FUNCTION__, $context);

        unset($message, $context);
    }

    /**
     * Log error
     *
     * @param string $message
     * @param array  $context
     */
    private static function error(string $message, array $context = []): void
    {
        array_unshift($context, strtoupper(__FUNCTION__), $message);
        self::save(__FUNCTION__, $context);

        unset($message, $context);
    }

    /**
     * Log warning
     *
     * @param string $message
     * @param array  $context
     */
    private static function warning(string $message, array $context = []): void
    {
        array_unshift($context, strtoupper(__FUNCTION__), $message);
        self::save(__FUNCTION__, $context);

        unset($message, $context);
    }

    /**
     * Log notice
     *
     * @param string $message
     * @param array  $context
     */
    private static function notice(string $message, array $context = []): void
    {
        array_unshift($context, strtoupper(__FUNCTION__), $message);
        self::save(__FUNCTION__, $context);

        unset($message, $context);
    }

    /**
     * Log info
     *
     * @param string $message
     * @param array  $context
     */
    private static function info(string $message, array $context = []): void
    {
        array_unshift($context, strtoupper(__FUNCTION__), $message);
        self::save(__FUNCTION__, $context);

        unset($message, $context);
    }

    /**
     * Log debug
     *
     * @param string $message
     * @param array  $context
     */
    private static function debug(string $message, array $context = []): void
    {
        array_unshift($context, strtoupper(__FUNCTION__), $message);
        self::save(__FUNCTION__, $context);

        unset($message, $context);
    }

    /**
     * Save logs
     *
     * @param string $name
     * @param array  $logs
     */
    private static function save(string $name, array $logs): void
    {
        //Check log path
        if (false === realpath(self::$file_path)) {
            $mkdir = mkdir(self::$file_path, 0664, true);

            if (!$mkdir) {
                return;
            }

            unset($mkdir);
        }

        //Add datetime
        array_unshift($logs, date('Y-m-d H:i:s'));

        //Generate log file
        $file = self::$file_path . $name . '-' . date('Ymd') . '.log';

        foreach ($logs as $value) {
            file_put_contents($file, $value . PHP_EOL, FILE_APPEND);
        }

        file_put_contents($file, PHP_EOL, FILE_APPEND);

        unset($name, $logs, $file, $value);
    }
}