<?php

namespace Data\Type;

use Iterator;
use Countable;
use ArrayAccess;
use LengthException;
use InvalidArgumentException;

class StringType extends Type implements ArrayAccess, Iterator, Countable
{
    protected static $supported_encodings;

    /**
     * @var string
     */
    protected $encoding;

    /**
     * @var int
     */
    protected $iteratorPosition = 0;

    /**
     * Constructor
     *
     * @param mixed $value
     */
    public function __construct($value = null, $encoding = null)
    {
        if ($encoding === null) {
            $this->encoding = mb_internal_encoding();
        } else {
            $this->encoding = $this->getRealEncoding($encoding);
        }

        $this->set($value);
    }

    /**
     * Get the value
     *
     * @param  string $encoding
     * @return string
     */
    public function value($encoding = null)
    {
        if ($this->value !== null) {
            if ($encoding === null) {
                return mb_convert_encoding($this->value, $this->encoding, 'UTF-8');
            } else {
                $encoding = $this->getRealEncoding($encoding);
                return mb_convert_encoding($this->value, $encoding, 'UTF-8');
            }
        }
    }

    /**
     * Check the value
     *
     * @param  mixed  $value
     * @return string
     */
    protected function check($value)
    {
        if ($value === false || $value === 0 || $value === 0.0 || $value === '0') {
            return '0';
        }

        if ($value === true || $value === 1 || $value === 1.0 || $value === '1') {
            return '1';
        }

        if ($value instanceof StringType) {
            return $value->value($this->encoding);
        }

        if ($value instanceof Type) {
            $value = $value->value();
        } else {
            if (is_array($value)) {
                throw new InvalidArgumentException('Invalid string, array given');
            }

            if (is_resource($value)) {
                throw new InvalidArgumentException('Invalid string, resource given');
            }

            if (is_object($value)) {
                throw new InvalidArgumentException('Invalid string, object given');
            }
        }

        if (is_array($value) || is_object($value) || is_resource($value)) {
            throw new InvalidArgumentException('Invalid string: ' . $value);
        }

        return mb_convert_encoding($value, 'UTF-8', $this->encoding);
    }

    /**
     * Cast instance to string
     *
     * @return string
     */
    public function __toString()
    {
        if ($this->value === null) {
            return '';
        }

        return mb_convert_encoding($this->value, $this->encoding, 'UTF-8');
    }

    /**
     * Creates an array with all of the aliases->encodings
     *
     * @return array
     */
    public static function supportedEncodings()
    {
        if (static::$supported_encodings === null) {
            $supported = mb_list_encodings();

            foreach ($supported as $key => $value) {
                static::$supported_encodings[strtolower($value)] = $value;

                foreach (mb_encoding_aliases($value) as $k => $v) {
                    static::$supported_encodings[strtolower($v)] = $value;
                }
            }
        }

        return static::$supported_encodings;
    }

    /**
     * Is this encoding supported?
     *
     * @param  string  $encoding
     * @return bool
     */
    public static function isEncodingSupported($encoding)
    {
        $encoding = strtolower($encoding);

        if (isset (static::supportedEncodings()[$encoding])) {
            return true;
        }

        return false;
    }

    /**
     * Return the real encoding of the given alias
     *
     * @param  string $encoding
     * @return string
     */
    public function getRealEncoding($encoding)
    {
        if (static::isEncodingSupported($encoding) === false) {
            throw new \Exception('Encoding is not supported: "' . $encoding . '"');
        }

        return static::supportedEncodings()[strtolower($encoding)];
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
        $from = Cast::Int($from);
        $length = Cast::_Int($length);

        if ($this->length() < $from) {
            throw new LengthException('From parameter must be smaller than the length of the string');
        }

        if ($this->length() < $length) {
            throw new LengthException('Length parameter must be smaller than the length of the string');
        }

        return mb_convert_encoding(mb_substr($this->value, $from, $length, 'UTF-8'), $this->encoding, 'UTF-8');
    }

    /**
     * Lowercase
     *
     * @return this
     */
    public function toLower()
    {
        $this->value = mb_strtolower($this->value, 'UTF-8');
        return $this;
    }

    /**
     * Uppercase
     *
     * @return this
     */
    public function toUpper()
    {
        $this->value = mb_strtoupper($this->value, 'UTF-8');
        return $this;
    }

    /**
     * Uppercase first letter
     *
     * @return this
     */
    public function upperFirst()
    {
        $this->value = mb_strtoupper(mb_substr($this->value, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($this->value, 1, null, 'UTF-8');
        return $this;
    }

    /**
     * Uppercase first letter of every word
     *
     * @return this
     */
    public function upperWords()
    {
        $this->value = mb_convert_case($this->value, MB_CASE_TITLE, 'UTF-8');
        return $this;
    }

    /**
     * @see ArrayAccess::offsetExists
     */
    public function offsetExists($offset)
    {
        $offset = Cast::_Int($offset);

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
            throw new InvalidArgumentException('Invalid offset: "' . $offset . '"');
        }

        return $this->substr($offset, 1);
    }

    /**
     * @see ArrayAccess::offsetSet
     */
    public function offsetSet($offset, $value)
    {
        $value = Cast::_String($value, $this->encoding);

        $new = static::create($this->substr(0, $offset) . $value . $this->substr($offset + 1), $this->encoding);
        $this->value = $new->value;
    }

    /**
     * @see ArrayAccess::offsetUnset
     */
    public function offsetUnset($offset)
    {
        $this->offsetSet($offset, '');
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

    /**
     * @see Countable::count
     */
    public function count()
    {
        return $this->length();
    }
}
