<?php
require_once __DIR__ . '/../config/database.php';

class MenuRepository {
    private PDO $conn;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    // Get all menu items
    public function getAll(): array {
        $stmt = $this->conn->prepare("SELECT id, parent_id, title, link FROM menu ORDER BY parent_id, id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Create a new menu item
    public function create(string $title, string $link, ?int $parentId = null): bool {
        $stmt = $this->conn->prepare("INSERT INTO menu (title, link, parent_id) VALUES (:title, :link, :parent_id)");
        return $stmt->execute([
            ':title' => $title,
            ':link' => $link,
            ':parent_id' => $parentId
        ]);
    }

    // Update a menu item
    public function update(int $id, string $title, string $link, ?int $parentId = null): bool {
        $stmt = $this->conn->prepare("UPDATE menu SET title = :title, link = :link, parent_id = :parent_id WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':title' => $title,
            ':link' => $link,
            ':parent_id' => $parentId
        ]);
    }
    public function addMenuItem(string $title, ?int $parentId = null): bool {
        if (empty($title)) return false;

        $stmt = $this->conn->prepare("INSERT INTO menu (title, parent_id) VALUES (:title, :parent_id)");
        return $stmt->execute([
            ':title' => $title,
            ':parent_id' => $parentId
        ]);
    }
    public function updateMenuItem(int $id, string $title, ?int $parentId = null): bool {
        if (empty($title)) return false;

        $stmt = $this->conn->prepare("UPDATE menu SET title = :title, parent_id = :parent_id WHERE id = :id");
        return $stmt->execute([
            ':title' => $title,
            ':parent_id' => $parentId,
            ':id' => $id
        ]);
    }
    public function deleteMenuItem(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM menu WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // Delete a menu item
    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM menu WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>
