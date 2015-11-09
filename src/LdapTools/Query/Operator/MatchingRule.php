<?php
/**
 * This file is part of the LdapTools package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LdapTools\Query\Operator;

use LdapTools\Exception\LdapQueryException;
use LdapTools\Utilities\LdapUtilities;

/**
 * Use LDAP matching rule OIDs.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class MatchingRule extends BaseOperator
{
    const SYMBOL = '=';

    /**
     * @var string
     */
    protected $oid = '';

    /**
     * @param string $attribute
     * @param string $oid
     * @param mixed $value
     */
    public function __construct($attribute, $oid, $value)
    {
        $this->validOperators = [ self::SYMBOL ];
        $this->operatorSymbol = self::SYMBOL;
        $this->attribute = $attribute;
        $this->value = $value;
        $this->oid = $oid;
    }

    /**
     * {@inheritdoc}
     */
    public function getLdapFilter()
    {
        if (!LdapUtilities::isValidAttributeFormat($this->oid)) {
            throw new LdapQueryException(sprintf('Matching rule "%s" is not a valid format.', $this->oid));
        }

        return self::SEPARATOR_START
            .$this->getAttributeToQuery()
            .':'.$this->oid.':'
            .$this->operatorSymbol
            .LdapUtilities::escapeValue($this->getValueForQuery(), null, LDAP_ESCAPE_FILTER)
            .self::SEPARATOR_END;
    }
}
