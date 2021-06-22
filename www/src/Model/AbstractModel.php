<?php


namespace App\Model;


use PDO;
use PDOException;
use PDOStatement;

abstract class AbstractModel
{
    private DatabaseConnection $databaseConnection;

    private PDO $connection;

    private const FETCH = "fetch";
    private const FETCH_ALL = "fetch_all";
    private const STATUS = "status";

    public function __construct()
    {
        $this->databaseConnection = new DatabaseConnection();
        $this->connection = $this->databaseConnection->getConnection();
    }

    protected function execute(string $sql, array $variables = [], string $resultType = self::FETCH)
    {
        $prepare = $this->connection->prepare($sql);

        $this->bindParam($prepare, $variables);

        $status = $prepare->execute();

        if ($resultType === self::FETCH) {
            return $prepare->fetch();
        } else if ($resultType === self::FETCH_ALL) {
            return $prepare->fetchAll();
        } else if ($resultType === self::STATUS) {
            return $status;
        }

        throw new PDOException("No option is true");
    }

    private function bindParam(PDOStatement $PDOStatement, array $variables = []): void
    {
        foreach ($variables as $key => &$value) {
            $PDOStatement->bindParam($key, $value);
        }
    }

}