<?php
/**
 * includes/upload_handler.php
 *
 * Shared helpers for resolving logo/menu-image uploads and building the
 * structured JSON columns (guide_content, menu_content) that are stored in
 * the campaigns and free_vouchers tables.
 */

function resolveLogoUpload(array $data, array $files, string $field = 'logo'): ?string
{
    if (!empty($data[$field . '_base64'])) {
        return uploadLogoBase64($data[$field . '_base64'], UPLOAD_DIR);
    }
    if (isset($files[$field]) && $files[$field]['error'] !== UPLOAD_ERR_NO_FILE) {
        return uploadLogo($files[$field], UPLOAD_DIR);
    }
    return null;
}

function resolveMenuImageUpload(array $data, array $files): ?string
{
    if (!empty($data['menu_image_base64'])) {
        return uploadLogoBase64($data['menu_image_base64'], UPLOAD_DIR);
    }
    if (isset($files['menu_image']) && $files['menu_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        return uploadLogo($files['menu_image'], UPLOAD_DIR);
    }
    return ($data['existing_menu_image'] ?? null) ?: null;
}

function buildGuideContent(array $data): ?string
{
    $address = $data['guide_address'] ?? null;
    $time    = $data['guide_time']    ?? null;
    $phone   = $data['guide_phone']   ?? null;

    if ($address === null && $time === null && $phone === null) {
        return null;
    }

    return json_encode([
        'address' => $address ?? '',
        'time'    => $time    ?? '',
        'phone'   => $phone   ?? '',
    ]);
}

function buildMenuContent(array $data, ?string $menuImageName): ?string
{
    $items = $data['menu_items'] ?? null;
    $note  = $data['menu_note']  ?? null;

    if ($items === null && $menuImageName === null && $note === null) {
        return null;
    }

    return json_encode([
        'note'  => $note          ?? '',
        'items' => $items         ?? '',
        'image' => $menuImageName ?? '',
    ]);
}
