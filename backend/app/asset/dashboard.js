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
    inProgress: [],
    done: []
};

function capitalizeFirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function createQuestCard(quest, showTime = false) {
    return `
        <div class="quest-card"
			 data-quest-id="${quest.quest_id}"
			 data-console="console"
             data-finish-time="${quest.finish_time}">

            <div class="quest-type ${quest.category_name}">
                <div class="quest-diamond"></div>
                <span class="quest-type-text">
                    ${capitalizeFirst(quest.category_name)}
                </span>
            </div>

            <div class="quest-body">
                <h3 class="quest-title">${quest.quest_name}</h3>
                <p class="quest-description">${quest.descriptions}</p>

                ${showTime
                  ? `<p class="quest-time">Sisa waktu: <span class="quest-timer">--:--</span></p>`
                  : ''}
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

	startGlobalQuestTimer();
}

async function loadQuestWithUser() {
	const res = await fetch("api/list_misi_user.php");
	const data = await res.json();

	questsData.inProgress = data["inProgress"];
	questsData.done = data["done"];
}

let questTimerInterval = null;

function startGlobalQuestTimer() {
    if (questTimerInterval) return;

    questTimerInterval = setInterval(() => {
		const now = Date.now();

        document
          .querySelectorAll('.quest-card[data-finish-time]')
          .forEach(card => {
            const finishTime = new Date(card.dataset.finishTime.replace(" ", "T"));
            const timerEl = card.querySelector('.quest-timer');
			let remaining = finishTime - now;

            if (!timerEl) return;

            if (remaining <= 0) {
                timerEl.textContent = 'Selesai';
                card.removeAttribute('data-finish-time');

                finishQuest(card.dataset.questId);
                return;
            }

            timerEl.textContent = formatTime(remaining);
        });

    }, 1000);
}

function moveQuestToDone(questId) {
    const index = questsData.inProgress.findIndex(
        q => String(q.quest_id) === String(questId)
    );

    if (index === -1) return;

    const [quest] = questsData.inProgress.splice(index, 1);

    quest.status = 'done';
    delete quest.finish_time;

    questsData.done.push(quest);

    renderQuests();
}


function formatTime(diff) {
    const hours = String(Math.floor(diff / 3600000)).padStart(2, "0");
    const minutes = String(Math.floor((diff % 3600000) / 60000)).padStart(2, "0");
	const seconds = String(Math.floor((diff % 60000) / 1000)).padStart(2, "0");    

	return `${hours}:${minutes}:${seconds}`;
}

function showRewardPopup(reward) {
	const overlay = document.getElementById('reward-overlay');
	const textEl = overlay.querySelector('.reward-text');
	const iconEl = overlay.querySelector('.reward-icon');

	switch (reward.type) {
	case 'achievement':
	  iconEl.textContent = 'Achievement';
	  textEl.textContent = `Achievement Unlocked: ${reward.data.name}`;
	  break;

	case 'sihir':
	  iconEl.textContent = 'Sihir';
	  textEl.textContent = `New Sihir Acquired: ${reward.data.name}`;
	  break;

	case 'exp':
	  iconEl.textContent = 'Experience';
	  textEl.textContent = `You gained ${reward.data.exp} EXP`;
	  break;
	}

	overlay.classList.remove('hidden');
}

function closeRewardPopup() {
  document.getElementById('reward-overlay')
    .classList.add('hidden');
}


function finishQuest(questId) {
	const formData = new FormData();
	formData.append("quest_id", questId);

    fetch('api/finish_quest.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.ok) {
			moveQuestToDone(questId);

			if (data.reward) {
        		showRewardPopup(data.reward);
      		}
        } else {
            console.warn(data.message);
        }
    });
}


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

function playMusic() {
	const ctx = new AudioContext();
	const audio = new Audio("asset/music/bgmusic.mp3");
	audio.volume = 0.03;
	const source = ctx.createMediaElementSource(audio);
	source.connect(ctx.destination);

	document.addEventListener("click", async () => {
	  if (ctx.state === "suspended") {
		await ctx.resume();
	  }
	  audio.play();
	}, { once: true });
}




window.toggleAnnouncement = toggleAnnouncement;
async function initDashboard() {
	// playMusic();
	loadQuestWithUser();
	renderQuests();
	littleAnim();
    setupModals();
	renderAnnouncements();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDashboard);
} else {
    initDashboard();
}
