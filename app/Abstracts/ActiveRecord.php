<?php

namespace Mike\Bnovo\Abstracts;

use Mike\Bnovo\Services\Database;
use Mike\Bnovo\Traits\Serializable;
use PDO;
use ReflectionClass;

abstract class ActiveRecord
{
    use Serializable;

    public ?int $id = null;

    public static function tableName(): string
    {
        $reflection = new ReflectionClass(static::class);
        return strtolower($reflection->getShortName());
    }

    public function save(): bool {
        $table = self::tableName();
        $fields = $this->toArray();
        if (isset($fields['id'])) {
            $setPart = [];
            $params = [];

            foreach ($fields as $key => $value) {
                if ($key !== 'id') {
                    $setPart[] = "$key = :$key";
                    $params[":$key"] = $value;
                }
            }

            $setPart = implode(", ", $setPart);
            $sql = "UPDATE {$table} SET $setPart WHERE id = :id";
            $params[":id"] = $fields['id'];
        } else {
            $columns = implode(", ", array_keys($fields));
            $placeholders = implode(", ", array_map(fn($key) => ":$key", array_keys($fields)));
            $sql = "INSERT INTO {$table} ($columns) VALUES ($placeholders)";
            $params = array_combine(array_map(fn($key) => ":$key", array_keys($fields)), array_values($fields));
        }

        $stmt = Database::instance()->query($sql, $params);
        return $stmt->rowCount() > 0;
    }

    public function delete(): bool {
        $table = self::tableName();
        $sql = "DELETE FROM {$table} WHERE id = :id";
        $stmt = Database::instance()->query($sql, [':id' => $this->id]);

        return $stmt->rowCount() > 0;
    }

    public static function find(int $id): ?static {
        $table = self::tableName();
        $sql = "SELECT * FROM {$table} WHERE id = :id";
        $stmt = Database::instance()->query($sql, [':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return static::fromArray($data);
        }

        return null;
    }

    /**
     * @return static[]|null
     */
    public static function all(): ?array {
        $table = self::tableName();
        $sql = "SELECT * FROM {$table}";
        $stmt = Database::instance()->query($sql);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $models = [];

        if ($data) {
            foreach ($data as $item) {
                $models[] = static::fromArray($item);
            }

            return $models;
        }

        return null;
    }
}