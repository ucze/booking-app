<?php declare(strict_types=1);

namespace App\Exception;

/**
 * Class ChairTakenException
 * @package App\Exception
 */
class ChairTakenException extends ApiException
{
    /**
     * ChairTakenException constructor.
     */
    public function __construct()
    {
        parent::__construct('This chair is not available in selected time slots', 400);
    }
}