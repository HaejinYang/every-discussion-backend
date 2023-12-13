<?php

namespace App\Util;

class ArrayUtil
{
    /**
     * 배열안에 키가 있는지 확인. 배열안의 키가 있지만 그 값이 null인경우에도 true 반환
     * @param array $keys 확인하고 싶은 키 배열
     * @param array $array 키가 있는지 확인할 배열
     * @return bool
     */
    public static function existKeys(array $keys, array $array): bool
    {
        if (empty($keys) || empty($array)) {
            return false;
        }

        foreach ($keys as $key) {
            if (!array_key_exists($key, $array)) {
                return false;
            }
        }

        return true;
    }

    /**
     * 배열안에 키가 있는지 확인. 배열안의 키가 있지만 그 값이 null이라면 false 반환
     * @param array $keys 확인하고 싶은 키 배열
     * @param array $array 키가 있는지 확인할 배열
     * @return bool
     */
    public static function existKeysStrictly(array $keys, array $array): bool
    {
        if (empty($keys) || empty($array)) {
            return false;
        }

        foreach ($keys as $key) {
            if (!isset($array[$key])) {
                return false;
            }
        }

        return true;
    }
}
