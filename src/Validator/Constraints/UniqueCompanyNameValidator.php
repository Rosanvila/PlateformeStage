<?php

// src/Validator/Constraints/UniqueCompanyNameValidator.php
namespace App\Validator\Constraints;

use App\Service\CompanyNameChecker;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqueCompanyNameValidator extends ConstraintValidator
{
    private CompanyNameChecker $companyNameChecker;

    public function __construct(CompanyNameChecker $companyNameChecker)
    {
        $this->companyNameChecker = $companyNameChecker;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueCompanyName) {
            throw new UnexpectedTypeException($constraint, UniqueCompanyName::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if ($this->companyNameChecker->doesCompanyNameExist($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}