<?php

declare(strict_types=1);

namespace Verdient\IRobotBox;

use Exception;
use Iterator;

/**
 * 获取运输方式列表
 * @author Verdient。
 */
class GetTransportList extends AbstractAction
{
    /**
     * 获取订单列表
     * @author Verdient。
     */
    public function list($params = [])
    {
        try {
            $res = $this->request('GetTransportList', [
                'GetTransportList ' => [
                    'trRequest' => [
                        'CustomerID' => $this->customerId,
                        'UserName' => $this->username,
                        'Password' => $this->password,
                        ...$params
                    ]
                ]
            ]);

            if ($res['GetTransportListResult']['Status'] === 'OK') {
                $resData = $res['GetTransportListResult'];
                if (empty($resData['TransportList']['ApiTransport'])) {
                    return Response::data([
                        'data' => []
                    ], $res);
                }
                if (array_is_list($resData['TransportList']['ApiTransport'])) {
                    return Response::data([
                        'data' =>  $resData['TransportList']['ApiTransport'],
                    ], $res);
                }
                return Response::data([
                    'data' => [$resData['TransportList']['ApiTransport']],
                ], $res);
            }
            return Response::failed($res['GetOrdersResult']['Msg'], $res);
        } catch (\Throwable $e) {
            return Response::failed($e->getMessage(), $e->getCode());
        }
    }

    /**
     * 单个迭代器
     * @return Iterator
     * @author Verdient。
     */
    public function each($params = []): Iterator
    {
        try {
            $res = $this->list($params);
        } catch (\Throwable) {
            $res = $this->list($params);
        }
        if (!$res->getIsOK()) {
            throw new Exception($res->getErrorMessage());
        }
        $resData = $res->getData();
        foreach ($resData['data'] as $row) {
            yield $row;
        }
    }
}
