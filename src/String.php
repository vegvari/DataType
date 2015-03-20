<?php

namespace Data\Type;

class String extends Basic implements \ArrayAccess, \Iterator, \Countable
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
    protected function __construct($value = null, $encoding = null)
    {
        $this->setEncoding($encoding);
        $this->set($value);
    }

    /**
     * Instance factory
     *
     * @param  mixed $value
     * @return String
     */
    public static function create($value = null, $encoding = null)
    {
        return new static($value, $encoding);
    }

    /**
     * Casting to the type, null not allowed
     *
     * @param  mixed $value
     * @return String
     */
    public static function cast($value, $encoding = null)
    {
        $value = static::create($value, $encoding)->value();
        return parent::castNullable($value);
    }

    /**
     * Casting to the type, null allowed
     *
     * @param  mixed $value
     * @return String
     */
    public static function castNullable($value, $encoding = null)
    {
        return static::create($value, $encoding)->value();
    }

    /**
     * Casting to the type, hide exception if any (return null)
     *
     * @param  mixed $value
     * @return String
     */
    public static function castSilent($value, $encoding = null)
    {
        try {
            return static::cast($value, $encoding);
        } catch (\InvalidArgumentException $e) {
        }
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
    public function check($value)
    {
        if ($value === false || $value === 0 || $value === 0.0 || $value === '0') {
            return '0';
        } elseif ($value === true || $value === 1 || $value === 1.0 || $value === '1') {
            return '1';
        } elseif ($value instanceof Basic) {
            return $value->value($this->encoding);
        } elseif (is_array($value) || is_object($value) || is_resource($value)) {
            throw new \InvalidArgumentException('Invalid string');
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
     * Sets the encoding
     *
     * @param string
     */
    public function setEncoding($encoding)
    {
        if ($encoding === null) {
            $this->encoding = mb_internal_encoding();
        } else {
            $this->encoding = $this->getRealEncoding($encoding);
        }
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
    public function isEncodingSupported($encoding)
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
        $encoding = strtolower($encoding);

        if (isset (static::supportedEncodings()[$encoding])) {
            return static::supportedEncodings()[$encoding];
        }

        throw new \Exception('Encoding is not supported (' . $encoding . ')');
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

        if ($this->length() < $from) {
            throw new \LengthException('From parameter must be smaller than the length of the string');
        }

        if ($this->length() < $length) {
            throw new \LengthException('Length parameter must be smaller than the length of the string');
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
            throw new \InvalidArgumentException('Invalid offset (' . $offset . ')');
        }

        return $this->substr($offset, 1);
    }

    /**
     * @see ArrayAccess::offsetSet
     */
    public function offsetSet($offset, $value)
    {
        $value = String::castNullable($value, $this->encoding);

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
