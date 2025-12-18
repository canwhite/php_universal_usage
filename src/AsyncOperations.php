<?php

//类似于其他语言的package，  Zack\ 是 供应商命名空间（Vendor Namespace），类似品牌前缀。
namespace Zack\PhpUniversalUsage;

class AsyncOperations
{
    // PHP中的"异步"概念和JavaScript不同
    // PHP主要是同步执行，但可以通过多种方式实现并发

    // 1. 使用多进程 (pcntl扩展)
    public function demonstrateMultiProcess()
    {
        echo "=== 多进程演示 ===" . PHP_EOL;

        // 检查pcntl扩展是否可用
        if (!function_exists('pcntl_fork')) {
            echo "pcntl扩展不可用，跳过多进程演示" . PHP_EOL;
            return;
        }

        $processes = [];
        $maxProcesses = 3;

        for ($i = 1; $i <= $maxProcesses; $i++) {
            $pid = pcntl_fork();

            if ($pid == -1) {
                // 创建进程失败
                die("无法创建子进程");
            } elseif ($pid) {
                // 父进程
                $processes[] = $pid;
            } else {
                // 子进程
                $this->doBackgroundWork($i);
                exit(0);
            }
        }

        // 等待所有子进程完成
        foreach ($processes as $pid) {
            pcntl_waitpid($pid, $status);
        }

        echo "所有子进程已完成" . PHP_EOL;
    }

    private function doBackgroundWork($workerId)
    {
        $sleepTime = rand(1, 3);
        echo "工作进程 $workerId 开始工作，睡眠 {$sleepTime} 秒" . PHP_EOL;
        sleep($sleepTime);
        echo "工作进程 $workerId 完成" . PHP_EOL;
    }

    // 2. 使用多线程 (pthreads扩展，PHP 7.2+不再维护)
    // 3. 使用ReactPHP或Swoole等异步框架

    // 4. 模拟异步操作的简单方法
    public function simulateAsyncOperations()
    {
        echo "=== 模拟异步操作 ===" . PHP_EOL;

        $start = microtime(true);

        // 模拟多个独立的耗时操作
        $tasks = [
            'task1' => $this->simulateApiCall('用户服务', 2),
            'task2' => $this->simulateApiCall('订单服务', 1),
            'task3' => $this->simulateApiCall('支付服务', 1.5)
        ];

        $end = microtime(true);
        echo "总耗时: " . round($end - $start, 2) . " 秒" . PHP_EOL;

        return $tasks;
    }

    private function simulateApiCall($serviceName, $seconds)
    {
        echo "调用 $serviceName..." . PHP_EOL;
        // 模拟网络请求延迟
        sleep($seconds);
        echo "$serviceName 响应完成" . PHP_EOL;
        return "$serviceName 结果";
    }

    // 5. 使用Generator实现类似异步的模式
    public function demonstrateGenerators()
    {
        echo "=== Generator演示 ===" . PHP_EOL;

        $generator = $this->processItems();

        foreach ($generator as $item) {
            echo "处理结果: $item" . PHP_EOL;
        }
    }

    private function processItems()
    {
        $items = ['数据1', '数据2', '数据3', '数据4', '数据5'];

        foreach ($items as $item) {
            // 模拟处理时间
            usleep(500000); // 0.5秒
            yield "已处理: $item";
        }
    }

    // 6. Promise风格的实现（简化版）
    public function demonstratePromiseLike()
    {
        echo "=== Promise风格演示 ===" . PHP_EOL;

        $promise = $this->asyncOperation()
            ->then(function($result) {
                echo "成功: $result" . PHP_EOL;
                return $result . " (已处理)";
            })
            ->catch(function($error) {
                echo "错误: $error" . PHP_EOL;
            });

        echo "异步操作已启动..." . PHP_EOL;
    }

    private function asyncOperation()
    {
        // 简化的Promise实现
        return new class {
            private $successCallback;
            private $errorCallback;

            public function then($callback) {
                $this->successCallback = $callback;
                return $this;
            }

            public function catch($callback) {
                $this->errorCallback = $callback;

                // 模拟异步操作
                if (rand(0, 1)) {
                    $result = "操作成功";
                    if ($this->successCallback) {
                        call_user_func($this->successCallback, $result);
                    }
                } else {
                    $error = "操作失败";
                    if ($this->errorCallback) {
                        call_user_func($this->errorCallback, $error);
                    }
                }
                return $this;
            }
        };
    }

    public function runAll()
    {
        echo "=== 异步操作演示开始 ===" . PHP_EOL . PHP_EOL;

        $this->simulateAsyncOperations();
        echo PHP_EOL;

        $this->demonstrateGenerators();
        echo PHP_EOL;

        $this->demonstratePromiseLike();
        echo PHP_EOL;

        $this->demonstrateMultiProcess();

        echo PHP_EOL . "=== 异步操作演示完成 ===" . PHP_EOL;
    }
}