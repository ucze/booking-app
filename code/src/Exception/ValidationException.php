<?php declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Validator\Exception\RuntimeException;

/**
 * Class ValidationException
 * @package App\Exception
 */
class ValidationException extends RuntimeException
{
    /**
     * @var array
     */
    protected array $validationErrors;

    public function __construct(array $errors)
    {
        $this->validationErrors = $errors;
        parent::__construct('There was a validation error', 400);
    }

    /**
     * @return array
     */
    public function getValidationErrors(): array
    {
        return $this->validationErrors;
    }
}