<?php

namespace Data\Type;

class String extends Basic implements \ArrayAccess, \Iterator
{
    protected $iteratorPosition = 0;

    /**
     * @see TypeInterface
     */
    public function check($value)
    {
        if ($value === false || $value === 0 || $value === 0.0 || $value === '0') {
            return '0';
        } elseif ($value === true || $value === 1 || $value === 1.0 || $value === '1') {
            return '1';
        } elseif ($value instanceof String) {
            return $value->value();
        } elseif ($value instanceof Basic) {
            $value = $value->value();
        } elseif (is_array($value) || is_object($value) || is_resource($value)) {
            throw new \InvalidArgumentException();
        }

        return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
    }

    /**
     * Returns the length of the string
     *
     * @return int
     */
    public function length()
    {
        return mb_strlen($this->value, 'UTF-8');
    }

    /**
     * Substring
     *
     * @param  int $from
     * @param  int $length
     * @return string|null
     */
    public function substr($from, $length = null)
    {
        $from = Int::cast($from);
        $length = Int::castNullable($length);

        if ($this->length() < $from || $this->length() < $length) {
            throw new \LengthException();
        }

        return self::make(mb_substr($this->value, $from, $length, 'UTF-8'));
    }

    /**
     * Lowercase
     *
     * @return string
     */
    public function toLower()
    {
        return self::make(mb_strtolower($this->value, 'UTF-8'));
    }

    /**
     * Uppercase
     *
     * @return string
     */
    public function toUpper()
    {
        return self::make(mb_strtoupper($this->value, 'UTF-8'));
    }

    /**
     * Uppercase first letter
     *
     * @return string
     */
    public function upperFirst()
    {
        return self::make(mb_strtoupper(mb_substr($this->value, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($this->value, 1, null, 'UTF-8'));
    }

    /**
     * Uppercase first letter of every word
     *
     * @return string
     */
    public function upperWords()
    {
        return self::make(mb_convert_case($this->value, MB_CASE_TITLE, 'UTF-8'));
    }

    /**
     * @see ArrayAccess::offsetExists
     */
    public function offsetExists($offset)
    {
        $offset = Int::castNullable($offset);

        if ($offset !== null && $offset >= 0 && $this->length() > $offset)
        {
            return true;
        }

        return false;
    }

    /**
     * @see ArrayAccess::offsetGet
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset) === false) {
            throw new \InvalidArgumentException();
        }

        return $this->substr($offset, 1);
    }

    /**
     * @see ArrayAccess::offsetSet
     */
    public function offsetSet($offset, $value)
    {
        throw new \LogicException('String class is immutable.');
    }

    /**
     * @see ArrayAccess::offsetUnset
     */
    public function offsetUnset($offset)
    {
        throw new \LogicException('String class is immutable.');
    }

    /**
     * @see Iterator::rewind
     */
    public function rewind()
    {
        $this->iteratorPosition = 0;
    }

    /**
     * @see Iterator::current
     */
    public function current()
    {
        return $this->offsetGet($this->iteratorPosition);
    }

    /**
     * @see Iterator::key
     */
    public function key()
    {
        return $this->iteratorPosition;
    }

    /**
     * @see Iterator::next
     */
    public function next()
    {
        $this->iteratorPosition++;
    }

    /**
     * @see Iterator::valid
     */
    public function valid()
    {
        return $this->offsetExists($this->iteratorPosition);
    }
}
