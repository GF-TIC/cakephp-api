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

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace CakeDC\Api\Service\Auth\Authenticate;

use Cake\Http\Response;
use Cake\Http\ServerRequest;

/**
 * Class FormAuthenticate.
 */
class FormAuthenticate extends BaseAuthenticate
{
    /**
     * Checks the fields to ensure they are supplied.
     *
     * @param \Cake\Http\ServerRequest $request The request that contains login information.
     * @param array $fields The fields to be checked.
     * @return bool False if the fields have not been supplied. True if they exist.
     */
    protected function _checkFields(ServerRequest $request, array $fields)
    {
        foreach ([$fields['username'], $fields['password']] as $field) {
            $value = $request->getData($field);
            if (empty($value) || !is_string($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getUser(ServerRequest $request)
    {
        $fields = $this->_config['fields'];
        if (!$this->_checkFields($request, $fields)) {
            return false;
        }

        return $this->_findUser(
            $request->getData($fields['username']),
            $request->getData($fields['password'])
        );
    }

    /**
     * @inheritDoc
     */
    public function authenticate(ServerRequest $request, Response $response)
    {
        return $this->getUser($request);
    }
}
