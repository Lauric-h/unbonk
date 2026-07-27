<?php

namespace App\Domain\Shared\Repository;

/**
 * @template T of object
 */
interface ObjectRepositoryInterface
{
    /**
     * @return T
     */
    public function get(mixed $id): object;

    /**
     * @return T|null
     */
    public function find(mixed $id): ?object;

    /**
     * @param T $object
     */
    public function add(object $object): void;

    /**
     * @param T $object
     */
    public function update(object $object): void;

    /**
     * @param T $object
     */
    public function remove(object $object): void;

    public function write(): void;

    /**
     * Clears the entity manager to free memory.
     */
    public function clear(): void;
}
