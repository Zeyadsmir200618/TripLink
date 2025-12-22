<?php
require_once __DIR__ . '/../Models/Menu.php';

class MenuService {
    private MenuRepository $repo;

    public function __construct(PDO $db) {
        $this->repo = new MenuRepository($db);
    }

    // Build nested menu
    public function buildMenuTree(array $items, ?int $parentId = null): array {
        $branch = [];
        foreach ($items as $item) {
            if ((int)$item['parent_id'] === $parentId) {
                $children = $this->buildMenuTree($items, $item['id']);
                if ($children) $item['children'] = $children;
                $branch[] = $item;
            }
        }
        return $branch;
    }

    // Get the full menu tree from database
    public function getMenuTree(): array {
        $items = $this->repo->getAll();
        return $this->buildMenuTree($items);
    }
}
?>
