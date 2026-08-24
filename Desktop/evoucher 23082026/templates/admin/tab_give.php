        <div id="give" class="tab-content <?php echo ($currentTab == 'give' || $currentTab == 'take') ? 'active' : ''; ?>">

            <!-- Hidden message -->
            <textarea id="give-message" style="display:none;"></textarea>

            <!-- Subtab switcher -->
            <div class="give-subtabs">
                <button class="give-subtab active" id="gstab-give" onclick="switchGiveSubtab('give')">
                    <i class="fas fa-gift"></i> Tặng Voucher
                </button>
                <button class="give-subtab" id="gstab-kho" onclick="switchGiveSubtab('kho')">
                    <i class="fas fa-inbox"></i> Kho đã lấy
                </button>
            </div>

            <!-- ══ Panel: Tặng ══ -->
            <div id="give-panel-give" class="give-panel active">

                <!-- Stats -->
                <div class="gtab-stats" id="give-stats">
                    <div class="gtab-stat give-stat-taken">
                        <span class="gtab-stat-num give-stat-num">—</span>
                        <span class="gtab-stat-label">Đã lấy</span>
                    </div>
                    <div class="gtab-stat give-stat-given">
                        <span class="gtab-stat-num give-stat-num">—</span>
                        <span class="gtab-stat-label">Đã tặng</span>
                    </div>
                    <div class="gtab-stat give-stat-remaining">
                        <span class="gtab-stat-num give-stat-num">—</span>
                        <span class="gtab-stat-label">Còn lại</span>
                    </div>
                </div>

                <!-- Lấy thêm collapsible -->
                <div class="gtab-take-wrap">
                    <button class="gtab-take-toggle" id="gtab-take-toggle" onclick="toggleTakeSection()">
                        <span><i class="fas fa-plus-circle"></i> Lấy thêm từ chiến dịch</span>
                        <i class="fas fa-chevron-down gtab-chevron" id="gtab-chevron"></i>
                    </button>
                    <div class="gtab-take-body" id="gtab-take-body">
                        <div id="take-campaigns-list" style="padding:12px 16px 0;"></div>
                        <div class="gtab-take-actions">
                            <button class="btn btn-danger btn-sm" onclick="clearAllTakenVouchers()">
                                <i class="fas fa-trash"></i> Xóa kho
                            </button>
                            <button class="btn btn-primary" onclick="confirmTakeVouchers()">
                                <i class="fas fa-check"></i> Xác nhận lấy
                            </button>
                        </div>
                    </div>
                </div>

                <!-- History -->
                <div class="gtab-history-wrap">
                    <div class="gtab-section-header">
                        <i class="fas fa-history"></i>
                        <span>Lịch sử tặng</span>
                    </div>
                    <div id="give-history">
                        <p class="give-empty">Chưa có lịch sử tặng e-voucher</p>
                    </div>
                </div>

            </div><!-- /panel give -->

            <!-- ══ Panel: Kho đã lấy ══ -->
            <div id="give-panel-kho" class="give-panel" style="display:none;">

                <!-- Filter bar -->
                <div class="kho-toolbar">
                    <div class="kho-filters">
                        <button class="kho-filter-btn active" data-filter="" onclick="setKhoFilter(this, '')">Tất cả</button>
                        <button class="kho-filter-btn" data-filter="available" onclick="setKhoFilter(this, 'available')">Chưa tặng</button>
                        <button class="kho-filter-btn" data-filter="given" onclick="setKhoFilter(this, 'given')">Đã tặng</button>
                        <button class="kho-filter-btn" data-filter="returned" onclick="setKhoFilter(this, 'returned')">Đã hoàn trả</button>
                    </div>
                </div>

                <!-- Select all row -->
                <div class="kho-select-all-row" id="kho-select-all-row" style="display:none;">
                    <label class="kho-check-label">
                        <input type="checkbox" id="kho-check-all" onchange="toggleAllTakenItems(this)">
                        <span>Chọn tất cả</span>
                    </label>
                </div>

                <!-- Items list (grouped by campaign) -->
                <div id="taken-items-list">
                    <p class="give-empty">Đang tải...</p>
                </div>

                <!-- Floating Hoàn tác bar (appears when items selected) -->
                <div class="kho-undo-bar" id="kho-undo-bar" style="display:none;">
                    <button class="kho-undo-btn" id="kho-undo-btn" onclick="undoSelectedTakenItems()">
                        <i class="fas fa-undo"></i>
                        <span>Hoàn tác</span>
                        <span class="kho-undo-count" id="kho-undo-count">0</span>
                    </button>
                </div>

            </div><!-- /panel kho -->

        </div>

        <!-- Sticky TẶNG button — only visible on give subtab -->
        <div class="gtab-action-bar" id="gtab-action-bar">
            <button class="gtab-give-btn" id="give-btn" onclick="giveVoucher()" disabled>
                <i class="fas fa-gift"></i>
                <span>TẶNG NGAY</span>
                <span class="gtab-count" id="give-btn-count">0</span>
            </button>
        </div>

<style>
/* ═══════════════════════════════════════════════════════
   TẶNG VOUCHER TAB — Mobile-first, thumb-optimised
   ═══════════════════════════════════════════════════════ */

#give {
    padding: 0 0 180px;
    background: var(--color-input-bg, #f8fafc);
    min-height: 60vh;
}

/* ── Give subtabs — same style as voucher-subtabs ── */
.give-subtabs {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
    border-bottom: 2px solid var(--color-border, #e2e8f0);
    padding-bottom: 0;
}
.give-subtab {
    padding: 10px 20px;
    border: none;
    background: none;
    color: var(--color-muted, #64748b);
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    margin-bottom: -2px;
    border-radius: 6px 6px 0 0;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 6px;
}
.give-subtab.active {
    color: var(--color-primary, #3b82f6);
    border-bottom-color: var(--color-primary, #3b82f6);
}
.give-subtab:hover:not(.active) {
    color: var(--color-text, #334155);
    background: var(--color-input-bg, #f8fafc);
}

/* ── Stats row ── */
.gtab-stats {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 10px;
    padding: 16px 16px 12px;
}
.gtab-stat {
    border-radius: 14px;
    padding: 14px 8px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
}
.give-stat-taken     { background: #eff6ff; border: 1.5px solid #bfdbfe; }
.give-stat-given     { background: #fff7ed; border: 1.5px solid #fed7aa; }
.give-stat-remaining { background: #f0fdf4; border: 1.5px solid #bbf7d0; }
body.dark-mode .give-stat-taken     { background: #1e3a5f; border-color: #2563eb; }
body.dark-mode .give-stat-given     { background: #431407; border-color: #ea580c; }
body.dark-mode .give-stat-remaining { background: #14532d; border-color: #16a34a; }
.gtab-stat-num { font-size: 2rem; font-weight: 800; line-height: 1; letter-spacing: -1px; }
.give-stat-taken     .gtab-stat-num { color: #2563eb; }
.give-stat-given     .gtab-stat-num { color: #ea580c; }
.give-stat-remaining .gtab-stat-num { color: #16a34a; }
.gtab-stat-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--color-muted, #64748b); }

/* ── Lấy thêm collapsible ── */
.gtab-take-wrap {
    margin: 0 16px 12px;
    background: var(--color-card, white);
    border-radius: 14px;
    border: 1px solid var(--color-border, #e2e8f0);
    overflow: hidden;
}
.gtab-take-toggle {
    width: 100%; display: flex; align-items: center; justify-content: space-between;
    padding: 14px 16px; background: none; border: none;
    font-size: 14px; font-weight: 600; color: var(--color-text, #334155);
    cursor: pointer; gap: 10px; min-height: 52px;
}
.gtab-take-toggle span { display: flex; align-items: center; gap: 8px; }
.gtab-take-toggle i.fa-plus-circle { color: var(--color-primary); font-size: 16px; }
.gtab-take-toggle:hover { background: var(--color-input-bg); }
.gtab-chevron { font-size: 13px; color: var(--color-muted); transition: transform 0.25s; }
.gtab-chevron.open { transform: rotate(180deg); }
.gtab-take-body { display: none; border-top: 1px solid var(--color-border); }
.gtab-take-body.open { display: block; }
.gtab-take-actions { display: flex; gap: 10px; padding: 12px 16px 16px; justify-content: flex-end; }
#take-campaigns-list .campaign-card { margin-bottom: 10px; }

/* ── History ── */
.gtab-history-wrap { padding: 0 16px; }
.gtab-section-header {
    display: flex; align-items: center; gap: 8px;
    font-size: 12px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.6px; color: var(--color-muted, #64748b);
    margin-bottom: 10px; padding-top: 4px;
}
.give-empty { text-align: center; color: var(--color-muted); font-size: 14px; padding: 28px 0; }
.give-history-item {
    background: var(--color-card, white); border-radius: 14px;
    padding: 14px 16px; margin-bottom: 10px;
    border: 1px solid var(--color-border, #f1f5f9);
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
}
.give-history-code { font-family: monospace; font-size: 16px; font-weight: 700; color: var(--color-text); margin-bottom: 3px; }
.give-history-time { font-size: 12px; color: var(--color-muted); margin-bottom: 10px; }
.give-history-copy-btn {
    flex-shrink: 0; width: 44px; height: 44px; display: flex;
    align-items: center; justify-content: center;
    background: var(--color-primary-light); color: var(--color-primary);
    border: 1.5px solid #bfdbfe; border-radius: 12px;
    font-size: 16px; cursor: pointer; transition: all 0.15s;
}
.give-history-copy-btn:active { transform: scale(0.92); }
.give-history-copy-btn.copied { background: #f0fdf4; color: #16a34a; border-color: #bbf7d0; }

/* Kho toolbar — filters only */
.kho-toolbar {
    display: flex; align-items: center;
    gap: 10px; padding: 12px 16px; flex-wrap: wrap;
}
.kho-filters { display: flex; gap: 6px; flex-wrap: wrap; flex: 1; }
.kho-filter-btn {
    padding: 7px 14px; border-radius: 20px; border: 1.5px solid var(--color-border);
    background: var(--color-card); color: var(--color-muted);
    font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.15s;
    white-space: nowrap;
}
.kho-filter-btn.active {
    background: var(--color-primary); border-color: var(--color-primary); color: white;
}

/* Floating Hoàn tác bar — always at bottom of kho panel */
.kho-undo-bar {
    position: sticky;
    bottom: 0;
    padding: 10px 16px calc(env(safe-area-inset-bottom, 0px) + 10px);
    background: var(--color-card, white);
    border-top: 1px solid var(--color-border);
    box-shadow: 0 -4px 16px rgba(0,0,0,0.08);
    z-index: 100;
}
.kho-undo-btn {
    width: 100%;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    padding: 14px 18px; border-radius: 12px; border: none;
    background: var(--color-warning); color: white;
    font-size: 15px; font-weight: 700; cursor: pointer;
    min-height: 48px; white-space: nowrap;
    box-shadow: 0 2px 8px rgba(245,158,11,0.35);
    transition: opacity 0.15s, transform 0.15s;
}
.kho-undo-btn:active { transform: scale(0.97); }
.kho-undo-count {
    background: rgba(255,255,255,0.3); border-radius: 20px;
    padding: 2px 10px; font-weight: 800; font-size: 13px;
}

.kho-select-all-row {
    padding: 8px 16px;
    border-bottom: 1px solid var(--color-border);
    background: var(--color-card);
}
.kho-check-label {
    display: flex; align-items: center; gap: 10px;
    font-size: 14px; font-weight: 600; color: var(--color-text); cursor: pointer;
}

/* Campaign group */
.kho-campaign-group { margin-bottom: 16px; }
.kho-campaign-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 16px; background: var(--color-input-bg);
    border-radius: 10px 10px 0 0;
    border: 1px solid var(--color-border); border-bottom: none;
    font-weight: 700; font-size: 14px; color: var(--color-text);
}
.kho-campaign-header .kho-badges { display: flex; gap: 6px; }
.kho-badge {
    font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 20px;
}
.kho-badge-available { background: #dcfce7; color: #166534; }
.kho-badge-given     { background: #fef3c7; color: #92400e; }
body.dark-mode .kho-badge-available { background: #14532d; color: #86efac; }
body.dark-mode .kho-badge-given     { background: #451a03; color: #fcd34d; }

/* Voucher row in kho */
.kho-voucher-row {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 16px;
    background: var(--color-card);
    border: 1px solid var(--color-border); border-top: none;
    transition: background 0.15s;
}
.kho-voucher-row:last-child { border-radius: 0 0 10px 10px; }
.kho-voucher-row:hover { background: var(--color-input-bg); }
.kho-voucher-row.selected { background: var(--color-primary-light); }

.kho-voucher-check {
    width: 22px; height: 22px; flex-shrink: 0;
    accent-color: var(--color-primary); cursor: pointer;
}
.kho-voucher-code {
    flex: 1; font-family: monospace; font-size: 15px;
    font-weight: 700; color: var(--color-primary); letter-spacing: 0.5px;
}
.kho-voucher-meta { font-size: 11px; color: var(--color-muted); margin-top: 2px; }
.kho-voucher-status { flex-shrink: 0; }

.kho-status-available { background: #dcfce7; color: #166534; }
.kho-status-given     { background: #fef3c7; color: #92400e; }
.kho-status-returned  { background: #f1f5f9; color: #64748b; }
body.dark-mode .kho-status-available { background: #14532d; color: #86efac; }
body.dark-mode .kho-status-given     { background: #451a03; color: #fcd34d; }
body.dark-mode .kho-status-returned  { background: #1e293b; color: #64748b; }

/* ── Sticky give button ── */
.gtab-action-bar {
    display: none;
    position: fixed; left: 0; right: 0; bottom: 0;
    padding: 12px 16px calc(env(safe-area-inset-bottom,0px) + 12px);
    background: var(--color-card, white);
    border-top: 1px solid var(--color-border);
    box-shadow: 0 -4px 20px rgba(0,0,0,0.08);
    z-index: 850;
}
.gtab-action-bar.visible { display: block; }
@media (max-width: 768px) {
    .gtab-action-bar { bottom: calc(env(safe-area-inset-bottom,0px) + 65px); border-radius: 20px 20px 0 0; }
    #give { padding-bottom: 220px; }
}
.gtab-give-btn {
    width: 100%; height: 62px; display: flex; align-items: center; justify-content: center; gap: 12px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white;
    border: none; border-radius: 16px; font-size: 18px; font-weight: 800; letter-spacing: 0.5px;
    cursor: pointer; box-shadow: 0 6px 24px rgba(59,130,246,0.45);
    transition: all 0.2s cubic-bezier(0.175,0.885,0.32,1.275); position: relative; overflow: hidden;
}
.gtab-give-btn::after {
    content:''; position:absolute; inset:0;
    background: linear-gradient(135deg,rgba(255,255,255,0.15),transparent); border-radius:16px;
}
.gtab-give-btn:not(:disabled) { animation: gtabPulse 2.5s ease-in-out infinite; }
.gtab-give-btn:active:not(:disabled) { transform:scale(0.97); box-shadow:0 3px 12px rgba(59,130,246,0.3); animation:none; }
.gtab-give-btn:disabled { background:linear-gradient(135deg,#94a3b8,#64748b); box-shadow:none; cursor:not-allowed; opacity:0.7; }
.gtab-give-btn i { font-size: 22px; }
.gtab-count { background:rgba(255,255,255,0.3); border-radius:20px; padding:3px 12px; font-size:16px; font-weight:900; min-width:36px; text-align:center; }
@keyframes gtabPulse {
    0%,100% { box-shadow:0 6px 24px rgba(59,130,246,0.45); }
    50%      { box-shadow:0 8px 32px rgba(59,130,246,0.7); }
}

/* ── Desktop overrides ── */
@media (min-width: 769px) {
    #give { background:transparent; padding-bottom:40px; }
    .gtab-stats { padding:0 0 16px; gap:16px; }
    .gtab-stat { padding:20px; }
    .gtab-stat-num { font-size:2.5rem; }
    .gtab-stat-label { font-size:12px; }
    .gtab-take-wrap { margin:0 0 16px; }
    .gtab-history-wrap { padding:0; }
    .kho-toolbar { padding:0 0 12px; }
    .gtab-action-bar { position:static; display:none; background:none; border:none; box-shadow:none; padding:16px 0 0; }
    .gtab-action-bar.visible { display:block; }
    .gtab-give-btn { height:56px; font-size:17px; }
}
</style>
