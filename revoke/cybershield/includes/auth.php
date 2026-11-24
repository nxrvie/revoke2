<?php
/** Gestion des sessions et des rôles */

function current_user(): ?array
{
    static $cache = null;
    if ($cache !== null) return $cache;
    if (empty($_SESSION['user_id'])) return null;
    $cache = DB::one("SELECT * FROM users WHERE id = ? AND statut = 'actif'", [$_SESSION['user_id']]);
    if (!$cache) {
        unset($_SESSION['user_id']);
        return null;
    }
    return $cache;
}

function require_login(): void
{
    if (!current_user()) redirect('login.php');
}

function require_role(string ...$roles): void
{
    require_login();
    if (!in_array(current_user()['role'], $roles, true)) {
        flash('Accès refusé : cette page n\'est pas autorisée pour votre rôle.', 'danger');
        redirect('dashboard.php');
    }
}

function is_role(string $role): bool
{
    $u = current_user();
    return $u && $u['role'] === $role;
}
