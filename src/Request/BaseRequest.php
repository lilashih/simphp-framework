<?php

namespace Lilashih\Simphp\Request;

use Rakit\Validation\Validator;
use Lilashih\Simphp\Exception\ValidationException;

abstract class BaseRequest
{
    public function validateCollection($type, array $items): array
    {
        $data = [];
        $error = [];

        foreach ($items as $key => $item) {
            try {
                $data[] = $this->validate($type, $item);
            } catch (ValidationException $e) {
                $error[$key] =$e->getData();
            }
        }

        if ($error) {
            throw new ValidationException($error);
        }

        return $data;
    }

    /**
     * @param array $type       [the methods of controller, like store, update, destroy...]
     * @param array $data
     * @param null $id
     * 
     * @return array
     */
    public function validate($type, array $data, $id = null): array
    {
        $rule = (is_callable([$this, "{$type}Rule"])) ? "{$type}Rule" : "rule";
        $format = (is_callable([$this, "{$type}Format"])) ? "{$type}Format" : "format";

        $validator = new Validator($this->message());

        $validator = $this->addValidator($validator);

        $validation = $validator->make($data, $this->{$rule}($data, $id));

        $validation->validate();

        if ($validation->fails()) {
            throw new ValidationException($validation->errors()->firstOfAll());
        }

        return $this->{$format}($data);
    }

    abstract protected function addValidator(Validator $validator): Validator;

    abstract protected function rule($data, $id): array;

    /**
     * Error message
     * 
     * @return array
     */
    abstract protected function message(): array;

    /**
     * Formating data before enter the controller
     *
     * @param array $data
     *
     * @return array
     */
    abstract protected function format(array $data): array;

}
