<?php

namespace Bitmap\Query;

use Bitmap\Entity;

class Update extends ModifyEntityQuery
{
    const VALUES_LIST_DELIMITER = ", ";

    /**
     * @{@inheritdoc}
     * @param string $class
     *
     * @return Update
     */
    public static function fromEntity(Entity $entity)
    {
        return new Update($entity);
    }

    protected function fieldValueClause()
    {
        $sql = [];

        foreach ($this->fieldValues() as $name => $value) {
            $sql[] = sprintf("`%s` = %s", $name, $value);
        }

        return implode(self::VALUES_LIST_DELIMITER, $sql);
    }

    public function sql()
    {
        return sprintf(
            "update `%s` set %s where `%s` = %s",
            $this->mapper->getTable(),
            $this->fieldValueClause(),
            $this->mapper->getPrimary()->getColumn(),
            $this->mapper->getPrimary()->get($this->entity)
        );
    }
}