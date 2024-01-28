<?php
namespace agumil\SatuSehatSDK\Response;

use Psr\Http\Message\ResponseInterface;

class Response
{
    const STATUS_1XX = 'Informational';

    const STATUS_2XX = 'Successful';

    const STATUS_3XX = 'Redirection';

    const STATUS_4XX = 'Client Error';

    const STATUS_5XX = 'Server Error';

    const STATUS_UNKNOWN = 'Unknown Error';

    private ResponseInterface $response;

    private int $http_code;

    private string $http_status;

    private string $content;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $this->http_code = $response->getStatusCode();
        $this->content = $response->getBody()->getContents();

        if ($this->http_code >= 100 && $this->http_code < 200) {
            $this->http_status = self::STATUS_1XX;
        } elseif ($this->http_code >= 200 && $this->http_code < 300) {
            $this->http_status = self::STATUS_2XX;
        } elseif ($this->http_code >= 300 && $this->http_code < 400) {
            $this->http_status = self::STATUS_3XX;
        } elseif ($this->http_code >= 400 && $this->http_code < 500) {
            $this->http_status = self::STATUS_4XX;
        } elseif ($this->http_code >= 500 && $this->http_code < 600) {
            $this->http_status = self::STATUS_5XX;
        } else {
            $this->http_status = self::STATUS_UNKNOWN;
        }
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getHttpStatus()
    {
        return $this->http_status;
    }

    public function getHttpCode()
    {
        return $this->http_code;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getContentAsObject()
    {
        return json_decode($this->content);
    }

    public function getContentAsArray()
    {
        return json_decode($this->content, true);
    }

    public function getErrorMessages()
    {
        $content = $this->getContentAsArray();

        $messages = [];
        foreach ($content['issue'] as $issue) {
            $tmpMsg = strtoupper($issue['severity']) . '::' . $issue['details']['text'];
            if (isset($issue['diagnostics'])) {
                $tmpMsg = $tmpMsg . ' - ' . $issue['diagnostics'];
            }

            $messages[] = $tmpMsg;
        }

        return $messages;
    }
}
