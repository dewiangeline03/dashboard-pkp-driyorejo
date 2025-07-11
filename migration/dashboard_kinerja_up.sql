CREATE TABLE IF NOT EXISTS public.akun (
    id uuid PRIMARY KEY NOT NULL,
    nama character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    akses integer NOT NULL,
    verified boolean NOT NULL,
    created_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS penilaian_kinerja.penilaian_kinerja (
    entry_id uuid NOT NULL PRIMARY KEY,
    periode date NOT NULL,
    user_entry VARCHAR(255) NOT NULL,
    created_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS penilaian_kinerja.rasio_pnbp_operasional (
    entry_id uuid NOT NULL PRIMARY KEY,
    pendapatan_pnbp BIGINT NOT NULL,
    beban_operasional BIGINT NOT NULL,
    beban_penyusutan BIGINT NOT NULL,
    target_rasio_pnbp_operasional INTEGER NOT NULL,
    FOREIGN KEY (entry_id) REFERENCES penilaian_kinerja.entry (entry_id)
);
CREATE TABLE IF NOT EXISTS penilaian_kinerja.modernisasi_pengelolaan_blu (
    entry_id uuid NOT NULL PRIMARY KEY,
    integrasi_data INTEGER NOT NULL,
    analitika_data INTEGER NOT NULL,
    sistem_informasi_manajemen INTEGER NOT NULL,
    website INTEGER NOT NULL,
    extramiles INTEGER NOT NULL,
    target_modernisasi_pengelolaan_blu INTEGER NOT NULL,
    FOREIGN KEY (entry_id) REFERENCES penilaian_kinerja.entry (entry_id)
);
CREATE TABLE IF NOT EXISTS penilaian_kinerja.maturity_level (
    entry_id uuid NOT NULL PRIMARY KEY,
    keuangan INTEGER NOT NULL,
    pelayanan INTEGER NOT NULL,
    kapabilitas_internal INTEGER NOT NULL,
    tata_kelola_kepemimpinan INTEGER NOT NULL,
    inovasi INTEGER NOT NULL,
    lingkungan INTEGER NOT NULL,
    target_maturity_level NUMERIC(4,2) NOT NULL,
    FOREIGN KEY (entry_id) REFERENCES penilaian_kinerja.entry (entry_id)
);
CREATE TABLE IF NOT EXISTS penilaian_kinerja.pendapatan_blu (
    entry_id uuid NOT NULL PRIMARY KEY,
    pendapatan_blu BIGINT NOT NULL,
    target_pendapatan_blu BIGINT NOT NULL,
    FOREIGN KEY (entry_id) REFERENCES penilaian_kinerja.entry (entry_id)
);
CREATE TABLE IF NOT EXISTS penilaian_kinerja.indeks_proyeksi_akurasi_pendapatan_blu (
    entry_id uuid NOT NULL PRIMARY KEY,
    ketepatan_waktu_penyampaian NUMERIC(4,2) NOT NULL,
    akurasi_proyeksi_pendapatan NUMERIC(4,2) NOT NULL,
    target_indeks_proyeksi_akurasi_pendapatan_blu NUMERIC(4,2) NOT NULL,
    FOREIGN KEY (entry_id) REFERENCES penilaian_kinerja.entry (entry_id)
);
CREATE TABLE IF NOT EXISTS penilaian_kinerja.kunjungan_rawat_darurat (
    entry_id uuid NOT NULL PRIMARY KEY,
    jumlah_kunjungan INTEGER NOT NULL,
    jumlah_hari_kerja INTEGER NOT NULL,
    target_kunjungan INTEGER NOT NULL,
    FOREIGN KEY (entry_id) REFERENCES penilaian_kinerja.entry (entry_id)
);
CREATE TABLE IF NOT EXISTS penilaian_kinerja.kunjungan_rawat_jalan (
    entry_id uuid NOT NULL PRIMARY KEY,
    jumlah_kunjungan INTEGER NOT NULL,
    jumlah_hari_kerja INTEGER NOT NULL,
    target_kunjungan INTEGER NOT NULL,
    FOREIGN KEY (entry_id) REFERENCES penilaian_kinerja.entry (entry_id)
);
CREATE TABLE IF NOT EXISTS penilaian_kinerja.kunjungan_rawat_inap (
    entry_id uuid NOT NULL PRIMARY KEY,
    jumlah_kunjungan INTEGER NOT NULL,
    jumlah_hari_kerja INTEGER NOT NULL,
    target_kunjungan INTEGER NOT NULL,
    FOREIGN KEY (entry_id) REFERENCES penilaian_kinerja.entry (entry_id)
);
CREATE TABLE IF NOT EXISTS penilaian_kinerja.kepuasan_masyarakat (
    entry_id uuid NOT NULL PRIMARY KEY,
    indeks_kepuasan_masyarakat NUMERIC(4,2) NOT NULL,
    target_indeks_kepuasan_masyarakat NUMERIC(4,2) NOT NULL,
    FOREIGN KEY (entry_id) REFERENCES penilaian_kinerja.entry (entry_id)
);
CREATE TABLE IF NOT EXISTS penilaian_kinerja.layanan_autopsi (
    entry_id uuid NOT NULL PRIMARY KEY,
    jumlah_pelaksanaan_autopsi INTEGER NOT NULL,
    target_pelaksanaan_autopsi INTEGER NOT NULL,
    FOREIGN KEY (entry_id) REFERENCES penilaian_kinerja.entry (entry_id)
);
CREATE TABLE IF NOT EXISTS penilaian_kinerja.bobot (
    entry_id uuid NOT NULL PRIMARY KEY,
    periode date NOT NULL,
    keuangan NUMERIC(3,2) NOT NULL,
    bisnis_internal NUMERIC(3,2) NOT NULL,
    pelanggan NUMERIC(3,2) NOT NULL,
    pembelajaran_pertumbuhan NUMERIC(3,2) NOT NULL,
    rasio_pnbp_operasional NUMERIC(3,2) NOT NULL,
    modernisasi_pengelolaan_blu NUMERIC(3,2) NOT NULL,
    maturity_level NUMERIC(3,2) NOT NULL,
    pendapatan_blu NUMERIC(3,2) NOT NULL,
    indeks_proyeksi_akurasi_pendapatan_blu NUMERIC(3,2) NOT NULL,
    kunjungan_rawat_darurat NUMERIC(3,2) NOT NULL,
    kunjungan_rawat_jalan NUMERIC(3,2) NOT NULL,
    kunjungan_rawat_inap NUMERIC(3,2) NOT NULL,
    kepuasan_masyarakat NUMERIC(3,2) NOT NULL,
    layanan_autopsi NUMERIC(3,2) NOT NULL,
    user_entry VARCHAR NOT NULL
    created_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp with time zone DEFAULT CURRENT_TIMESTAMP
);

