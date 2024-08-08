<?php

declare(strict_types=1);

namespace Verdient\IRobotBox;

use SoapClient;
use stdClass;
use Throwable;

/**
 * 抽象动作
 * @author Verdient。
 */
abstract class AbstractAction
{
    /**
     * @param string $wsdl WSDL地址
     * @param string $username 用户名
     * @param string $password 密码
     * @param int|string $customerId 客户编号
     * @param string|null $proxyHost 代理主机
     * @param string|null $proxyPort 代理端口
     */
    public function __construct(
        protected string $wsdl,
        protected string $username,
        protected string $password,
        protected int|string $customerId,
        protected string|null $proxyHost = null,
        protected string|null $proxyPort = null
    ) {
    }

    /**
     * 发起请求
     * @param string $action 请求的动作
     * @param array $data 数据
     * @return array
     * @throws Throwable
     * @author Verdient。
     */
    public function request(string $action, array $data = [])
    {
        $options = [
            'cache_wsdl' => WSDL_CACHE_DISK
        ];
        if ($this->proxyHost) {
            $options['proxy_host'] = $this->proxyHost;
            $options['proxy_port'] = $this->proxyPort;
        }
        return $this->toArray(
            (new SoapClient($this->wsdl, $options))
                ->__soapCall($action, $data)
        );
    }

    /**
     * 将对象转换为数组
     * @param stdClass|array 待转换的数据
     * @return array
     * @author Verdient。
     */
    protected function toArray(stdClass|array $data): array
    {
        $result = [];
        foreach ((array) $data as $key => $value) {
            if (is_object($value) || is_array($value)) {
                $result[$key] = $this->toArray($value);
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}
