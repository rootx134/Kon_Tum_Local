// admin.js — Entry point (ES Module)
// Imports all feature modules and exposes their functions to window
// so HTML inline onclick handlers continue to work.

import { triggerLogoUpload, previewLogo, updateLogoUploader,
         handleLogoPaste, triggerMenuImageUpload, previewMenuImage,
         handleMenuImagePaste } from './modules/uploader.js';

import { openModal, closeModal } from './modules/modal.js';

import { showSuccess, showError, showInfo, showWarning,
         escapeHtml, copyTextToClipboard,
         switchTab, loadTabContent, invalidateTab, invalidateTabs,
         toggleMobileMenu, closeMobileMenu,
         generateRandomCodes, populateStructuredContent,
         setupScrollTop, initDarkMode, toggleDarkMode,
         switchVoucherSubtab, toggleTakeSection } from './modules/ui.js';

import { loadDashboard } from './modules/dashboard.js';

import { loadCampaigns, loadCampaignVouchers, toggleVouchers,
         openCreateCampaignModal, editCampaign, addVouchersToCampaign,
         deleteCampaign, initCampaignForms } from './modules/campaigns.js';

import { loadVouchers, editVoucherDetails, restoreVoucher, deleteVoucher,
         searchVouchers, renderVouchers, setupVoucherSearch,
         copyVoucherLink, getStatusText, editVoucherCode, copyVoucherToFree,
         openChangePasswordModal, initVoucherForms } from './modules/vouchers.js';

import { loadFreeVouchers, openFreeVoucherModal, editFreeVoucher,
         deleteFreeVoucher, setupFreeVoucherSearch,
         initFreeVoucherForms } from './modules/free-vouchers.js';

import { loadGiveStats, loadGiveHistory, loadGiveTab, giveVoucher,
         confirmTakeVouchers, toggleCampaignSelection, loadTakeCampaigns,
         clearAllTakenVouchers, confirmClearAllTakenVouchers,
         switchGiveSubtab, setKhoFilter, loadTakenItems,
         toggleTakenItem, toggleAllTakenItems, undoSelectedTakenItems,
         copyLink, copyLinkWithFeedback, copyCodeWithFeedback, openVoucherLink } from './modules/give-take.js';

import { loadSessions, revokeSession, logoutAllDevices } from './modules/sessions.js';

import { loadSettings, saveGiveMessage, resetGiveMessage, bumpSwCache } from './modules/settings.js';

import { loadApiClients, openCreateApiClientModal, editApiClient,
         handleApiClientSubmit, revokeApiClient, regenApiKey } from './modules/api_clients.js';

import { loadVaasAnalytics } from './modules/vaas_analytics.js';

// ── Expose all functions needed by HTML inline event handlers ─────────────
Object.assign(window, {
    // Uploaders
    triggerLogoUpload, previewLogo, updateLogoUploader,
    handleLogoPaste, triggerMenuImageUpload, previewMenuImage, handleMenuImagePaste,

    // Modals
    openModal, closeModal,

    // UI
    showSuccess, showError, showInfo, showWarning,
    escapeHtml, copyTextToClipboard,
    switchTab, loadTabContent, invalidateTab, invalidateTabs,
    toggleMobileMenu, closeMobileMenu,
    generateRandomCodes, populateStructuredContent,
    toggleDarkMode, switchVoucherSubtab, toggleTakeSection,

    // Dashboard
    loadDashboard,

    // Campaigns
    loadCampaigns, loadCampaignVouchers, toggleVouchers,
    openCreateCampaignModal, editCampaign, addVouchersToCampaign, deleteCampaign,

    // Vouchers
    loadVouchers, editVoucherDetails, restoreVoucher, deleteVoucher,
    searchVouchers, renderVouchers,
    copyVoucherLink, getStatusText, editVoucherCode, copyVoucherToFree,
    openChangePasswordModal,

    // Free vouchers
    loadFreeVouchers, openFreeVoucherModal, editFreeVoucher, deleteFreeVoucher,

    // Give & Take
    loadGiveStats, loadGiveHistory, loadGiveTab, giveVoucher,
    confirmTakeVouchers, toggleCampaignSelection, loadTakeCampaigns,
    clearAllTakenVouchers, confirmClearAllTakenVouchers,
    switchGiveSubtab, setKhoFilter, loadTakenItems,
    toggleTakenItem, toggleAllTakenItems, undoSelectedTakenItems,
    copyLink, copyLinkWithFeedback, copyCodeWithFeedback, openVoucherLink,

    // VaaS API Clients & Analytics
    loadApiClients, openCreateApiClientModal, editApiClient,
    handleApiClientSubmit, revokeApiClient, regenApiKey,
    loadVaasAnalytics,

    // Sessions
    loadSessions, revokeSession, logoutAllDevices,

    // Settings
    loadSettings, saveGiveMessage, resetGiveMessage, bumpSwCache,

    // Misc
    logout,
});

// ── Misc helpers ──────────────────────────────────────────────────────────
function logout() {
    showWarning('Xác nhận đăng xuất', 'Bạn có chắc chắn muốn đăng xuất khỏi hệ thống?', () => {
        window.location.href = 'logout.php';
    });
}

// ── Initialise on DOM ready ───────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    initDarkMode();
    setupScrollTop();

    document.querySelectorAll('.logo-file-input, .menu-image-file-input').forEach(input => {
        input.addEventListener('click', e => e.stopPropagation());
    });

    initCampaignForms();
    initVoucherForms();
    initFreeVoucherForms();

    setTimeout(setupFreeVoucherSearch, 200);

    loadTabContent(window.CURRENT_TAB || 'dashboard');
});
