        <div id="vouchers" class="tab-content <?php echo ($currentTab == 'vouchers' || $currentTab == 'free_vouchers') ? 'active' : ''; ?>">

            <!-- Sub-tab switcher -->
            <div class="voucher-subtabs">
                <button class="voucher-subtab active" id="subtab-campaign" onclick="switchVoucherSubtab('campaign')">
                    <i class="fas fa-ticket-alt"></i> E-voucher chiến dịch
                </button>
                <button class="voucher-subtab" id="subtab-free" onclick="switchVoucherSubtab('free')">
                    <i class="fas fa-bolt"></i> Voucher tự do
                </button>
            </div>

            <!-- Panel: Campaign vouchers -->
            <div id="voucher-panel-campaign" class="voucher-panel active">
                <div class="card">
                    <h2 style="margin-bottom:16px;">
                        <i class="fas fa-ticket-alt" style="color:var(--color-primary);margin-right:8px;"></i>Quản lý E-voucher chiến dịch
                    </h2>

                    <!-- Filters -->
                    <div class="voucher-filter-bar">
                        <div class="voucher-filter-search">
                            <div style="position:relative;">
                                <i class="fas fa-search" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:13px; pointer-events:none;"></i>
                                <input type="text" id="search-voucher" placeholder="Tìm kiếm e-voucher..." style="padding-left:32px;">
                            </div>
                            <div id="search-loading" style="display:none; text-align:center; margin-top:8px; font-size:13px; color:var(--color-muted);">
                                <i class="fas fa-spinner fa-spin"></i> Đang tìm kiếm...
                            </div>
                        </div>
                        <div class="voucher-filter-controls">
                            <select id="filter-status" title="Lọc theo trạng thái">
                                <option value="">Tất cả trạng thái</option>
                                <option value="unused">Chưa sử dụng</option>
                                <option value="used">Đã sử dụng</option>
                                <option value="expired">Hết hạn</option>
                            </select>
                            <select id="filter-sort" title="Sắp xếp">
                                <option value="newest">Mới nhất</option>
                                <option value="oldest">Cũ nhất</option>
                                <option value="used-recent">Dùng gần đây</option>
                            </select>
                        </div>
                    </div>

                    <div id="vouchers-list"></div>
                </div>
            </div>

            <!-- Panel: Free vouchers -->
            <div id="voucher-panel-free" class="voucher-panel" style="display:none;">
                <div class="card">
                    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:16px;">
                        <h2 style="margin:0;">
                            <i class="fas fa-bolt" style="color:var(--color-primary);margin-right:8px;"></i>Voucher tự do
                        </h2>
                        <button class="btn btn-primary" onclick="openFreeVoucherModal()">
                            <i class="fas fa-plus"></i> Tạo mới
                        </button>
                    </div>
                    <p class="list-meta" style="margin-bottom:16px;">
                        Các e-voucher độc lập, không thuộc chiến dịch nào. Có thể tự tạo hoặc sao chép từ chiến dịch.
                    </p>

                    <!-- Filters -->
                    <div class="voucher-filter-bar">
                        <div class="voucher-filter-search">
                            <div style="position:relative;">
                                <i class="fas fa-search" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:13px; pointer-events:none;"></i>
                                <input type="text" id="search-free-voucher" placeholder="Tìm kiếm voucher tự do..." style="padding-left:32px;">
                            </div>
                        </div>
                        <div class="voucher-filter-controls">
                            <select id="filter-free-status" title="Lọc theo trạng thái">
                                <option value="">Tất cả trạng thái</option>
                                <option value="unused">Chưa sử dụng</option>
                                <option value="used">Đã sử dụng</option>
                                <option value="expired">Hết hạn</option>
                            </select>
                            <select id="filter-free-sort" title="Sắp xếp">
                                <option value="newest">Mới nhất</option>
                                <option value="oldest">Cũ nhất</option>
                            </select>
                        </div>
                    </div>

                    <div id="free-vouchers-list"></div>
                </div>
            </div>

        </div>

<style>
/* ── Voucher sub-tabs ── */
.voucher-subtabs {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
    border-bottom: 2px solid var(--color-border, #e2e8f0);
    padding-bottom: 0;
}
.voucher-subtab {
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
.voucher-subtab.active {
    color: var(--color-primary, #3b82f6);
    border-bottom-color: var(--color-primary, #3b82f6);
}
.voucher-subtab:hover:not(.active) {
    color: var(--color-text, #334155);
    background: var(--color-input-bg, #f8fafc);
}

/* ── Filter bar ── */
.voucher-filter-bar {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 16px;
}
.voucher-filter-search {
    width: 100%;
}
.voucher-filter-search input {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid var(--color-border, #e2e8f0);
    border-radius: 8px;
    font-size: 14px;
    background: var(--color-input-bg, #f8fafc);
    color: var(--color-text, #334155);
    box-sizing: border-box;
    transition: border-color 0.2s;
}
.voucher-filter-search input:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
}
.voucher-filter-controls {
    display: flex;
    gap: 8px;
    width: 100%;
}
.voucher-filter-controls select {
    flex: 1;
    padding: 9px 10px;
    border: 1px solid var(--color-border, #e2e8f0);
    border-radius: 8px;
    font-size: 13px;
    background: var(--color-input-bg, #f8fafc);
    color: var(--color-text, #334155);
    cursor: pointer;
    min-width: 0;
    transition: border-color 0.2s;
}
.voucher-filter-controls select:focus {
    outline: none;
    border-color: var(--color-primary);
}

@media (max-width: 480px) {
    .voucher-subtab { padding: 8px 12px; font-size: 13px; }
    .voucher-filter-controls { flex-direction: column; }
}
</style>
