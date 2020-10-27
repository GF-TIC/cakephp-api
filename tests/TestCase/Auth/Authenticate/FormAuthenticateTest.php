<?php
declare(strict_types=1);

/**
 * Copyright 2016 - 2019, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2016 - 2019, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace CakeDC\Api\Test\TestCase\Auth\Authenticate;

use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use CakeDC\Api\Service\Action\CrudIndexAction;
use CakeDC\Api\Service\Auth\Authenticate\FormAuthenticate;
use CakeDC\Api\Service\FallbackService;
use CakeDC\Api\Test\ConfigTrait;
use CakeDC\Api\TestSuite\TestCase;

/**
 * Class FormAuthenticateTest
 *
 * @coversDefaultClass \CakeDC\Api\Service\Auth\Authenticate\FormAuthenticate
 */
class FormAuthenticateTest extends TestCase
{
    use ConfigTrait;

    /**
     * @var \CakeDC\Api\Service\Auth\Authenticate\FormAuthenticate
     */
    public $form;

    /**a
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp(): void
    {
        $request = new ServerRequest();
        $response = new Response();
        $service = new FallbackService([
            'request' => $request,
            'response' => $response,
        ]);
        $action = new CrudIndexAction([
            'service' => $service,
            'request' => $request,
            'response' => $response,
        ]);
        $this->form = new FormAuthenticate($action, ['require_ssl' => false]);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown(): void
    {
        unset($this->form);
    }

    /**
     * test
     *
     * @return void
     */
    public function testAuthenticateHappy()
    {
        $request = new ServerRequest([
            'post' => [
                'username' => 'user-1',
                'password' => '12345',
            ]
        ]);
        $result = $this->form->authenticate($request, new Response());
        self::assertEquals('user-1', $result['username']);
    }

    /**
     * test
     *
     * @return void
     */
    public function testAuthenticateFail()
    {
        $request = new ServerRequest(['post' => []]);
        $result = $this->form->authenticate($request, new Response());
        self::assertFalse($result);


        $request = new ServerRequest([
            'post' => [
                'username' => 'user-1',
            ]
        ]);
        $result = $this->form->authenticate($request, new Response());
        self::assertFalse($result);

        $request = new ServerRequest([
            'get' => [
                'username' => 'user-1',
                'password' => '12345',
            ]
        ]);
        $result = $this->form->authenticate($request, new Response());
        self::assertFalse($result);
    }
}
