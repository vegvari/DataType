<?php

namespace Data\Type;

class String extends Type implements \ArrayAccess, \Iterator
{
    protected $length = 0;
    protected $iteratorPosition = 0;

    /**
     * @see TypeInterface
     */
    public function __construct($value)
    {
        parent::__construct($value);
        $this->length = mb_strlen($this->value, 'UTF-8');
    }

    /**
     * @see TypeInterface
     */
    public function check($value)
    {
        $value = parent::check($value);

        if ($value === false) {
            return '0';
        }

        if ($value === true) {
            return '1';
        }

        if ($value === null) {
            throw new \InvalidArgumentException();
        }

        return mb_convert_encoding($value, 'UTF-8', 'UTF-8');
    }

    /**
     * Length
     *
     * @return int
     */
    public function length()
    {
        return $this->length;
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
        $from = Natural::cast($from);
        $length = Natural::castNullable($length);

        if ($this->length() <= $from) {
            throw new \LengthException();
        }

        return self::create(mb_substr($this->value, $from, $length, 'UTF-8'));
    }

    /**
     * Lowercase
     *
     * @return string
     */
    public function toLower()
    {
        return self::create(mb_strtolower($this->value, 'UTF-8'));
    }

    /**
     * Uppercase
     *
     * @return string
     */
    public function toUpper()
    {
        return self::create(mb_strtoupper($this->value, 'UTF-8'));
    }

    /**
     * Uppercase first letter
     *
     * @return string
     */
    public function upperFirst()
    {
        return self::create(mb_strtoupper(mb_substr($this->value, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($this->value, 1, null, 'UTF-8'));
    }

    /**
     * Uppercase first letter of every word
     *
     * @return string
     */
    public function upperWords()
    {
        return self::create(mb_convert_case($this->value, MB_CASE_TITLE, 'UTF-8'));
    }

    /**
     * @see ArrayAccess::offsetExists
     */
    public function offsetExists($offset)
    {
        $offset = Int::cast($offset);

        if ($offset >= 0 && $this->length() > $offset)
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
        $offset = Natural::cast($offset);

        return $this->substr($offset, 1)->value();
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
