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

    private ResponseInterface|array $response;

    private int $http_code;

    private string $http_status;

    private string $content;

    public function __construct(ResponseInterface | array $response)
    {
        if ($response instanceof ResponseInterface) {
            $this->response = $response;
            $this->http_code = $response->getStatusCode();
            $this->content = $response->getBody()->getContents();
        } else {
            $this->response = $response;
            $this->http_code = 000;
            $this->content = json_encode($response);
        }

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
        // Respons sukses tidak punya pesan error. Tanpa guard ini, body 2xx
        // (mis. {"message":"success"} pada KFA v3) akan dilaporkan sebagai
        // error oleh pemanggil yang memeriksa daftar pesan.
        if ($this->http_code >= 100 && $this->http_code < 400) {
            return [];
        }

        $content = $this->getContentAsArray();
        $messages = [];

        if ($this->response instanceof ResponseInterface) {
            // OperationOutcome tidak selalu lengkap: `details` dan `severity`
            // boleh tidak ada. Tanpa guard, satu issue tanpa details memunculkan
            // warning sekaligus menghilangkan pesan error-nya sendiri.
            $issues = isset($content['issue']) && is_array($content['issue']) ? $content['issue'] : [];

            foreach ($issues as $issue) {
                if (!is_array($issue)) {
                    continue;
                }

                $severity = strtoupper($issue['severity'] ?? 'unknown');
                $text = $issue['details']['text'] ?? null;
                $diagnostics = $issue['diagnostics'] ?? null;
                $code = $issue['code'] ?? null;

                $tmpMsg = $severity . '::' . ($text ?? $diagnostics ?? $code ?? 'Unknown error');

                if ($text !== null && $diagnostics !== null) {
                    $tmpMsg = $tmpMsg . ' - ' . $diagnostics;
                }

                $messages[] = $tmpMsg;
            }

            // Bukan OperationOutcome: layanan lain (mis. KFA) memakai bentuk
            // {"message": "..."} atau {"error": true, "message": "..."}.
            // Tanpa fallback ini pesan errornya hilang dan hanya tersisa kode.
            if ($messages === [] && is_array($content)) {
                foreach (['message', 'error'] as $key) {
                    if (isset($content[$key]) && is_string($content[$key]) && trim($content[$key]) !== '') {
                        $messages[] = trim($content[$key]);
                        break;
                    }
                }
            }

            // 5xx dari gateway biasanya text/plain ("Gateway Timeout"), bukan
            // OperationOutcome. Kembalikan apa adanya agar tidak tampak kosong.
            if ($messages === [] && trim($this->content) !== '') {
                $messages[] = trim($this->content);
            }
        } else {
            $messages[] = $content['error'] ?? '';
        }

        return $messages;
    }
}
