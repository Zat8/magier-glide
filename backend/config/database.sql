create database if not exists magier_glide;

create table rank_master (
	rank_level INT PRIMARY KEY,
	exp_required INT NOT NULL
);

create table sihir_master (
	sihir_id int AUTO_INCREMENT not null primary key,
	sihir_name VARCHAR(100) NOT NULL,
    descriptions TEXT
);

create table quest_category (
	category_id int AUTO_INCREMENT not null primary key,
	category_name varchar(100) not null
);

create table achievement_master (
    achievement_id INT AUTO_INCREMENT not null PRIMARY KEY,
    achievement_name VARCHAR(100) NOT NULL,
    descriptions TEXT,
    exp_reward INT NOT NULL
);

CREATE TABLE pengumuman_guild (
    id INT AUTO_INCREMENT not null PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

create table users (
	id varchar(50) not null primary key,
	username varchar(100) not null,
	password varchar(255) not null,
	experience INT NOT NULL DEFAULT 0,
    rank_user INT NOT NULL DEFAULT 1,
	role ENUM('penyihir', 'resepsionis') NOT NULL DEFAULT 'resepsionis',
	ras ENUM('elf', 'manusia', 'dwarf', 'half-demon', 'half-monster', 'spirits', 'herioc-spirits') DEFAULT "manusia";
	elemen ENUM('api','air', 'angin', 'tanah', 'alam');
	umur INT,

	created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
	updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- quest table alamak --
create table quest_master (
	quest_id INT AUTO_INCREMENT not null PRIMARY KEY,
	category_id int not null,

    quest_name VARCHAR(100) NOT NULL,
	objections TEXT,
    descriptions TEXT,
    duration_seconds INT NOT NULL,
    exp_reward INT NOT NULL,

    FOREIGN KEY (category_id) REFERENCES quest_category(category_id) ON DELETE CASCADE
);

-- relasi tabel begitulah --

create table users_sihir (
	id INT AUTO_INCREMENT not null PRIMARY KEY,
    user_id varchar(100) NOT NULL,
    sihir_id INT NOT NULL,
    acquired_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (sihir_id) REFERENCES sihir_master(sihir_id) ON DELETE CASCADE
);

CREATE TABLE users_achievement (
    id INT AUTO_INCREMENT not null PRIMARY KEY,
    user_id varchar(100) NOT NULL,
    achievement_id INT NOT NULL,
    completed_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (achievement_id) REFERENCES achievement_master(achievement_id) ON DELETE CASCADE
);

create table users_quests (
	id INT AUTO_INCREMENT PRIMARY KEY,
    user_id varchar(100) NOT NULL,
    quest_id INT NOT NULL,
    
    status ENUM('not_started', 'in_progress', 'completed') NOT NULL DEFAULT 'not_started',

    start_time DATETIME NULL,
    finish_time DATETIME NULL,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (quest_id) REFERENCES quest_master(quest_id) ON DELETE CASCADE
);


-- kita insert kan --
INSERT INTO rank_master (rank_level, exp_required) VALUES
(1, 100),
(2, 250),
(3, 500),
(4, 1000),
(5, 2000),
(6, 3500),
(7, 5000),
(8, 8000),
(9, 12000);

INSERT INTO quest_category (category_name) VALUES
("Exploration"),
("Escort"),
("Invetigation");

INSERT INTO achievement_master (achievement_name, descriptions, exp_reward) VALUES
("Long Journey", "Berpetualangan 1000 Tahun", 500),
("Disciple of Flamme", "Murid dari penyihir terhebat sepanjang sejarah", 300),
("First Class Mage", "Penyihir kelas tertinggi", 800),
("Demon King Slayer", "Pembunuh Raja Iblis", 1000),
("Heroes Party", "Penyihir Dari Party Pahlawan", 700),
("Novice Spellcaster", "Menguasai mantra dasar pertama", 20),
("Intermediate Spellcaster", "Menguasai 10 mantra level menengah", 60),
("Advanced Sorcerer", "Menguasai 5 mantra tingkat tinggi", 120),
("Master of Arcane", "Menguasai seluruh elemen sihir", 300),
("Mana Overflow", "Mencapai kapasitas mana maksimum", 50),
("Unbreakable Will", "Mengalahkan musuh dengan HP < 10%", 40),
("Silent Hunter", "Menyelesaikan quest tanpa terdeteksi", 80),
("Duelist", "Menang 10 pertarungan individu", 70),
("Champion of Realms", "Menang 100 pertarungan", 300),
("Elemental Lord", "Menguasai elemen api, air, angin, tanah", 150),
("Daily Adventurer", "Menyelesaikan 1 quest harian", 15),
("Weekly Hero", "Menyelesaikan 7 quest mingguan", 80),
("Quest Machine", "Menyelesaikan 100 quest", 300),
("Explorer", "Mengunjungi 10 daerah baru", 40),
("World Wanderer", "Mengunjungi 50 daerah baru", 200),
("Ancient Ruins Researcher", "Menjelajahi 5 reruntuhan kuno", 120),
("Treasure Seeker", "Menemukan harta langka", 150),
("Guild Contributor", "Berpartisipasi dalam 10 event guild", 60),
("Guild Protector", "Menyelesaikan misi pertahanan guild", 90),
("Guild Legend", "Dikenang oleh seluruh anggota guild", 300),
("Helper of Many", "Membantu 20 anggota guild lainnya", 70),
("Immortal Memory", "Mengalami ingatan yang kembali setelah ratusan tahun", 500),
("Sage's Blessing", "Mendapatkan pengakuan dari penyihir kuno", 400),
("Ancient Bloodline", "Menemukan identitas rahasia dari masa lalu", 500),
("Fate Breaker", "Mengubah takdir yang telah ditetapkan", 700),
("World Savior", "Menyelamatkan satu wilayah dari kehancuran total", 900),
("Arcane Collector", "Mengumpulkan 20 artefak sihir kuno", 200),
("Time Wanderer", "Bertahan hidup lebih dari 500 tahun", 300),
("Forgotten Hero", "Dikenang meskipun telah menghilang ratusan tahun", 700);

INSERT INTO sihir_master (sihir_name, descriptions) VALUES
("Fire Ball", "Menembak bola api ke arah yang ditentukan, semakin besar daya sihir yang digunakan semakin besar pula ukuran dan damage nya"),
("Fire Wall", "Membuat dinding api yang menutupu pengguna"),
("Flagrate", "Menulis di udara menggunakan api"),
("Inferno", "Membuat pilar api yang sangat besar dan sangat panas"),
("Conflagrate", "Mengutuk target dengan api yang tak bisa padam hingga target musnah");
-- Sihir Elemen
("Ice Lance", "Membentuk tombak es yang ditembakkan dengan kecepatan tinggi untuk menusuk musuh."),
("Frost Bind", "Membekukan kaki target dan membuatnya tidak dapat bergerak sementara."),
("Blizzard Veil", "Menciptakan badai salju kecil untuk menyamarkan pergerakan pengguna."),
("Aqua Sphere", "Membuat bola air pelindung yang menyerap damage sihir."),
("Thunder Strike", "Melepaskan serangan kilat satu titik dengan akurasi tinggi."),
("Storm Edge", "Menyelimuti senjata dengan angin tajam untuk meningkatkan daya serang."),
("Gale Dash", "Menggunakan angin untuk mempercepat gerakan pengguna secara singkat."),
("Stone Skin", "Menguatkan tubuh pengguna dengan lapisan batu tipis."),
("Earth Shackles", "Mengikat target menggunakan akar dan tanah di sekitarnya."),
("Terra Spike", "Menghentakkan tanah dan memunculkan pilar batu di bawah target."),

-- Sihir Pelindung & Utility
("Mana Shield", "Perisai sihir yang menyerap sejumlah damage berdasarkan mana pengguna."),
("Barrier Dome", "Menciptakan kubah pelindung yang kuat di sekitar area kecil."),
("Detection Field", "Mendeteksi kehadiran makhluk hidup atau sihir dalam radius tertentu."),
("Anti-Magic Veil", "Melemahkan sihir di area sekitarnya."),
("Teleport Mark", "Menandai lokasi tertentu sebagai titik teleportasi untuk digunakan nanti."),
("Spatial Flick", "Perpindahan jarak pendek dalam sekejap untuk menghindar dari serangan."),
("Levitation", "Membuat pengguna dapat melayang di udara."),
("Arcane Light", "Menciptakan cahaya yang mengikuti pengguna."),
("Mana Thread", "Menghubungkan objek dengan benang sihir untuk mengendalikannya dari jauh."),

-- Sihir Frieren-style Unik & Absurd (sesuai vibe manga)
("Flower Bloom", "Membuat bunga bermekaran di sekitar pengguna, biasanya digunakan hanya untuk estetika."),
("Clean Sweep", "Membersihkan area dari debu dan kotoran seketika."),
("Warm Tea", "Memanaskan sebuah cangkir teh secara sempurna dengan sihir."),
("Tiny Bird", "Membuat burung kecil dari sihir untuk mengirim pesan."),
("Small Explosion", "Ledakan kecil yang nyaris tidak berbahaya, biasanya dipakai untuk latihan."),
("Sparkle", "Menghasilkan kilauan indah di udara tanpa efek ofensif."),
("Animate Doll", "Menghidupkan boneka kecil untuk membantu tugas ringan."),
("Pocket Storage", "Membuat ruang kecil untuk menyimpan barang secara sementara."),
("Sound Muffling", "Meredam suara di sekitar pengguna."),
("Insect Repel", "Menghasilkan bau sihir tak kasat mata yang mengusir serangga."),

-- Sihir Kuno / Sihir Tingkat Tinggi
("Ancient Seal", "Menyegel monster atau artefak berbahaya untuk waktu yang lama."),
("Astral Projection", "Memisahkan kesadaran dari tubuh untuk menjelajahi area tertentu."),
("Chrono Step", "Mengganggu alur waktu singkat untuk bergerak lebih cepat dari penglihatan biasa."),
("Void Chain", "Rantai sihir yang mengikat jiwa dan tubuh target."),
("Soul Lantern", "Menangkap dan membaca sisa-sisa energi spiritual di lokasi tertentu."),
("Genesis Flame", "Api kuno yang dikatakan berasal dari era para Arc Mage."),
("Heavenly Comet", "Menjatuhkan meteor kecil dari langit, membutuhkan sihir sangat tinggi."),
("Arcane Burst", "Ledakan energi murni dari mana yang dimaterialisasi."),
("Phantom Mirage", "Menciptakan ilusi tiga dimensi yang sangat realistis."),
("Eternal Rune", "Mengukir rune sihir yang aktif secara permanen sampai dihancurkan.");

INSERT INTO quest_master (category_id, quest_name, objections, descriptions, duration_seconds, exp_reward) VALUES
(1, "Herb For Healing",
 "Kumpulkan 5 Lichtblume.",
 "Lichtblume adalah tanaman penyembuh langka yang hanya muncul saat pagi berkabut. Guild membutuhkan tanaman ini untuk stok ramuan.",
 180, 20),

(1, "Ancient Mana Fragment",
 "Temukan 3 pecahan kristal mana.",
 "Reruntuhan kuno di pegunungan menyimpan pecahan kristal mana yang memancarkan energi lembut. Guild ingin menelitinya.",
 210, 30),

(1, "Lost Merchant Map",
 "Temukan peta pedagang yang hilang.",
 "Seorang pedagang kehilangan peta penting saat melewati hutan utara. Peta itu mungkin tersangkut di pepohonan.",
 150, 25),

(1, "Crystal Dew Hunt",
 "Ambil 4 embun kristal.",
 "Embun kristal jarang muncul dan hanya dapat dikumpulkan saat matahari terbit. Embun ini digunakan untuk ritual sihir sederhana.",
 200, 28),

(1, "Whispering Stone",
 "Temukan 2 Whispering Stone.",
 "Batu resonansi kecil yang mengeluarkan getaran mana halus tersebar di lembah. Mereka mudah ditemukan jika mendengarkan suara bergema.",
 160, 22),

(1, "Dawn Mushroom Forage",
 "Kumpulkan 3 jamur bercahaya.",
 "Jamur bercahaya tumbuh di area hutan gelap pada dini hari. Cahaya birunya dapat digunakan sebagai bahan ramuan.",
 170, 24);

INSERT INTO quest_master (category_id, quest_name, objections, descriptions, duration_seconds, exp_reward) VALUES
(2, "Escort Young Mage",
 "Antar apprentice melalui 4 checkpoint.",
 "Seorang penyihir muda ingin menuju Menara Pelatihan, tetapi jalannya cukup jauh dan rawan ilusi.",
 240, 35),

(2, "Medicine Delivery",
 "Kirim ramuan ke desa tetangga.",
 "Desa tetangga mengalami demam ringan dan membutuhkan ramuan penyembuh. Guild menugaskanmu sebagai pengantar.",
 200, 30),

(2, "Caravan Assistance",
 "Bantu karavan melewati 3 area ilusi.",
 "Karavan kecil memerlukan bantuan karena rute hutan dipenuhi ilusi samar.",
 260, 40),

(2, "Library Archive Transfer",
 "Pindahkan 5 gulungan sihir kuno.",
 "Perpustakaan kota meminta bantuan memindahkan gulungan sihir kuno ke ruang penyimpanan.",
 180, 25),

(2, "Magic Lantern Guide",
 "Bawa lentera sihir melewati 5 checkpoint malam.",
 "Lentera sihir harus dibawa ke desa terpencil. Cahaya lentera melindungi dari monster malam.",
 230, 33),

(2, "Healing Herb Delivery",
 "Kirimkan 3 kantong herbal.",
 "Penyembuh lokal membutuhkan bahan mentah dengan segera. Kamu harus mengantar tumbuhan tersebut sebelum layu.",
 150, 27);

INSERT INTO quest_master (category_id, quest_name, objections, descriptions, duration_seconds, exp_reward) VALUES
(3, "Mana Distortion Check",
 "Selesaikan pemeriksaan 3 titik anomali.",
 "Aliran mana di pinggir hutan terasa tidak stabil. Guild ingin memastikan tidak ada sihir liar yang membahayakan.",
 230, 40),

(3, "Ruins Resonance",
 "Analisis 3 sumber suara.",
 "Suara aneh dari reruntuhan kuno terdeteksi oleh mage senior. Kamu diminta menyelidikinya.",
 250, 42),

(3, "Illusion Trace",
 "Temukan 3 jejak ilusi.",
 "Banyak warga melihat bayangan misterius di tepi danau malam hari. Kamu harus melacak sumber sihir ilusi tersebut.",
 200, 38),

(3, "Arcane Sound Scan",
 "Scan 4 titik suara magis.",
 "Guild mendeteksi suara magis yang berulang tiap tengah malam. Sumbernya perlu diidentifikasi.",
 260, 45),

(3, "Mana Fog Analysis",
 "Kumpulkan 3 sampel kabut mana.",
 "Kabut mana yang pekat muncul di lembah timur dan mengganggu visibilitas. Guild mengirimmu untuk menganalisisnya.",
 210, 43),

(3, "Ruins Scripture Decode",
 "Susun 4 potongan simbol menjadi urutan benar.",
 "Tulisan kuno ditemukan pada dinding reruntuhan. Memahaminya bisa membuka rahasia sihir lama.",
 300, 50);

INSERT INTO pengumuman_guild (title, content) VALUES
-- 1
("Perekrutan Penyihir Baru",
 "Guild sedang membuka perekrutan anggota baru untuk divisi eksplorasi. 
  Penyihir dengan spesialisasi elemen dasar seperti api, air, dan ilusi sangat dibutuhkan."),

-- 2
("Gangguan Mana di Hutan Utara",
 "Telah terdeteksi fluktuasi mana tidak stabil di Hutan Utara. 
  Semua anggota diminta berhati-hati dan tidak melakukan perjalanan sendirian."),

-- 3
("Laporan Tentang Monster Ilusi",
 "Beberapa warga melaporkan penampakan monster berbentuk siluet di sekitar Danau Tieg. 
  Divisi Investigasi diminta menindaklanjuti."),

-- 4
("Distribusi Ramuan Penyembuh",
 "Stok ramuan penyembuh baru tiba dari penyihir alkimia. 
  Anggota yang sedang menjalankan quest berbahaya dapat mengambil jatah mereka di gudang bawah tanah."),

-- 5
("Turnamen Sihir Tahunan",
 "Guild akan mengadakan Turnamen Sihir Tahunan bulan depan. 
  Anggota dapat mendaftar mulai hari ini. 
  Pemenang akan mendapatkan gelar kehormatan dan hadiah sihir kuno."),

-- 6
("Peringatan Ancaman Demon Remnant",
 "Fragmen aura iblis terdeteksi di reruntuhan timur. 
  Dilarang keras mendekati area tersebut tanpa izin langsung dari ketua dewan penyihir."),

-- 7
("Permintaan Bantuan Desa Fernheim",
 "Desa Fernheim meminta bantuan untuk memeriksa anomali cuaca ekstrem. 
  Penyihir spesialis analisis sihir alam diprioritaskan."),

-- 8
("Pengiriman Gulungan Sihir Kuno",
 "Pustakawan guild membutuhkan bantuan untuk memindahkan gulungan sihir kuno ke ruang penyimpanan baru. 
  Tugas ini terbuka untuk anggota peringkat Apprentice ke atas."),

-- 9
("Krisis Embun Kristal",
 "Embun kristal yang dibutuhkan untuk ritual pemurnian tahunan mengalami penurunan drastis. 
  Semua anggota eksplorasi diminta membantu pengumpulan untuk minggu ini."),

-- 10
("Larangan Eksperimen Sihir Berbahaya",
 "Beberapa anggota melakukan percobaan sihir tingkat tinggi tanpa pengawasan. 
  Hal ini sangat membahayakan struktur ruang guild. 
  Mulai sekarang semua eksperimen harus mendapat persetujuan dari instruktur sihir senior.");

