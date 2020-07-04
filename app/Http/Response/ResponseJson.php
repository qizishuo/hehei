<?php
namespace App\Http\Response;


trait ResponseJson
{
    /**
     * 返回一个json
     * @param $code 状态码
     * @param $message 返回说明
     * @param $data 返回数据集合
     * @return false | string
     */
    private function jsonResponse($code, $message, $data)
    {
        $content = [
            'code' => $code,
            'msg' => $message,
            'data' => $data
        ];
        $content = json_encode($content, JSON_UNESCAPED_UNICODE);
        return response($content);
    }

    /**
     * 成功的时候返回结果
     * @param $data 返回数据集合
     * @return false | string
     */
    public function jsonSuccessData($data = [])
    {
        // 使用错误码  ApiErrDesc::SUCCESS[0]错误码，  ApiErrDesc::SUCCESS[1]错误描述
        return $this->jsonResponse(200, '请求成功', $data);
    }

    /**
     * 失败的时候返回
     * @param $code 状态码
     * @param $message 返回说明
     * @param $data 返回数据集合
     * @return false | string
     */
    public function jsonErrorData($code = 0, $message = '出错了', $data = [])
    {
        return $this->jsonResponse($code, $message, $data);
    }

    public function parseAuth(string $auth, string $restrict_type = "Basic")
    {
        [$type, $data] = explode(" ", $auth);
        if ($type != $restrict_type) {
            abort(401, "Authorization 类型错误");
        }

        switch ($type) {
            case "Basic":
                $data = base64_decode($data);
                return explode(":", $data);
            case "Bearer":
                return $data;
            default:
                abort(401, "Authorization 解析失败");
        }
    }
}


