// modules/ui.js — Toast notifications, tab switching, utilities

// ── XSS protection ────────────────────────────────────────────────────────
export function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    const div = document.createElement('div');
    div.textContent = String(str);
    return div.innerHTML;
}

// ── Toast notifications ───────────────────────────────────────────────────
export function showToast(title, message, type = 'success') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `toast ${type}`;

    let icon = 'fa-check-circle';
    if (type === 'error')                   icon = 'fa-exclamation-circle';
    if (type === 'info' || type === 'warning') icon = 'fa-info-circle';

    toast.innerHTML = `
        <div class="toast-icon"><i class="fas ${icon}"></i></div>
        <div class="toast-body">
            <div class="toast-title">${escapeHtml(title)}</div>
            <div class="toast-message">${escapeHtml(message)}</div>
        </div>
    `;

    container.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 10);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
    }, 3000);
}

export function showSuccess(title, message) { showToast(title, message, 'success'); }
export function showError(title, message)   { showToast(title, message, 'error'); }
export function showInfo(title, message)    { showToast(title, message, 'info'); }

// Warning needs confirmation — uses modal
export function showWarning(title, message, onConfirm) {
    document.getElementById('warning-title').textContent   = title;
    document.getElementById('warning-message').textContent = message;

    const confirmBtn = document.getElementById('warning-confirm-btn');
    confirmBtn.onclick = function () {
        // closeModal imported lazily to avoid circular dep — call via window
        window.closeModal('warning-notification-modal');
        if (onConfirm) onConfirm();
    };

    window.openModal('warning-notification-modal');
}

// ── Clipboard ─────────────────────────────────────────────────────────────
export function copyTextToClipboard(text) {
    let syncOk = false;
    try {
        const ta = document.createElement('textarea');
        ta.value = text;
        ta.contentEditable = 'true';
        ta.readOnly = false;
        ta.style.position = 'absolute';
        ta.style.left = '-9999px';
        document.body.appendChild(ta);

        const isIOS = navigator.userAgent.match(/ipad|iphone/i);
        if (isIOS) {
            const range = document.createRange();
            range.selectNodeContents(ta);
            const selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
            ta.setSelectionRange(0, 999999);
        } else {
            ta.select();
        }

        syncOk = document.execCommand('copy');
        document.body.removeChild(ta);
    } catch (e) {
        syncOk = false;
    }

    if (navigator.clipboard) {
        return navigator.clipboard.writeText(text).then(() => true).catch(() => syncOk);
    }
    return Promise.resolve(syncOk);
}

// ── Mobile menu ───────────────────────────────────────────────────────────
export function toggleMobileMenu() {
    document.querySelector('.mobile-menu').classList.toggle('active');
    document.querySelector('.mobile-menu-overlay').classList.toggle('active');
}

export function closeMobileMenu() {
    document.querySelector('.mobile-menu').classList.remove('active');
    document.querySelector('.mobile-menu-overlay').classList.remove('active');
}

// ── Tab switching with lazy-load cache ───────────────────────────────────
// Tracks which tabs have been loaded at least once.
const _loadedTabs = new Set();

/** Invalidate one tab so it re-fetches on next switch. */
export function invalidateTab(tabName) {
    _loadedTabs.delete(tabName);
}

/** Invalidate multiple tabs at once. */
export function invalidateTabs(...tabNames) {
    tabNames.forEach(t => _loadedTabs.delete(t));
}

export function switchTab(tabName) {
    const url = new URL(window.location);
    url.searchParams.set('tab', tabName);
    window.history.pushState({}, '', url);

    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    document.querySelectorAll('.tab, .bottom-nav-item').forEach(t => t.classList.remove('active'));

    document.getElementById(tabName)?.classList.add('active');
    document.querySelectorAll(`.tab[onclick*="${tabName}"], .bottom-nav-item[data-tab="${tabName}"]`)
        .forEach(el => el.classList.add('active'));

    loadTabContent(tabName);
}

export function loadTabContent(tabName, force = false) {
    // Skip re-fetch if already loaded (lazy cache) unless forced
    if (!force && _loadedTabs.has(tabName)) return;
    _loadedTabs.add(tabName);

    switch (tabName) {
        case 'dashboard':     window.loadDashboard?.();                               break;
        case 'campaigns':     window.loadCampaigns?.();                               break;
        case 'vouchers':      window.loadVouchers?.(); window.loadFreeVouchers?.();   break;
        case 'take':          // fallthrough — take is now part of give tab
        case 'give':          window.loadGiveTab?.(); window.loadTakeCampaigns?.();   break;
        case 'vaas_clients':   window.loadApiClients?.();                              break;
        case 'vaas_logs':      window.loadVaasLogs?.(1);                               break;
        case 'vaas_analytics': window.loadVaasAnalytics?.();                           break;
        case 'account':       // fallthrough — account is now part of settings tab
        case 'settings':      window.loadSettings?.(); window.loadSessions?.();       break;
    }
    _syncGiveActionBar(tabName);
}

// ── Scroll-to-top ─────────────────────────────────────────────────────────
export function setupScrollTop() {
    const btn = document.createElement('button');
    btn.id = 'scroll-top-btn';
    btn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    btn.style.cssText = [
        'width:44px', 'height:44px', 'border-radius:50%',
        'border:none', 'background:linear-gradient(135deg,#667eea,#764ba2)',
        'color:#fff', 'box-shadow:0 4px 10px rgba(0,0,0,0.15)',
        'cursor:pointer', 'display:none'
    ].join(';');
    document.body.appendChild(btn);

    btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
    window.addEventListener('scroll', () => {
        const show = window.scrollY > 200;
        btn.style.display     = show ? 'inline-flex' : 'none';
        btn.style.alignItems    = 'center';
        btn.style.justifyContent = 'center';
    }, { passive: true });
}

// ── Structured content helpers ────────────────────────────────────────────
export function populateStructuredContent(form, data) {
    if (data && data.guide_content) {
        const addrInput  = form.querySelector('input[name="guide_address"]');
        const timeInput  = form.querySelector('input[name="guide_time"]');
        const phoneInput = form.querySelector('input[name="guide_phone"]');
        try {
            const g = JSON.parse(data.guide_content);
            if (g && g.address !== undefined) {
                if (addrInput)  addrInput.value  = g.address || '';
                if (timeInput)  timeInput.value  = g.time    || '';
                if (phoneInput) phoneInput.value = g.phone   || '';
            }
        } catch (e) {
            const html = data.guide_content;
            const addrMatch  = html.match(/<strong>(?:Địa\s*chỉ|Địa\s*điểm):?<\/strong>\s*(.*?)<\/div>/i);
            const timeMatch  = html.match(/<strong>(?:Giờ|Thời\s*gian).*?<\/strong>\s*(.*?)<\/div>/i);
            const phoneMatch = html.match(/<strong>(?:Điện\s*thoại|Hotline|SĐT|Phone):?<\/strong>\s*(.*?)<\/div>/i);
            if (addrInput)  addrInput.value  = addrMatch  ? addrMatch[1].replace(/<[^>]*>?/gm, '').trim()  : '';
            if (timeInput)  timeInput.value  = timeMatch  ? timeMatch[1].replace(/<[^>]*>?/gm, '').trim()  : '';
            if (phoneInput) phoneInput.value = phoneMatch ? phoneMatch[1].replace(/<[^>]*>?/gm, '').trim() : '';
        }
    }

    if (data && data.menu_content) {
        const itemsInput     = form.querySelector('textarea[name="menu_items"]');
        const imgInputHidden = form.querySelector('input[name="existing_menu_image"]');
        const noteInput      = form.querySelector('textarea[name="menu_note"]');
        let imageName = '';

        try {
            const m = JSON.parse(data.menu_content);
            if (m && typeof m === 'object') {
                if (itemsInput)     itemsInput.value     = m.items ?? '';
                if (noteInput)      noteInput.value      = m.note  ?? '';
                imageName = m.image ?? '';
                if (imgInputHidden) imgInputHidden.value = imageName;
            }
        } catch (e) {
            const html     = data.menu_content;
            const imgMatch = html.match(/<img[^>]+src="([^">]+)"/i);
            if (imgMatch) {
                imageName = imgMatch[1].split('/').pop();
                if (imgInputHidden) imgInputHidden.value = imageName;
            }
            const items = [];
            const liRegex = /<li[^>]*>(.*?)<\/li>/gi;
            let match;
            while ((match = liRegex.exec(html)) !== null) {
                items.push(match[1].replace(/<[^>]*>?/gm, '').trim());
            }
            if (itemsInput && items.length > 0) itemsInput.value = items.join('\n');
        }

        // Render menu image preview if an image filename exists
        if (imageName) {
            const uploader    = form.querySelector('.menu-image-uploader');
            const preview     = uploader?.querySelector('.menu-image-preview');
            const placeholder = uploader?.querySelector('.upload-placeholder');
            if (preview)     { preview.src = 'uploads/' + imageName; preview.style.display = 'block'; }
            if (placeholder) { placeholder.style.display = 'none'; }
        }
    }
}

// ── Misc ──────────────────────────────────────────────────────────────────
export function generateRandomCodes(event) {
    const codes = [];
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    const array = new Uint32Array(25 * 5);
    crypto.getRandomValues(array);
    for (let i = 0; i < 25; i++) {
        let code = '';
        for (let j = 0; j < 5; j++) code += chars.charAt(array[i * 5 + j] % chars.length);
        codes.push(code);
    }
    const button   = event.target.closest('button');
    const textarea = button.closest('form').querySelector('textarea[name="codes"]');
    if (textarea) textarea.value = codes.join('\n');
}

// ── Give tab action bar visibility ───────────────────────────────────────
function _syncGiveActionBar(tabName) {
    const bar = document.getElementById('gtab-action-bar');
    if (!bar) return;
    const isGive = tabName === 'give' || tabName === 'take';
    bar.classList.toggle('visible', isGive);
}

// ── Give tab: toggle take section ────────────────────────────────────────
export function toggleTakeSection() {
    const body    = document.getElementById('gtab-take-body');
    const chevron = document.getElementById('gtab-chevron');
    if (!body) return;
    const isOpen = body.classList.toggle('open');
    if (chevron) chevron.classList.toggle('open', isOpen);
    if (isOpen) window.loadTakeCampaigns?.();
}

// ── Voucher sub-tab switcher ──────────────────────────────────────────────
export function switchVoucherSubtab(which) {
    const panels = { campaign: 'voucher-panel-campaign', free: 'voucher-panel-free' };
    const btns   = { campaign: 'subtab-campaign',        free: 'subtab-free' };

    Object.entries(panels).forEach(([key, id]) => {
        const el = document.getElementById(id);
        if (el) el.style.display = key === which ? '' : 'none';
    });
    Object.entries(btns).forEach(([key, id]) => {
        const el = document.getElementById(id);
        if (el) el.classList.toggle('active', key === which);
    });
}

// ── Dark mode ─────────────────────────────────────────────────────────────
const DARK_KEY = 'ekp_dark_mode';

export function initDarkMode() {
    const saved = localStorage.getItem(DARK_KEY);
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const isDark = saved !== null ? saved === '1' : prefersDark;
    applyDarkMode(isDark);
}

export function toggleDarkMode(checked) {
    localStorage.setItem(DARK_KEY, checked ? '1' : '0');
    applyDarkMode(checked);
}

function applyDarkMode(isDark) {
    document.body.classList.toggle('dark-mode', isDark);
    const checkbox = document.getElementById('dark-mode-toggle');
    if (checkbox) checkbox.checked = isDark;
    const icon = document.getElementById('dark-toggle-icon');
    if (icon) {
        icon.className = isDark ? 'fas fa-moon' : 'fas fa-sun';
        icon.style.color = isDark ? '#818cf8' : '#f59e0b';
    }
}