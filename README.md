# supports
各类基础操作支持，HttpClient，Config 存取操作，Logs...

# 安装方法
```
composer require wannanbigpig/supports
```

# 使用说明
未做说明处请参考源码使用
```php
// 测试日志
public function log()
{
    $log = new log([
       'driver' => 'single',
       'level' => 'info',
       'format' => "%datetime% > %channel% [ %level_name% ] > %message% %context% %extra%\r\n\n",
       'path' => '/tmp/wannanbigpig.alipay.log',
    ]);
    $log->info('测试日志');
}

// 测试HttpClient
class foo {
    use HttpRequest;
    
    public function query()
    {
         echo $this->request('POST','http://api.juheapi.com/japi/toh', [
            'key'   => '********',
            'v'     => '1.0',
            'month' => '3',
            'day'   => '28',
        ]);
    }
}

// 测试Config,支持用点获取多维数组，接口 IteratorAggregate, ArrayAccess, Serializable, Countable
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
