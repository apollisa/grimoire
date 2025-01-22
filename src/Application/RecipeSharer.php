<?php

namespace App\Application;

use App\Domain\Recipe\RecipeId;
use App\Domain\Recipe\RecipeRepository;
use App\Infrastructure\Recipe\RecipeIdType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Component\String\Slugger\SluggerInterface;
use Throwable;

class RecipeSharer
{
    public function __construct(
        private readonly Connection $connection,
        private readonly RecipeRepository $repository,
        private readonly SluggerInterface $slugger,
    ) {}

    /**
     * @throws Exception
     * @throws Throwable
     */
    public function share(RecipeId $id): string
    {
        return $this->connection
            ->executeQuery(
                "SELECT slug FROM recipe WHERE id = :id",
                ["id" => $id],
                ["id" => RecipeIdType::NAME],
            )
            ->fetchOne() ?:
            $this->connection->transactional(
                fn(): string => $this->createSlug($id),
            );
    }

    /**
     * @throws Exception
     */
    private function createSlug(RecipeId $id): string
    {
        $recipe = $this->repository->ofId($id);
        $slug = $this->slugger->slug($recipe->name())->lower();
        $this->connection->executeStatement(
            "UPDATE recipe SET slug = :slug WHERE id = :id",
            ["slug" => $slug, "id" => $id],
            ["id" => RecipeIdType::NAME],
        );
        return $slug;
    }
}
