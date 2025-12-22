<?php
require_once __DIR__ . '/../services/Menu.php';

class MenuController {
    private MenuService $service;

    public function __construct(PDO $db) {
        $this->service = new MenuService($db);
    }

    /* -------------------------------------------------------
     * VIEW MENU
     * ------------------------------------------------------- */
    public function view(): void {
        try {
            $menuTree = $this->service->getMenuTree();
            require __DIR__ . '/../views/menu.php';
        } catch (Exception $e) {
            echo "❌ Error loading menu: " . $e->getMessage();
        }
    }

    /* -------------------------------------------------------
     * ADD NEW MENU ITEM
     * ------------------------------------------------------- */
    public function add(array $data): array {
        $title = trim($data['title'] ?? '');
        $parentId = $data['parent_id'] ?? null;

        if (empty($title)) {
            return ['status' => 'error', 'message' => 'Menu title is required.'];
        }

        $added = $this->service->addMenuItem($title, $parentId);
        return $added ? ['status' => 'success'] : ['status' => 'error', 'message' => 'Failed to add menu item.'];
    }

    /* -------------------------------------------------------
     * UPDATE MENU ITEM
     * ------------------------------------------------------- */
    public function update(int $id, array $data): array {
        $title = trim($data['title'] ?? '');
        $parentId = $data['parent_id'] ?? null;

        if (empty($title)) {
            return ['status' => 'error', 'message' => 'Menu title is required.'];
        }

        $updated = $this->service->updateMenuItem($id, $title, $parentId);
        return $updated ? ['status' => 'success'] : ['status' => 'error', 'message' => 'Failed to update menu item.'];
    }

    /* -------------------------------------------------------
     * DELETE MENU ITEM
     * ------------------------------------------------------- */
    public function delete(int $id): array {
        $deleted = $this->service->deleteMenuItem($id);
        return $deleted ? ['status' => 'success'] : ['status' => 'error', 'message' => 'Failed to delete menu item.'];
    }
}
?>
