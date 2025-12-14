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

const res = await fetch("api/list_misi.php");
const questData = await res.json();
console.log(questData);

const questThemes = {
    exploration: {
        color: '#87A1D5',        
        label: 'Exploration Quest',
		containerBg: "asset/foto/peksplor.png",
		bgImage: "asset/foto/bg_eksplor.webp",
		character: "frieren"
    },
    escort: {
        color: '#87D593',        
        label: 'Escort Quest',
		containerBg: "asset/foto/pescort.png",
		bgImage: "asset/foto/bg_escort.webp",
		character: "stark"
	},
    investigation: {
        color: '#9B87D5',        
        label: 'Investigation Quest',
   		containerBg: "asset/foto/pinvest.png",
		bgImage: "asset/foto/bg_invest.webp",
		character: "fern"
	}
};

preloadImage(
	questThemes.exploration.containerBg, questThemes.investigation.containerBg, questThemes.escort.containerBg,
	questThemes.exploration.bgImage, questThemes.investigation.bgImage, questThemes.escort.bgImage
);

let currentQuestType = 'exploration'; 
let currentQuest = null;

const background = document.querySelector(".background");
const questListContainer = document.getElementById('questList');
const modalOverlay = document.getElementById('modalOverlay');
const modalContent = document.getElementById('modalContent');
const modalSubtitle = document.getElementById('modalSubtitle');
const modalTitle = document.getElementById('modalTitle');
const modalObjective = document.getElementById('modalObjective');
const modalDescription = document.getElementById('modalDescription');
const btnTakeQuest = document.getElementById('btnTakeQuest');
const questTypeButtons = document.querySelectorAll('.quest-type-btn');

const imgBg = Array.from(background.querySelectorAll("img"));
const container = document.querySelector(".main-container");

function renderQuestList(type) {
    const quests = questData[type];
    const theme = questThemes[type];
	const bgBig = imgBg[0];
	const bgSml = imgBg[1];

	bgBig.src = theme.bgImage;
	bgSml.src = theme.containerBg;
	container.style.backgroundImage = `url(./${theme.containerBg})`;
	Array.from(document.querySelectorAll(".character-card")).forEach((item, idx) => {
		item.src = `asset/foto/${theme.character}-${4 - (idx + 1)}.png`
	})

    questListContainer.innerHTML = '';
    questListContainer.setAttribute('data-theme', type);

    quests.forEach(quest => {
        const card = document.createElement('div');
        card.className = 'quest-card';
        card.style.borderLeftColor = theme.color;
		const miniDesc = quest.descriptions.slice(0, 70) + "...";

        card.innerHTML = `
            <div class="quest-icon" style="background-color: ${theme.color}">
                <img src="asset/foto/4dm.png">
            </div>
            <div class="quest-content">
                <h3 class="quest-title">${quest.quest_name}</h3>
                <p class="quest-description">${miniDesc}</p>
            </div>
        `;
        card.addEventListener('click', () => openModal(quest, type));
        questListContainer.appendChild(card);
    });
}

function changeQuestType(type) {
    currentQuestType = type;
    questTypeButtons.forEach(btn => {
        if (btn.dataset.type === type) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
    renderQuestList(type);
}

function openModal(quest, type) {
    currentQuest = quest;
    const theme = questThemes[type];
    modalSubtitle.textContent = theme.label;
    modalTitle.textContent = quest.quest_name;
    modalObjective.textContent = quest.objections;
    modalDescription.textContent = quest.descriptions;
    modalContent.style.backgroundColor = theme.color;
    modalOverlay.classList.add('active');
}

function closeModal() {
    modalOverlay.classList.remove('active');
    currentQuest = null;
}

function preloadImage(...src) {
	src.forEach(item => {
		const img = new Image();
		img.src = item;
	})
}

questTypeButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        const type = btn.dataset.type; 
        changeQuestType(type);
    });
});

btnTakeQuest.addEventListener('click', () => {
    closeModal();
    console.log('Quest diambil:', currentQuest);
});
modalOverlay.addEventListener('click', (e) => {
    if (e.target === modalOverlay) {
        closeModal();
    }
});
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modalOverlay.classList.contains('active')) {
        closeModal();
    }
});

renderQuestList(currentQuestType);
