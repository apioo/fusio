<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2016 Christoph Kappestein <k42b3.x@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Impl\Service\App;

use Fusio\Impl\Authorization\TokenGenerator;
use Fusio\Impl\Table\App\Code as TableAppCode;

/**
 * Code
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Code
{
    protected $appCodeTable;

    public function __construct(TableAppCode $appCodeTable)
    {
        $this->appCodeTable = $appCodeTable;
    }

    public function getCode($appKey, $appSecret, $code, $redirectUri)
    {
        return $this->appCodeTable->getCodeByRequest($appKey, $appSecret, $code, $redirectUri);
    }

    public function generateCode($appId, $userId, $redirectUri, array $scopes)
    {
        $code = TokenGenerator::generateCode();

        $this->appCodeTable->create([
            'appId'       => $appId,
            'userId'      => $userId,
            'code'        => $code,
            'redirectUri' => $redirectUri,
            'scope'       => implode(',', $scopes),
            'date'        => new \DateTime(),
        ]);

        return $code;
    }
}
