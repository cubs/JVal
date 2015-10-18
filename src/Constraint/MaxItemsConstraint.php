<?php

namespace JsonSchema\Constraint;

use JsonSchema\Constraint;
use JsonSchema\Context;
use JsonSchema\Exception\ConstraintException;
use JsonSchema\Types;
use JsonSchema\Walker;
use stdClass;

class MaxItemsConstraint implements Constraint
{
    public function keywords()
    {
        return ['maxItems'];
    }

    public function supports($type)
    {
        return $type === Types::TYPE_ARRAY;
    }

    public function normalize(stdClass $schema, Context $context, Walker $walker)
    {
        if (!is_int($schema->maxItems)) {
            throw new ConstraintException(
                'maxItems must be an integer',
                ConstraintException::MAX_ITEMS_NOT_INTEGER,
                $context
            );
        }

        if ($schema->maxItems <= 0) {
            throw new ConstraintException(
                'maxItems must be greater than 0',
                ConstraintException::MAX_ITEMS_NOT_POSITIVE,
                $context
            );
        }
    }

    public function apply($instance, stdClass $schema, Context $context, Walker $walker)
    {
        if (count($instance) > $schema->maxItems) {
            $context->addViolation(
                'number of items should be less than, or equal to, %s',
                [$schema->maxItems]
            );
        }
    }
}
