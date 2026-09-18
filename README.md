# PHP Universal Usage

一个全面的PHP学习和演示项目，涵盖PHP基础语法、常用功能和高级特性。

## 📋 项目概述

这个项目旨在帮助开发者快速学习和掌握PHP的核心功能，包括：
- 基础语法和数据类型
- 字符串和数组操作
- 条件判断和循环控制
- 类型系统与类型判断
- 面向对象（封装、继承、多态）
- HTTP请求处理
- 异步编程概念
- 包管理和依赖注入


## 🏗️ 项目结构

```
php_universal_usage/
├── README.md                 # 项目说明文档（本文件）
├── composer.json             # Composer依赖配置文件（PSR-4 自动加载规则）
├── composer.lock             # 依赖锁文件
├── main.php                  # 项目入口文件，按 A~F 顺序演示所有功能
├── CREATE_PROJECT.md         # init 与 create-project 的区别说明
├── src/                      # 源代码目录（文件名按学习顺序编号）
│   ├── A_HelloWorld.php      # 基础语法：字符串/数组/字典/集合/JSON/控制流
│   ├── B_TypeTesting.php     # 类型系统与类型判断
│   ├── C_OopBasics.php       # 面向对象：封装/继承/多态
│   ├── D_HttpClient.php      # HTTP请求处理类
│   ├── E_AsyncOperations.php # 异步操作和并发处理类
│   └── F_PackageManagement.php # 包管理功能演示类
├── laravel_project/          # 用来练手的 Laravel 项目
└── vendor/                   # Composer依赖包目录
    ├── autoload.php          # 自动加载文件
    └── composer/             # Composer内部文件
```

> `src/` 下的文件名按**学习顺序**加了 `A_` ~ `F_` 前缀。
> 前缀同时加在**文件名和类名**上（`A_HelloWorld.php` 里的类就叫 `A_HelloWorld`），
> 因为 PSR-4 要求"文件名 = 类名"，而 PHP 类名不能以数字开头 —— 用字母才能既排序又不破坏自动加载。

## 📁 目录说明

### 根目录文件

#### `composer.json`
- **作用**: 项目依赖管理和自动加载配置
- **功能**:
  - 定义项目名称和版本
  - 配置PSR-4自动加载规则
  - 管理第三方依赖包
  - 设置项目元数据

#### `main.php`
- **作用**: 项目的主入口文件和演示程序
- **功能**:
  - 按 A~F 的顺序依次调用 `src/` 下的演示类
  - 演示所有PHP功能模块
  - 提供完整的学习示例
  - 可以直接运行查看效果

### `src/` 源代码目录

#### `A_HelloWorld.php`
- **作用**: PHP基础语法的核心演示类
- **包含功能**:
  - 字符串操作（连接、替换、分割、转换等）
  - 数组操作（索引数组、关联数组、遍历、排序等）
  - 字典（关联数组）操作与常用函数
  - 集合（用数组模拟）与集合运算
  - JSON数据处理
  - 条件判断（if-else, switch-case）
  - 循环控制（for, while, do-while）
  - 嵌套循环和流程控制（break, continue）

#### `B_TypeTesting.php`
- **作用**: PHP类型系统和类型判断的演示类
- **包含功能**:
  - 基本类型测试（`gettype` / `is_int` / `is_string` 等）
  - 复合类型与特殊类型（数组、对象、资源、闭包、生成器）
  - 类型转换与宽松比较 / 严格比较的区别
  - 类型声明、联合类型（PHP 8.0+）、可空类型

#### `C_OopBasics.php`
- **作用**: 面向对象三大特性的演示类（一个文件看完整条继承链）
- **包含功能**:
  - **封装**：`public` / `protected` / `private` / `readonly`、getter/setter 校验、链式调用
  - **继承**：`extends`、`parent::`、方法重写、`instanceof`、类常量与静态属性
  - **多态**：抽象类、接口（`implements`）、接口类型声明、运行时方法分发
  - 补充：`trait`、魔术方法 `__toString()`、`self::` 与 `static::` 的区别
  - 内含 `Feedable`（接口）、`Animal`（抽象类）、`Dog` / `Cat`（子类）、`FeedingRobot`（接口实现）

#### `D_HttpClient.php`
- **作用**: HTTP请求处理的演示类
- **包含功能**:
  - GET请求实现
  - POST请求实现
  - 文件上传功能
  - 错误处理和响应解析
  - cURL库的使用

#### `E_AsyncOperations.php`
- **作用**: 异步编程和并发处理演示类
- **包含功能**:
  - 多进程处理（使用pcntl扩展）
  - Generator生成器使用
  - Promise风格编程模式
  - 异步操作的模拟实现

#### `F_PackageManagement.php`
- **作用**: Composer包管理功能演示类
- **包含功能**:
  - Composer配置信息展示
  - 自动加载机制演示
  - 常用包推荐和说明
  - 包管理命令介绍

### `vendor/` 依赖目录

- **作用**: 存储Composer下载的第三方包
- **重要文件**:
  - `autoload.php`: 自动加载入口文件
  - `composer/`: Composer内部管理文件
- **注意**: 这个目录由Composer管理，不应手动修改

## 🚀 快速开始

### 环境要求
- PHP 8.1 或更高版本（用到构造器属性提升、`readonly`、联合类型等特性；本项目在 8.5 上验证）
- Composer（PHP包管理器）

### 安装步骤

1. **克隆或下载项目**
   ```bash
   git clone <repository-url> php_universal_usage
   cd php_universal_usage
   ```

2. **安装依赖**
   ```bash
   composer install
   ```

3. **运行演示程序**
   ```bash
   php main.php
   ```

## 📚 学习路径

### 1. 基础语法（`src/A_HelloWorld.php`）
- PHP变量和数据类型
- 字符串操作函数
- 数组处理方法
- 字典和集合的表示方式
- 条件判断和循环

### 2. 类型系统（`src/B_TypeTesting.php`）
- 类型判断函数
- 类型转换规则
- 宽松比较和严格比较
- 类型声明与联合类型

### 3. 面向对象（`src/C_OopBasics.php`）
- 封装：访问修饰符与数据校验
- 继承：复用父类、重写方法
- 多态：抽象类与接口
- trait 与魔术方法

### 4. 网络编程（`src/D_HttpClient.php`）
- cURL库使用
- HTTP请求处理
- API调用方法

### 5. 异步编程（`src/E_AsyncOperations.php`）
- PHP异步编程概念
- 多进程处理
- Generator使用技巧

### 6. 工程实践（`src/F_PackageManagement.php`）
- Composer包管理
- 项目组织结构
- 依赖管理最佳实践

## 🔧 开发指南

### 添加新功能
1. 在 `src/` 目录下创建新的类文件，按学习顺序加字母前缀（`G_`、`H_`……）
2. 前缀要**同时加在文件名和类名上**：`G_NewTopic.php` 里的类必须叫 `G_NewTopic`，
   否则 PSR-4 找不到这个类（类名不能以数字开头，所以用字母而不是 `07_`）
3. 在 `main.php` 中 `use` 进来并按顺序调用
4. 更新本README文档

### 代码规范
- 遵循PSR-4自动加载标准
- 使用驼峰命名法命名类和方法
- 添加适当的注释和文档
- 保持代码的可读性和可维护性

## 📖 相关知识

### PHP基础概念
- **弱类型语言**: 变量类型自动转换
- **服务端语言**: 主要在Web服务器上运行
- **面向对象**: 支持完整的面向对象编程
- **丰富的内置函数**: 提供大量实用的内置函数

### Composer包管理
- **依赖管理**: 自动下载和管理项目依赖
- **自动加载**: 根据PSR规范自动加载类文件
- **版本控制**: 精确控制依赖包版本
- **生态丰富**: 超过20万个开源包

### Composer 与 yarn / npm 对照（写给前端同学）

Composer 在 PHP 里的位置，基本等于 yarn 在 JS 里的位置：

| yarn / npm | Composer | 说明 |
|---|---|---|
| `yarn init` | `composer init` | 都是"问几个问题，生成清单文件"，都不装依赖 |
| `yarn init -y` | `composer init -n` | 跳过提问走默认值；`composer init -n` 会拿「系统用户名/当前目录名」拼包名（在 `init_demo/` 里跑就是 `doing/init_demo`） |
| `yarn add monolog/monolog` | `composer require monolog/monolog` | 装依赖并写进清单 |
| `yarn add -D phpunit/phpunit` | `composer require --dev phpunit/phpunit` | 开发依赖 |
| `yarn install` | `composer install` | 按锁文件还原依赖 |
| `yarn create react-app my-app` | `composer create-project laravel/laravel my-app` | 拉一整套项目骨架，不只是装包 |
| `package.json` | `composer.json` | 清单文件 |
| `yarn.lock` | `composer.lock` | 锁文件 |
| `node_modules/` | `vendor/` | 依赖目录 |
| `package.json` 的 `scripts` | `composer.json` 的 `scripts` | 自定义命令 |

**两个不一样的地方：**

1. **`composer init` 会多问一项 PSR-4 autoload 映射**，yarn 里没有对应物 ——
   PHP 没有 Node 那套路径解析，类的自动加载就靠这个映射（本项目是 `Zack\PhpUniversalUsage\` → `src/`）。
   它还会从包名自动推命名空间：`--name=zack/demo --autoload=src/` 直接生成 `"Zack\\Demo\\": "src/"`，
   并顺手把 `vendor/autoload.php` 生成出来。

2. **`composer init` 只管生成清单文件，不管项目结构** ——
   `init` + `require laravel/laravel` 造不出 Laravel 项目（没有 `artisan`、没有 `public/index.php`），
   必须用 `create-project`，详见 `CREATE_PROJECT.md`。
   对应到 JS：`yarn init` + `yarn add react` 也造不出 `create-react-app` 那套目录。

> ⚠️ **实测坑**：目录里已有 `composer.json` 时跑 `composer init` 会**直接重写它**，
> 非交互模式（`-n`）和交互模式都拦不住（向导不会提示"已存在，是否更新"）。别在项目根目录随手跑。

### 最佳实践
- 使用composer进行依赖管理
- 遵循PSR编码标准
- 利用命名空间避免冲突
- 合理使用异常处理

## 🤝 贡献

欢迎提交Issue和Pull Request来改进这个项目！

## 📄 许可证

本项目采用MIT许可证，详情请查看LICENSE文件。

---

**注意**: 这是一个学习演示项目，生产环境使用请根据实际需求进行修改和优化。
