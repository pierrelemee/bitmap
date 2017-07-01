<?php

namespace Bitmap;

use Exception;

abstract class ArrayMappedEntity extends Entity
{
    public function initializeMapper(Mapper $mapper)
    {
        $mapping = $this->getMappingArray();

        if (isset($mapping['table'])) {
            $mapper->setTable($mapping['table']);
        }

        $primary = self::getMandatoryConfig($mapping, 'primary', "Missing primary in mapping array");

        $mapper->addPrimary(
            self::getMandatoryConfig($primary, 'name', "Missing name key in primary config"),
            self::getConfig($primary, 'type', null),
            self::getConfig($primary, 'column', null),
            self::getConfig($primary, 'getter', null),
            self::getConfig($primary, 'setter', null)
        );

        foreach ($fields = self::getConfig($mapping, 'fields', []) as $name => $field) {
            $mapper->addField(
                $name,
                self::getConfig($field, 'type', null),
                self::getConfig($field, 'column', null),
                self::getConfig($field, 'getter', null),
                self::getConfig($field, 'setter', null)
            );
        }

        foreach ($associations = self::getConfig($mapping, 'associations', []) as $name => $association) {
            switch ($type = self::getMandatoryConfig($association, 'type', "Missing key type in association $name config")) {
                case 'one':
                    $mapper->addAssociationOne(
                        $name,
                        self::getMandatoryConfig($association, 'class', "Missing key class in association $name config"),
                        self::getConfig($field, 'column', null),
                        self::getConfig($field, 'getter', null),
                        self::getConfig($field, 'setter', null)
                    );
                    break;
                case 'one-to-many':
                    $mapper->addAssociationOneToMany(
                        $name,
                        self::getMandatoryConfig($association, 'class', "Missing key class in association $name config"),
                        self::getConfig($association, 'column', null),
                        self::getConfig($association, 'getter', null),
                        self::getConfig($association, 'setter', null)
                    );
                    break;
                case 'many-to-many':
                    $mapper->addAssociationManyToMany(
                        $name,
                        self::getMandatoryConfig($association, 'class', "Missing key class in association $name config"),
                        self::getMandatoryConfig($association, 'via', "Missing key via in association $name config"),
                        self::getConfig($association, 'via-source', null),
                        self::getConfig($association, 'via-target', null),
                        self::getConfig($association, 'column', null),
                        self::getConfig($association, 'getter', null),
                        self::getConfig($association, 'setter', null)
                    );
                    break;
            }

        }
    }

    protected abstract function getMappingArray();

    private static function getConfig(array $data, $key, $default)
    {
        if (isset($data[$key])) {
            return $data[$key];
        }

        return $default;
    }

    private static function getMandatoryConfig(array $data, $key, $message)
    {
        if (isset($data[$key])) {
            return $data[$key];
        }

        throw new Exception($message);
    }
}