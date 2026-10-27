<?php

namespace App\Exceptions;

use RuntimeException;

class CategoryNotEmpty extends RuntimeException
{
    public function __construct(string $message = 'Категорію не можна видалити: вона містить продукти.')
    {
        parent::__construct($message);
    }
}