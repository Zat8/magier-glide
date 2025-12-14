const hamburger = document.getElementById('hamburger');
const navMenu = document.getElementById('navMenu');

hamburger.addEventListener('click', function() {
    hamburger.classList.toggle('active');
    navMenu.classList.toggle('active');
});

const navLinks = document.querySelectorAll('.nav-link, .logout-btn');
navLinks.forEach(link => {
    link.addEventListener('click', function() {
        hamburger.classList.remove('active');
        navMenu.classList.remove('active');
    });
});


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
                <p class="announcement-text">${announcement.content}</p>
            </div>
        </div>
    `;
}

async function renderAnnouncements() {
	const container = document.getElementById('announcementsContainer');
	const res = await fetch("api/list_pengumuman.php");
	const announcementsData = await res.json();

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

    if (!inProgressContainer) {
        inProgressContainer.innerHTML = questsData.inProgress
            .map(quest => createQuestCard(quest, true))
            .join('');
    }

    if (!doneContainer) {
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

    const leaderboardBtn = document.getElementById('leaderboardBtn');
    const closeLeaderboardModal = document.getElementById('closeLeaderboardModal');
    const leaderboardModal = document.getElementById('leaderboardModal');

    if (leaderboardBtn) {
        leaderboardBtn.addEventListener('click', () => {
            showModal('leaderboardModal');
        });
    }

    if (closeLeaderboardModal) {
        closeLeaderboardModal.addEventListener('click', () => hideModal('leaderboardModal'));
    }

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


function littleAnim() {
	let targetX = 0, targetY = 0;
	let targetBgX = 0, targetBgY = 0;

	let currentX = 0, currentY = 0;
	let bgX = 0, bgY = 0;

	const bg = document.querySelector(".background-blur");
	const title = document.querySelector(".character-display");

	document.addEventListener("mousemove", (e) => {
		const x = (e.clientX / window.innerWidth) - 0.5;
		const y = (e.clientY / window.innerHeight) - 0.5;

		targetBgX = -x * 60;
		targetBgY = -y * 60;

		targetX = -x * 20;
		targetY = -y * 20;
	});

	function animate() {
		bgX += (targetBgX - bgX) * 0.08;
		bgY += (targetBgY - bgY) * 0.08;

		currentX += (targetX - currentX) * 0.2;
		currentY += (targetY - currentY) * 0.2;

		bg.style.transform = `translate3d(${bgX}px, ${bgY}px, 0)`;
		title.style.transform = `translate(${currentX}px, ${currentY}px)`;

		requestAnimationFrame(animate);
	}

	animate();

}

window.toggleAnnouncement = toggleAnnouncement;
async function initDashboard() {
	littleAnim();
    setupModals();
	renderAnnouncements();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDashboard);
} else {
    initDashboard();
}
