<div id="campaigns" class="tab-content <?php echo $currentTab == 'campaigns' ? 'active' : ''; ?>">
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>Quản lý Chiến dịch</h2>
            <button class="btn btn-primary" onclick="openCreateCampaignModal()">
                <i class="fas fa-plus"></i>
            </button>
        </div>
        <div id="campaigns-list">
            <!-- Campaigns will be loaded here -->
        </div>
    </div>
</div>