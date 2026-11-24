<?php
/** Helpers globaux CyberShield */

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

function flash(string $message, string $type = 'success'): void
{
    $_SESSION['flash'][] = ['message' => $message, 'type' => $type];
}

function get_flashes(): array
{
    $f = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $f;
}

function old(string $key, $default = '')
{
    return $_SESSION['old'][$key] ?? $default;
}

function set_old(array $data): void
{
    $_SESSION['old'] = $data;
}

function clear_old(): void
{
    unset($_SESSION['old']);
}

function format_date($date, string $format = 'd/m/Y'): string
{
    if (!$date || $date === '0000-00-00 00:00:00') return '—';
    $ts = is_numeric($date) ? (int) $date : strtotime($date);
    return $ts ? date($format, $ts) : '—';
}

function money($amount): string
{
    return number_format((float) $amount, 2, ',', ' ') . ' DT';
}

function humanize($value): string
{
    $labels = [
        'admin' => 'Admin', 'technicien' => 'Technicien', 'client' => 'Client',
        'actif' => 'Actif', 'inactif' => 'Inactif', 'ouverte' => 'Ouverte',
        'en_attente' => 'En attente', 'planifie' => 'Planifiée', 'en_cours' => 'En cours',
        'termine' => 'Terminée', 'annule' => 'Annulée', 'resolue' => 'Résolue',
        'faible' => 'Faible', 'moyenne' => 'Moyenne', 'haute' => 'Haute', 'critique' => 'Critique',
        'en_maintenance' => 'En maintenance', 'compromis' => 'Compromis',
        '1' => 'Actif', '0' => 'Inactif',
    ];
    $value = (string) $value;
    return $labels[$value] ?? ucfirst(str_replace('_', ' ', $value));
}

function badge($value): string
{
    $v = (string) $value;
    $map = [
        'actif' => 'success', 'termine' => 'success', 'resolue' => 'success', 'faible' => 'success',
        'en_attente' => 'warning', 'moyenne' => 'warning', 'en_maintenance' => 'warning', 'planifie' => 'purple',
        'en_cours' => 'info', 'ouverte' => 'info',
        'critique' => 'danger', 'haute' => 'danger', 'compromis' => 'danger', 'annule' => 'danger', 'inactif' => 'muted',
        '1' => 'success', '0' => 'muted',
    ];
    $class = $map[$v] ?? 'muted';
    return '<span class="badge badge-' . $class . '">' . e(humanize($v)) . '</span>';
}

/** Icônes SVG inline (aucune dépendance externe) */
function icon(string $name, int $size = 20): string
{
    $p = [
        'shield'   => '<path d="M12 2 4 5v6c0 5 3.4 9.4 8 11 4.6-1.6 8-6 8-11V5l-8-3Z"/><path d="m9 12 2 2 4-4"/>',
        'home'     => '<path d="m3 10 9-7 9 7v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 21v-7h6v7"/>',
        'users'    => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
        'user'     => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
        'device'   => '<rect x="2" y="4" width="20" height="14" rx="2"/><path d="M8 21h8M12 18v3"/>',
        'wrench'   => '<path d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-3 3-3-3z"/>',
        'alert'    => '<path d="M12 9v4M12 17h.01"/><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/>',
        'report'   => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M8 13h8M8 17h5"/>',
        'settings' => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1-2.8 2.8-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.6V21h-4v-.1A1.7 1.7 0 0 0 8.9 19a1.7 1.7 0 0 0-1.9.3l-.1.1-2.8-2.8.1-.1a1.7 1.7 0 0 0 .3-1.9 1.7 1.7 0 0 0-1.6-1H3v-4h.1A1.7 1.7 0 0 0 5 8.9a1.7 1.7 0 0 0-.3-1.9l-.1-.1 2.8-2.8.1.1a1.7 1.7 0 0 0 1.9.3 1.7 1.7 0 0 0 1-1.6V3h4v.1a1.7 1.7 0 0 0 1 1.6 1.7 1.7 0 0 0 1.9-.3l.1-.1 2.8 2.8-.1.1a1.7 1.7 0 0 0-.3 1.9 1.7 1.7 0 0 0 1.6 1H21v4h-.1a1.7 1.7 0 0 0-1.5 1z"/>',
        'logout'   => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>',
        'search'   => '<circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>',
        'plus'     => '<path d="M12 5v14M5 12h14"/>',
        'edit'     => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>',
        'trash'    => '<path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v5M14 11v5"/>',
        'eye'      => '<path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>',
        'chart'    => '<path d="M3 3v18h18M7 16v-5M12 16V7M17 16v-3"/>',
        'lock'     => '<rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>',
        'mail'     => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 6 10-6"/>',
        'phone'    => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2 4.2 2 2 0 0 1 4 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.2-1.1a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7a2 2 0 0 1 1.7 2z"/>',
        'menu'     => '<path d="M3 6h18M3 12h18M3 18h18"/>',
        'check'    => '<path d="m5 12 4 4L19 6"/>',
        'clock'    => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'target'   => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1"/>',
        'document' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M8 13h8M8 17h8"/>',
        'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'spark'    => '<path d="m12 2 1.8 6.2L20 10l-6.2 1.8L12 18l-1.8-6.2L4 10l6.2-1.8z"/>',
    ];
    $paths = $p[$name] ?? $p['shield'];
    return '<svg class="icon" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $paths . '</svg>';
}

/** Graphique barres SVG autonome (pas de CDN) */
function chart_bar(array $data, string $color = '#22d3ee'): string
{
    if (!$data) return '<p class="muted">Aucune donnée</p>';
    $max = max(1, max(array_values($data)));
    $w = 560; $h = 220; $pad = 34; $n = count($data);
    $bw = ($w - $pad * 2) / $n;
    $svg = '<svg viewBox="0 0 ' . $w . ' ' . $h . '" class="chart" preserveAspectRatio="xMidYMid meet">';
    for ($i = 0; $i <= 4; $i++) {
        $y = $pad + ($h - $pad * 2) * $i / 4;
        $svg .= '<line x1="' . $pad . '" y1="' . $y . '" x2="' . ($w - $pad) . '" y2="' . $y . '" stroke="#1e3257" stroke-dasharray="3 4"/>';
        $svg .= '<text x="4" y="' . ($y + 4) . '" fill="#8fa6c8" font-size="11">' . round($max * (4 - $i) / 4) . '</text>';
    }
    $i = 0;
    foreach ($data as $label => $val) {
        $bh = ($h - $pad * 2) * $val / $max;
        $x = $pad + $i * $bw + $bw * .2;
        $y = $h - $pad - $bh;
        $svg .= '<rect x="' . $x . '" y="' . $y . '" width="' . ($bw * .6) . '" height="' . $bh . '" rx="4" fill="' . $color . '" opacity=".85"/>';
        $svg .= '<text x="' . ($x + $bw * .3) . '" y="' . ($y - 6) . '" fill="#e6f0ff" font-size="11" text-anchor="middle">' . $val . '</text>';
        $svg .= '<text x="' . ($x + $bw * .3) . '" y="' . ($h - 10) . '" fill="#8fa6c8" font-size="10" text-anchor="middle">' . e(humanize($label)) . '</text>';
        $i++;
    }
    return $svg . '</svg>';
}

/** Graphique camembert SVG autonome */
function chart_donut(array $data): string
{
    if (!$data) return '<p class="muted">Aucune donnée</p>';
    $colors = ['#22d3ee', '#3b82f6', '#f59e0b', '#a855f7', '#ef4444', '#10b981'];
    $total = array_sum($data);
    $cx = 90; $cy = 90; $r = 62; $sw = 26;
    $circ = 2 * M_PI * $r;
    $svg = '<svg viewBox="0 0 180 180" class="donut"><circle cx="90" cy="90" r="' . $r . '" fill="none" stroke="#152544" stroke-width="' . $sw . '"/>';
    $offset = 0; $i = 0;
    foreach ($data as $label => $val) {
        if ($val <= 0) { $i++; continue; }
        $len = $circ * $val / $total;
        $svg .= '<circle cx="90" cy="90" r="' . $r . '" fill="none" stroke="' . $colors[$i % count($colors)] . '" stroke-width="' . $sw . '" stroke-dasharray="' . ($len - 2) . ' ' . ($circ - $len + 2) . '" stroke-dashoffset="' . (-$offset) . '" transform="rotate(-90 90 90)"/>';
        $offset += $len; $i++;
    }
    $svg .= '<text x="90" y="86" fill="#e6f0ff" font-size="24" font-weight="700" text-anchor="middle">' . $total . '</text>';
    $svg .= '<text x="90" y="104" fill="#8fa6c8" font-size="11" text-anchor="middle">Total</text></svg>';
    $legend = '<ul class="legend">';
    $i = 0;
    foreach ($data as $label => $val) {
        $legend .= '<li><span style="background:' . $colors[$i % count($colors)] . '"></span>' . e(humanize($label)) . ' <b>' . $val . '</b></li>';
        $i++;
    }
    return '<div class="donut-wrap">' . $svg . $legend . '</div>';
}

/** Options statiques pour les champs select */
/** Label de mois français à partir de "YYYY-MM" */
function month_label(string $key): string
{
    $names = ['janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'];
    $m = (int) substr($key, 5, 2);
    return ($names[$m - 1] ?? $key) . ' ' . substr($key, 0, 4);
}

/** Requête « 6 derniers mois » compatible MySQL et SQLite */
function db_month_query(string $where = '1=1'): string
{
    if (DB::driver() === 'sqlite') {
        return "SELECT strftime('%Y-%m', date_demande) k, COUNT(*) c FROM interventions WHERE $where AND date_demande >= date('now','-6 months') GROUP BY k ORDER BY k";
    }
    return "SELECT DATE_FORMAT(date_demande, '%Y-%m') k, COUNT(*) c FROM interventions WHERE $where AND date_demande >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) GROUP BY k ORDER BY k";
}

function status_options(string $type): array
{
    switch ($type) {
        case 'role':     return ['admin' => 'Admin', 'technicien' => 'Technicien', 'client' => 'Client'];
        case 'statut':   return ['actif' => 'Actif', 'inactif' => 'Inactif'];
        case 'device':   return ['actif' => 'Actif', 'en_maintenance' => 'En maintenance', 'inactif' => 'Inactif', 'compromis' => 'Compromis'];
        case 'intervention': return ['en_attente' => 'En attente', 'planifie' => 'Planifiée', 'en_cours' => 'En cours', 'termine' => 'Terminée', 'annule' => 'Annulée'];
        case 'alert':    return ['ouverte' => 'Ouverte', 'en_cours' => 'En cours', 'resolue' => 'Résolue'];
        case 'gravite':  return ['faible' => 'Faible', 'moyenne' => 'Moyenne', 'haute' => 'Haute', 'critique' => 'Critique'];
        default:         return [];
    }
}
