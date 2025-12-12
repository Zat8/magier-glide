/*
================================================================================
DASHBOARD.JS - FRIEREN RPG GAME
================================================================================
JavaScript untuk mengelola interaktivitas dan dynamic content di dashboard
Menggunakan Vanilla JavaScript (ES6+)
================================================================================
*/

// Hamburger Menu (Mobile)
const hamburger = document.getElementById('hamburger');
const navMenu = document.getElementById('navMenu');

hamburger.addEventListener('click', function() {
    hamburger.classList.toggle('active');
    navMenu.classList.toggle('active');
});

// Close menu saat klik link (Mobile)
const navLinks = document.querySelectorAll('.nav-link, .logout-btn');

navLinks.forEach(link => {
    link.addEventListener('click', function() {
        hamburger.classList.remove('active');
        navMenu.classList.remove('active');
    });
});

// ============================================================================
// 1. GUILD ANNOUNCEMENTS DATA
// ============================================================================

/**
 * Data pengumuman guild
 * Total 12 announcements, 8 visible dan 4 perlu scroll
 */
const announcementsData = [
    {
        title: 'Perekrutan Penyihir Baru',
        description: 'Guild sedang membuka perekrutan anggota baru untuk divisi eksplorasi. Penyihir dengan spesialisasi elemen dasar seperti api, air, dan ilusi sangat dibutuhkan.'
    },
    {
        title: 'Perekrutan Penyihir Baru',
        description: 'Guild sedang membuka perekrutan anggota baru untuk divisi eksplorasi. Penyihir dengan spesialisasi elemen dasar seperti api, air, dan ilusi sangat dibutuhkan.'
    },
    {
        title: 'Perekrutan Penyihir Baru',
        description: 'Guild sedang membuka perekrutan anggota baru untuk divisi eksplorasi. Penyihir dengan spesialisasi elemen dasar seperti api, air, dan ilusi sangat dibutuhkan.'
    },
    {
        title: 'Perekrutan Penyihir Baru',
        description: 'Guild sedang membuka perekrutan anggota baru untuk divisi eksplorasi. Penyihir dengan spesialisasi elemen dasar seperti api, air, dan ilusi sangat dibutuhkan.'
    },
    {
        title: 'Perekrutan Penyihir Baru',
        description: 'Guild sedang membuka perekrutan anggota baru untuk divisi eksplorasi. Penyihir dengan spesialisasi elemen dasar seperti api, air, dan ilusi sangat dibutuhkan.'
    },
    {
        title: 'Perekrutan Penyihir Baru',
        description: 'Guild sedang membuka perekrutan anggota baru untuk divisi eksplorasi. Penyihir dengan spesialisasi elemen dasar seperti api, air, dan ilusi sangat dibutuhkan.'
    },
    {
        title: 'Perekrutan Penyihir Baru',
        description: 'Guild sedang membuka perekrutan anggota baru untuk divisi eksplorasi. Penyihir dengan spesialisasi elemen dasar seperti api, air, dan ilusi sangat dibutuhkan.'
    },
    {
        title: 'Perekrutan Penyihir Baru',
        description: 'Guild sedang membuka perekrutan anggota baru untuk divisi eksplorasi. Penyihir dengan spesialisasi elemen dasar seperti api, air, dan ilusi sangat dibutuhkan.'
    },
    {
        title: 'Perekrutan Penyihir Baru',
        description: 'Guild sedang membuka perekrutan anggota baru untuk divisi eksplorasi. Penyihir dengan spesialisasi elemen dasar seperti api, air, dan ilusi sangat dibutuhkan.'
    },
    {
        title: 'Perekrutan Penyihir Baru',
        description: 'Guild sedang membuka perekrutan anggota baru untuk divisi eksplorasi. Penyihir dengan spesialisasi elemen dasar seperti api, air, dan ilusi sangat dibutuhkan.'
    },
    {
        title: 'Perekrutan Penyihir Baru',
        description: 'Guild sedang membuka perekrutan anggota baru untuk divisi eksplorasi. Penyihir dengan spesialisasi elemen dasar seperti api, air, dan ilusi sangat dibutuhkan.'
    },
    {
        title: 'Perekrutan Penyihir Baru',
        description: 'Guild sedang membuka perekrutan anggota baru untuk divisi eksplorasi. Penyihir dengan spesialisasi elemen dasar seperti api, air, dan ilusi sangat dibutuhkan.'
    }
];

// ============================================================================
// 2. LEVEL SYSTEM CALCULATION
// ============================================================================

/**
 * Level thresholds berdasarkan requirement:
 * Level 9→8: 5 quest
 * Level 8→7: 5 + 10 = 15 quest
 * Level 7→6: 15 + 15 = 30 quest
 * Level 6→5: 30 + 20 = 50 quest
 * Level 5→4: 50 + 25 = 75 quest
 * Level 4→3: 75 + 30 = 105 quest
 * Level 3→2: 105 + 35 = 140 quest
 * Level 2→1: 140 + 40 = 180 quest
 */
const levelThresholds = [0, 5, 15, 30, 50, 75, 105, 140, 180];

/**
 * Calculate current level based on completed quests
 * @param {number} completedQuests - Jumlah quest yang sudah diselesaikan
 * @returns {number} - Current level (1-9, dimana 1 adalah tertinggi)
 */
function getLevelFromQuests(completedQuests) {
    // Loop dari threshold tertinggi ke terendah
    for (let i = levelThresholds.length - 1; i >= 0; i--) {
        if (completedQuests >= levelThresholds[i]) {
            return 9 - i; // Convert index ke level number
        }
    }
    return 9; // Default level 9 (terendah)
}

/**
 * Calculate progress percentage to next level
 * @param {number} completedQuests - Jumlah quest yang sudah diselesaikan
 * @returns {number} - Progress percentage (0-100)
 */
function getProgressToNextLevel(completedQuests) {
    const currentLevel = getLevelFromQuests(completedQuests);
    
    // Jika sudah level 1 (tertinggi), progress 100%
    if (currentLevel === 1) {
        return 100;
    }
    
    // Get current and next threshold
    const currentThresholdIndex = 9 - currentLevel;
    const currentThreshold = levelThresholds[currentThresholdIndex];
    const nextThreshold = levelThresholds[currentThresholdIndex + 1];
    
    // Calculate progress
    const questsInCurrentLevel = completedQuests - currentThreshold;
    const questsNeededForNextLevel = nextThreshold - currentThreshold;
    const progress = (questsInCurrentLevel / questsNeededForNextLevel) * 100;
    
    return Math.min(progress, 100);
}

/**
 * Get level suffix (st, nd, rd, th)
 * @param {number} level - Level number
 * @returns {string} - Suffix string
 */
function getLevelSuffix(level) {
    if (level === 1) return 'st';
    if (level === 2) return 'nd';
    if (level === 3) return 'rd';
    return 'th';
}

/**
 * Update player level display
 * @param {number} completedQuests - Jumlah quest yang sudah diselesaikan
 */
function updatePlayerLevel(completedQuests) {
    const level = getLevelFromQuests(completedQuests);
    const levelNumberEl = document.getElementById('levelNumber');
    const levelSuffixEl = document.getElementById('levelSuffix');
    
    if (levelNumberEl) {
        levelNumberEl.textContent = level;
    }
    
    if (levelSuffixEl) {
        levelSuffixEl.textContent = getLevelSuffix(level);
    }
}

/**
 * Update progress bar display
 * @param {number} completedQuests - Jumlah quest yang sudah diselesaikan
 */
function updateProgressBar(completedQuests) {
    const progressBar = document.getElementById('progressBar');
    const progressPercent = getProgressToNextLevel(completedQuests);
    
    if (progressBar) {
        // Delay untuk smooth animation saat page load
        setTimeout(() => {
            progressBar.style.width = `${progressPercent}%`;
        }, 100);
    }
}

// ============================================================================
// 3. ANNOUNCEMENTS RENDERING & ACCORDION
// ============================================================================

/**
 * Create announcement item HTML
 * @param {object} announcement - Announcement data object
 * @param {number} index - Index untuk unique ID
 * @returns {string} - HTML string
 */
function createAnnouncementItem(announcement, index) {
    return `
        <div class="announcement-item">
            <button class="announcement-header" onclick="toggleAnnouncement(${index})">
                <div class="announcement-title-group">
                    <div class="announcement-diamond"></div>
                    <span class="announcement-title">${announcement.title}</span>
                </div>
                <span class="announcement-arrow" id="arrow-${index}">›</span>
            </button>
            <div class="announcement-content" id="content-${index}">
                <p class="announcement-text">${announcement.description}</p>
            </div>
        </div>
    `;
}

/**
 * Render all announcements
 */
function renderAnnouncements() {
    const container = document.getElementById('announcementsContainer');
    
    if (container) {
        // Generate HTML untuk semua announcements
        const announcementsHTML = announcementsData
            .map((announcement, index) => createAnnouncementItem(announcement, index))
            .join('');
        
        container.innerHTML = announcementsHTML;
    }
}

/**
 * Toggle announcement accordion
 * @param {number} index - Index of announcement to toggle
 */
function toggleAnnouncement(index) {
    const content = document.getElementById(`content-${index}`);
    const arrow = document.getElementById(`arrow-${index}`);
    
    if (content && arrow) {
        // Check if currently expanded
        const isExpanded = content.classList.contains('expanded');
        
        if (isExpanded) {
            // Collapse
            content.classList.remove('expanded');
            arrow.classList.remove('expanded');
        } else {
            // Expand
            content.classList.add('expanded');
            arrow.classList.add('expanded');
        }
    }
}

// Make function available globally for onclick
window.toggleAnnouncement = toggleAnnouncement;

// ============================================================================
// 4. INITIALIZATION
// ============================================================================

/**
 * Initialize dashboard
 * Dipanggil saat DOM sudah fully loaded
 */
function initDashboard() {
    console.log('🎮 Initializing Frieren RPG Dashboard...');
    
    // 1. Render announcements
    renderAnnouncements();
    
    // 2. Update player level & progress bar
    // Example: 12 completed quests (bisa diganti dengan data dari API/localStorage)
    const completedQuests = 12;
    updatePlayerLevel(completedQuests);
    updateProgressBar(completedQuests);
    
    // 3. Log info
    console.log('✅ Dashboard initialized successfully!');
    console.log(`📊 Level: ${getLevelFromQuests(completedQuests)}/9`);
    console.log(`📈 Progress: ${getProgressToNextLevel(completedQuests).toFixed(1)}%`);
    console.log(`📢 Announcements: ${announcementsData.length} loaded`);
}

// Wait for DOM to be fully loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDashboard);
} else {
    initDashboard();
}

/*
================================================================================
END OF DASHBOARD.JS
================================================================================

PENJELASAN STRUKTUR & LOGIC:

1. DATA MANAGEMENT:
   - announcementsData: Array of 12 announcements
   - Menggunakan array untuk mudah di-loop dan di-render

2. LEVEL SYSTEM:
   - levelThresholds: Array yang define kapan player naik level
   - getLevelFromQuests(): Loop dari belakang untuk efficient calculation
   - getProgressToNextLevel(): Calculate percentage untuk progress bar
   - getLevelSuffix(): Helper untuk format level display (1st, 2nd, 3rd, 4th)

3. UPDATE FUNCTIONS:
   - updatePlayerLevel(): Update level number & suffix di UI
   - updateProgressBar(): Update width progress bar dengan smooth animation
   - Menggunakan setTimeout untuk delay animation saat page load

4. ANNOUNCEMENTS:
   - createAnnouncementItem(): Generate HTML string untuk 1 announcement
   - renderAnnouncements(): Loop semua data dan inject ke DOM
   - toggleAnnouncement(): Toggle expand/collapse dengan class manipulation
   - window.toggleAnnouncement: Expose function untuk onclick HTML attribute

5. INITIALIZATION:
   - initDashboard(): Main function yang dipanggil saat page load
   - DOMContentLoaded check: Ensure DOM ready sebelum manipulasi
   - Console logs: Helpful untuk debugging

6. KEY FEATURES:
   ✅ Dynamic level calculation berdasarkan quest completion
   ✅ Smooth progress bar animation
   ✅ Accordion untuk announcements
   ✅ Efficient rendering dengan array methods
   ✅ Console logging untuk debugging

7. FUTURE ENHANCEMENTS:
   - Connect ke API untuk fetch real data
   - localStorage untuk persist user progress
   - Add loading states
   - Error handling untuk robustness
   - Add event listeners untuk interactive elements

================================================================================
*/