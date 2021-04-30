<?php

namespace Jwt\Service;

use Exception;
use \Firebase\JWT\JWT;

/**
 * Class JwtService
 * @package Jwt\Service
 */
class JwtService
{

    public const INVALID_TOKEN = "Invalid token";
    public const TOKEN_EXPIRED = "Invalid expired";
    private $key;
    private $clock;

    /**
     * JwtService constructor.
     * @param $key
     * @param IClock $clock
     */
    public function __construct($key, IClock $clock)
    {
        $this->key = $key;
        $this->clock = $clock;
    }

    /**
     * @param $key
     * @return JwtService
     */
    public static function createWithKey($key)
    {
        return new JwtService($key, new SystemClock);
    }

    /**
     * @param $key
     * @param $clock
     * @return JwtService
     */
    public static function createWithKeyAndClock($key, IClock $clock)
    {
        return new JwtService($key, $clock);
    }

    /**
     * @param array $data
     * @return string
     */
    public function signIn(array $data)
    {
        return $this->encodeToken($data);
    }

    /**
     * @param $token
     * @return bool
     */
    public function isValid($token)
    {
        try {
            $this->assertTokenIsValid($token);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @throws Exception
     */
    public function getData($token)
    {
        $this->assertTokenIsValid($token);
        return $this->decodeToken($token)->data;
    }

    /**
     * @param $dataToken
     * @throws Exception
     */
    private function assertTokenExpired($dataToken)
    {
        if ($this->clock->now()->getTimestamp() > $dataToken->exp) {
            throw new Exception(self::TOKEN_EXPIRED);
        }
    }

    /**
     * @param $token
     * @throws Exception
     */
    private function assertTokenIsValid($token)
    {
        $this->assertTokenExpired($this->decodeToken($token));
    }

    /**
     * @param array $data
     * @return string
     */
    private function encodeToken(array $data)
    {
        $token = [
            "exp" => $this->clock->now()->getTimestamp() + (24 * 60 * 60),
            "data" => $data
        ];
        return JWT::encode($token, $this->key);
    }

    /**
     * @throws Exception
     */
    private function decodeToken($token)
    {
        try {
            return JWT::decode($token, $this->key, array('HS256'));
        } catch (Exception $e) {
            throw new Exception(self::INVALID_TOKEN);
        }
    }
}
