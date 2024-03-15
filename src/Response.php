<?php

declare(strict_types=1);

namespace Verdient\IRobotBox;

/**
 * 响应
 * @author Verdient。
 */
class Response
{
    /**
     * 请求是否成功
     * @author Verdient。
     */
    protected bool $isOK = false;

    /**
     * 错误码
     * @author Verdient。
     */
    protected int|string|null $errorCode = null;

    /**
     * 错误信息
     * @author Verdient。
     */
    protected ?string $errorMessage = null;

    /**
     * 响应信息
     * @author Verdient。
     */
    protected mixed $response = null;

    /**
     * 数据
     * @author Verdient。
     */
    protected mixed $data = null;

    /**
     * 创建数据响应
     * @param mixed 响应数据
     * @param ?array $rawResponse 原始响应
     * @return static
     * @author Verdi ent。
     */
    public static function data($data, ?array $rawResponse = null): static
    {
        $result = new static;
        $result->isOK = true;
        $result->data = $data;
        $result->response = $rawResponse;
        return $result;
    }

    /**
     * 创建失败响应
     * @param string $message 提示信息
     * @param int $code 错误码
     * @param ?array $rawResponse 原始响应
     * @return static
     * @author Verdient。
     */
    public static function failed($message, $code = null, ?array $rawResponse = null): static
    {
        $result = new static;
        $result->isOK = false;
        $result->errorMessage = $message;
        $result->errorCode = $code;
        $result->response = $rawResponse;
        return $result;
    }

    /**
     * 获取响应对象
     * @return mixed
     * @author Verdient。
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * 获取是否成功
     * @return bool
     * @author Verdient。
     */
    public function getIsOK()
    {
        return $this->isOK;
    }

    /**
     * 获取错误码
     * @return int
     * @author Verdient。
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * 获取错误信息
     * @return string
     * @author Verdient。
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * 获取返回的数据
     * @return array
     * @author Verdient。
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @inheritdoc
     * @return mixed
     * @author Verdient。
     */
    public function __get($name)
    {
        return $this->data[$name] ?? null;
    }
}
