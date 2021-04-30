<?php

namespace Jwt\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Jwt\Service\JwtService;
use DateTime;
use Exception;

class JwtServiceTest extends TestCase
{
    Const REGEX_TOKEN = '/^[a-zA-Z0-9\-_]+?\.[a-zA-Z0-9\-_]+?\.([a-zA-Z0-9\-_]+)?$/';
    Const KEY = "ABC123";

    /**
     * @var JwtService
     */
    private $service;

    public function setUp() : void
    {
        $this->service = JwtService::createWithKey(self::KEY);
    }

    /**
     * @test
     * @return void
     */
    public function can_validate_a_token()
    {
        $data = ["Test"];
        $token = $this->service->signIn($data);

        $this->assertTrue($this->service->isValid($token));
        $this->assertFalse($this->service->isValid("INVALID TOKEN"));
    }

    /**
     * @test
     * @return void
     */
    public function can_get_data_from_token()
    {
        $data = ["Test"];
        $token = $this->service->signIn($data);

        $actualData = $this->service->getData($token);


        $this->assertEquals($data[0], $actualData[0]);
        $this->assertNotEquals("INVALID DATA", $actualData[0]);
    }

    /**
     * @test
     * @return void
     */
    public function can_not_get_data_from_invalid_token()
    {
        try{
            $this->service->getData("INVALID TOKEN");
            $this->fail();
        }catch (Exception $e){
            $this->assertEquals(JwtService::INVALID_TOKEN, $e->getMessage());
        }
    }

    /**
     * @test
     * @return void
     */
    public function token_valid_for_24_hour()
    {
        $data = ["Test"];
        $clock = new ManualClock(new DateTime());
        $this->service = JwtService::createWithKeyAndClock(self::KEY, $clock);
        $token = $this->service->signIn($data);

        $clock->passTime("23 hour");
        $this->assertTrue($this->service->isValid($token));

        $clock->passTime("2 hour");
        $this->assertFalse($this->service->isValid($token));
    }

    /**
     * @test
     * @return void
     */
    public function can_not_get_data_from_expired_token()
    {
        try{
            $data = ["Test"];
            $clock = new ManualClock(new DateTime());
            $this->service = JwtService::createWithKeyAndClock(self::KEY, $clock);
            $token = $this->service->signIn($data);

            $clock->passTime("25 hour");
            $this->service->getData($token);
            $this->fail();
        }catch (Exception $e){
            $this->assertEquals(JwtService::TOKEN_EXPIRED, $e->getMessage());
        }
    }

}
