<?php

namespace Zack\PhpUniversalUsage;

class TypeTesting
{
    // PHP类型测试和验证
    public function demonstrateTypeTesting()
    {
        echo "=== 类型测试演示 ===" . PHP_EOL;

        // 测试基本类型
        $this->testBasicTypes();
        echo PHP_EOL;

        // 测试复合类型
        $this->testCompoundTypes();
        echo PHP_EOL;

        // 测试特殊类型
        $this->testSpecialTypes();
        echo PHP_EOL;

        // 类型转换测试
        $this->testTypeCasting();
        echo PHP_EOL;

        // PHP 8+ 的联合类型和枚举测试
        $this->testModernTypes();
    }

    private function testBasicTypes()
    {
        echo "--- 基本类型测试 ---" . PHP_EOL;

        $variables = [
            'string' => 'Hello World',
            'integer' => 42,
            'float' => 3.14159,
            'boolean' => true,
            'null' => null
        ];

        foreach ($variables as $name => $value) {
            $type = gettype($value);
            echo "变量 '$name' 的值: ";
            var_export($value);
            echo " (类型: $type)" . PHP_EOL;

            // 使用类型检测函数
            switch ($name) {
                case 'string':
                    echo "  is_string(): " . (is_string($value) ? 'true' : 'false') . PHP_EOL;
                    break;
                case 'integer':
                    echo "  is_int(): " . (is_int($value) ? 'true' : 'false') . PHP_EOL;
                    echo "  is_numeric(): " . (is_numeric($value) ? 'true' : 'false') . PHP_EOL;
                    break;
                case 'float':
                    echo "  is_float(): " . (is_float($value) ? 'true' : 'false') . PHP_EOL;
                    echo "  is_numeric(): " . (is_numeric($value) ? 'true' : 'false') . PHP_EOL;
                    break;
                case 'boolean':
                    echo "  is_bool(): " . (is_bool($value) ? 'true' : 'false') . PHP_EOL;
                    break;
                case 'null':
                    echo "  is_null(): " . (is_null($value) ? 'true' : 'false') . PHP_EOL;
                    break;
            }
        }
    }

    private function testCompoundTypes()
    {
        echo "--- 复合类型测试 ---" . PHP_EOL;

        // 数组测试
        $array = ['apple', 'banana', 'orange'];
        echo "数组: ";
        var_export($array);
        echo " (类型: " . gettype($array) . ")" . PHP_EOL;
        echo "  is_array(): " . (is_array($array) ? 'true' : 'false') . PHP_EOL;
        echo "  count(): " . count($array) . PHP_EOL;

        // 对象测试
        $object = new \stdClass();
        $object->name = 'Test Object';
        $object->value = 100;
        echo PHP_EOL . "对象: stdClass" . " (类型: " . gettype($object) . ")" . PHP_EOL;
        echo "  is_object(): " . (is_object($object) ? 'true' : 'false') . PHP_EOL;
        echo "  get_class(): " . get_class($object) . PHP_EOL;

        // 当前类对象测试
        echo PHP_EOL . "当前类对象: TypeTesting" . " (类型: " . gettype($this) . ")" . PHP_EOL;
        echo "  instanceof TypeTesting: " . ($this instanceof TypeTesting ? 'true' : 'false') . PHP_EOL;
        echo "  get_class(): " . get_class($this) . PHP_EOL;
    }

    private function testSpecialTypes()
    {
        echo "--- 特殊类型测试 ---" . PHP_EOL;

        // 资源类型测试
        $fileHandle = fopen('php://memory', 'r');
        echo "文件资源: " . gettype($fileHandle) . PHP_EOL;
        echo "  is_resource(): " . (is_resource($fileHandle) ? 'true' : 'false') . PHP_EOL;
        echo "  get_resource_type(): " . get_resource_type($fileHandle) . PHP_EOL;
        fclose($fileHandle);

        // 闭包测试
        $closure = function($x) { return $x * 2; };
        echo PHP_EOL . "闭包: " . gettype($closure) . PHP_EOL;
        echo "  is_callable(): " . (is_callable($closure) ? 'true' : 'false') . PHP_EOL;
        echo "  闭包调用(5): " . $closure(5) . PHP_EOL;

        // 生成器测试
        $generator = $this->createTestGenerator();
        echo PHP_EOL . "生成器: " . gettype($generator) . PHP_EOL;
        echo "  instanceof Generator: " . ($generator instanceof \Generator ? 'true' : 'false') . PHP_EOL;
    }

    private function testTypeCasting()
    {
        echo "--- 类型转换测试 ---" . PHP_EOL;

        $string = "123abc";
        echo "原字符串: '$string'" . PHP_EOL;
        echo "  (int) 强制转换: " . (int)$string . PHP_EOL;
        echo "  (float) 强制转换: " . (float)$string . PHP_EOL;
        echo "  (bool) 强制转换: " . ((bool)$string ? 'true' : 'false') . PHP_EOL;

        $number = 0;
        echo PHP_EOL . "原数字: $number" . PHP_EOL;
        echo "  (string) 强制转换: '" . (string)$number . "'" . PHP_EOL;
        echo "  (bool) 强制转换: " . ((bool)$number ? 'true' : 'false') . PHP_EOL;

        $array = ['a', 'b', 'c'];
        echo PHP_EOL . "原数组: ";
        var_export($array);
        echo PHP_EOL;
        echo "  (object) 强制转换: ";
        var_export((object)$array);
        echo PHP_EOL;

        // 类型严格比较
        $value = '42';
        echo PHP_EOL . "严格类型比较测试: " . PHP_EOL;
        echo "  '42' == 42: " . ('42' == 42 ? 'true' : 'false') . " (宽松比较)" . PHP_EOL;
        echo "  '42' === 42: " . ('42' === 42 ? 'true' : 'false') . " (严格比较)" . PHP_EOL;
    }

    private function testModernTypes()
    {
        echo "--- 现代 PHP 类型测试 ---" . PHP_EOL;

        // 测试有类型声明的函数
        echo "有类型声明的函数测试:" . PHP_EOL;
        try {
            $result = $this->typeHintedFunction(42);
            echo "  typeHintedFunction(42): $result" . PHP_EOL;

            $result = $this->typeHintedFunction("42");
            echo "  typeHintedFunction('42'): $result" . PHP_EOL;
        } catch (\TypeError $e) {
            echo "  类型错误: " . $e->getMessage() . PHP_EOL;
        }

        // 联合类型测试 (PHP 8.0+)
        if (PHP_VERSION_ID >= 80000) {
            echo PHP_EOL . "联合类型测试:" . PHP_EOL;
            $result = $this->unionTypeFunction("hello");
            echo "  unionTypeFunction('hello'): $result" . PHP_EOL;

            $result = $this->unionTypeFunction(123);
            echo "  unionTypeFunction(123): $result" . PHP_EOL;
        }

        // 可空类型测试
        echo PHP_EOL . "可空类型测试:" . PHP_EOL;
        $result = $this->nullableFunction("not null");
        echo "  nullableFunction('not null'): $result" . PHP_EOL;

        $result = $this->nullableFunction(null);
        echo "  nullableFunction(null): $result" . PHP_EOL;
    }

    // 有类型声明的函数
    private function typeHintedFunction(int $number): string
    {
        return "接收到整数: $number";
    }

    // 联合类型函数 (PHP 8.0+)
    private function unionTypeFunction(string|int $value): string
    {
        if (is_string($value)) {
            return "接收到字符串: $value";
        } else {
            return "接收到整数: $value";
        }
    }

    // 可空类型函数
    private function nullableFunction(?string $value): string
    {
        return $value === null ? "接收到 null 值" : "接收到字符串: $value";
    }

    private function createTestGenerator()
    {
        for ($i = 1; $i <= 3; $i++) {
            yield "测试值 $i";
        }
    }
}