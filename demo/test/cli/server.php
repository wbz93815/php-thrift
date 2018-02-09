<?php
namespace test;

/**
 * @name
 * @author baozhou.wang
 * @date 2018/2/9 17:48
 */
error_reporting(E_ALL);

$projectPath = dirname(__FILE__) . '/../';
$idlPath = $projectPath . 'idl';
require_once $idlPath . '/lib/Thrift/ClassLoader/ThriftClassLoader.php';

use Thrift\ClassLoader\ThriftClassLoader;

$genPath = $projectPath . 'gen-php';

$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', $idlPath . '/lib');
$loader->registerDefinition('Test', $genPath);
$loader->register();

use Thrift\Factory\TBinaryProtocolFactory;
use Thrift\Factory\TTransportFactory;
use Thrift\Server\TServerSocket;
use Thrift\Server\TSimpleServer;

class HelloHandler implements \Test\helloIf
{
    protected $log = [];
    public function getImageInfo($url)
    {
        echo "{$url}\r\n";
        return serialize(pathinfo($url));
    }
};

header('Content-Type', 'application/x-thrift');
if (php_sapi_name() == 'cli') {
    echo "\r\n";
}

$handler = new \Test\HelloHandler();
$processor = new \Test\helloProcessor($handler);

$transport = new TServerSocket('127.0.0.1', 9988);
$protocolFactory = new TBinaryProtocolFactory();
$transportFactory = new TTransportFactory();
$tServer = new TSimpleServer($processor, $transport, $transportFactory, $transportFactory, $protocolFactory, $protocolFactory);
$tServer->serve();