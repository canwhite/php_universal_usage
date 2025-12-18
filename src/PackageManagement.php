<?php

namespace Zack\PhpUniversalUsage;

class PackageManagement
{
    public function demonstrateComposerInfo()
    {
        echo "=== Composer包管理演示 ===" . PHP_EOL;

        // 读取composer.json
        $composerPath = __DIR__ . '/../composer.json';
        if (file_exists($composerPath)) {
            $composerConfig = json_decode(file_get_contents($composerPath), true);

            echo "项目信息:" . PHP_EOL;
            echo "名称: " . ($composerConfig['name'] ?? '未设置') . PHP_EOL;
            echo "类型: " . ($composerConfig['type'] ?? '未设置') . PHP_EOL;
            echo "描述: " . ($composerConfig['description'] ?? '未设置') . PHP_EOL;
            echo "版本: " . ($composerConfig['version'] ?? '未设置') . PHP_EOL;
        }

        // 检查vendor目录
        $vendorPath = __DIR__ . '/../vendor';
        if (is_dir($vendorPath)) {
            echo PHP_EOL . "已安装的包:" . PHP_EOL;

            // 读取installed.json获取已安装的包信息
            $installedJson = $vendorPath . '/composer/installed.json';
            if (file_exists($installedJson)) {
                $installed = json_decode(file_get_contents($installedJson), true);

                if (isset($installed['packages'])) {
                    // Composer 2.x 格式
                    $packages = $installed['packages'];
                } else {
                    // Composer 1.x 格式
                    $packages = $installed;
                }

                foreach ($packages as $package) {
                    $name = $package['name'] ?? '未知';
                    $version = $package['version'] ?? '未知';
                    $description = $package['description'] ?? '';
                    echo "- $name: $version" . ($description ? " - $description" : "") . PHP_EOL;
                }
            }
        } else {
            echo "vendor目录不存在，请运行 'composer install'" . PHP_EOL;
        }
    }

    public function demonstrateAutoloading()
    {
        echo PHP_EOL . "=== 自动加载演示 ===" . PHP_EOL;

        // 检查自动加载文件
        $autoloadPath = __DIR__ . '/../vendor/autoload.php';
        if (file_exists($autoloadPath)) {
            echo "自动加载文件存在: " . $autoloadPath . PHP_EOL;

            // 演示类自动加载
            echo "当前类的命名空间: " . __NAMESPACE__ . PHP_EOL;
            echo "当前类名: " . __CLASS__ . PHP_EOL;

            // 演示已注册的自动加载器
            $autoloaders = spl_autoload_functions();
            echo "已注册的自动加载器数量: " . count($autoloaders) . PHP_EOL;

        } else {
            echo "自动加载文件不存在，请运行 'composer install'" . PHP_EOL;
        }
    }

    public function showUsefulComposerCommands()
    {
        echo PHP_EOL . "=== 常用Composer命令 ===" . PHP_EOL;

        $commands = [
            'composer init' => '创建新的composer.json文件',
            'composer install' => '安装composer.json中定义的依赖',
            'composer update' => '更新依赖包到最新版本',
            'composer require 包名' => '添加新的依赖包',
            'composer remove 包名' => '移除依赖包',
            'composer search 关键词' => '搜索包',
            'composer show 包名' => '显示包的详细信息',
            'composer show --installed' => '显示所有已安装的包',
            'composer outdated' => '显示可更新的包',
            'composer validate' => '验证composer.json文件',
            'composer dump-autoload' => '重新生成自动加载文件',
            'composer run-script' => '运行自定义脚本',
            'composer licenses' => '显示所有依赖的许可证信息'
        ];

        foreach ($commands as $command => $description) {
            echo sprintf("%-25s - %s", $command, $description) . PHP_EOL;
        }
    }

    public function demonstrateCommonPackages()
    {
        echo PHP_EOL . "=== 常用PHP包推荐 ===" . PHP_EOL;

        $packages = [
            // HTTP客户端
            'guzzlehttp/guzzle' => '强大的HTTP客户端，支持异步请求',

            // 数据库
            'illuminate/database' => 'Laravel的数据库ORM组件',
            'doctrine/orm' => '功能强大的对象关系映射器',

            // 日志
            'monolog/monolog' => '功能完善的日志库',

            // 缓存
            'predis/predis' => 'Redis PHP客户端',

            // 验证
            'respect/validation' => '强大的验证库',

            // 模板引擎
            'twig/twig' => '现代PHP模板引擎',

            // 测试
            'phpunit/phpunit' => 'PHP单元测试框架',

            // 队列
            'enqueue/enqueue' => '消息队列库',

            // 图片处理
            'intervention/image' => '图片处理库',

            // 文件处理
            'symfony/filesystem' => '文件系统操作库',

            // 配置管理
            'vlucas/phpdotenv' => '环境变量管理',

            // 时间处理
            'nesbot/carbon' => '强大的时间日期处理库',

            // JSON Schema验证
            'justinrainbow/json-schema' => 'JSON Schema验证器'
        ];

        foreach ($packages as $package => $description) {
            echo sprintf("%-35s - %s", $package, $description) . PHP_EOL;
        }
    }

    public function runAll()
    {
        $this->demonstrateComposerInfo();
        $this->demonstrateAutoloading();
        $this->showUsefulComposerCommands();
        $this->demonstrateCommonPackages();
    }
}