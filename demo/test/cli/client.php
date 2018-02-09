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


use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\TBufferedTransport;
use Thrift\Exception\TException;

try {
    $socket = new TSocket('127.0.0.1', 9988);
    $transport = new TBufferedTransport($socket, 1024, 1024);
    $protocol = new TBinaryProtocol($transport);
    $client = new \Test\helloClient($protocol);
    $transport->open();

    $result = $client->getImageInfo('http://www.image.com/files/8813/5551/7470/cruise-ship.png');
    $result = unserialize($result);
    var_dump($result);
    echo "\n";

    $transport->close();
} catch (TException $ex) {
    print 'TException: ' . $ex->getMessage() . "\n";
}