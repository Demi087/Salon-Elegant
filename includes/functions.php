<?php
function siteUrl(string $path = ''): string {
    global $APP_BASE_PATH;
    return $APP_BASE_PATH . '/' . ltrim($path, '/');
}

function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function isLoggedIn(): bool {
    return !empty($_SESSION['user_id']);
}

function isAdmin(): bool {
    return isLoggedIn() && (($_SESSION['role'] ?? '') === 'admin');
}

function currentUsername(): string {
    return $_SESSION['username'] ?? '';
}

function currentUserId(): ?int {
    return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
}

function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

function formatPrice($amount): string {
    return number_format((float)$amount, 0, '.', ' ') . ' руб.';
}

function formatDate($dateString): string {
    $timestamp = strtotime((string)$dateString);
    if (!$timestamp) return '';
    return date('d.m.Y', $timestamp);
}

function formatDateTime($dateString): string {
    $timestamp = strtotime((string)$dateString);
    if (!$timestamp) return '';
    return date('d.m.Y H:i', $timestamp);
}
