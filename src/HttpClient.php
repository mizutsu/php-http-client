<?php
declare(strict_types=1);

namespace Mizutsu\Lib;

/**
 * This class is simple HTTP client lib
 *
 * @author Masaharu Suizu <ma.suizu@gmail.com>
 * @link https://github.com/mizutsu/php-http-client
 */
class HttpClient {

    const TIMEOUT_DEFAULT = 30;
    const ERR_MESSAGE_FORMAT = 'curl_errno : %d curl_error_message : %s';

    private $ch = null;
    private ?int $responseCode = null;

    public function __construct()
    {
        $this->ch = curl_init();
    }

    public function __destruct()
    {
        curl_close($this->ch);
    }

    /**
     * Execute curl
     *
     * @return string
     *
     * @throws \Exception
     */
    public function execute(): string
    {
        $response = curl_exec($this->ch);

        if ($response === false) {
            $exceptionMessage = $this->makeExceptionMessage();
            throw new \Exception($exceptionMessage, curl_errno($this->ch));
        }

        $responseCode = curl_getinfo($this->ch, CURLINFO_RESPONSE_CODE);
        $this->setResponseCode($responseCode);

        return $response;
    }

    /**
     * Set options for cURL
     *
     * @param array $options Options to set curl_setoption_array()
     *
     * @return void
     *
     * @throws \Exception
     */
    public function setCurlOptions(array $options): void
    {
        curl_reset($this->ch);

        $options[CURLOPT_RETURNTRANSFER] = true;
        if (! isset($options[CURLOPT_TIMEOUT])) {
            $options[CURLOPT_TIMEOUT] = self::TIMEOUT_DEFAULT;
        }
        $isSuccess = curl_setopt_array($this->ch, $options);

        if (! $isSuccess) {
            $exceptionMessage = $this->makeExceptionMessage();
            throw new \Exception($exceptionMessage, curl_errno($this->ch));
        }
    }

    /**
     * Set HTTP response code
     * 
     * @param int $responseCode HTTP response code
     *
     * @return void
     */
    private function setResponseCode(int $responseCode): void
    {
        $this->responseCode = $responseCode;
    }

    /**
     * Get HTTP response code
     * 
     * @return int|null
     */
    public function getResponseCode(): ?int
    {
        return $this->responseCode;
    }

    /**
     * Get response details
     *
     * @return array curl_getinfo($ch) values <br>
     * https://www.php.net/manual/en/function.curl-getinfo.php#refsect1-function.curl-getinfo-returnvalues
     *
     * @throws \Exception
     */
    public function getResponseDetails(): array
    {
        $responses = curl_getinfo($this->ch);

        if ($responses === false) {
            $exceptionMessage = $this->makeExceptionMessage();
            throw new \Exception($exceptionMessage, curl_errno($this->ch));
        }

        return $responses;
    }

    /**
     * Make message for exception
     *
     * @return string message<br>
     * curl_errno : %d curl_error_message : %s
     */
    private function makeExceptionMessage(): string
    {
        return sprintf(
            self::ERR_MESSAGE_FORMAT,
            curl_errno($this->ch),
            curl_error($this->ch)
        );
    }
}
