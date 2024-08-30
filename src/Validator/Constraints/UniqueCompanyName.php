<?php

// src/Validator/Constraints/UniqueCompanyName.php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueCompanyName extends Constraint
{
    public string $message = 'The company name "{{ value }}" already exists.';
}