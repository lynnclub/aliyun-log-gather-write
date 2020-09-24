## 阿里云日志集中写入

### 开发原因

阿里云日志在单条日志频繁写入时，可能会出现QPS限制。因此，开发了一个日志填写与传输分离的工具包，减少请求次数。

### 特性

1. 日志填写与传输分离；
2. 单条日志可追加字段；
3. 提供了日志单例类；
4. 支持按《PSR-3: Logger Interface》日志规范的方法使用。

### 使用方式

#### 安装工具包

```bash
composer require lynncho/aliyun-log-gather-write
```

#### 工作流程

1. 使用阿里云日志配置初始化
2. 填写日志，收集在日志实例中
3. 集中传输日志，传输将清空累积的日志

注：请留意日志传输完成之后，是否还有日志需要传输？否则存在日志丢失的风险。比如后续的框架运行日志、报错日志。

#### 代码示例

日志实例初始化

```php
use Lynncho\Aliyunlog\GatherWrite\Logger;
use Lynncho\Aliyunlog\GatherWrite\LoggerSingle;

$config = [
    'endPoint' => '',
    'accessId' => '',
    'accessKey' => '',
    'project' => '',
    'logStore' => ''
];

$logger = new Logger($config);

//或者，使用静态单例初始化日志实例
LoggerSingle::init($config);

//或者，使用静态单例嵌套日志实例
LoggerSingle::setLogger($logger);
```

使用PSR日志标准方法填写日志

```php
use Psr\Log\LogLevel;

$logger->log(LogLevel::DEBUG, 'log info', ['log info' => ['test']]);
$logger->info('hello world', ['info' => ['test']]);
$logger->error('has error', ['info' => ['error']]);

//静态单例方法，效果一致
LoggerSingle::info('LoggerSingle info', ['info' => ['test']]);
LoggerSingle::error('LoggerSingle error', ['info' => ['test']]);
```

自定义单条日志字段

```php
//日志默认拥有Message、Level、Date、Content字段，可对最新一条日志追加自定义字段。自定义字段优先。
$logger->addLogItemFields(['TestField' => 123]);

//自定义一条新日志
$logger->addLogItemFields(['NewLogTestField' => 1234], true);
```

推送日志

```php
//传输操作将清空累积的日志
$logger->push();

//或者，使用静态单例推送
LoggerSingle::push();
```

### 使用建议

1. 如果存在容器，建议将`Lynncho\Aliyunlog\GatherWrite\Logger`实例放在容器中，以便实现单例效果；
2. 如果没有容器，建议使用`Lynncho\Aliyunlog\GatherWrite\LoggerSingle`门面静态类；