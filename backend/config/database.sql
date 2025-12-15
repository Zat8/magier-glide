create database if not exists magier_glide;

create table events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    title varchar(255) not null,
    banner varchar(255) not null,
	
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

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
	email varchar(100) not null,
	user_title TEXT,
	password varchar(255) not null,
	experience INT NOT NULL DEFAULT 0,
    rank_user INT NOT NULL DEFAULT 1,
	role ENUM('penyihir', 'resepsionis') NOT NULL DEFAULT 'resepsionis',
	ras ENUM('elf', 'manusia', 'dwarf', 'half-demon', 'half-monster', 'spirits', 'herioc-spirits') DEFAULT "manusia",
	elemen ENUM('api','air', 'angin', 'tanah', 'alam');
	umur INT,
	profile_picture varchar(255) DEFAULT "profile-default.png"

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

CREATE TABLE users_sihir (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id varchar(50) NOT NULL,
    sihir_id INT NOT NULL,
    acquired_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uniq_user_sihir (user_id, sihir_id),
    CONSTRAINT fk_us_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_us_sihir
        FOREIGN KEY (sihir_id) REFERENCES sihir_master(sihir_id)
        ON DELETE CASCADE
);

CREATE TABLE users_achievement (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id varchar(50) NOT NULL,
    achievement_id INT NOT NULL,

    completed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uniq_user_achievement (user_id, achievement_id),

    CONSTRAINT fk_ua_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_ua_achievement
        FOREIGN KEY (achievement_id) REFERENCES achievement_master(achievement_id)
        ON DELETE CASCADE
);

CREATE TABLE users_quests (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id varchar(50) NOT NULL,
    quest_id INT NOT NULL,

    status ENUM('not_started', 'in_progress', 'completed') NOT NULL DEFAULT 'in_progress',

    start_time DATETIME NOT NULL,
    finish_time DATETIME NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY uniq_user_quest (user_id, quest_id),

    CONSTRAINT fk_uq_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_uq_quest
        FOREIGN KEY (quest_id) REFERENCES quest_master(quest_id)
        ON DELETE CASCADE
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

("Mana Shield", "Perisai sihir yang menyerap sejumlah damage berdasarkan mana pengguna."),
("Barrier Dome", "Menciptakan kubah pelindung yang kuat di sekitar area kecil."),
("Detection Field", "Mendeteksi kehadiran makhluk hidup atau sihir dalam radius tertentu."),
("Anti-Magic Veil", "Melemahkan sihir di area sekitarnya."),
("Teleport Mark", "Menandai lokasi tertentu sebagai titik teleportasi untuk digunakan nanti."),
("Spatial Flick", "Perpindahan jarak pendek dalam sekejap untuk menghindar dari serangan."),
("Levitation", "Membuat pengguna dapat melayang di udara."),
("Arcane Light", "Menciptakan cahaya yang mengikuti pengguna."),
("Mana Thread", "Menghubungkan objek dengan benang sihir untuk mengendalikannya dari jauh."),

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
170, 24),
(1,"Forest Mana Survey","Jelajahi 3 titik mana hutan","Aliran mana di hutan barat berubah perlahan dan perlu dicatat.",180,22),
(1,"Ancient Flower Bloom","Kumpulkan 4 bunga kuno","Bunga langka mekar hanya dalam waktu singkat setelah hujan.",200,24),
(1,"Cave Light Crystal","Ambil 3 kristal cahaya","Kristal bercahaya ditemukan di gua dangkal.",210,26),
(1,"Riverbank Herb","Kumpulkan 5 herbal sungai","Herbal tumbuh di sepanjang sungai dengan mana stabil.",160,20),
(1,"Fallen Rune Fragment","Cari 2 fragmen rune","Rune tua terkubur di tanah berlumut.",190,23),
(1,"Morning Fog Essence","Kumpulkan 3 esensi kabut","Kabut pagi menyimpan residu mana.",150,21),
(1,"Old Camp Remains","Periksa sisa perkemahan","Jejak petualang lama masih tertinggal.",170,22),
(1,"Wind Mana Spot","Temukan 3 pusaran angin","Angin membawa aliran mana kecil.",200,25),
(1,"Sunlit Stone","Ambil 2 batu bercahaya","Batu ini menyerap cahaya matahari.",140,19),
(1,"Abandoned Well","Selidiki sumur tua","Sumur lama menyimpan aura samar.",180,23),
(1,"Glowing Moss","Kumpulkan 4 lumut bercahaya","Lumut ini digunakan untuk ramuan ringan.",160,20),
(1,"Forest Boundary Check","Jelajahi batas hutan","Pastikan tidak ada distorsi mana.",210,27),
(1,"Crystal Leaf","Kumpulkan 3 daun kristal","Daun mengeras oleh mana alam.",170,24),
(1,"Old Signpost","Temukan tanda arah lama","Petunjuk jalur lama guild.",150,18),
(1,"Hilltop Survey","Survey puncak bukit","Pandangan luas untuk pemetaan.",240,28),
(1,"Faint Mana Echo","Cari 2 gema mana","Resonansi kecil terdeteksi.",200,26),
(1,"Hidden Stream","Temukan aliran tersembunyi","Airnya mengandung mana murni.",220,29),
(1,"Forest Relic","Ambil 1 relik kecil","Relik peninggalan mage lama.",260,30),
(1,"Moonlit Clearing","Amati area cahaya bulan","Mana aktif saat malam.",240,28),
(1,"Stone Circle","Periksa lingkaran batu","Formasi ritual kuno.",300,30);


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
150, 27),
(2,"Escort Herb Collector","Antar pengumpul herbal","Perjalanan melewati jalur aman.",220,30),
(2,"Village Supply Run","Kirim suplai desa","Desa kecil membutuhkan pasokan.",240,32),
(2,"Mage Apprentice Training","Dampingi latihan","Pastikan latihan berjalan aman.",200,28),
(2,"Scroll Transport","Antar gulungan sihir","Dokumen rapuh harus dijaga.",260,34),
(2,"Night Watch Escort","Temani patroli malam","Mencegah gangguan monster.",300,36),
(2,"Scholar Journey","Antar peneliti","Peneliti menghindari ilusi.",280,33),
(2,"Lantern Bearer","Jaga lentera mana","Lentera tidak boleh padam.",260,35),
(2,"Medicine Caravan","Kawal karavan medis","Jalur panjang dan sepi.",360,40),
(2,"Training Equipment Delivery","Kirim alat latihan","Peralatan berat dan mahal.",220,31),
(2,"Library Guard Duty","Jaga perpustakaan","Malam hari rawan gangguan.",300,34),
(2,"Child Escort","Antar anak desa","Perjalanan singkat tapi penting.",180,28),
(2,"Potion Shipment","Kawal ramuan","Botol mudah pecah.",240,32),
(2,"Magic Tool Transfer","Pindahkan alat sihir","Alat tidak boleh aktif.",280,35),
(2,"Forest Crossing Guide","Pandu jalur hutan","Hutan dipenuhi ilusi.",300,38),
(2,"Supply Wagon Escort","Kawal gerobak","Gerobak sering macet.",360,42),
(2,"Apprentice Return","Antar murid pulang","Perjalanan sore hari.",200,29),
(2,"Village Night Lamp","Pasang lentera desa","Pekerjaan malam.",240,31),
(2,"Sacred Item Escort","Kawal benda suci","Aura kuat menarik monster.",420,45),
(2,"Training Scroll Return","Kembalikan gulungan","Waktu terbatas.",260,34),
(2,"Guild Message Delivery","Antar pesan guild","Pesan bersifat rahasia.",180,28);

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
300, 50),

(3,"Mana Pulse Scan","Scan 3 pulsa mana","Pulsa muncul berkala.",260,38),
(3,"Ancient Rune Study","Analisis 2 rune","Rune mulai aktif kembali.",300,42),
(3,"Illusion Residue","Kumpulkan residu ilusi","Jejak sihir tersisa.",280,40),
(3,"Sound Origin Trace","Lacak sumber suara","Suara muncul acak.",320,44),
(3,"Fog Density Test","Uji kepadatan kabut","Kabut mengganggu navigasi.",300,41),
(3,"Old Magic Circle","Teliti lingkaran sihir","Lingkaran tak aktif.",260,39),
(3,"Mana Leak Detection","Temukan kebocoran mana","Mana mengalir liar.",340,45),
(3,"Ruins Echo Mapping","Petakan gema reruntuhan","Suara berulang.",360,46),
(3,"Arcane Signal","Identifikasi sinyal sihir","Sinyal tidak stabil.",300,43),
(3,"Mirror Lake Illusion","Selidiki danau","Pantulan aneh muncul.",320,44),
(3,"Ancient Script Copy","Salin teks kuno","Tulisan rapuh.",280,40),
(3,"Mana Crystal Test","Uji kristal mana","Kristal bereaksi aneh.",300,42),
(3,"Lost Spell Trace","Cari jejak mantra","Mantra lama tertinggal.",360,48),
(3,"Mana Weather Study","Catat cuaca mana","Perubahan mendadak.",300,43),
(3,"Ruins Symbol Decode","Dekode simbol","Butuh ketelitian.",360,47),
(3,"Arcane Footprint","Telusuri jejak sihir","Jejak samar.",280,39),
(3,"Mana Node Survey","Survey node mana","Node mulai aktif.",340,45),
(3,"Illusion Source Confirm","Konfirmasi sumber ilusi","Data belum lengkap.",360,48),
(3,"Ancient Barrier Check","Periksa penghalang","Penghalang melemah.",420,52),
(3,"Forbidden Rune Alert","Investigasi rune terlarang","Rune berbahaya.",480,55);






INSERT INTO pengumuman_guild (title, content) VALUES
("Perekrutan Penyihir Baru",
 "Guild sedang membuka perekrutan anggota baru untuk divisi eksplorasi. 
  Penyihir dengan spesialisasi elemen dasar seperti api, air, dan ilusi sangat dibutuhkan."),

("Gangguan Mana di Hutan Utara",
 "Telah terdeteksi fluktuasi mana tidak stabil di Hutan Utara. 
  Semua anggota diminta berhati-hati dan tidak melakukan perjalanan sendirian."),

("Laporan Tentang Monster Ilusi",
 "Beberapa warga melaporkan penampakan monster berbentuk siluet di sekitar Danau Tieg. 
  Divisi Investigasi diminta menindaklanjuti."),

("Distribusi Ramuan Penyembuh",
 "Stok ramuan penyembuh baru tiba dari penyihir alkimia. 
  Anggota yang sedang menjalankan quest berbahaya dapat mengambil jatah mereka di gudang bawah tanah."),

("Turnamen Sihir Tahunan",
 "Guild akan mengadakan Turnamen Sihir Tahunan bulan depan. 
  Anggota dapat mendaftar mulai hari ini. 
  Pemenang akan mendapatkan gelar kehormatan dan hadiah sihir kuno."),

("Peringatan Ancaman Demon Remnant",
 "Fragmen aura iblis terdeteksi di reruntuhan timur. 
  Dilarang keras mendekati area tersebut tanpa izin langsung dari ketua dewan penyihir."),

("Permintaan Bantuan Desa Fernheim",
 "Desa Fernheim meminta bantuan untuk memeriksa anomali cuaca ekstrem. 
  Penyihir spesialis analisis sihir alam diprioritaskan."),

("Pengiriman Gulungan Sihir Kuno",
 "Pustakawan guild membutuhkan bantuan untuk memindahkan gulungan sihir kuno ke ruang penyimpanan baru. 
  Tugas ini terbuka untuk anggota peringkat Apprentice ke atas."),

("Krisis Embun Kristal",
 "Embun kristal yang dibutuhkan untuk ritual pemurnian tahunan mengalami penurunan drastis. 
  Semua anggota eksplorasi diminta membantu pengumpulan untuk minggu ini."),

("Larangan Eksperimen Sihir Berbahaya",
 "Beberapa anggota melakukan percobaan sihir tingkat tinggi tanpa pengawasan. 
  Hal ini sangat membahayakan struktur ruang guild. 
  Mulai sekarang semua eksperimen harus mendapat persetujuan dari instruktur sihir senior.");

INSERT INTO achievement_master (achievement_name, descriptions, exp_reward) VALUES
("Rookie Mage", "Memulai perjalanan sebagai penyihir pemula.", 5),
("First Mana Control", "Berhasil mengendalikan mana untuk pertama kali.", 8),
("Beginner Spellcaster", "Mempelajari sihir dasar pertama.", 10);

INSERT INTO sihir_master (sihir_name, descriptions) VALUES
("Spark Flicker", "Percikan api kecil sebagai latihan dasar manipulasi mana."),
("Minor Shield", "Perisai tipis yang hanya mampu menahan serangan kecil."),
("Mana Breeze", "Hembusan angin lemah untuk latihan kontrol sihir.");

INSERT INTO users  (id, username, email, user_title, password, experience, rank_user, role, ras, elemen, umur) VALUES
('user_frieren_9xA1', 'Frieren', 'frieren@guild.magic', 'Ancient Mage of the Hero Party', '$2y$dummyhash', 9800, 9, 'penyihir', 'elf', 'alam', 1000),
('user_fern_8Bq2', 'Fern', 'fern@guild.magic', 'Silent Prodigy Mage', '$2y$dummyhash', 8200, 8, 'penyihir', 'manusia', 'air', 19),
('user_stark_7Lp3', 'Stark', 'stark@guild.magic', 'Reluctant Warrior', '$2y$dummyhash', 7100, 7, 'penyihir', 'manusia', 'tanah', 20),
('user_himmel_6Zd4', 'Himmel', 'himmel@guild.magic', 'Hero of the Past', '$2y$dummyhash', 6500, 6, 'penyihir', 'herioc-spirits', 'angin', 26),
('user_heiter_5Wc5', 'Heiter', 'heiter@guild.magic', 'Priest of Miracles', '$2y$dummyhash', 5200, 5, 'penyihir', 'manusia', 'alam', 28),
('user_eisen_4Km6', 'Eisen', 'eisen@guild.magic', 'Dwarven Shield', '$2y$dummyhash', 4300, 4, 'penyihir', 'dwarf', 'tanah', 90),
('user_lawine_3Pp7', 'Lawine', 'lawine@guild.magic', 'Ice Mage Apprentice', '$2y$dummyhash', 3000, 3, 'penyihir', 'manusia', 'air', 18),
('user_kanne_2Ux8', 'Kanne', 'kanne@guild.magic', 'Water Manipulator', '$2y$dummyhash', 1800, 2, 'penyihir', 'manusia', 'air', 18),
('user_apprentice_1Vr9', 'Guild Apprentice', 'apprentice@guild.magic', 'New Mage', '$2y$dummyhash', 400, 1, 'penyihir', 'manusia', 'angin', 16);

insert into users (id, username, email, password, role, user_title) VALUES
("user_693d110d6df1f", "admin", "admin@resmi.com", "$2y$10$CteLCMCJKomjkiE5QrQ2Ru/1xtaFeBtYruDpCixZGXtoONNB4mq/W", "resepsionis", "12345");
