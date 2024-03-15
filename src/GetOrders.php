<?php

declare(strict_types=1);

namespace Verdient\IRobotBox;

use Exception;
use Iterator;

/**
 * 获取订单
 * @author Verdient。
 */
class GetOrders extends AbstractAction
{
    /**
     * 获取订单列表
     * @author Verdient。
     */
    public function list($params = [])
    {
        if (!isset($params['NextToken'])) {
            $params['NextToken'] = -1;
        }
        try {
            $res = $this->request('GetOrders', [
                'GetOrders' => [
                    'orderRequest' => [
                        'CustomerID' => $this->customerId,
                        'UserName' => $this->username,
                        'Password' => $this->password,
                        ...$params
                    ]
                ]
            ]);
            if (isset($res['Status']) && $res['Status'] != 'OK') {
                return Response::failed($res['Msg'], $res['Status'], $res);
            }
            if ($res['GetOrdersResult']['Status'] === 'OK') {
                $resData = $res['GetOrdersResult'];
                if (empty($resData['OrderInfoList']['ApiOrderInfo'])) {
                    return Response::data([
                        'data' => [],
                        'NextToken' => $resData['NextToken']
                    ], $res);
                }
                if (array_is_list($resData['OrderInfoList']['ApiOrderInfo'])) {
                    return Response::data([
                        'data' =>  $resData['OrderInfoList']['ApiOrderInfo'],
                        'NextToken' => $resData['NextToken']
                    ], $res);
                }
                return Response::data([
                    'data' => [$resData['OrderInfoList']['ApiOrderInfo']],
                    'NextToken' => $resData['NextToken']
                ], $res);
            }
            return Response::failed($res['GetOrdersResult']['Msg'], $res['GetOrdersResult']['Status'], $res);
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
        $nextToken = -1;

        do {

            $params['NextToken'] = $nextToken;

            try {
                $res = $this->list($params);
            } catch (\Throwable) {
                $res = $this->list($params);
            }

            if (!$res->getIsOK()) {
                throw new Exception($res->getErrorMessage());
            }
            foreach ($res->data as $row) {
                yield $row;
            }
            $nextToken = $res->NextToken;
        } while ($nextToken != -1);
    }
}
