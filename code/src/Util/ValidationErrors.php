<?php

namespace App\Util;

use App\Exception\ValidationException;
use Symfony\Component\Form\FormInterface;

/**
 * Class ValidationErrors
 * @package App\Util
 */
final class ValidationErrors
{
    /**
     * @param FormInterface $form
     * @throws ValidationException
     */
    public function handle(FormInterface $form)
    {
        $errors = $this->parse($form);
        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    protected function parse(FormInterface $form)
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->parse($childForm)) {
                    $errors[$childForm->getName()] = $childErrors[0];
                }
            }
        }
        return $errors;
    }
}