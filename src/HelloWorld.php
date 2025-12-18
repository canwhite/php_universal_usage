<?php

namespace Zack\PhpUniversalUsage;

class HelloWorld
{
    public function sayHello()
    {
        return "Hello, World!";
    }

    public function demonstrateStringOperations()
    {
        //23333, 有趣的$, 我是个变量的意思
        $name = "张三";
        $greeting = "你好";

        // 字符串连接，EOL ～ end of line，可以理解为换行符
        echo $greeting . ", " . $name . "!" . PHP_EOL;

        // 字符串插值
        echo "$greeting, $name!" . PHP_EOL;


        // 字符串函数
        $text = "  Hello World  ";
        echo trim($text) . PHP_EOL;           // 去除首尾空格
        echo strlen($text) . PHP_EOL;         // 字符串长度
        echo strtoupper($text) . PHP_EOL;     // 转大写
        echo strtolower($text) . PHP_EOL;     // 转小写
        echo str_replace("World", "PHP", $text) . PHP_EOL; // 替换

        // 字符串分割和连接
        $fruits = "apple,banana,orange";
        // explode其用如其名，字符串大爆炸
        $fruitArray = explode(",", $fruits);
        print_r($fruitArray);

        // 内爆，第一次见
        // 到这里你应该也发现了，php即为喜欢使用静态方法去处理问题
        $joined = implode(" | ", $fruitArray);
        echo $joined . PHP_EOL;
    }

    public function demonstrateArrayOperations()
    {
        // 索引数组
        $fruits = ["apple", "banana", "orange"];

        // 添加元素
        $fruits[] = "grape";


        //array的push函数
        array_push($fruits, "mango");

        echo "添加水果后的数组:" . PHP_EOL;
        print_r($fruits);

        // array_pop操作 - 从数组末尾移除元素
        echo PHP_EOL . "=== 数组pop操作演示 ===" . PHP_EOL;
        $lastFruit = array_pop($fruits);  // 移除并返回最后一个元素
        echo "被弹出的水果: $lastFruit" . PHP_EOL;
        echo "弹出水果后的数组:" . PHP_EOL;
        print_r($fruits);


        // 其他数组栈操作
        echo PHP_EOL . "=== 其他数组栈操作 ===" . PHP_EOL;

        // array_shift - 从数组开头移除元素 (队列出队)
        $firstFruit = array_shift($fruits);
        echo "从开头移除的水果: $firstFruit" . PHP_EOL;
        echo "array_shift后的数组:" . PHP_EOL;
        print_r($fruits);

        // array_unshift - 从数组开头添加元素 (队列入队)
        array_unshift($fruits, "strawberry", "kiwi");
        echo PHP_EOL . "在开头添加水果后的数组:" . PHP_EOL;

        //第二种输出方式，用来输出数组或对象，后边的_r是readable的意思
        print_r($fruits);

        // 访问元素
        echo "第一个水果: " . $fruits[0] . PHP_EOL;

        // 数组长度
        echo "水果数量: " . count($fruits) . PHP_EOL;

        // 检查元素是否存在
        if (in_array("apple", $fruits)) {
            echo "包含苹果" . PHP_EOL;
        }

        // 数组遍历
        // foreach的时候给原来的$fruits拆分形态也是很有意思
        foreach ($fruits as $index => $fruit) {
            echo "$index: $fruit" . PHP_EOL;
        }

        // 数组排序
        sort($fruits);  // 升序
        //--tou
        print_r($fruits);

        // 数组切片
        $slice = array_slice($fruits, 0, 2);
        print_r($slice);

        // 关联数组（类似map），数组map形态
        // 这个形态，ok
        $person = [
            "name" => "张三",
            "age" => 25,
            "city" => "北京"
        ];

        // 访问和修改
        echo $person["name"] . PHP_EOL;
        $person["age"] = 26;

        // 检查键是否存在
        if (array_key_exists("city", $person)) {
            echo "来自" . $person["city"] . PHP_EOL;
        }

        // 获取所有键和值
        print_r(array_keys($person));
        print_r(array_values($person));
    }

    public function demonstrateJsonOperations()
    {
        // 数组转JSON
        $data = [
            "name" => "张三",
            "age" => 25,
            "hobbies" => ["读书", "游泳", "编程"]
        ];

        //encode and decode
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        echo "JSON字符串: " . $json . PHP_EOL;

        // JSON转数组
        $decoded = json_decode($json, true);
        print_r($decoded);
    }

    public function demonstrateControlStructures()
    {
        echo "=== 条件判断和循环演示 ===" . PHP_EOL;

        // if-else条件判断
        $score = 85;
        echo "分数: $score - ";
        if ($score >= 90) {
            echo "优秀" . PHP_EOL;
        } elseif ($score >= 80) {
            echo "良好" . PHP_EOL;
        } elseif ($score >= 60) {
            echo "及格" . PHP_EOL;
        } else {
            echo "不及格" . PHP_EOL;
        }

        // switch-case条件判断
        $day = "Wednesday";
        echo "今天是 $day - ";
        switch (strtolower($day)) {
            case "monday":
                echo "周一" . PHP_EOL;
                break;
            case "tuesday":
                echo "周二" . PHP_EOL;
                break;
            case "wednesday":
                echo "周三" . PHP_EOL;
                break;
            case "thursday":
                echo "周四" . PHP_EOL;
                break;
            case "friday":
                echo "周五" . PHP_EOL;
                break;
            case "saturday":
            case "sunday":
                echo "周末" . PHP_EOL;
                break;
            default:
                echo "未知" . PHP_EOL;
                break;
        }

        // for循环
        echo PHP_EOL . "for循环示例:" . PHP_EOL;
        for ($i = 1; $i <= 5; $i++) {
            echo "第 $i 次循环" . PHP_EOL;
        }

        // 嵌套for循环 - 九九乘法表
        echo PHP_EOL . "九九乘法表:" . PHP_EOL;
        for ($i = 1; $i <= 9; $i++) {
            for ($j = 1; $j <= $i; $j++) {
                echo "$j × $i = " . ($i * $j) . "\t";
            }
            echo PHP_EOL;
        }

        // while循环
        echo PHP_EOL . "while循环示例:" . PHP_EOL;
        $count = 1;
        while ($count <= 3) {
            echo "while循环第 $count 次" . PHP_EOL;
            $count++;
        }

        // do-while循环
        echo PHP_EOL . "do-while循环示例:" . PHP_EOL;
        $doCount = 1;
        do {
            echo "do-while循环第 $doCount 次" . PHP_EOL;
            $doCount++;
        } while ($doCount <= 2);

        // 条件判断与循环结合
        echo PHP_EOL . "条件判断与循环结合示例:" . PHP_EOL;
        $numbers = [12, 7, 15, 3, 9, 21, 6];
        echo "数组: [" . implode(", ", $numbers) . "]" . PHP_EOL;
        echo "处理结果:" . PHP_EOL;

        for ($i = 0; $i < count($numbers); $i++) {
            $num = $numbers[$i];
            if ($num % 2 == 0) {
                echo "数字 $num 是偶数";
                if ($num > 10) {
                    echo " 且大于10";
                }
                echo PHP_EOL;
            } else {
                echo "数字 $num 是奇数";
                if ($num < 10) {
                    echo " 且小于10";
                }
                echo PHP_EOL;
            }
        }

        // break和continue示例
        echo PHP_EOL . "break和continue示例:" . PHP_EOL;
        for ($i = 1; $i <= 10; $i++) {
            if ($i == 4) {
                continue; // 跳过4
            }
            if ($i == 8) {
                break; // 在8处停止
            }
            echo "处理数字: $i" . PHP_EOL;
        }
    }

    public function run()
    {
        echo $this->sayHello() . PHP_EOL;

        echo "\n=== 条件判断和循环演示 ===" . PHP_EOL;
        $this->demonstrateControlStructures();

        echo "\n=== 字符串操作演示 ===" . PHP_EOL;
        $this->demonstrateStringOperations();

        echo "\n=== 数组操作演示 ===" . PHP_EOL;
        $this->demonstrateArrayOperations();

        echo "\n=== JSON操作演示 ===" . PHP_EOL;
        $this->demonstrateJsonOperations();
    }
}