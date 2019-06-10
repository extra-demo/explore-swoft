<?php

use Minbaby\SwoftClient\JsonPacket;
use Minbaby\SwoftClient\Packet;
use Minbaby\SwoftClient\PacketInterface;
use Minbaby\SwoftClient\Protocol;

require __DIR__ . '/vendor/autoload.php';


$start = microtime(true);

class Proxy {
    /**
     * @var float
     */
    protected $version;
    private $packet;
    private $class;
    private $ext = [];

    public function __construct($class, $ext = [], $version = '1.0')
    {
        $this->class = $class;
        $this->packet = new Packet();
        $this->ext = $ext;
        $this->version = $version;
    }


    public function __call($name, $arguments)
    {
        $protocol = new Protocol(
            $this->class,
            $name,
            $arguments,
            $this->ext,
            $this->version
        );
        return $this->CallRemoteFuc($protocol, $this->packet);
    }

    /**
     * @param Protocol        $protocol
     * @param PacketInterface $packet
     * @return \Minbaby\SwoftClient\Response
     * @throws Exception
     */
    protected function CallRemoteFuc(Protocol $protocol, PacketInterface $packet)
    {
        $fp = stream_socket_client('tcp://127.0.0.1:18307', $errno, $errstr);
        if (!$fp) {
            throw new Exception("stream_socket_client fail errno={$errno} errstr={$errstr}");
        }

        $data = $packet->encode($protocol);
        fwrite($fp, $data);

        $result = '';
        while(!feof($fp)) {
            $tmp = stream_socket_recvfrom($fp, 1024);
            if (empty($tmp)) {
                break;
            }
            $result .= $tmp;
            if (strpos($result, $packet->getPackageEof())) {
                break;
            }
        }

        fclose($fp);
        $res = $packet->decodeResponse($result);
        if ($res->getError()) {
            throw new \Minbaby\SwoftClient\RpcException($res->getError()->getMessage(), $res->getError()->getCode());
        }

        return $res->getResult();
    }
}

/** @var \App\Rpc\Lib\UserInterface $v */
$v = new Proxy(\App\Rpc\Lib\UserInterface::class);
$i = 0;
while($i<1000) {
//    $v->getBigContent();
$v->getList(1, 2);
//var_dump($v->exception());
    $i++;
}

echo microtime(true) - $start;
