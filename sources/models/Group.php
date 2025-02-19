<?php

require_once __DIR__ . "/../core/Database.php";
require_once __DIR__ . "/../core/QueryBuilder.php";

class Group
{
    public static function addUserToGroup(int $group_id, int $user_id, string $role = 'read'): bool
    {
        $pdo = Database::getConnection();
        $query = $pdo->prepare("
            INSERT INTO group_members (group_id, user_id, role) 
            VALUES (:group_id, :user_id, :role)
            ON DUPLICATE KEY UPDATE role = VALUES(role)
        ");
    
        return $query->execute([
            'group_id' => $group_id,
            'user_id' => $user_id,
            'role' => $role
        ]);
    }
    

    public static function removeUserFromGroup(int $group_id, int $user_id): bool
    {
        $pdo = Database::getConnection();
        $query = $pdo->prepare("
            DELETE FROM group_members WHERE group_id = :group_id AND user_id = :user_id
        ");
        return $query->execute([
            'group_id' => $group_id,
            'user_id' => $user_id
        ]);
    }
    
    public static function getUserGroups(int $user_id): array
    {
        $pdo = Database::getConnection();

        $query = $pdo->prepare("
            SELECT DISTINCT g.id, g.name ,g.owner_id
            FROM groups g
            LEFT JOIN group_members gm ON g.id = gm.group_id
            WHERE g.owner_id = :owner_id OR gm.user_id = :member_id
        ");

        $query->execute([
            'owner_id' => $user_id,
            'member_id' => $user_id
        ]);

        return $query->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function isUserInGroup(int $user_id, int $group_id): bool
    {
        $pdo = Database::getConnection();
    
        $query = $pdo->prepare("
            SELECT 1 FROM groups g
            LEFT JOIN group_members gm ON g.id = gm.group_id
            WHERE (g.owner_id = :user_id1 OR gm.user_id = :user_id2)
            AND g.id = :group_id
        ");
    
        $query->execute([
            'user_id1' => $user_id,
            'user_id2' => $user_id,
            'group_id' => $group_id
        ]);
    
        return (bool) $query->fetch();
    }
    
    public static function getGroupById(int $group_id): ?array
    {
        $queryBuilder = new QueryBuilder();
        
        return $queryBuilder
            ->select(["id", "name", "owner_id"])
            ->from("groups")
            ->where("id", $group_id)
            ->fetch();
    }
    public static function getOwnerId(int $group_id): ?int
    {
        $queryBuilder = new QueryBuilder();
        $group = $queryBuilder
            ->select(["owner_id"])
            ->from("groups")
            ->where("id", $group_id)
            ->fetch();

        return $group ? $group['owner_id'] : null;
    }
    public static function isOwner(int $user_id, int $group_id): bool
    {
        $pdo = Database::getConnection();
        $query = $pdo->prepare("SELECT 1 FROM groups WHERE id = ? AND owner_id = ?");
        $query->execute([$group_id, $user_id]);

        return (bool) $query->fetch();
    }

    public static function getGroupMembers(int $group_id): array
    {
        $pdo = Database::getConnection();
        $query = $pdo->prepare("
            SELECT u.id, u.firstname, u.lastname, gm.role 
            FROM users u
            JOIN group_members gm ON u.id = gm.user_id
            WHERE gm.group_id = ?
        ");
        $query->execute([$group_id]);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function addMember(int $group_id, int $user_id, string $role = 'read'): bool
    {
        $pdo = Database::getConnection();
        $query = $pdo->prepare("INSERT INTO group_members (group_id, user_id, role) VALUES (?, ?, ?)");
        return $query->execute([$group_id, $user_id, $role]);
    }

    public static function removeMember(int $group_id, int $user_id): bool
    {
        $pdo = Database::getConnection();
        $query = $pdo->prepare("DELETE FROM group_members WHERE group_id = ? AND user_id = ?");
        return $query->execute([$group_id, $user_id]);
    }

    public static function updateMemberRole(int $group_id, int $user_id, string $role): bool
    {
        $pdo = Database::getConnection();
        $query = $pdo->prepare("UPDATE group_members SET role = ? WHERE group_id = ? AND user_id = ?");
        return $query->execute([$role, $group_id, $user_id]);
    }
}
