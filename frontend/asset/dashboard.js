/*
================================================================================
DASHBOARD.JS - FRIEREN RPG GAME (WITH MODALS)
================================================================================
*/

// ============================================================================
// HAMBURGER MENU (MOBILE)
// ============================================================================

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

const levelThresholds = [0, 5, 15, 30, 50, 75, 105, 140, 180];

function getLevelFromQuests(completedQuests) {
    for (let i = levelThresholds.length - 1; i >= 0; i--) {
        if (completedQuests >= levelThresholds[i]) {
            return 9 - i;
        }
    }
    return 9;
}

function getProgressToNextLevel(completedQuests) {
    const currentLevel = getLevelFromQuests(completedQuests);
    
    if (currentLevel === 1) {
        return 100;
    }
    
    const currentThresholdIndex = 9 - currentLevel;
    const currentThreshold = levelThresholds[currentThresholdIndex];
    const nextThreshold = levelThresholds[currentThresholdIndex + 1];
    
    const questsInCurrentLevel = completedQuests - currentThreshold;
    const questsNeededForNextLevel = nextThreshold - currentThreshold;
    const progress = (questsInCurrentLevel / questsNeededForNextLevel) * 100;
    
    return Math.min(progress, 100);
}

function getLevelSuffix(level) {
    if (level === 1) return 'st';
    if (level === 2) return 'nd';
    if (level === 3) return 'rd';
    return 'th';
}

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

function updateProgressBar(completedQuests) {
    const progressBar = document.getElementById('progressBar');
    const progressPercent = getProgressToNextLevel(completedQuests);
    
    if (progressBar) {
        setTimeout(() => {
            progressBar.style.width = `${progressPercent}%`;
        }, 100);
    }
}

// ============================================================================
// 3. ANNOUNCEMENTS RENDERING & ACCORDION
// ============================================================================

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

function renderAnnouncements() {
    const container = document.getElementById('announcementsContainer');
    
    if (container) {
        const announcementsHTML = announcementsData
            .map((announcement, index) => createAnnouncementItem(announcement, index))
            .join('');
        
        container.innerHTML = announcementsHTML;
    }
}

function toggleAnnouncement(index) {
    const content = document.getElementById(`content-${index}`);
    const arrow = document.getElementById(`arrow-${index}`);
    
    if (content && arrow) {
        const isExpanded = content.classList.contains('expanded');
        
        if (isExpanded) {
            content.classList.remove('expanded');
            arrow.classList.remove('expanded');
        } else {
            content.classList.add('expanded');
            arrow.classList.add('expanded');
        }
    }
}

window.toggleAnnouncement = toggleAnnouncement;

// ============================================================================
// 4. QUEST DATA & RENDERING
// ============================================================================

const questsData = {
    inProgress: [
        {
            id: 1,
            type: 'exploration',
            title: 'Herb For Healing',
            description: 'Mengumpulkan 5 tanaman Lichtblume yang hanya tumbuh saat pagi berkabut.',
            time: '00:02:00'
        },
        {
            id: 2,
            type: 'escort',
            title: 'Herb For Healing',
            description: 'Mengumpulkan 5 tanaman Lichtblume yang hanya tumbuh saat pagi berkabut.',
            time: '00:02:00'
        },
        {
            id: 3,
            type: 'investigation',
            title: 'Herb For Healing',
            description: 'Mengumpulkan 5 tanaman Lichtblume yang hanya tumbuh saat pagi berkabut.',
            time: '00:02:00'
        },
        {
            id: 4,
            type: 'exploration',
            title: 'Herb For Healing',
            description: 'Mengumpulkan 5 tanaman Lichtblume yang hanya tumbuh saat pagi berkabut.',
            time: '00:02:00'
        },
        {
            id: 5,
            type: 'escort',
            title: 'Herb For Healing',
            description: 'Mengumpulkan 5 tanaman Lichtblume yang hanya tumbuh saat pagi berkabut.',
            time: '00:02:00'
        },
        {
            id: 6,
            type: 'investigation',
            title: 'Herb For Healing',
            description: 'Mengumpulkan 5 tanaman Lichtblume yang hanya tumbuh saat pagi berkabut.',
            time: '00:02:00'
        },
        {
            id: 7,
            type: 'exploration',
            title: 'Herb For Healing',
            description: 'Mengumpulkan 5 tanaman Lichtblume yang hanya tumbuh saat pagi berkabut.',
            time: '00:02:00'
        },
        {
            id: 8,
            type: 'escort',
            title: 'Herb For Healing',
            description: 'Mengumpulkan 5 tanaman Lichtblume yang hanya tumbuh saat pagi berkabut.',
            time: '00:02:00'
        }
    ],
    done: [
        {
            id: 9,
            type: 'exploration',
            title: 'Herb For Healing',
            description: 'Mengumpulkan 5 tanaman Lichtblume yang hanya tumbuh saat pagi berkabut.'
        },
        {
            id: 10,
            type: 'escort',
            title: 'Herb For Healing',
            description: 'Mengumpulkan 5 tanaman Lichtblume yang hanya tumbuh saat pagi berkabut.'
        },
        {
            id: 11,
            type: 'investigation',
            title: 'Herb For Healing',
            description: 'Mengumpulkan 5 tanaman Lichtblume yang hanya tumbuh saat pagi berkabut.'
        }
    ]
};

function capitalizeFirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function createQuestCard(quest, showTime = false) {
    return `
        <div class="quest-card">
            <div class="quest-type ${quest.type}">
                <div class="quest-diamond"></div>
                <span class="quest-type-text">${capitalizeFirst(quest.type)}</span>
            </div>
            <div class="quest-body">
                <h3 class="quest-title">${quest.title}</h3>
                <p class="quest-description">${quest.description}</p>
                ${showTime ? `<p class="quest-time">Waktu: ${quest.time}</p>` : ''}
            </div>
        </div>
    `;
}

function renderQuests() {
    const inProgressContainer = document.getElementById('questInProgress');
    const doneContainer = document.getElementById('questDone');
    const inProgressCount = document.getElementById('inProgressCount');
    const doneCount = document.getElementById('doneCount');

    if (inProgressContainer) {
        inProgressContainer.innerHTML = questsData.inProgress
            .map(quest => createQuestCard(quest, true))
            .join('');
    }

    if (doneContainer) {
        doneContainer.innerHTML = questsData.done
            .map(quest => createQuestCard(quest, false))
            .join('');
    }

    if (inProgressCount) {
        inProgressCount.textContent = questsData.inProgress.length;
    }

    if (doneCount) {
        doneCount.textContent = questsData.done.length;
    }
}

// ============================================================================
// 5. LEADERBOARD DATA & RENDERING
// ============================================================================

const leaderboardData = [
    { rank: 1, name: 'FRIEREN', email: 'frieren@gmail.com', class: 'FIRST CLASS' },
    { rank: 2, name: 'FERN', email: 'fern@gmail.com', class: 'SECOND CLASS' },
    { rank: 3, name: 'STARK', email: 'stark@gmail.com', class: 'SECOND CLASS' },
    { rank: 4, name: 'HIMMEL', email: 'himmel@gmail.com', class: 'SECOND CLASS' },
    { rank: 5, name: 'HEITER', email: 'heiter@gmail.com', class: 'THIRD CLASS' },
    { rank: 6, name: 'EISEN', email: 'eisen@gmail.com', class: 'THIRD CLASS' }
];

function createLeaderboardItem(player) {
    return `
        <div class="leaderboard-item">
            <div class="leaderboard-rank">
                <span class="rank-number">${player.rank}</span>
            </div>
            <div class="leaderboard-info">
                <div class="player-avatar"></div>
                <div class="player-name-group">
                    <h3>${player.name}</h3>
                    <p class="player-email">${player.email}</p>
                </div>
            </div>
            <div class="leaderboard-class">
                <span class="class-badge">${player.class}</span>
            </div>
        </div>
    `;
}

function renderLeaderboard() {
    const container = document.getElementById('leaderboardList');

    if (container) {
        container.innerHTML = leaderboardData
            .map(player => createLeaderboardItem(player))
            .join('');
    }
}

// ============================================================================
// 6. MODAL MANAGEMENT
// ============================================================================

function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function hideModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

function setupModals() {
    // Quest Modal
    const questBtn = document.getElementById('questBtn');
    const closeQuestModal = document.getElementById('closeQuestModal');
    const questModal = document.getElementById('questModal');

    if (questBtn) {
        questBtn.addEventListener('click', () => {
            showModal('questModal');
            renderQuests();
        });
    }

    if (closeQuestModal) {
        closeQuestModal.addEventListener('click', () => hideModal('questModal'));
    }

    // Leaderboard Modal
    const leaderboardBtn = document.getElementById('leaderboardBtn');
    const closeLeaderboardModal = document.getElementById('closeLeaderboardModal');
    const leaderboardModal = document.getElementById('leaderboardModal');

    if (leaderboardBtn) {
        leaderboardBtn.addEventListener('click', () => {
            showModal('leaderboardModal');
            renderLeaderboard();
        });
    }

    if (closeLeaderboardModal) {
        closeLeaderboardModal.addEventListener('click', () => hideModal('leaderboardModal'));
    }

    // Close modal saat click di luar content
    if (questModal) {
        questModal.addEventListener('click', (e) => {
            if (e.target === questModal) {
                hideModal('questModal');
            }
        });
    }

    if (leaderboardModal) {
        leaderboardModal.addEventListener('click', (e) => {
            if (e.target === leaderboardModal) {
                hideModal('leaderboardModal');
            }
        });
    }
}

// ============================================================================
// 7. INITIALIZATION
// ============================================================================

function initDashboard() {
    console.log('🎮 Initializing Frieren RPG Dashboard...');
    
    // 1. Render announcements
    renderAnnouncements();
    
    // 2. Update player level & progress bar
    const completedQuests = 12;
    updatePlayerLevel(completedQuests);
    updateProgressBar(completedQuests);
    
    // 3. Setup modal event listeners
    setupModals();
    
    // 4. Log info
    console.log('✅ Dashboard initialized successfully!');
    console.log(`📊 Level: ${getLevelFromQuests(completedQuests)}/9`);
    console.log(`📈 Progress: ${getProgressToNextLevel(completedQuests).toFixed(1)}%`);
    console.log(`📢 Announcements: ${announcementsData.length} loaded`);
    console.log(`🎯 Quests: ${questsData.inProgress.length} in progress, ${questsData.done.length} done`);
    console.log(`🏆 Leaderboard: ${leaderboardData.length} players`);
}

// Wait for DOM to be fully loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDashboard);
} else {
    initDashboard();
}

/*
================================================================================
END OF DASHBOARD.JS (WITH MODALS)
================================================================================

FEATURES INTEGRATED:
✅ Hamburger menu untuk mobile
✅ Guild announcements dengan accordion
✅ Level system dengan progress bar
✅ Quest modal dengan in progress & done sections
✅ Leaderboard modal dengan player rankings
✅ Modal management (show/hide, click outside to close)
✅ Dynamic rendering semua content
✅ Smooth animations & transitions

================================================================================
*/