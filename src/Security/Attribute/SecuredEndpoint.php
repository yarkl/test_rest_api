<?php

declare(strict_types=1);

namespace App\Security\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class SecuredEndpoint
{
}
