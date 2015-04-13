<?php

namespace Data\Type;

class _bool extends Basic
{
    /**
     * Get the value
     *
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * Check the value
     *
     * @param  mixed $value
     * @return bool
     */
    public function check($value)
    {
        if ($value === false || $value === 0 || $value === 0.0 || $value === '0') {
            return false;
        } elseif ($value === true || $value === 1 || $value === 1.0 || $value === '1') {
            return true;
        } elseif ($value instanceof Bool) {
            return $value->value();
        } elseif ($value instanceof Basic) {
            $value = $value->value();
        }

        if ($value === false || $value === 0 || $value === 0.0 || $value === '0') {
            return false;
        } elseif ($value === true || $value === 1 || $value === 1.0 || $value === '1') {
            return true;
        }

        throw new \InvalidArgumentException('Invalid bool');
    }
}
