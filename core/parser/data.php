<?php

/**
 * Data Parser
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

namespace core\parser;

class data
{
    //Base64 data header
    const BASE64 = 'data:text/argv;base64,';

    /**
     * Encode data in base64 with data header
     *
     * @param string $value
     *
     * @return string
     */
    public static function encode(string $value): string
    {
        return self::BASE64 . base64_encode($value);
    }

    /**
     * Decode data in base64 with data header
     *
     * @param string $value
     *
     * @return string
     */
    public static function decode(string $value): string
    {
        if (0 !== strpos($value, self::BASE64)) {
            return $value;
        }

        $value = substr($value, strlen(self::BASE64));
        $value = base64_decode($value, true);

        return $value;
    }

    /**
     * Build argument
     *
     * @param object $reflect
     * @param array  $input
     *
     * @return array
     * @throws \Exception
     */
    public static function build_argv(object $reflect, array $input): array
    {
        //Get method params
        if (empty($params = $reflect->getParameters())) {
            return [];
        }

        $data = $diff = [];

        //Process params
        foreach ($params as $param) {
            //Get param name
            $name = $param->getName();

            //Check param data
            if (isset($input[$name])) {
                switch ($param->getType()) {
                    case 'int':
                        is_numeric($input[$name]) ? $data[] = (int)$input[$name] : $diff[] = $name;
                        break;
                    case 'bool':
                        is_bool($input[$name]) ? $data[] = (bool)$input[$name] : $diff[] = $name;
                        break;
                    case 'float':
                        is_numeric($input[$name]) ? $data[] = (float)$input[$name] : $diff[] = $name;
                        break;
                    case 'array':
                        is_array($input[$name]) || is_object($input[$name]) ? $data[] = (array)$input[$name] : $diff[] = $name;
                        break;
                    case 'string':
                        is_string($input[$name]) || is_numeric($input[$name]) ? $data[] = (string)$input[$name] : $diff[] = $name;
                        break;
                    case 'object':
                        is_object($input[$name]) || is_array($input[$name]) ? $data[] = (object)$input[$name] : $diff[] = $name;
                        break;
                    default:
                        $data[] = $input[$name];
                        break;
                }
            } else {
                $param->isDefaultValueAvailable() ? $data[] = $param->getDefaultValue() : $diff[] = $name;
            }
        }

        //Report argument missing
        if (!empty($diff)) {
            throw new \Exception('Argument mismatch [' . (implode(', ', $diff)) . ']');
        }

        unset($reflect, $input, $params, $diff, $param, $name);
        return $data;
    }
}