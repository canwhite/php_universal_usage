<?php

namespace Zack\PhpUniversalUsage;

/**
 * 面向对象三件套：封装 / 继承 / 多态
 *
 * 为了一个文件就能看完，这里把所有类都写在一起了：
 *   Feedable（接口） -> Animal（抽象类） -> Dog / Cat（子类），外加 HasOwner（trait）、FeedingRobot
 *
 * 注意：PSR-4 自动加载的规矩是"一个类一个文件，文件名 = 类名"，
 * 所以只有 C_OopBasics 能被自动加载到，剩下的类是跟着这个文件一起被定义出来的。
 * 真实项目里请拆成独立文件（src/Oop/Animal.php 这样），这里纯粹是为了好读。
 */
class C_OopBasics
{
    public function run()
    {
        echo "=== 面向对象：封装 / 继承 / 多态 ===" . PHP_EOL;

        $this->demonstrateEncapsulation();
        $this->demonstrateInheritance();
        $this->demonstratePolymorphism();
        $this->demonstrateExtras();
    }

    // ==================== 1. 封装 ====================
    private function demonstrateEncapsulation()
    {
        echo PHP_EOL . "--- 1. 封装：数据只能通过方法进出 ---" . PHP_EOL;

        $dog = new Dog('旺财');
        // setAge / setName 都返回 $this，所以能一路点下去（链式调用）
        $dog->setAge(3)->setName('  旺财  '); // setName 里顺手 trim 了

        echo "getId():  " . $dog->getId() . "（只有 getter 没有 setter，这就是只读属性）" . PHP_EOL;
        echo "getName(): " . $dog->getName() . PHP_EOL;
        echo "getAge():  " . $dog->getAge() . PHP_EOL;

        $dog->setAge(4);
        echo "setAge(4) 之后: " . $dog->getAge() . PHP_EOL;

        // 封装的真正价值不是"藏起来"，而是"保证对象状态永远合法"：
        // 不合法的值在 setter 里就被拦下了，对象内部永远不会出现 -1 岁的狗
        try {
            $dog->setAge(-1);
        } catch (\InvalidArgumentException $e) {
            echo "setAge(-1) 被拒绝: " . $e->getMessage() . PHP_EOL;
        }
        echo "年龄没被改坏，仍然是: " . $dog->getAge() . PHP_EOL;

        // 从外部直接碰 protected / private 会怎样？
        echo PHP_EOL . "从外部直接访问非 public 属性：" . PHP_EOL;
        try {
            echo $dog->tricks; // private，声明在 Dog 自己身上
        } catch (\Error $e) {
            echo "  \$dog->tricks -> " . $e->getMessage() . PHP_EOL;
        }
        try {
            echo $dog->name;   // protected，Animal 自己 + 子类能用
        } catch (\Error $e) {
            echo "  \$dog->name   -> " . $e->getMessage() . PHP_EOL;
        }
        // 坑一：这里抛的是 Error 不是 Exception，catch 要写 \Error（或 \Throwable），
        //       写 \Exception 是抓不住的

        // 坑二：父类里声明的 private 属性（$id）更隐蔽 —— 从外部访问连 Error 都不给，
        //       只当它"不存在"，抛个 Warning 就继续往下跑，非常容易查错。
        //       排查时别靠 isset，它回答的是"可见吗"而不是"存在吗"：
        echo "isset(\$dog->tricks) = " . var_export(isset($dog->tricks), true) . "（可见性，不可见）" . PHP_EOL;
        echo "property_exists(\$dog, 'tricks') = " . var_export(property_exists($dog, 'tricks'), true)
            . "（Dog 自己声明的 private，看得见）" . PHP_EOL;
        echo "property_exists(\$dog, 'id') = " . var_export(property_exists($dog, 'id'), true)
            . "（父类声明的 private，子类视角直接看不见）" . PHP_EOL;

        // 想把一个对象的属性看全，只能用反射，而且得往父类上找：
        $ref = new \ReflectionClass($dog);
        echo "Dog 自己声明的属性: " . implode('、', array_map(
            fn($p) => $p->getName(),
            $ref->getProperties()
        )) . PHP_EOL;
        echo "父类 Animal 声明的属性: " . implode('、', array_map(
            fn($p) => $p->getName(),
            $ref->getParentClass()->getProperties()
        )) . PHP_EOL;
    }

    // ==================== 2. 继承 ====================
    private function demonstrateInheritance()
    {
        echo PHP_EOL . "--- 2. 继承：子类复用父类，再长出自己的一部分 ---" . PHP_EOL;

        $cat = new Cat('咪咪', indoor: false);
        $dog = new Dog('旺财');

        // 父类的常量，子类直接拿来用
        echo "Animal::KINGDOM = " . Animal::KINGDOM . PHP_EOL;
        echo "Dog::KINGDOM    = " . Dog::KINGDOM . "（继承来的常量）" . PHP_EOL;
        echo "Cat::KINGDOM    = " . Cat::KINGDOM . PHP_EOL;

        // instanceof 会沿着继承链一路为真
        var_dump($dog instanceof Dog);      // true，本身
        var_dump($dog instanceof Animal);   // true，父类
        var_dump($dog instanceof Feedable); // true，实现的接口
        var_dump($cat instanceof Dog);      // false，猫不是狗

        // 查看继承链和实现的接口
        echo "Dog 的父类: " . get_parent_class($dog) . PHP_EOL;
        print_r(class_parents($dog));
        print_r(class_implements($dog));

        // 继承来的方法，子类直接就能用
        echo $dog->whoAmI() . PHP_EOL; // self 永远是 Animal，static 是实际的 Dog

        // 子类自己的扩展：父类完全不知道 Dog 有 tricks
        $dog->learnTrick('握手')->learnTrick('装死');
        echo $dog->describe() . PHP_EOL; // Dog 重写了 describe，内部又调了 parent::describe()
        echo $cat->describe() . PHP_EOL; // Cat 没重写，用的就是父类那一份

        echo "动物总数（static 属性，所有对象共享一份）: " . Animal::population() . PHP_EOL;
    }

    // ==================== 3. 多态 ====================
    private function demonstratePolymorphism()
    {
        echo PHP_EOL . "--- 3. 多态：同一行代码，不同对象给出不同结果 ---" . PHP_EOL;

        // 关键：数组里声明的是"动物"，装的可以是任意子类。
        // 调用方只认识 Animal，将来再加个 Pig 也不用改这里的循环。
        $animals = [
            new Dog('旺财'),
            new Cat('咪咪'),
            new Cat('雪球', indoor: true),
        ];

        foreach ($animals as $animal) {
            // $animal->speak() 究竟跑哪段代码，要等运行时看它到底是什么对象
            echo "  " . $animal->getName() . " -> " . $animal->speak() . PHP_EOL;
        }

        // 多态更常见的落地形态：函数签名只认父类 / 接口，不认具体类
        echo PHP_EOL . "喂食（参数类型写的是 Feedable 接口）：" . PHP_EOL;
        $feedables = [
            new Dog('旺财'),
            new Cat('咪咪'),
            new FeedingRobot('R2D2'), // 机器人不是动物，但同样能被喂
        ];
        foreach ($feedables as $feedable) {
            echo "  " . $feedable->eat('狗粮') . PHP_EOL;
        }
        echo "  Feedable::MAX_DAILY_MEALS = " . Feedable::MAX_DAILY_MEALS . PHP_EOL;

        // 传错类型会被类型声明当场挡下来 —— 接口就是契约
        echo PHP_EOL . "接口即契约，传个 stdClass 试试：" . PHP_EOL;
        try {
            $this->feed(new \stdClass());
        } catch (\TypeError $e) {
            echo "  TypeError: " . $e->getMessage() . PHP_EOL;
        }
    }

    // 类型声明写成接口而不是具体类，这就是"面向接口编程"
    private function feed(Feedable $feedable): string
    {
        return $feedable->eat('饲料');
    }

    // ==================== 4. 顺带的几个常用件 ====================
    private function demonstrateExtras()
    {
        echo PHP_EOL . "--- 4. 补充：trait / 魔术方法 / 静态成员 ---" . PHP_EOL;

        // trait：一段可以"贴"到任意类里的代码，解决"想复用但没有继承关系"的问题
        $dog = new Dog('旺财');
        $dog->setOwner('张三');
        echo "旺财的主人: " . $dog->getOwner() . PHP_EOL;

        // 谁 use 了 trait，谁才白得这些方法；Cat 没用，就真的没有
        $cat = new Cat('咪咪');
        echo "咪咪能不能查主人: " . (method_exists($cat, 'getOwner') ? '能' : '不能（Cat 没 use HasOwner）') . PHP_EOL;

        // 魔术方法 __toString()：让对象能被当成字符串用
        echo "直接 echo 对象: " . $dog . PHP_EOL;

        echo "到目前为止一共 new 了 " . Animal::population() . " 只动物" . PHP_EOL;
    }
}

/**
 * 接口：只规定"能做什么"，不管"你是谁"
 *  - 一个类只能 extends 一个父类，但能 implements 任意多个接口
 *  - 接口里的方法都只有声明没有方法体（常量除外），实现类一个都不能少
 */
interface Feedable
{
    public const MAX_DAILY_MEALS = 5;

    public function eat(string $food): string;
}

/**
 * trait：一段能贴到任意类里的代码
 *  继承是"是不是"的关系（狗是动物），trait 是"有没有"的关系（狗有主人，
 *  但"有主人"这件事和继承树无关，塞进父类不合适），所以单独抽出来。
 */
trait HasOwner
{
    private ?string $ownerName = null;

    public function setOwner(string $ownerName): static
    {
        $this->ownerName = $ownerName;
        return $this;
    }

    public function getOwner(): string
    {
        return $this->ownerName ?? '流浪中，暂无主人';
    }
}

/**
 * 抽象类：半成品
 *  - 能被继承，但不能被 new（"动物"太抽象了）
 *  - 既能写实现（公共逻辑），也能留坑（abstract 方法逼子类实现）
 */
abstract class Animal implements Feedable
{
    public const KINGDOM = '动物界';

    // static 属性：所有对象共享同一份
    private static int $population = 0;

    // private：只有本类内部能碰，子类也不行，外部想改只能走 setter
    private string $id;
    private ?int $age = null;

    // protected：本类 + 子类能用，外部不行
    protected string $name;

    // readonly：只能在构造函数里赋值一次，之后再改就报错
    protected readonly string $species;

    public function __construct(string $name, string $species)
    {
        $this->name = $name;
        $this->species = $species;

        self::$population++;
        $this->id = 'A-' . str_pad((string) self::$population, 3, '0', STR_PAD_LEFT);
    }

    // ---- 数据的进出口：id 只读，age 必须过校验 ----
    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    // 返回 static（也就是 $this），是为了支持链式调用
    public function setName(string $name): static
    {
        $name = trim($name);
        if ($name === '') {
            throw new \InvalidArgumentException('名字不能为空');
        }

        $this->name = $name;
        return $this;
    }

    public function setAge(int $age): static
    {
        if ($age < 0 || $age > 100) {
            throw new \InvalidArgumentException("年龄 $age 不合法，必须是 0~100");
        }

        $this->age = $age;
        return $this;
    }

    // ---- 留给子类的坑 ----
    abstract public function speak(): string;

    // 模板方法：父类把流程定死，其中一步 speak() 交给子类决定 —— 多态最典型的用法
    public function describe(): string
    {
        return sprintf(
            '[%s] %s（%s，%s）说：%s',
            $this->id,
            $this->name,
            $this->species,
            self::KINGDOM,
            $this->speak()
        );
    }

    public static function population(): int
    {
        return self::$population;
    }

    /**
     * self:: 和 static:: 的区别（后期静态绑定），初学者最容易踩的坑：
     *   self::   永远指向"写这行代码的类"，也就是 Animal
     *   static:: 指向"实际被 new 出来的类"，也就是子类
     */
    final public function whoAmI(): string
    {
        return 'self: ' . self::class . ' / static: ' . static::class;
    }

    // 魔术方法：定义之后对象就能被当成字符串用（echo $dog）
    public function __toString(): string
    {
        return $this->describe();
    }
}

class Dog extends Animal
{
    use HasOwner; // trait 是"贴代码"，和继承互不冲突

    // 子类自己的封装，父类完全不知道有这回事
    private array $tricks = [];

    public function __construct(string $name)
    {
        // parent:: 调父类构造函数。不调的话父类的 private 属性就是空的，后面全崩。
        // 品种这种固定信息由子类写死，外部就不用重复传了
        parent::__construct($name, '犬科');
    }

    // 重写父类的抽象方法 —— 多态真正落地的地方
    public function speak(): string
    {
        return '汪汪汪';
    }

    public function eat(string $food): string
    {
        // $name 是父类的 protected 属性，子类能直接用；private 的 $id 就不行
        return "{$this->name} 一口吞掉了 $food";
    }

    // 重写普通方法：用 parent:: 保住父类原有行为，再叠加自己的部分
    public function describe(): string
    {
        $base = parent::describe();

        if ($this->tricks === []) {
            return $base . '（还没学会任何技能）';
        }

        return $base . '（技能：' . implode('、', $this->tricks) . '）';
    }

    public function learnTrick(string $trick): static
    {
        $this->tricks[] = $trick;
        return $this;
    }

    public function getTricks(): array
    {
        return $this->tricks;
    }
}

class Cat extends Animal
{
    /**
     * 构造器属性提升（PHP 8.0+）：参数前加修饰符，PHP 自动帮你声明属性 + 赋值，
     * 等价于先写 private bool $indoor; 再在构造函数里 $this->indoor = $indoor;
     * 属性少时很省事，一多就别用了 —— 全堆在参数里反而看不清
     */
    public function __construct(string $name, private bool $indoor = true)
    {
        parent::__construct($name, '猫科');
    }

    public function speak(): string
    {
        return '喵～';
    }

    public function eat(string $food): string
    {
        return "{$this->name} 挑挑拣拣地吃了两口 $food";
    }

    public function isIndoor(): bool
    {
        return $this->indoor;
    }

    // 没重写 describe()，所以 Cat 用的就是父类 Animal 那一份 —— 这就是继承的价值
}

/**
 * 和 Animal 没有任何继承关系（机器人不是动物），但它也能被"喂"。
 * 这正是接口存在的最大理由：
 *   调用方依赖抽象父类 Animal 时，机器人根本进不了那个循环；
 *   依赖接口 Feedable 时，狗、猫、机器人可以一起排队被喂。
 */
class FeedingRobot implements Feedable
{
    public function __construct(private string $model)
    {
    }

    public function eat(string $food): string
    {
        return "{$this->model} 把 $food 处理成了电量";
    }

    public function getModel(): string
    {
        return $this->model;
    }
}
