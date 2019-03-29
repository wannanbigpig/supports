# supports
各类基础操作支持，HttpClient，Config 存取操作，Logs...

# 安装方法
```
composer require wannanbigpig/supports
```

# 使用说明
```php
// public static function createLogger($file = NULL, $identify = 'wannanbigpig.supports', $level = Logger::DEBUG, $type = 'daily', $max_files = 30) 方法配置日志路径等

// 测试日志
public function log()
{
    Log::info('测试日志');
}

// 测试HttpClient
public function query()
{
    echo $this->get('http://api.juheapi.com/japi/toh', [
        'key'   => '********',
        'v'     => '1.0',
        'month' => '3',
        'day'   => '28',
    ]);
}

// 测试Config,支持用点获取多维数组，接口 ArrayAccess, IteratorAggregate, Countable
public function Config()
{
    $config = new Config([
        'aaa' => 'sfa',
        'bbb' => 'bbb'
    ]);
    $config->set('a.b',['ads','dsds']);
    print_r($config->get('ccc'));
    print_r($config->get());
}
```
