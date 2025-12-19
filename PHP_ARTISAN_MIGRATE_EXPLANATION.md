# php artisan migrate 命令完全解析

## 🔍 核心概念

### `php artisan migrate` 的作用
- **创建数据库表结构**（不插入数据）
- **修改数据库表结构**（添加/删除/修改字段）
- **管理数据库架构**（索引、约束、外键等）
- **版本控制数据库设计**（追踪结构变化）

### migrate 是表字段编辑工具
`migrate` 是 Laravel 中**对表字段和表结构进行完整编辑**的工具，包括：

#### ✅ migrate 能做的表结构操作：
- **创建新表**：`Schema::create()`
- **添加字段**：`$table->string('email')`
- **删除字段**：`$table->dropColumn('phone')`
- **修改字段属性**：`$table->string('name', 100)->change()`
- **重命名字段**：`$table->renameColumn('old', 'new')`
- **添加索引**：`$table->index('email')`
- **添加外键约束**：`$table->foreign('user_id')->references('id')->on('users')`
- **删除表**：`Schema::dropIfExists('posts')`

#### ❌ migrate 不能做的操作：
- **插入数据**（这是 db:seed 的工作）
- **更新数据**（这是业务代码的工作）
- **查询数据**（这是业务代码的工作）

## 🎯 migrate vs seed 的区别

| 操作 | 命令 | 作用 | 结果 |
|------|------|------|------|
| **建表结构** | `php artisan migrate` | 创建/修改表 | 空表 |
| **插入数据** | `php artisan db:seed` | 填充数据 | 有数据的表 |

## 📁 Laravel 数据库管理文件结构

```
database/
├── migrations/          # 迁移文件（表结构定义）
│   ├── 2014_10_12_000000_create_users_table.php
│   ├── 2014_10_12_100000_create_password_resets_table.php
│   └── 2024_01_15_create_posts_table.php
├── seeders/            # 数据填充文件（数据插入）
│   ├── DatabaseSeeder.php
│   ├── UserSeeder.php
│   └── PostSeeder.php
└── factories/          # 数据工厂（批量生成假数据）
    ├── UserFactory.php
    └── PostFactory.php
```

## 🛠️ 完整的开发流程

### 第一阶段：创建表结构（migrate）

#### 1. 创建模型和迁移文件（推荐方式）
```bash
# 同时创建模型和迁移文件
php artisan make:model Post -m

# 这个命令会创建两个文件：
# 1. 模型文件：app/Models/Post.php
# 2. 迁移文件：database/migrations/2024_01_15_123456_create_posts_table.php
```

#### 或单独创建迁移文件
```bash
# 只创建迁移文件
php artisan make:migration create_posts_table
```

#### 2. 查看创建的文件

**`php artisan make:model Post -m` 会自动创建两个文件：**

##### 模型文件 `app/Models/Post.php`：
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
}
```

##### 迁移文件 `database/migrations/2024_01_15_123456_create_posts_table.php`：
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            // 🔑 这里需要你手动添加字段
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
```

#### 3. 编辑迁移文件，添加表字段
```php
<?php
// database/migrations/2024_01_15_123456_create_posts_table.php

public function up()
{
    Schema::create('posts', function (Blueprint $table) {
        $table->id();                           // id 主键
        $table->string('title');                // 标题字段
        $table->text('content');                // 内容字段
        $table->foreignId('user_id')            // 外键字段
              ->constrained('users')            // 关联 users 表
              ->onDelete('cascade');            // 级联删除
        $table->string('status')->default('draft');  // 状态字段，默认 draft
        $table->timestamps();                   // created_at, updated_at
    });
}

public function down()
{
    Schema::dropIfExists('posts');  // 回滚时删除表
}
```

#### 4. 编辑模型文件，添加业务逻辑（可选）
```php
<?php
// app/Models/Post.php

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'status', 'user_id'];

    // 关联关系
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 作用域
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
```

#### 5. 执行迁移（创建空表）
```bash
php artisan migrate

# 输出结果：
Migrating: 2024_01_15_123456_create_posts_table
Migrated:  2024_01_15_123456_create_posts_table
```

#### 6. 检查数据库结果
```sql
-- 执行后的 SQL 语句
CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `posts_user_id_foreign` (`user_id`),
  CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
);

-- 表是空的，只有结构
SELECT * FROM posts;  -- 返回 0 行数据
```

### 第二阶段：插入数据（seed）

#### 1. 创建填充器
```bash
php artisan make:seeder PostSeeder
```

#### 2. 编写填充器文件
```php
<?php
// database/seeders/PostSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;

class PostSeeder extends Seeder
{
    public function run()
    {
        // 获取第一个用户
        $user = User::first();

        // 插入测试数据
        Post::create([
            'title' => '我的第一篇文章',
            'content' => '这是文章的内容...',
            'user_id' => $user->id,
            'status' => 'published'
        ]);

        Post::create([
            'title' => 'Laravel 迁移教程',
            'content' => '这篇教程讲解 migrate 和 seed 的区别...',
            'user_id' => $user->id,
            'status' => 'published'
        ]);
    }
}
```

#### 3. 执行数据填充
```bash
# 运行特定填充器
php artisan db:seed --class=PostSeeder

# 输出结果：
Seeding: Database\Seeders\PostSeeder
```

#### 4. 检查数据库结果
```sql
-- 现在表里有数据了
SELECT * FROM posts;

-- 输出结果：
+----+--------------------------+--------------------------------+---------+-----------+---------------------+---------------------+
| id | title                    | content                        | user_id | status    | created_at          | updated_at          |
+----+--------------------------+--------------------------------+---------+-----------+---------------------+---------------------+
|  1 | 我的第一篇文章            | 这是文章的内容...               |       1 | published | 2024-01-15 10:30:00 | 2024-01-15 10:30:00 |
|  2 | Laravel 迁移教程         | 这篇教程讲解 migrate 和 seed...|       1 | published | 2024-01-15 10:30:01 | 2024-01-15 10:30:01 |
+----+--------------------------+--------------------------------+---------+-----------+---------------------+---------------------+
```

## 🔧 migrate 相关命令详解

### 基础迁移命令
```bash
# 运行所有未执行的迁移
php artisan migrate

# 查看迁移状态
php artisan migrate:status
```

**输出示例：**
```
+------+------------------------------------------------+--------+
| Ran? | Migration                                      | Batch  |
+------+------------------------------------------------+--------+
| Yes  | 2014_10_12_000000_create_users_table           | 1      |
| Yes  | 2014_10_12_100000_create_password_resets_table | 1      |
| No   | 2024_01_15_create_posts_table                  |        |
+------+------------------------------------------------+--------+
```

### 回滚和重置命令
```bash
# 回滚最后一次迁移（撤销一个表）
php artisan migrate:rollback

# 回滚指定数量的迁移
php artisan migrate:rollback --step=3

# 回滚所有迁移
php artisan migrate:reset

# 删除所有表并重新运行所有迁移
php artisan migrate:fresh

# 删除所有表、重新运行迁移、然后填充数据
php artisan migrate:fresh --seed
```

## 🎨 迁移文件常用字段类型

### 基础字段类型
```php
// 主键
$table->id();                    // 等同于 bigInteger('id')->unsigned()->primary()
$table->uuid('id');             // UUID 主键

// 字符串字段
$table->string('name');         // VARCHAR(255)
$table->string('email', 100);   // VARCHAR(100)
$table->char('code', 4);        // CHAR(4)

// 文本字段
$table->text('content');        // TEXT
$table->longText('description'); // LONGTEXT

// 数字字段
$table->integer('age');         // INTEGER
$table->bigInteger('views');    // BIGINT
$table->decimal('price', 8, 2); // DECIMAL(8,2)
$table->float('rating');        // FLOAT

// 日期时间字段
$table->date('birthday');       // DATE
$table->dateTime('published_at'); // DATETIME
$table->timestamp('created_at'); // TIMESTAMP
$table->timestamps();           // created_at, updated_at
```

### 特殊字段类型
```php
// 布尔值
$table->boolean('is_active');   // BOOLEAN/TINYINT(1)

// JSON 字段
$table->json('metadata');       // JSON
$table->jsonb('settings');      // JSONB (PostgreSQL)

// 枚举字段
$table->enum('status', ['draft', 'published', 'archived']);

// 外键
$table->foreignId('user_id')->constrained();
$table->foreignId('category_id')->nullable()->constrained();

// 软删除
$table->softDeletes();          // deleted_at
```

### 字段修饰符
```php
// 默认值
$table->string('status')->default('active');

// 允许 NULL
$table->string('middle_name')->nullable();

// 唯一约束
$table->string('email')->unique();

// 注释
$table->string('name')->comment('用户姓名');

// 在某个字段后添加
$table->string('last_name')->after('first_name');

// 索引
$table->index('email');         // 普通索引
$table->unique('email');        // 唯一索引
$table->spatialIndex('location'); // 空间索引
```

## 🔍 填充器数据来源详解

### 填充器的数据从哪里来？

#### 1. **系统初始数据**（固定的、必需的）
```php
<?php
// database/seeders/RoleSeeder.php

class RoleSeeder extends Seeder
{
    public function run()
    {
        // 系统必需的角色数据（不能随机）
        DB::table('roles')->insert([
            ['name' => 'admin', 'description' => '管理员', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'user', 'description' => '普通用户', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'guest', 'description' => '访客', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
```

#### 2. **Factory 生成的假数据**（随机的测试数据）
```php
<?php
// database/seeders/UserSeeder.php

use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 使用 Factory 生成随机测试用户
        User::factory()->count(50)->create();

        // 或者混合使用
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);
    }
}
```

#### 3. **有意义的演示数据**（既不是系统必需，也不是纯随机）
```php
<?php
// database/seeders/DemoDataSeeder.php

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // 创建有意义的演示用户
        $demoUsers = [
            [
                'name' => '张三',
                'email' => 'zhangsan@example.com',
                'bio' => '这是一个演示用户，用于展示系统功能',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => '李四',
                'email' => 'lisi@example.com',
                'bio' => '另一个演示用户，展示各种功能',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($demoUsers as $user) {
            User::create($user);
        }
    }
}
```

### Factory（工厂）的工作原理

#### 创建 Factory 文件
```bash
php artisan make:factory UserFactory --model=User
```

#### Factory 文件内容
```php
<?php
// database/factories/UserFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => fake()->name(),                    // 随机姓名：'John Doe', 'Jane Smith'
            'email' => fake()->unique()->safeEmail(),    // 随机唯一邮箱
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }
}
```

#### Faker 提供的假数据类型
```php
// Faker 可以生成各种类型的假数据
'name'                    // 随机姓名：'John Doe', 'Jane Smith'
'email'                   // 随机邮箱：'john@example.com', 'jane@gmail.com'
'phoneNumber'             // 随机电话：'+1234567890'
'address'                 // 随机地址：'123 Main St, City, State'
'text'                    // 随机文本段落
'sentence'                // 随机句子
'paragraph'               // 随机段落
'numberBetween(1, 100)'   // 1-100 的随机数字
'dateTimeBetween($startDate, $endDate)'  // 随机日期时间
'imageUrl(640, 480)'      // 随机图片 URL
'company'                 // 随机公司名
'jobTitle'                // 随机职位名
'randomElement($array)'   // 从数组中随机选择元素
'sentence($nbWords = 6)'  // 随机生成句子
'paragraph($nbSentences = 3)' // 随机生成段落
```

### 数据来源对比总结

| 数据类型 | 来源 | 用途 | 示例 |
|---------|------|------|------|
| **系统初始数据** | 硬编码 | 系统必需数据 | 角色权限、基础配置 |
| **测试数据** | Factory + Faker | 开发测试 | 随机用户、测试文章 |
| **演示数据** | 有意义的手动数据 | 功能演示 | 演示账号、示例内容 |

### 不同环境的填充策略

#### 开发环境
```php
<?php
// database/seeders/DatabaseSeeder.php

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 所有环境都需要的基础数据
        $this->call([
            RoleSeeder::class,           // 系统角色
            PermissionSeeder::class,     // 权限配置
        ]);

        // 只在开发环境填充测试数据
        if (app()->environment(['local', 'testing'])) {
            $this->call([
                UserSeeder::class,       // 50个测试用户
                PostSeeder::class,       // 100篇测试文章
                CommentSeeder::class,    // 500条测试评论
            ]);
        }

        // 演示数据
        $this->call([
            DemoDataSeeder::class,       // 演示账号
        ]);
    }
}
```

#### 生产环境部署
```bash
# 1. 只建表结构
php artisan migrate

# 2. 只填充系统必需数据（不填充测试数据）
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=PermissionSeeder
# 不运行 UserSeeder（避免创建测试用户）
```

## 🎭 数据填充高级用法

### 使用工厂批量生成数据
```php
<?php
// database/factories/PostFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(5),
            'user_id' => User::factory(),  // 关联用户工厂
            'status' => $this->faker->randomElement(['draft', 'published']),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}

// 在 seeder 中使用工厂
public function run()
{
    // 创建 50 个文章
    Post::factory()->count(50)->create();

    // 创建 10 个已发布的文章
    Post::factory()->published()->count(10)->create();
}
```

### 关联数据填充
```php
<?php
// database/seeders/BlogSeeder.php

public function run()
{
    // 创建用户
    $users = User::factory()->count(10)->create();

    // 为每个用户创建文章
    $users->each(function ($user) {
        Post::factory()->count(5)->create([
            'user_id' => $user->id
        ]);

        // 为每篇文章创建评论
        $user->posts()->each(function ($post) {
            Comment::factory()->count(3)->create([
                'post_id' => $post->id
            ]);
        });
    });
}
```

## 🛡️ 最佳实践

### 1. 迁移命名规范
```bash
# 好的命名
php artisan make:migration create_posts_table
php artisan make:migration add_status_to_posts_table
php artisan make:migration drop_soft_deletes_from_posts_table

# 避免的命名
php artisan make:migration create_table_for_posts
php artisan make:migration post_migration
```

### 2. 迁移文件组织
```php
// database/migrations/2024_01_15_123456_create_posts_table.php
public function up()
{
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('content');
        $table->timestamps();
    });
}

// database/migrations/2024_01_16_123456_add_status_to_posts_table.php
public function up()
{
    Schema::table('posts', function (Blueprint $table) {
        $table->string('status')->default('draft')->after('content');
    });
}

// database/migrations/2024_01_17_123456_add_soft_deletes_to_posts_table.php
public function up()
{
    Schema::table('posts', function (Blueprint $table) {
        $table->softDeletes();
    });
}
```

### 3. 生产环境部署
```bash
# 生产环境部署步骤
1. git pull origin main
2. composer install --no-dev
3. php artisan migrate
4. php artisan config:cache
5. php artisan route:cache
```

## 🚀 常见问题解决

### 1. 迁移失败处理
```bash
# 查看详细错误信息
php artisan migrate --force

# 强制执行迁移（生产环境慎用）
php artisan migrate --force

# 回滚到安全状态
php artisan migrate:rollback
```

### 2. 外键约束问题
```php
// 正确的关联顺序
public function up()
{
    // 1. 先创建主表
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });

    // 2. 再创建从表
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->foreignId('user_id')->constrained();  // 引用 users 表
        $table->timestamps();
    });
}
```

### 3. 长字段和索引限制
```php
// MySQL 索引长度限制处理
Schema::table('users', function (Blueprint $table) {
    $table->string('name', 191);  // 限制长度以适应索引
    $table->string('email', 191)->unique();
});
```

## 📊 总结

### migrate 作为表字段编辑工具的完整功能

#### ✅ migrate 能做的一切表结构操作：
- **创建新表**：`Schema::create('table_name', ...)`
- **添加字段**：`$table->string('field_name')`
- **删除字段**：`$table->dropColumn('field_name')`
- **修改字段属性**：`$table->string('field_name', 100)->change()`
- **重命名字段**：`$table->renameColumn('old_name', 'new_name')`
- **添加索引**：`$table->index('field_name')`
- **添加唯一约束**：`$table->unique('field_name')`
- **添加外键约束**：`$table->foreign('field_id')->references('id')->on('related_table')`
- **添加默认值**：`$table->string('status')->default('active')`
- **设置字段可为空**：`$table->string('middle_name')->nullable()`
- **删除表**：`Schema::dropIfExists('table_name')`

#### 🛠️ `make:model -m` 的优势：
- **一步到位**：同时创建模型和迁移文件
- **开发效率高**：减少重复操作
- **标准化流程**：符合 Laravel 最佳实践

#### ❌ migrate 绝对不做的操作：
- **插入数据** → 使用 `db:seed`
- **更新数据** → 使用业务代码
- **查询数据** → 使用业务代码
- **数据处理逻辑** → 使用业务代码

### 完整的开发流程：
```
1. php artisan make:model Post -m     # 创建模型和迁移文件
2. 编辑迁移文件，定义表字段和结构
3. 编辑模型文件，定义业务逻辑（可选）
4. php artisan migrate               # 执行表结构创建/修改
5. php artisan db:seed --class=PostSeeder  # 插入测试数据（可选）
6. 在业务代码中操作数据
```

### 关键记忆点：
- **migrate = 数据库建筑师** → 设计和建造表结构
- **seed = 数据管理员** → 管理初始和测试数据
- **业务代码 = 数据使用者** → 日常数据操作

### 环境策略总结：
| 环境 | migrate | db:seed | 用途 |
|------|---------|---------|------|
| **开发环境** | ✅ | ✅ | 表结构 + 大量测试数据 |
| **测试环境** | ✅ | ✅ | 表结构 + 基础测试数据 |
| **生产环境** | ✅ | ✅ | 表结构 + 必需系统数据 |

**这样就能清晰理解 migrate 作为 Laravel 数据库表字段编辑工具的完整功能和使用方法了！**