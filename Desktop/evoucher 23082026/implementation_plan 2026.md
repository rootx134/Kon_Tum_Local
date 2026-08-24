# 🎨 Kế Hoạch Chi Tiết Đồng Bộ Giao Diện Frontend — Fan Cứng Web App

> **Phiên bản**: v2.0 — Dark Luxury × Gold Accent × Glassmorphism  
> **Mục tiêu**: Chuẩn hóa 100% thành phần UI, đồng nhất ngôn ngữ thiết kế từ Header đến từng nút bấm.  
> **Ưu tiên**: Mobile-first PWA, zero layout shift, micro-animations nhẹ nhàng.

---

## 🔍 ĐÁNH GIÁ HIỆN TRẠNG

| Module | Vấn đề hiện tại |
|---|---|
| **`page.tsx` (Homepage)** | Dùng `bg-[#f4f7fb]` — **màu sáng** không đồng nhất với Dark Theme của AppHeader |
| **`page.tsx` (Homepage)** | Hero banner dùng `from-blue-800 via-blue-600` — **màu xanh không khớp** brand Vàng Kim |
| **`page.tsx` (Homepage)** | Bảng xếp hạng dùng màu `bg-white` sáng, `text-blue-700` — **lạc theme Dark** |
| **`rewards/page.tsx`** | Toàn bộ layout viết **inline** không tách component — **khó bảo trì** |
| **`globals.css`** | Thiếu **font Plus Jakarta Sans**, thiếu animation `shimmer`, `slide-up`, thiếu utility classes dùng chung |
| **`layout.tsx`** | Thiếu khai báo Open Graph, PWA viewport meta, theme-color |
| **`AppHeader.tsx`** | Hoạt động tốt — Chỉ cần tinh chỉnh active state indicator cho Desktop nav links |
| **`NavigationDrawer.tsx`** | Hoạt động tốt — Cần thêm Active Link indicator rõ hơn |
| **`VoucherDetailModal.tsx`** | Giao diện đã tốt nhưng thiếu **Skeleton loading state** |

---

## 📦 BƯỚC 1: CỐT LÕI — DESIGN SYSTEM & CSS FOUNDATION

### [MODIFY] `src/app/globals.css`
**Mục tiêu**: Bổ sung font, CSS tokens, animation utilities, utility classes dùng chung.

**Thay đổi cụ thể**:
- Thêm import **Plus Jakarta Sans** song song với Inter:
  ```css
  @import url("https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800;900&display=swap");
  ```
- Cập nhật `--font-sans` ưu tiên Plus Jakarta Sans
- Thêm CSS custom properties (tokens):
  ```css
  :root {
    --gold-400: #fbbf24;
    --gold-glow: 0 0 20px rgba(251,191,36,0.3);
    --glass-bg: rgba(15,23,42,0.8);
    --glass-border: rgba(51,65,85,0.8);
    --surface: #0f172a;
  }
  ```
- Thêm keyframe animations:
  ```css
  @keyframes shimmer { ... }       /* Skeleton loading effect */
  @keyframes slide-up { ... }      /* Card enter animation */
  @keyframes glow-pulse { ... }    /* Gold glow effect */
  @keyframes number-count { ... }  /* Số đếm lên animation */
  ```
- Thêm utility classes:
  ```css
  .glass-card { background: var(--glass-bg); backdrop-filter: blur(12px); border: 1px solid var(--glass-border); }
  .text-gold-gradient { background: linear-gradient(135deg, #fbbf24, #fde68a, #f59e0b); -webkit-background-clip: text; ... }
  .btn-gold { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: #020617; font-weight: 700; ... }
  .skeleton { background: linear-gradient(90deg, #1e293b 25%, #334155 50%, #1e293b 75%); animation: shimmer 1.5s infinite; }
  ```

---

### [MODIFY] `src/app/layout.tsx`
**Mục tiêu**: Bổ sung metadata chuẩn PWA, Open Graph, viewport.

**Thay đổi cụ thể**:
- Mở rộng `metadata` object:
  ```ts
  export const metadata: Metadata = {
    title: "Fan Cứng Kon Tum + | Bảng Xếp Hạng Cộng Đồng",
    description: "...",
    themeColor: "#020617",
    viewport: "width=device-width, initial-scale=1, viewport-fit=cover",
    openGraph: { ... },
  };
  ```
- Thêm `<meta name="theme-color">` cho PWA bar màu Dark

---

## 📦 BƯỚC 2: HEADER & NAVIGATION

### [MODIFY] `src/components/AppHeader.tsx`
**Thay đổi cụ thể**:
- Desktop quick links: thêm `active` state indicator bằng đường viền dưới màu amber khi đang ở trang đó
- Thêm micro-animation `transition-all duration-200` cho Xu Pill khi số dư thay đổi
- Cải thiện `aria-label`, đảm bảo accessibility

```tsx
// Desktop quick nav — thêm active indicator
const isActive = (href: string) => pathname === href;

<Link href="/"
  className={`... ${isActive('/') ? 'text-amber-400 border-b-2 border-amber-400' : 'text-slate-300'}`}
>
```

### [MODIFY] `src/components/NavigationDrawer.tsx`
**Thay đổi cụ thể**:
- Cải thiện Active item: thay vì chỉ màu nền, thêm đường viền trái `border-l-2 border-amber-400`
- Thêm count badge động trên mục "Ví Xu" hiển thị số dư
- Smooth enter/exit animation cho Drawer thông qua CSS transitions

---

## 📦 BƯỚC 3: TRANG CHỦ — BẢNG XẾP HẠNG (`/`)

> ⚠️ **Đây là thay đổi lớn nhất** — toàn bộ `page.tsx` cần được refactor theme từ Light → Dark

### [MODIFY] `src/app/page.tsx`

**Thay đổi 1: Background toàn trang**
```tsx
// TRƯỚC
<main className="min-h-screen bg-[#f4f7fb] text-slate-950">

// SAU
<main className="min-h-screen bg-slate-950 text-slate-100">
```

**Thay đổi 2: Hero Banner — Từ xanh sang Dark × Gold**
```tsx
// TRƯỚC
<section className="bg-gradient-to-br from-blue-800 via-blue-600 to-cyan-500 ...">

// SAU
<section className="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 border-b border-slate-800/80 ...">
  {/* Decorative gold glow background */}
  <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(251,191,36,0.08),transparent_70%)]" />
```

**Thay đổi 3: Period Selector Pills — từ white sang Glass**
```tsx
// TRƯỚC
<section className="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">

// SAU
<section className="rounded-2xl glass-card p-4">
  {/* Pill tabs */}
  <Link className={`rounded-xl px-4 py-2 text-sm font-bold transition ${
    isSelected 
      ? 'bg-amber-500 text-slate-950 shadow-md shadow-amber-500/20' 
      : 'bg-slate-800/80 text-slate-400 hover:text-white hover:bg-slate-700'
  }`}>
```

**Thay đổi 4: Leaderboard Table — từ white sang Dark**
```tsx
// TRƯỚC — Header row
<div className="... bg-slate-50 ... text-slate-500">

// SAU
<div className="... bg-slate-900/60 ... text-slate-500">

// TRƯỚC — Row item
<li className="... border-slate-100 ...">
  <strong className="text-right text-lg text-blue-700">

// SAU
<li className="... border-slate-800/60 ... hover:bg-slate-800/40 transition-colors">
  <strong className="text-right text-lg font-black text-amber-400">
```

**Thay đổi 5: Top 3 Podium Cards (MỚI)**
- Tách 3 mục đầu tiên ra khỏi danh sách, hiển thị dạng **3 Card Podium** nổi bật:
```tsx
{/* Podium Section — chỉ hiển thị khi ranking >= 3 */}
<div className="grid grid-cols-3 gap-3 mt-6 mb-4">
  {/* Rank 2 — Silver */}
  <PodiumCard item={shown[1]} rank={2} theme="silver" />
  {/* Rank 1 — Gold (taller) */}
  <PodiumCard item={shown[0]} rank={1} theme="gold" />
  {/* Rank 3 — Bronze */}
  <PodiumCard item={shown[2]} rank={3} theme="bronze" />
</div>

{/* List bắt đầu từ rank 4 */}
<ol className="...">
  {shown.slice(3).map(...)}
</ol>
```

**Tách component `PodiumCard`** (viết thêm vào `page.tsx` hoặc tách file `components/PodiumCard.tsx`):
- Rank 1: `border-amber-400/50 shadow-amber-500/20`, avatar viền vàng phát sáng, icon vương miện
- Rank 2: `border-slate-400/50`, avatar viền bạc
- Rank 3: `border-amber-700/50`, avatar viền đồng

---

## 📦 BƯỚC 4: TRANG ĐỔI THƯỞNG & VÍ XU (`/rewards`)

### [MODIFY] `src/app/rewards/page.tsx`

**Thay đổi 1: Ví Xu Wallet Card — từ đơn giản sang VIP Hologram**
```tsx
// SAU — VIP Wallet Card
<div className="relative overflow-hidden rounded-2xl p-5 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 border border-amber-500/20 shadow-xl shadow-amber-500/5">
  {/* Hologram shimmer overlay */}
  <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(251,191,36,0.08),transparent_60%)]" />
  
  {/* Xu Balance */}
  <div className="text-3xl font-black text-gold-gradient">
    {xuBalance.toLocaleString()}
    <span className="text-base font-bold ml-1.5 text-amber-400/80">Xu</span>
  </div>
  
  {/* Quick Actions */}
  <div className="flex gap-2 mt-4">
    <button className="btn-gold flex-1 py-2 rounded-xl text-sm">
      ⟳ Đổi điểm → Xu
    </button>
    <button className="flex-1 py-2 rounded-xl text-sm bg-slate-700/80 text-slate-300 border border-slate-600">
      Lịch sử
    </button>
  </div>
</div>
```

**Thay đổi 2: Category Filter Tabs**
```tsx
// Thêm mảng categories
const CATEGORIES = ['Tất cả', 'Ẩm thực', 'Giải trí', 'Mua sắm', 'Đặc quyền Top Fan'];

// Horizontal scroll pill tabs
<div className="flex gap-2 overflow-x-auto pb-1 no-scrollbar">
  {CATEGORIES.map(cat => (
    <button key={cat}
      className={`flex-none px-4 py-1.5 rounded-full text-xs font-bold border transition whitespace-nowrap ${
        activeCategory === cat
          ? 'bg-amber-400 text-slate-950 border-amber-400'
          : 'bg-slate-800 text-slate-400 border-slate-700 hover:border-slate-500'
      }`}
    >
      {cat}
    </button>
  ))}
</div>
```

**Thay đổi 3: Voucher Card — Chuẩn hóa 4 trạng thái nút**
```tsx
// Logic xác định trạng thái nút
function getVoucherButtonState(campaign: Campaign, xuBalance: number, myVouchers: UserVoucher[]) {
  const owned = myVouchers.find(v => v.campaign_id === campaign.id && v.status !== 'expired');
  if (owned) return { label: 'Xem Voucher', style: 'owned', disabled: false };
  if (campaign.available_qty === 0) return { label: 'Hết lượt voucher', style: 'out', disabled: true };
  if (xuBalance < (campaign.xu_cost ?? 0)) return {
    label: `Thiếu ${(campaign.xu_cost ?? 0) - xuBalance} Xu`,
    style: 'insufficient', disabled: true
  };
  return { label: 'Đổi Ngay', style: 'available', disabled: false };
}

// Style mapping
const buttonStyles = {
  available: 'bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold shadow-md shadow-amber-500/20',
  owned: 'bg-emerald-900/60 text-emerald-300 border border-emerald-700/50',
  out: 'bg-slate-800 text-rose-400/70 border border-rose-900/50 cursor-not-allowed',
  insufficient: 'bg-slate-800 text-slate-500 border border-slate-700 cursor-not-allowed',
};
```

**Thay đổi 4: Voucher Card Layout**
```tsx
// Voucher Card chuẩn (thay thế giao diện cũ)
<div className="glass-card rounded-2xl overflow-hidden hover:border-amber-500/30 transition-all duration-200">
  {/* Logo banner */}
  <div className="h-24 bg-slate-800 flex items-center justify-center p-4">
    <img src={campaign.logo_url} className="h-full object-contain" />
  </div>
  
  {/* Content */}
  <div className="p-4">
    <p className="font-bold text-sm text-slate-100 line-clamp-2">{campaign.title || campaign.sponsor_name}</p>
    
    {/* Xu cost badge */}
    <div className="flex items-center gap-1.5 mt-2">
      <IconXu className="h-4 w-4 text-amber-400" />
      <span className="text-amber-400 font-black text-sm">{campaign.xu_cost ?? 0} Xu</span>
      <span className="text-slate-500 text-xs ml-auto">còn {campaign.available_qty} lượt</span>
    </div>
    
    {/* CTA Button */}
    <button className={`w-full mt-3 py-2 rounded-xl text-sm transition ${buttonStyles[state.style]}`}>
      {state.label}
    </button>
  </div>
</div>
```

---

## 📦 BƯỚC 5: TRANG NHIỆM VỤ (`/tasks`)

### [MODIFY] `src/app/tasks/page.tsx`

**Thay đổi chính**: Thêm ProgressBar cho từng nhiệm vụ, Skeleton loading, hiệu ứng pulse cho nút nhận thưởng.

```tsx
// Task Card component với ProgressBar
<div className="glass-card rounded-2xl p-4">
  <div className="flex items-start gap-3">
    <div className={`p-2.5 rounded-xl ${task.completed ? 'bg-emerald-500/20' : 'bg-amber-500/10'}`}>
      <TaskIcon className={`h-5 w-5 ${task.completed ? 'text-emerald-400' : 'text-amber-400'}`} />
    </div>
    <div className="flex-1">
      <p className="font-semibold text-sm text-slate-100">{task.title}</p>
      <p className="text-xs text-slate-400 mt-0.5">{task.description}</p>
      
      {/* Progress Bar */}
      <div className="mt-2.5">
        <div className="flex justify-between text-xs text-slate-500 mb-1">
          <span>{task.progress}/{task.total}</span>
          <span className="text-amber-400 font-bold">+{task.reward} Xu</span>
        </div>
        <div className="h-1.5 bg-slate-800 rounded-full overflow-hidden">
          <div
            className="h-full bg-gradient-to-r from-amber-500 to-yellow-400 rounded-full transition-all duration-500"
            style={{ width: `${(task.progress / task.total) * 100}%` }}
          />
        </div>
      </div>
    </div>
    
    {/* Claim button — pulse khi đủ điều kiện */}
    {task.claimable && (
      <button className="flex-none py-1.5 px-3 rounded-xl bg-amber-400 text-slate-950 text-xs font-black
        animate-pulse-glow shadow-lg shadow-amber-500/25">
        Nhận Xu
      </button>
    )}
  </div>
</div>
```

---

## 📦 BƯỚC 6: TRANG CÀI ĐẶT (`/account/settings`)

### [MODIFY] `src/app/account/settings/page.tsx`

**Thay đổi chính**: Thống nhất tất cả `input`, `textarea`, `select` sang Dark theme, thêm section dividers rõ ràng.

```tsx
// Chuẩn input Dark Glass
const inputClass = `
  w-full rounded-xl bg-slate-900/90 border border-slate-700/80 
  text-slate-100 placeholder-slate-500
  px-4 py-2.5 text-sm
  focus:border-amber-400 focus:ring-1 focus:ring-amber-400/30 
  focus:outline-none transition
`;

// Section Card
<div className="glass-card rounded-2xl p-5 space-y-4">
  <h2 className="text-sm font-bold text-amber-400 uppercase tracking-wider flex items-center gap-2">
    <IconUser className="h-4 w-4" />
    Hồ Sơ Cá Nhân
  </h2>
  {/* inputs */}
</div>
```

---

## 📦 BƯỚC 7: CÁC COMPONENT CHIA SẺ

### [MODIFY] `src/components/ConvertPointsModal.tsx`
- Chuẩn hóa Modal overlay: `bg-black/70 backdrop-blur-sm`
- Modal panel: `glass-card rounded-2xl max-w-sm w-full mx-4`
- Quick amount buttons: 10 Xu, 50 Xu, 100 Xu, Tất cả

### [MODIFY] `src/components/notification-toast.tsx`
- Chuẩn hóa Toast: `glass-card rounded-xl border-l-4 border-amber-400`

### [MODIFY] `src/components/submit-button.tsx`
- Chuẩn hóa nút submit: class `btn-gold` từ utility class đã định nghĩa

---

## ⚡ TIẾN ĐỘ TRIỂN KHAI

| Bước | File cần sửa | Độ ưu tiên | Ước lượng |
|---|---|---|---|
| **1. CSS Foundation** | `globals.css`, `layout.tsx` | 🔴 Cao nhất | ~30 phút |
| **2. Homepage Dark Theme** | `app/page.tsx` | 🔴 Cao | ~45 phút |
| **3. Rewards Page** | `app/rewards/page.tsx` | 🔴 Cao | ~60 phút |
| **4. AppHeader refine** | `components/AppHeader.tsx` | 🟡 Trung bình | ~15 phút |
| **5. Tasks Page** | `app/tasks/page.tsx` | 🟡 Trung bình | ~30 phút |
| **6. Account Settings** | `app/account/settings/page.tsx` | 🟡 Trung bình | ~30 phút |
| **7. Shared Components** | `ConvertPointsModal`, `notification-toast`, `submit-button` | 🟢 Thấp | ~20 phút |
| **8. Commit & Deploy** | git push → Vercel | — | ~5 phút |

> **Tổng ước lượng**: ~3.5 - 4 giờ triển khai.

---

## 📐 NGUYÊN TẮC CODE KHI TRIỂN KHAI
1. **Không xóa logic** — chỉ thay đổi className và cấu trúc JSX trình bày.
2. **Tách component** khi một đoạn JSX lặp lại >= 2 lần (Podium Card, Task Card, Voucher Card).
3. **Utility class trước, inline style sau** — dùng class `.glass-card`, `.btn-gold`, `.text-gold-gradient` từ `globals.css`.
4. **Test từng bước sau mỗi Bước** trước khi push lên GitHub.

---

> ✅ **Bạn có muốn tôi bắt đầu triển khai ngay từ Bước 1 (CSS Foundation + Homepage) không?**
