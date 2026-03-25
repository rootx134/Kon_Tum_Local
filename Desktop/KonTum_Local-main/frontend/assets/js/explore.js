// explore.js - Interactive Map View & Discovery Logic

let exploreMap = null;
let userMarker = null;
let exploreMarkers = [];

async function initExploreTab() {
    // 1. Khởi tạo mượt mà Bản đồ TrackAsia nếu chưa có
    if (!exploreMap) {
        initExploreMap();
    } else {
        // Resize map when tab is shown to fix rendering glitches
        setTimeout(() => exploreMap.resize(), 100);
    }

    // 2. Gắn sự kiện Toggle View
    const btnToggle = document.getElementById('btnExploreToggleView');
    if (btnToggle) {
        btnToggle.onclick = toggleExploreView;
    }

    // 3. Gắn sự kiện Radar (Gần tôi)
    const btnRadar = document.getElementById('btnExploreRadar');
    if (btnRadar) {
        btnRadar.onclick = activateRadar;
    }

    // Load tất cả địa điểm lên bản đồ Explorer lần đầu nếu chưa có marker
    if (exploreMarkers.length === 0) {
        await loadExplorePlaces();
    }
}

function initExploreMap() {
    trackasiagl.accessToken = 'public_key'; // Update if you have a specific token
    
    exploreMap = new trackasiagl.Map({
        container: 'exploreMap',
        style: 'https://basemaps.cartocdn.com/gl/voyager-gl-style/style.json',
        center: [108.0000, 14.3500], // Tọa độ trung tâm Kon Tum
        zoom: 13,
        pitch: 40,
        bearing: 0
    });

    // Thêm control điều hướng
    exploreMap.addControl(new trackasiagl.NavigationControl({
        showCompass: true,
        showZoom: false
    }), 'bottom-right');

    exploreMap.on('load', () => {
        exploreMap.resize();
    });

    // Ẩn preview khi click ra ngoài map
    exploreMap.on('click', (e) => {
        // Nếu click vào vùng trống (không phải marker)
        hidePreviewCard();
    });
}

function toggleExploreView() {
    const mapView = document.getElementById('explore-map-view');
    const listView = document.getElementById('explore-list-view');
    const btnIcon = document.querySelector('#btnExploreToggleView i');
    const btnText = document.querySelector('#btnExploreToggleView');

    if (mapView.classList.contains('hidden')) {
        // Đang xem List -> Chuyển sang Map
        listView.classList.add('hidden');
        mapView.classList.remove('hidden');
        btnIcon.className = 'fa-solid fa-list';
        btnText.innerHTML = '<i class="fa-solid fa-list"></i> Danh sách';
        exploreMap.resize();
    } else {
        // Đang xem Map -> Chuyển sang List
        mapView.classList.add('hidden');
        listView.classList.remove('hidden');
        btnIcon.className = 'fa-solid fa-map';
        btnText.innerHTML = '<i class="fa-solid fa-map-location-dot"></i> Bản đồ';
    }
}

async function loadExplorePlaces() {
    try {
        const places = await window.apiService.getPlaces({ status: 'approved' });
        
        if (!places) throw new Error('Không lấy được dữ liệu địa điểm');

        places.forEach(place => addExploreMarker(place));
    } catch (err) {
        console.error('Lỗi khi tải địa điểm Explore:', err);
    }
}

function addExploreMarker(place) {
    if (!place.latitude || !place.longitude) return;

    let imgUrl = place.thumbnail || (place.image_objects && place.image_objects.length > 0 ? place.image_objects[0].url : 'https://placehold.co/100x100?text=KT');

    // Tạo HTML cho custom marker
    const el = document.createElement('div');
    el.className = 'custom-marker';
    el.innerHTML = `
        <div class="w-10 h-10 rounded-full border-2 border-white shadow-lg overflow-hidden bg-white hover:scale-110 active:scale-95 transition-transform cursor-pointer origin-bottom" style="margin-top: -10px;">
            <img src="${imgUrl}" class="w-full h-full object-cover">
        </div>
    `;

    const marker = new trackasiagl.Marker({ element: el })
        .setLngLat([parseFloat(place.longitude), parseFloat(place.latitude)])
        .addTo(exploreMap);

    // Gắn sự kiện click để hiện popup Preview
    el.addEventListener('click', (e) => {
        e.stopPropagation(); // Ngăn sự kiện click map
        showPreviewCard(place);
        
        // Fly to marker
        exploreMap.flyTo({
            center: [parseFloat(place.longitude), parseFloat(place.latitude)],
            zoom: 15,
            offset: [0, -100] // Dịch tâm xuống một chút do có bottom sheet
        });
    });

    exploreMarkers.push({ marker, data: place });
}

function showPreviewCard(place) {
    const previewContainer = document.getElementById('exploreMapPreview');
    const categoryName = getCategoryNameLocal(place.category_id);
    const isOpen = checkIsOpen(place.opening_time, place.closing_time);
    let imgUrl = place.thumbnail || (place.image_objects && place.image_objects.length > 0 ? place.image_objects[0].url : 'https://placehold.co/100x100?text=KT');

    previewContainer.innerHTML = `
        <img src="${imgUrl}" class="w-20 h-20 rounded-2xl object-cover shadow-sm">
        <div class="flex-1 overflow-hidden">
            <h4 class="font-bold text-gray-800 dark:text-gray-100 truncate text-base">${place.name}</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400 truncate mb-1"><i class="fa-solid fa-location-dot mr-1"></i> ${place.address}</p>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-xs font-bold px-2 py-0.5 rounded-md ${isOpen ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">${isOpen ? 'Đang mở' : 'Đóng cửa'}</span>
                <span class="text-xs text-[#ff5500] font-bold bg-[#ff5500]/10 px-2 py-0.5 rounded-md">${categoryName}</span>
            </div>
        </div>
        <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300">
            <i class="fa-solid fa-chevron-right"></i>
        </div>
    `;

    // Click vào card để xem chi tiết
    previewContainer.onclick = () => {
        window.openPlaceDetail(place);
    };

    // Hiện animation trượt lên
    previewContainer.classList.remove('translate-y-[200%]');
    previewContainer.classList.add('translate-y-0');
}

function hidePreviewCard() {
    const previewContainer = document.getElementById('exploreMapPreview');
    if (previewContainer) {
        previewContainer.classList.add('translate-y-[200%]');
        previewContainer.classList.remove('translate-y-0');
    }
}

function activateRadar() {
    if (!navigator.geolocation) {
        showToast('Trình duyệt của bạn không hỗ trợ định vị GPS.', 'error');
        return;
    }

    const btnRadar = document.getElementById('btnExploreRadar');
    const originalContent = btnRadar.innerHTML;
    btnRadar.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang quét...';
    btnRadar.classList.add('animate-pulse');

    navigator.geolocation.getCurrentPosition(
        (position) => {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;

            // Đặt marker người dùng
            if (userMarker) {
                userMarker.setLngLat([userLng, userLat]);
            } else {
                const el = document.createElement('div');
                el.className = 'user-gps-marker';
                el.innerHTML = `
                    <div class="relative w-6 h-6 flex items-center justify-center -ml-3 -mt-3">
                        <div class="absolute inset-0 bg-blue-500 rounded-full animate-ping opacity-75"></div>
                        <div class="absolute w-4 h-4 bg-blue-600 border-2 border-white rounded-full shadow-lg"></div>
                    </div>
                `;
                userMarker = new trackasiagl.Marker({ element: el })
                    .setLngLat([userLng, userLat])
                    .addTo(exploreMap);
            }

            // Bay tới vị trí người dùng
            exploreMap.flyTo({
                center: [userLng, userLat],
                zoom: 14,
                pitch: 50
            });

            // Lọc các địa điểm gần đây (ví dụ bán kính 5km) và đang mở cửa
            filterPlacesNearMe(userLat, userLng, 5);

            btnRadar.innerHTML = '<i class="fa-solid fa-check"></i> Đang ở đây';
            btnRadar.classList.remove('animate-pulse');
            btnRadar.classList.add('bg-blue-100', 'text-blue-600');
            setTimeout(() => {
                btnRadar.innerHTML = originalContent;
                btnRadar.classList.remove('bg-blue-100', 'text-blue-600');
            }, 3000);
        },
        (error) => {
            console.error('Lỗi định vị:', error);
            btnRadar.innerHTML = originalContent;
            btnRadar.classList.remove('animate-pulse');
            showToast('Không thể lấy vị trí. Vui lòng cấp quyền GPS.', 'error');
        },
        { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }
    );
}

function filterPlacesNearMe(lat1, lon1, radiusKm) {
    // Thuật toán Haversine tính khoảng cách
    exploreMarkers.forEach(item => {
        const place = item.data;
        const lat2 = parseFloat(place.latitude);
        const lon2 = parseFloat(place.longitude);
        
        const R = 6371; // Bán kính trái đất (km)
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = 
            Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
            Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        const distance = R * c;

        const isOpen = checkIsOpen(place.opening_time, place.closing_time);

        if (distance <= radiusKm) {
            // Nổi bật marker đang mở cửa, làm mờ marker ở xa/đóng cửa
            item.marker.getElement().style.opacity = isOpen ? 1 : 0.5;
            item.marker.getElement().style.transform = isOpen ? 'scale(1.1)' : 'scale(0.9)';
            
            // Nếu muốn chỉ hiển thị điểm gần:
            // item.marker.getElement().style.display = 'block';
        } else {
            // item.marker.getElement().style.display = 'none';
            item.marker.getElement().style.opacity = 0.3;
        }
    });

    showToast(`Đã tìm thấy các địa điểm quanh bạn trong bán kính ${radiusKm}km`, 'success');
}

// Giả lập hàm getCategoryNameLocal & checkIsOpen nếu chưa có trong global
function getCategoryNameLocal(id) {
    const categories = {
        '1': 'Cafe & Trà',
        '2': 'Nhà hàng',
        '3': 'Quán ăn vặt',
        '4': 'Check-in',
        '5': 'Khách sạn'
    };
    return categories[id] || 'Địa điểm';
}

function checkIsOpen(openTimeStr, closeTimeStr) {
    if (!openTimeStr || !closeTimeStr) return true; // Default open
    try {
        const now = new Date();
        const currentHours = now.getHours();
        const currentMins = now.getMinutes();
        
        const [openH, openM] = openTimeStr.split(':').map(Number);
        const [closeH, closeM] = closeTimeStr.split(':').map(Number);
        
        const currentTotal = currentHours * 60 + currentMins;
        const openTotal = openH * 60 + openM;
        const closeTotal = closeH * 60 + closeM;
        
        if (closeTotal < openTotal) {
            // Mở xuyên đêm (vd: 18:00 đến 02:00)
            return currentTotal >= openTotal || currentTotal <= closeTotal;
        }
        return currentTotal >= openTotal && currentTotal <= closeTotal;
    } catch {
        return true;
    }
}
