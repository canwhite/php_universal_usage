<?php

require_once 'vendor/autoload.php';

//它竟然用的也是use，很有趣的方式，另外竟然是反斜杠引入的路径
// src/ 下的文件名按学习顺序加了 A_ ~ F_ 前缀，类名跟着一起改
// （类名不能以数字开头，用字母才能继续走 PSR-4 自动加载）
use Zack\PhpUniversalUsage\A_HelloWorld;
use Zack\PhpUniversalUsage\B_TypeTesting;
use Zack\PhpUniversalUsage\C_OopBasics;
use Zack\PhpUniversalUsage\D_HttpClient;
use Zack\PhpUniversalUsage\E_AsyncOperations;
use Zack\PhpUniversalUsage\F_PackageManagement;

echo "🚀 PHP语法和功能演示" . PHP_EOL;
echo "====================" . PHP_EOL . PHP_EOL;

// A. 基础语法演示（src/A_HelloWorld.php）
$helloWorld = new A_HelloWorld();
$helloWorld->run();

echo PHP_EOL . PHP_EOL;

// B. 类型测试演示（src/B_TypeTesting.php）
$typeTesting = new B_TypeTesting();
$typeTesting->demonstrateTypeTesting();

echo PHP_EOL;

// C. 面向对象：封装 / 继承 / 多态（src/C_OopBasics.php）
$oopBasics = new C_OopBasics();
$oopBasics->run();

echo PHP_EOL;

// D. HTTP请求演示（src/D_HttpClient.php）
$httpClient = new D_HttpClient();
$httpClient->demonstrateApiCalls();

// E. 异步操作演示（src/E_AsyncOperations.php）
$asyncOps = new E_AsyncOperations();
$asyncOps->runAll();

echo PHP_EOL;

// F. 包管理演示（src/F_PackageManagement.php）
$packageManager = new F_PackageManagement();
$packageManager->runAll();

echo PHP_EOL . PHP_EOL . "✨ 演示完成！" . PHP_EOL;
