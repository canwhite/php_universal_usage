<?php

namespace Zack\PhpUniversalUsage;

class HelloWorld
{
    public function sayHello()
    {
        return "Hello, World!";
    }

    //字符串的操作演示
    public function demonstrateStringOperations()
    {
        //23333, 有趣的$, 我是个变量的意思
        $name = "张三";
        $greeting = "你好";

        // 字符串连接，EOL : End of line，可以理解为换行符
        // echo输出，用.连接字符串
        echo $greeting . ", " . $name . "!" . PHP_EOL;

        // 字符串插值
        echo "$greeting, $name!" . PHP_EOL;


        // 字符串函数
        $text = "  Hello World  ";
        // 字符串操作函数演示，静态方法的使用方式，php的函数库是非常丰富的
        echo trim($text) . PHP_EOL;           // 去除首尾空格
        echo strlen($text) . PHP_EOL;         // 字符串长度
        echo strtoupper($text) . PHP_EOL;     // 转大写
        echo strtolower($text) . PHP_EOL;     // 转小写
        //为什么把参数放在最后一个呢？
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


    //数组的操作演示
    public function demonstrateArrayOperations()
    {
        // 索引数组
        $fruits = ["apple", "banana", "orange"];

        // 这个可以理解为追加元素的语法糖
        $fruits[] = "grape";


        //也是基于静态函数的操作，我们主要记这个吧，那个语法糖太鸡儿奇怪了
        array_push($fruits, "mango");

        echo "添加水果后的数组:" . PHP_EOL;
        //print_r是一个非常有用的函数，专门用来打印数组和对象的,
        //和echo有什么区别？echo主要用来打印字符串，而print_r可以打印数组和对象的结构，方便调试和查看数据结构。
        //后边的r是什么意思？这里的r是readable的意思，表示输出的内容是可读的格式，适合调试和查看数据结构。
        print_r($fruits);


        // array_pop操作 - 从数组末尾移除元素
        echo PHP_EOL . "=== 数组pop操作演示 ===" . PHP_EOL;
        $lastFruit = array_pop($fruits);  // 移除并返回最后一个元素
        echo "被弹出的水果: $lastFruit" . PHP_EOL;
        echo "弹出水果后的数组:" . PHP_EOL;
        print_r($fruits);

        //总结，所以这方面是比较简单的，一个push一个pop

        // 其他数组栈操作
        echo PHP_EOL . "=== 其他数组栈操作 ===" . PHP_EOL;

        // array_shift - 从数组开头移除元素 (队列出队)
        // shift的意思是移动，移位，array_shift就是把数组的元素向左移动一位，移除第一个元素
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

        // 数组长度，它算数组长度的方法挺奇怪的
        echo "水果数量: " . count($fruits) . PHP_EOL;

        // 检查元素是否存在，这个也很有意思，in array好直白呀
        if (in_array("apple", $fruits)) {
            echo "包含苹果" . PHP_EOL;
        }

        // 数组遍历
        // foreach的时候给原来的$fruits拆分形态也是很有意思
        // 这个as key => value的形式，和python的enumerate很像
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

        // 关联数组（类似map），数组map形态，这个形态ok
        $person = [
            "name" => "张三",
            "age" => 25,
            "city" => "北京"
        ];

        // 访问和修改
        echo $person["name"] . PHP_EOL;
        $person["age"] = 26;

        // 获取所有键和值
        print_r(array_keys($person));
        print_r(array_values($person));

        // 补充一个array_map的用法，map的意思是映射，array_map就是把数组中的每个元素映射到一个新的值上
        /**
        回调函数作为第一个参数的常用数组函数，主要就是 array_map。
        其他你常打交道的数组函数，基本都是“数组在前，回调在后”：

        函数	参数顺序
        array_map	回调在前：array_map($callback, $array...)
        array_filter	数组在前：array_filter($array, $callback)
        array_reduce	数组在前：array_reduce($array, $callback, $initial)
        array_walk	数组在前：array_walk(&$array, $callback)
        usort / uasort / uksort	数组在前：usort(&$array, $callback)
        array_udiff / array_uintersect	数组在前，回调在最后
        array_find / array_any / array_all	数组在前：array_find($array, $callback)
         */
        $numbers = [1, 2, 3, 4, 5];

        //主要array_map是回调在前
        //而且理由还挺硬核：不是 PHP 随便乱来，而是被“可变参数必须放最后”这条语法规则逼出来的。 
        //map的时候值是可变的，基于函数式原则，可以把它放在后边
        $squared = array_map(function($n) {
            return $n * $n;
        }, $numbers);
        print_r($squared);

        //有filter的用法，filter的意思是过滤，array_filter就是把数组中的元素过滤掉不符合条件的元素
        $evenNumbers = array_filter($numbers, function($n) {
            return $n % 2 === 0;
        });
        print_r($evenNumbers);

        //有类似于python的reduce的用法，reduce的意思是归约，array_reduce就是把数组中的元素归约成一个值
        $sum = array_reduce($numbers, function($carry, $n) {
            return $carry + $n;
        }, 0);
        print_r($sum);

    }


    //json的处理
    public function demonstrateDictOperations()
    {
        // PHP 没有单独的 dict 类型 —— 键值对就是用"关联数组"来表示
        // 别的语言里叫 dict / hash / map，PHP 统称 associative array

        // 1. 创建
        $user = [
            "name" => "张三",
            "age" => 25,
            "city" => "北京",
        ];
        // 先声明空数组再逐个赋值，结果一样
        $empty = [];
        $empty["name"] = "李四";
        $empty["age"] = 30;
        print_r($empty);

        // 2. 增 / 改 —— 语法完全一样：键不存在就是新增，存在就是覆盖
        $user["email"] = "zhangsan@example.com"; // 新增
        $user["age"] = 26;                       // 修改
        echo "年龄: " . $user["age"] . PHP_EOL;

        // 3. 删除
        unset($user["city"]);
        echo "删除 city 后剩下的键: " . implode(", ", array_keys($user)) . PHP_EOL;

        // 4. 取值给默认值 —— 键不存在时直接取会报 warning，加 ?? 就安全
        echo "昵称: " . ($user["nickname"] ?? "未设置") . PHP_EOL;

        // 5. 判断键是否存在：array_key_exists 和 isset 不一样
        $config = ["debug" => false, "cache" => null];
        var_dump(array_key_exists("cache", $config)); // true，键确实在
        var_dump(isset($config["cache"]));            // false，isset 认为 null 等于不存在
        // 只关心"有没有这个键"用 array_key_exists，关心"有没有值"用 isset

        // 6. 遍历 —— foreach 同时拿键和值
        foreach ($user as $key => $value) {
            echo "$key => $value" . PHP_EOL;
        }

        // 7. 嵌套：值本身也可以是 dict 或数组
        $order = [
            "id" => 1001,
            "customer" => ["name" => "王五", "phone" => "13800000000"],
            "items" => ["苹果", "香蕉"],
        ];
        echo "客户电话: " . $order["customer"]["phone"] . PHP_EOL;
        echo "商品数量: " . count($order["items"]) . PHP_EOL;

        // 8. 排序：ksort 按 key 排，asort 按 value 排（两者都保留键名）
        $scores = ["banana" => 3, "apple" => 5, "cherry" => 1];
        $byKey = $scores;
        ksort($byKey);
        echo "按 key 排序:" . PHP_EOL;
        print_r($byKey);
        $byValue = $scores;
        asort($byValue);
        echo "按 value 排序:" . PHP_EOL;
        print_r($byValue);

        // 9. 合并两个 dict：array_merge 和 + 的行为不一样
        $a = ["a" => 1, "b" => 2];
        $b = ["b" => 9, "c" => 3];
        echo "array_merge 合并（右边覆盖左边，b 变成 9）:" . PHP_EOL;
        print_r(array_merge($a, $b));
        echo "+ 运算符合并（左边保留，b 还是 2）:" . PHP_EOL;
        print_r($a + $b);
    }


    public function demonstrateJsonOperations()
    {
        // 数组转JSON
        $data = [
            "name" => "张三",
            "age" => 25,
            "hobbies" => ["读书", "游泳", "编程"]
        ];
        //记起来也挺方便，直接就是json_encode和json_decode
        //这里第二个参数如何理解？未转译成unicode的json，也就是json字符串
        //unicode是一种什么样的数据结构？
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        echo "JSON字符串: " . $json . PHP_EOL;

        // JSON转数组，为true的话就是数组，否则就是对象
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

        echo "\n=== 字典(dict)操作演示 ===" . PHP_EOL;
        $this->demonstrateDictOperations();

        echo "\n=== array_map 演示 ===" . PHP_EOL;
        $this->demonstrateArrayMapOperations();

        echo "\n=== JSON操作演示 ===" . PHP_EOL;
        $this->demonstrateJsonOperations();
    }
}