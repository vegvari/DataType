<?php

namespace Data\Type;

use InvalidArgumentException;
use \Data\Type\Exceptions\InvalidStringException;

class StringType extends Type
{
    /**
     * @var string
     */
    const TYPE = 'string';

    /**
     * @var string
     */
    protected $encoding;

    /**
     * @var int
     */
    protected $iterator_position;

    /**
     * Constructor
     *
     * @param mixed $value
     * @param strig $encoding
     */
    public function __construct($value = null, $encoding = null)
    {
        $this->setEncoding($encoding);
        parent::__construct($value);
    }

    /**
     * Set the encoding
     *
     * @param  string $encoding
     * @return void
     */
    protected function setEncoding($encoding)
    {
        if ($encoding === null) {
            $this->encoding = mb_internal_encoding();
        } else {
            $this->encoding = $this->getRealEncoding($encoding);
        }
    }

    /**
     * Get the encoding
     *
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * Format the value
     *
     * @param  string      $encoding
     * @return string|null
     */
    public function value($encoding = null)
    {
        if ($encoding !== null) {
            $encoding = $this->getRealEncoding($encoding);
        } else {
            $encoding = $this->encoding;
        }

        if ($this->value !== null) {
            return mb_convert_encoding($this->value, $encoding, $this->encoding);
        }
    }

    /**
     * Check the value
     *
     * @param  mixed       $value
     * @return string|null
     */
    protected function check($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

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
            if ($value->isNull()) {
                return null;
            }

            return (string) $value;
        } else {
            if (is_array($value)) {
                throw new InvalidStringException('Invalid string: array');
            }

            if (is_resource($value)) {
                throw new InvalidStringException('Invalid string: resource');
            }

            if (is_object($value)) {
                throw new InvalidStringException('Invalid string: object');
            }
        }

        return mb_convert_encoding($value, $this->encoding, $this->encoding);
    }

    /**
     * Creates an array with all of the aliases->encodings
     *
     * @return array
     */
    public static function supportedEncodings()
    {
        static $supported_encodings = ['pass' => 'pass'];

        if ($supported_encodings === ['pass' => 'pass']) {
            $supported = mb_list_encodings();

            foreach ($supported as $key => $value) {
                $supported_encodings[strtolower($value)] = $value;

                foreach (mb_encoding_aliases($value) as $k => $v) {
                    $supported_encodings[strtolower($v)] = $value;
                }
            }
        }

        return $supported_encodings;
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

        if (isset(static::supportedEncodings()[$encoding])) {
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
    public static function getRealEncoding($encoding)
    {
        if (static::isEncodingSupported($encoding) === false) {
            throw new InvalidArgumentException('Encoding is not supported: "' . $encoding . '"');
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
        if ($this->value === null) {
            return 0;
        }

        return mb_strlen($this->value, $this->encoding);
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
        $from = Cast::uInt($from);
        $length = Cast::_uInt($length);

        if ($from > $this->length()) {
            throw new InvalidArgumentException('From parameter is greater than the length of the string');
        }

        if ($length > $this->length()) {
            throw new InvalidArgumentException('Length parameter is greater than the length of the string');
        }

        return mb_substr($this->value, $from, $length, $this->encoding);
    }

    /**
     * Lowercase
     *
     * @return this
     */
    public function toLower()
    {
        $this->set(mb_strtolower($this->value, $this->encoding));
        return $this;
    }

    /**
     * Uppercase
     *
     * @return this
     */
    public function toUpper()
    {
        $this->set(mb_strtoupper($this->value, $this->encoding));
        return $this;
    }

    /**
     * Uppercase first letter
     *
     * @return this
     */
    public function upperFirst()
    {
        $this->set(mb_strtoupper(mb_substr($this->value, 0, 1, $this->encoding), $this->encoding) . mb_substr($this->value, 1, null, $this->encoding));
        return $this;
    }

    /**
     * Uppercase first letter of every word
     *
     * @return this
     */
    public function upperWords()
    {
        $this->set(mb_convert_case($this->value, MB_CASE_TITLE, $this->encoding));
        return $this;
    }
}
