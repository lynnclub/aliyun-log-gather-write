## 阿里云日志集中写入

### 开发原因

阿里云日志在单条日志频繁写入时，可能会出现QPS限制。因此，开发了一个日志填写与传输分离的工具包，减少请求次数。

### 特性

1. 日志填写与传输分离；
2. 单条日志可追加字段；
3. 提供了日志单例类；

### 使用方式

安装工具包

```bash
composer require lynncho/aliyun-log-gather-write
```

代码示例

```php
use Lynncho\Aliyunlog\GatherWrite\Logger;
use Lynncho\Aliyunlog\GatherWrite\LoggerSingle;
use Psr\Log\LogLevel;

$config = [
    'endPoint' => '',
    'accessId' => '',
    'accessKey' => '',
    'project' => '',
    'logStore' => ''
];

$logger = new Logger($config);
$logger->log(LogLevel::DEBUG, 'log info', ['log info' => ['test']]);
$logger->info('info', ['info' => ['test']]);

$logger->error('error', ['info' => ['error']]);
$logger->additionFields(['TestField' => 123]);

LoggerSingle::setLogger($logger);
LoggerSingle::info('LoggerSingle info', ['info' => ['test']]);

$logger->push();

LoggerSingle::init($config);
LoggerSingle::error('LoggerSingle error', ['info' => ['test']]);
LoggerSingle::push();
```

### 使用建议

1. 如果存在容器，建议将`Lynncho\Aliyunlog\GatherWrite\Logger`实例放在容器中，以便实现单例效果；
2. 如果没有容器，建议使用`Lynncho\Aliyunlog\GatherWrite\LoggerSingle`门面静态类；