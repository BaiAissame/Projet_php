<?php

require_once __DIR__ . "/../core/Database.php";

try {
    $pdo = Database::getConnection();
    
    echo "Démarrage du seeding...\n";

    // 🔥 Insérer des utilisateurs
    $users = [
        ["email" => "admin@example.com", "password" => password_hash("admin123", PASSWORD_DEFAULT), "firstname" => "Admin", "lastname" => "User"],
        ["email" => "user1@example.com", "password" => password_hash("password123", PASSWORD_DEFAULT), "firstname" => "John", "lastname" => "Doe"],
        ["email" => "user2@example.com", "password" => password_hash("password123", PASSWORD_DEFAULT), "firstname" => "Jane", "lastname" => "Doe"]
    ];

    foreach ($users as $user) {
        $query = $pdo->prepare("
            INSERT INTO users (email, password, firstname, lastname) 
            VALUES (:email, :password, :firstname, :lastname)
        ");
        $query->execute($user);
    }
    echo "✅ Utilisateurs insérés\n";

    // 🔥 Récupérer les ID des utilisateurs
    $userIds = $pdo->query("SELECT id FROM users")->fetchAll(PDO::FETCH_COLUMN);

    // 🔥 Insérer des groupes
    $groups = [
        ["name" => "Groupe 1", "owner_id" => $userIds[0]],
        ["name" => "Groupe 2", "owner_id" => $userIds[1]],
        ["name" => "Groupe 3", "owner_id" => $userIds[2]]
    ];

    foreach ($groups as $group) {
        $query = $pdo->prepare("
            INSERT INTO groups (name, owner_id) 
            VALUES (:name, :owner_id)
        ");
        $query->execute($group);
    }
    echo "✅ Groupes insérés\n";

    // 🔥 Récupérer les ID des groupes
    $groupIds = $pdo->query("SELECT id FROM groups")->fetchAll(PDO::FETCH_COLUMN);

    // 🔥 Ajouter des membres aux groupes
    $groupMembers = [
        ["group_id" => $groupIds[0], "user_id" => $userIds[1], "role" => "write"],
        ["group_id" => $groupIds[0], "user_id" => $userIds[2], "role" => "read"],
        ["group_id" => $groupIds[1], "user_id" => $userIds[0], "role" => "read"],
        ["group_id" => $groupIds[2], "user_id" => $userIds[1], "role" => "write"]
    ];

    foreach ($groupMembers as $member) {
        $query = $pdo->prepare("
            INSERT INTO group_members (group_id, user_id, role) 
            VALUES (:group_id, :user_id, :role)
        ");
        $query->execute($member);
    }
    echo "✅ Membres de groupes insérés\n";

    echo "🎉 Seeding terminé avec succès !\n";

} catch (PDOException $e) {
    die("❌ Erreur lors du seeding : " . $e->getMessage() . "\n");
}
