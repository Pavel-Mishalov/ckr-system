<?php

namespace App;

use PDO;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class Main
{
    private Config $config;
    private PDO $db;

    private Environment $twig;

    public function __construct(Config $config = new Config)
    {
        $this->config = $config;
        $this->twig = new Environment(new FilesystemLoader($this->config->base_dir . '/templates'));
        $this->db = new PDO(
            sprintf("mysql:host=%s;dbname=%s;charset=utf8mb4", $this->config->db_host, $this->config->db_name),
            $this->config->db_user,
            $this->config->db_password,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ],
        );
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function run(): void
    {
        $groupId = (int)filter_input(INPUT_GET, 'group', FILTER_VALIDATE_INT);

        echo $this->twig->render('index.twig', [
            'tree'     => $this->getGroupTree($groupId),
            'active'   => $groupId,
            'products' => $this->getProduct($groupId),
        ]);
    }

    public function getProduct(int $group_id = 0): array
    {
        if ($group_id == 0) {
            $request = $this->db->prepare('SELECT name, id FROM products ORDER BY name');
            $request->execute();
            return $request->fetchAll(PDO::FETCH_ASSOC);
        }

        $request = $this->db->prepare(<<<sql
SELECT name, id
FROM products
WHERE exists(
    select 1 from group_tree
    where group_tree.child_id = products.id_group and group_tree.parent_id = :group_id
)
ORDER BY name
sql
        );

        $request->execute(['group_id' => $group_id]);

        return $request->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getGroupTree(int $active = 0): array
    {
        $request = $this->db->prepare(<<<sql
select
    id,
    name,
    id_parent,
    (select count(1) from products where products.id_group in (select group_tree.child_id from group_tree where group_tree.parent_id = `groups`.id)) as num
from `groups`
where
    -- Корневые всегда выводим
    id_parent is NULL
OR
    -- Выводим всех родителей целевой
    exists(
        select 1 from group_tree where child_id = :active and group_tree.parent_id = `groups`.id
    )
OR
    -- Выводим все дочерние целевой
    exists(
        select 1 from group_tree where child_id = `groups`.id and group_tree.parent_id = :active and depth = 1
    )
sql
        );
        $request->execute(['active' => $active]);

        $indexedItems = [];
        $tree = [];

        while ($row = $request->fetch(PDO::FETCH_ASSOC)) {
            $row['child'] = [];
            $row['active'] = $row['id'] === $active;
            $indexedItems[$row['id']] = $row;
        }

        foreach ($indexedItems as $id => &$item) {
            if (is_null($item['id_parent'])) {
                $tree[] = &$item;
                continue;
            }

            $parentId = $item['id_parent'];
            if (isset($indexedItems[$parentId])) {
                $indexedItems[$parentId]['child'][] = &$item;
            }
        }

        return $tree;
    }

    public function dd($arg)
    {
        echo "<pre>";
        print_r($arg);
        exit();
    }

}