<?php

namespace Verdient\IRobotBox;

/**
 * 赛盒
 * @author Verdient。
 */
class IRobotBox
{
    /**
     * @param string $host 主机地址
     * @param string $username 用户名
     * @param string $password 密码
     * @param int|string $customerId 客户编号
     * @param string|null $proxyHost 代理主机
     * @param string|null $proxyPort 代理端口
     */
    public function __construct(
        protected string $host,
        protected string $username,
        protected string $password,
        protected int|string $customerId,
        protected string|null $proxyHost = null,
        protected string|null $proxyPort = null
    ) {
    }

    /**
     * 获取订单
     * @return GetOrders
     * @author Verdient。
     */
    public function getOrders(): GetOrders
    {
        return new GetOrders(
            $this->host . '/Api/API_Irobotbox_Orders.asmx?wsdl',
            $this->username,
            $this->password,
            $this->customerId,
            $this->proxyHost,
            $this->proxyPort
        );
    }

    /**
     * 获取运输方式
     * @return GetTransportList
     * @author Verdient。
     */
    public function getTransportList()
    {
        return new GetTransportList(
            $this->host . '/Api/API_Irobotbox_Orders.asmx?wsdl',
            $this->username,
            $this->password,
            $this->customerId,
            $this->proxyHost,
            $this->proxyPort
        );
    }
}
