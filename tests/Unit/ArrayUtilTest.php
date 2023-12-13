<?php

namespace Tests\Unit;

use App\Util\ArrayUtil;
use PHPUnit\Framework\TestCase;

class ArrayUtilTest extends TestCase
{
    // 메소드 이름 앞에 'test_'가 붙어야 테스트가 진행됨.
    public function test_existKeys_emptyKeysOrArray_returnFalse(): void
    {
        $actual = ArrayUtil::existKeys([], []);
        $this->assertFalse($actual, 'if key or array empty, return false');
    }

    public function test_existKeys_keyInArray_returnTrue(): void
    {
        $actual = ArrayUtil::existKeys(['a'], ['a' => 1, 'b' => 2]);
        $this->assertTrue($actual, 'if key exists, return true');
    }

    public function test_existKeys_keyNotInArray_returnFalse(): void
    {
        $actual = ArrayUtil::existKeys(['c'], ['a' => 1, 'b' => 2]);
        $this->assertFalse($actual, 'if key exists, return true');
    }

    public function test_existKeys_KeyInArrayButNullValue_returnTrue(): void
    {
        $actual = ArrayUtil::existKeys(['a'], ['a' => null]);
        $this->assertTrue($actual, 'if key exists, return true');
    }

    public function test_existKeysStrictly_emptyKeysOrArray_returnFalse(): void
    {
        $actual = ArrayUtil::existKeysStrictly([], []);
        $this->assertFalse($actual, 'if key or array empty, return false');
    }

    public function test_existKeysStrictly_keyInArray_returnTrue(): void
    {
        $actual = ArrayUtil::existKeysStrictly(['a'], ['a' => 1, 'b' => 2]);
        $this->assertTrue($actual, 'if key exists, return true');
    }

    public function test_existKeysStrictly_keyNotInArray_returnFalse(): void
    {
        $actual = ArrayUtil::existKeysStrictly(['c'], ['a' => 1, 'b' => 2]);
        $this->assertFalse($actual, 'if key exists, return true');
    }

    public function test_existKeysStrictly_KeyInArrayButNullValue_returnFalse(): void
    {
        $actual = ArrayUtil::existKeysStrictly(['a'], ['a' => null]);
        $this->assertFalse($actual, 'if key exists, return true');
    }
}
