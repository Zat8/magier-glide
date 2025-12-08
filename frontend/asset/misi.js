// ========================================
// DATA QUEST
// ========================================

// Navbar Scroll Effect
window.addEventListener('scroll', function() {
    const navbar = document.getElementById('navbar');
    
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

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


// Object berisi semua data quest, dikelompokkan berdasarkan tipe
const questData = {
    // Quest tipe Exploration
    exploration: [
        {
            id: 1,
            title: "Herb For Healing",
            description: "Mengumpulkan 5 tanaman Lichtblume yang hanya tumbuh saat pagi berkabut.",
            objective: "Kumpulkan 5 Lichtblume.",
            details: "Saat fajar menyibak kabut tipis lembah, Lichtblume yang langka mulai mekar dan memancarkan cahaya lembut. Guild membutuhkan tanaman ini untuk membuat ramuan pemulih yang hanya bisa diracik dengan kelopak segarnya."
        },
        {
            id: 2,
            title: "Ancient Mana Fragment",
            description: "Menemukan pecahan kristal mana yang tersebar di reruntuhan kuno.",
            objective: "Temukan 3 Mana Fragment.",
            details: "Reruntuhan kuno menyimpan pecahan kristal mana yang masih memancarkan energi magis. Kristal ini dibutuhkan untuk penelitian guild tentang sihir kuno."
        },
        {
            id: 3,
            title: "Lost Merchant Map",
            description: "Menjelajahi hutan utara untuk menemukan peta yang hilang milik pedagang.",
            objective: "Temukan peta di hutan utara.",
            details: "Seorang pedagang kehilangan peta berharga yang berisi rute perdagangan rahasia. Peta tersebut diduga terjatuh di hutan utara saat serangan monster."
        },
        {
            id: 4,
            title: "Crystal Dew Hunt",
            description: "Mengumpulkan embun kristal yang muncul di rerumputan saat fajar.",
            objective: "Kumpulkan 10 Crystal Dew.",
            details: "Embun kristal adalah bahan langka yang hanya muncul saat fajar di padang rumput tinggi. Material ini dibutuhkan untuk membuat ramuan pemulih mana."
        },
        {
            id: 5,
            title: "Whispering Stone",
            description: "Mencari batu kecil yang mengeluarkan suara aneh di gua timur.",
            objective: "Temukan Whispering Stone.",
            details: "Penduduk desa melaporkan mendengar bisikan aneh dari gua timur. Diduga ada batu magis yang beresonansi dengan energi sekitar."
        }
    ],
    
    // Quest tipe Escort
    escort: [
        {
            id: 6,
            title: "Merchant Escort",
            description: "Mengawal pedagang menuju kota berikutnya melalui jalur berbahaya.",
            objective: "Amankan perjalanan pedagang.",
            details: "Seorang pedagang membutuhkan pengawalan untuk membawa barang berharga melewati wilayah yang sering diserang bandit dan monster liar."
        },
        {
            id: 7,
            title: "Scholar Protection",
            description: "Melindungi sarjana yang sedang melakukan penelitian di reruntuhan berbahaya.",
            objective: "Lindungi sarjana selama penelitian.",
            details: "Sarjana guild membutuhkan perlindungan saat meneliti reruntuhan kuno yang dipenuhi jebakan dan monster penjaga."
        },
        {
            id: 8,
            title: "Noble's Journey",
            description: "Mengawal bangsawan muda dalam perjalanan diplomatik ke kerajaan tetangga.",
            objective: "Pastikan bangsawan tiba dengan selamat.",
            details: "Seorang bangsawan muda harus melakukan perjalanan diplomatik penting. Keamanan perjalanan sangat krusial untuk hubungan antar kerajaan."
        }
    ],
    
    // Quest tipe Investigation
    investigation: [
        {
            id: 9,
            title: "Missing Villagers",
            description: "Menyelidiki hilangnya beberapa penduduk desa di wilayah perbatasan.",
            objective: "Temukan petunjuk hilangnya penduduk.",
            details: "Beberapa penduduk desa hilang secara misterius dalam seminggu terakhir. Guild meminta investigasi menyeluruh untuk menemukan apa yang sebenarnya terjadi."
        },
        {
            id: 10,
            title: "Cursed Artifact",
            description: "Meneliti artefak aneh yang ditemukan petani dan menyebabkan kejadian ganjil.",
            objective: "Identifikasi dan amankan artefak.",
            details: "Seorang petani menemukan artefak kuno yang menyebabkan tanaman di sekitarnya layu dan hewan ternak bertingkah aneh. Perlu investigasi segera."
        },
        {
            id: 11,
            title: "Haunted Manor",
            description: "Menyelidiki mansion tua yang konon dihantui dan menakuti warga sekitar.",
            objective: "Ungkap misteri mansion tua.",
            details: "Mansion tua di pinggir kota dilaporkan mengeluarkan suara aneh dan cahaya mencurigakan di malam hari. Warga ketakutan dan meminta guild menyelidiki."
        },
        {
            id: 12,
            title: "Magical Anomaly",
            description: "Meneliti gangguan aliran mana yang terjadi di area hutan selatan.",
            objective: "Temukan sumber gangguan mana.",
            details: "Penyihir guild melaporkan gangguan signifikan pada aliran mana di hutan selatan. Anomali ini dapat membahayakan ekosistem magis di area tersebut."
        }
    ]
};

// ========================================
// TEMA WARNA UNTUK SETIAP TIPE QUEST
// ========================================

// Object berisi konfigurasi warna untuk setiap tipe quest
const questThemes = {
    exploration: {
        color: '#87A1D5',        // Warna utama (biru)
        label: 'Exploration Quest' // Label untuk ditampilkan
    },
    escort: {
        color: '#87D593',        // Warna utama (hijau)
        label: 'Escort Quest'
    },
    investigation: {
        color: '#9B87D5',        // Warna utama (ungu)
        label: 'Investigation Quest'
    }
};

// ========================================
// STATE MANAGEMENT
// ========================================

// Variable untuk menyimpan state aplikasi
let currentQuestType = 'exploration'; // Tipe quest yang sedang aktif
let currentQuest = null;               // Quest yang sedang dipilih untuk modal

// ========================================
// DOM ELEMENTS
// ========================================

// Mengambil referensi ke elemen-elemen DOM yang akan dimanipulasi
const questListContainer = document.getElementById('questList');
const modalOverlay = document.getElementById('modalOverlay');
const modalContent = document.getElementById('modalContent');
const modalSubtitle = document.getElementById('modalSubtitle');
const modalTitle = document.getElementById('modalTitle');
const modalObjective = document.getElementById('modalObjective');
const modalDescription = document.getElementById('modalDescription');
const btnTakeQuest = document.getElementById('btnTakeQuest');

// Mengambil semua button quest type
const questTypeButtons = document.querySelectorAll('.quest-type-btn');

// ========================================
// FUNCTION: RENDER QUEST LIST
// ========================================

/**
 * Fungsi untuk me-render (menampilkan) daftar quest sesuai tipe yang dipilih
 * @param {string} type - Tipe quest: 'exploration', 'escort', atau 'investigation'
 */
function renderQuestList(type) {
    // Ambil data quest sesuai tipe
    const quests = questData[type];
    
    // Ambil konfigurasi tema warna
    const theme = questThemes[type];
    
    // Kosongkan container terlebih dahulu
    questListContainer.innerHTML = '';
    
    // Update background color container sesuai tema
    questListContainer.setAttribute('data-theme', type);
    
    // Loop semua quest dan buat card untuk setiap quest
    quests.forEach(quest => {
        // Buat elemen card
        const card = document.createElement('div');
        card.className = 'quest-card';
        
        // Set border kiri sesuai warna tema
        card.style.borderLeftColor = theme.color;
        
        // Isi HTML card dengan data quest
        card.innerHTML = `
            <div class="quest-icon" style="background-color: ${theme.color}">
                <img src="asset/foto/4dm.png">
            </div>
            <div class="quest-content">
                <h3 class="quest-title">${quest.title}</h3>
                <p class="quest-description">${quest.description}</p>
            </div>
        `;
        
        // Tambahkan event listener untuk membuka modal saat card diklik
        card.addEventListener('click', () => openModal(quest, type));
        
        // Masukkan card ke container
        questListContainer.appendChild(card);
    });
}

// ========================================
// FUNCTION: CHANGE QUEST TYPE
// ========================================

/**
 * Fungsi untuk mengganti tipe quest yang aktif
 * @param {string} type - Tipe quest yang akan diaktifkan
 */
function changeQuestType(type) {
    // Update state
    currentQuestType = type;
    
    // Update class 'active' pada button quest type
    questTypeButtons.forEach(btn => {
        // Jika button sesuai dengan tipe yang dipilih, tambahkan class 'active'
        if (btn.dataset.type === type) {
            btn.classList.add('active');
        } else {
            // Jika tidak, hapus class 'active'
            btn.classList.remove('active');
        }
    });
    
    // Render ulang quest list dengan tipe yang baru
    renderQuestList(type);
}

// ========================================
// FUNCTION: OPEN MODAL
// ========================================

/**
 * Fungsi untuk membuka modal detail quest
 * @param {Object} quest - Data quest yang akan ditampilkan
 * @param {string} type - Tipe quest untuk styling
 */
function openModal(quest, type) {
    // Simpan quest yang dipilih ke state
    currentQuest = quest;
    
    // Ambil tema warna
    const theme = questThemes[type];
    
    // Update isi modal dengan data quest
    modalSubtitle.textContent = theme.label;
    modalTitle.textContent = quest.title;
    modalObjective.textContent = quest.objective;
    modalDescription.textContent = quest.details;
    
    // Update warna background modal sesuai tema
    modalContent.style.backgroundColor = theme.color;
    
    // Tampilkan modal dengan menambahkan class 'active'
    modalOverlay.classList.add('active');
}

// ========================================
// FUNCTION: CLOSE MODAL
// ========================================

/**
 * Fungsi untuk menutup modal
 */
function closeModal() {
    // Sembunyikan modal dengan menghapus class 'active'
    modalOverlay.classList.remove('active');
    
    // Reset state quest yang dipilih
    currentQuest = null;
}

// ========================================
// EVENT LISTENERS
// ========================================

/**
 * Setup event listeners untuk quest type buttons
 * Saat button diklik, ganti tipe quest yang aktif
 */
questTypeButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        const type = btn.dataset.type; // Ambil tipe dari data attribute
        changeQuestType(type);
    });
});

/**
 * Event listener untuk button "Ambil Quest"
 * Saat diklik, tutup modal
 */
btnTakeQuest.addEventListener('click', () => {
    closeModal();
    
    // TODO: Di sini bisa ditambahkan logic untuk menyimpan quest yang diambil
    // Misalnya: kirim data ke server, simpan ke local storage, dll
    console.log('Quest diambil:', currentQuest);
});

/**
 * Event listener untuk overlay modal
 * Saat overlay (area gelap) diklik, tutup modal
 */
modalOverlay.addEventListener('click', (e) => {
    // Pastikan yang diklik adalah overlay, bukan modal content
    if (e.target === modalOverlay) {
        closeModal();
    }
});

/**
 * Event listener untuk tombol ESC
 * Menutup modal saat tombol ESC ditekan
 */
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modalOverlay.classList.contains('active')) {
        closeModal();
    }
});

// ========================================
// INITIALIZATION
// ========================================

/**
 * Inisialisasi aplikasi saat halaman pertama kali dimuat
 * Render quest list dengan tipe default (exploration)
 */
document.addEventListener('DOMContentLoaded', () => {
    // Render quest list pertama kali dengan tipe exploration
    renderQuestList(currentQuestType);
    
    console.log('Frieren Quest Board initialized!');
});