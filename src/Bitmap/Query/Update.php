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
     * @return Delete
     */
    public static function fromEntity(Entity $entity)
    {
        return new Update($entity);
    }

    protected function fieldValues()
    {
        $sql = [];

        foreach (parent::fieldValues() as $name => $value) {
            $sql[] = sprintf("`%s` = %s", $name, $value);
        }

        return implode(self::VALUES_LIST_DELIMITER, $sql);
    }

    public function sql()
    {
        return sprintf(
            "update `%s` set %s where `%s` = %s",
            $this->mapper->getTable(),
            $this->fieldValues(),
            $this->mapper->getPrimary()->getColumn(),
            $this->mapper->getPrimary()->get($this->entity)
        );
    }
}