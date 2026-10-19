-- Esquema aprimorado para o banco de dados Eclipse
-- Melhorias: separação de concertos e álbuns, gêneros, playlists de usuários, endereços, métodos de pagamento, índices para performance
CREATE DATABASE IF NOT EXISTS eclipse_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE eclipse_db;

-- Tabelas principais do sistema
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('user','admin') DEFAULT 'user',
  first_name VARCHAR(50),
  last_name VARCHAR(50),
  phone VARCHAR(20),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_username (username),
  INDEX idx_email (email),
  INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Endereços dos usuários
CREATE TABLE IF NOT EXISTS addresses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  type ENUM('billing','shipping') DEFAULT 'billing',
  street VARCHAR(255) NOT NULL,
  city VARCHAR(100) NOT NULL,
  state VARCHAR(100),
  postal_code VARCHAR(20) NOT NULL,
  country VARCHAR(100) NOT NULL,
  is_default TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_id (user_id),
  INDEX idx_type (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Gêneros musicais
CREATE TABLE IF NOT EXISTS genres (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE,
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Artistas
CREATE TABLE IF NOT EXISTS artists (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  bio TEXT,
  image VARCHAR(255),
  website VARCHAR(255),
  social_links JSON,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Álbuns
CREATE TABLE IF NOT EXISTS albums (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  artist_id INT,
  description TEXT,
  release_date DATE,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  stock INT NOT NULL DEFAULT 0,
  image VARCHAR(255),
  genre_id INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (artist_id) REFERENCES artists(id) ON DELETE SET NULL,
  FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE SET NULL,
  INDEX idx_title (title),
  INDEX idx_artist_id (artist_id),
  INDEX idx_genre_id (genre_id),
  INDEX idx_release_date (release_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Músicas
CREATE TABLE IF NOT EXISTS musics (
  id INT AUTO_INCREMENT PRIMARY KEY,
  artist_id INT,
  album_id INT,
  title VARCHAR(200) NOT NULL,
  duration INT DEFAULT 0, -- em segundos
  track_number INT,
  file_path VARCHAR(255),
  plays BIGINT DEFAULT 0,
  genre_id INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (artist_id) REFERENCES artists(id) ON DELETE SET NULL,
  FOREIGN KEY (album_id) REFERENCES albums(id) ON DELETE CASCADE,
  FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE SET NULL,
  INDEX idx_title (title),
  INDEX idx_artist_id (artist_id),
  INDEX idx_album_id (album_id),
  INDEX idx_genre_id (genre_id),
  INDEX idx_plays (plays)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Concertos
CREATE TABLE IF NOT EXISTS concerts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  artist_id INT,
  description TEXT,
  event_date DATETIME NOT NULL,
  venue VARCHAR(255) NOT NULL,
  city VARCHAR(100) NOT NULL,
  country VARCHAR(100) NOT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  max_capacity INT,
  available_tickets INT,
  image VARCHAR(255),
  status ENUM('upcoming','cancelled','completed') DEFAULT 'upcoming',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (artist_id) REFERENCES artists(id) ON DELETE SET NULL,
  INDEX idx_title (title),
  INDEX idx_artist_id (artist_id),
  INDEX idx_event_date (event_date),
  INDEX idx_city (city),
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Playlists globais
CREATE TABLE IF NOT EXISTS playlists (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  description TEXT,
  image VARCHAR(255),
  is_featured TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_title (title),
  INDEX idx_is_featured (is_featured)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Playlists de usuários
CREATE TABLE IF NOT EXISTS user_playlists (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  title VARCHAR(150) NOT NULL,
  description TEXT,
  is_public TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_id (user_id),
  INDEX idx_title (title),
  INDEX idx_is_public (is_public)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Relacionamento músicas-playlists (muitos-para-muitos)
CREATE TABLE IF NOT EXISTS playlist_musics (
  id INT AUTO_INCREMENT PRIMARY KEY,
  playlist_id INT NOT NULL,
  music_id INT NOT NULL,
  position INT DEFAULT 0,
  added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (playlist_id) REFERENCES playlists(id) ON DELETE CASCADE,
  FOREIGN KEY (music_id) REFERENCES musics(id) ON DELETE CASCADE,
  UNIQUE KEY unique_playlist_music (playlist_id, music_id),
  INDEX idx_playlist_id (playlist_id),
  INDEX idx_music_id (music_id),
  INDEX idx_position (position)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Relacionamento músicas-playlists de usuários
CREATE TABLE IF NOT EXISTS user_playlist_musics (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_playlist_id INT NOT NULL,
  music_id INT NOT NULL,
  position INT DEFAULT 0,
  added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_playlist_id) REFERENCES user_playlists(id) ON DELETE CASCADE,
  FOREIGN KEY (music_id) REFERENCES musics(id) ON DELETE CASCADE,
  UNIQUE KEY unique_user_playlist_music (user_playlist_id, music_id),
  INDEX idx_user_playlist_id (user_playlist_id),
  INDEX idx_music_id (music_id),
  INDEX idx_position (position)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Métodos de pagamento
CREATE TABLE IF NOT EXISTS payment_methods (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  type ENUM('credit_card','debit_card','paypal','bank_transfer') NOT NULL,
  provider VARCHAR(50),
  last_four VARCHAR(4),
  expiry_month INT,
  expiry_year INT,
  is_default TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_id (user_id),
  INDEX idx_type (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Pedidos
CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  billing_address_id INT,
  shipping_address_id INT,
  payment_method_id INT,
  subtotal DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  tax DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  shipping_cost DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  currency VARCHAR(3) DEFAULT 'EUR',
  status ENUM('pending','paid','shipped','delivered','cancelled','refunded') DEFAULT 'pending',
  payment_status ENUM('pending','completed','failed','refunded') DEFAULT 'pending',
  order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (billing_address_id) REFERENCES addresses(id) ON DELETE SET NULL,
  FOREIGN KEY (shipping_address_id) REFERENCES addresses(id) ON DELETE SET NULL,
  FOREIGN KEY (payment_method_id) REFERENCES payment_methods(id) ON DELETE SET NULL,
  INDEX idx_user_id (user_id),
  INDEX idx_status (status),
  INDEX idx_payment_status (payment_status),
  INDEX idx_order_date (order_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Itens dos pedidos (para álbuns e concertos)
CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  item_type ENUM('album','concert') NOT NULL,
  item_id INT NOT NULL, -- ID do álbum ou concerto
  quantity INT NOT NULL DEFAULT 1,
  price_each DECIMAL(10,2) NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  INDEX idx_order_id (order_id),
  INDEX idx_item_type (item_type),
  INDEX idx_item_id (item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Ingressos para concertos
CREATE TABLE IF NOT EXISTS concert_tickets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_item_id INT NOT NULL,
  concert_id INT NOT NULL,
  ticket_code VARCHAR(50) UNIQUE NOT NULL,
  seat_number VARCHAR(10),
  status ENUM('valid','used','cancelled') DEFAULT 'valid',
  qr_code VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_item_id) REFERENCES order_items(id) ON DELETE CASCADE,
  FOREIGN KEY (concert_id) REFERENCES concerts(id) ON DELETE CASCADE,
  INDEX idx_order_item_id (order_item_id),
  INDEX idx_concert_id (concert_id),
  INDEX idx_ticket_code (ticket_code),
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Mensagens de contato
CREATE TABLE IF NOT EXISTS contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  date_of_birth DATE NULL,
  phone VARCHAR(50) NULL,
  email VARCHAR(255) NOT NULL,
  subject VARCHAR(150) NOT NULL,
  message TEXT NOT NULL,
  consent TINYINT(1) NOT NULL DEFAULT 0,
  ip VARCHAR(45) NULL,
  user_agent TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_email (email),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Banners
CREATE TABLE IF NOT EXISTS banners (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150),
  image VARCHAR(255) NOT NULL,
  link VARCHAR(255),
  position VARCHAR(50) DEFAULT 'home',
  active TINYINT(1) DEFAULT 1,
  sort_order INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_position (position),
  INDEX idx_active (active),
  INDEX idx_sort_order (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Comentários
CREATE TABLE IF NOT EXISTS comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  content_type ENUM('album','concert','artist','music') DEFAULT 'album',
  content_id INT NOT NULL,
  content TEXT NOT NULL,
  rating TINYINT NULL CHECK (rating >= 1 AND rating <= 5),
  status ENUM('pending','approved','spam','deleted') DEFAULT 'pending',
  ip VARCHAR(45),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_user_id (user_id),
  INDEX idx_content_type (content_type),
  INDEX idx_content_id (content_id),
  INDEX idx_status (status),
  INDEX idx_rating (rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Páginas estáticas
CREATE TABLE IF NOT EXISTS pages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(150) UNIQUE NOT NULL,
  title VARCHAR(255) NOT NULL,
  content LONGTEXT NOT NULL,
  meta_description TEXT,
  meta_keywords TEXT,
  is_published TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_slug (slug),
  INDEX idx_is_published (is_published)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Logs administrativos
CREATE TABLE IF NOT EXISTS admin_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  admin_id INT NULL,
  action VARCHAR(150) NOT NULL,
  entity_type VARCHAR(50),
  entity_id INT,
  old_values JSON,
  new_values JSON,
  note TEXT,
  ip VARCHAR(45),
  user_agent TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_admin_id (admin_id),
  INDEX idx_action (action),
  INDEX idx_entity_type (entity_type),
  INDEX idx_entity_id (entity_id),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Estatísticas de reprodução
CREATE TABLE IF NOT EXISTS play_stats (
  id INT AUTO_INCREMENT PRIMARY KEY,
  music_id INT NOT NULL,
  user_id INT NULL,
  ip VARCHAR(45),
  played_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (music_id) REFERENCES musics(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_music_id (music_id),
  INDEX idx_user_id (user_id),
  INDEX idx_played_at (played_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Favoritos
CREATE TABLE IF NOT EXISTS favorites (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  item_type ENUM('album','concert','artist','music','playlist') NOT NULL,
  item_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE KEY unique_user_item (user_id, item_type, item_id),
  INDEX idx_user_id (user_id),
  INDEX idx_item_type (item_type),
  INDEX idx_item_id (item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserir alguns gêneros padrão
INSERT IGNORE INTO genres (name, description) VALUES
('Rock', 'Música rock e suas variações'),
('Pop', 'Música pop e mainstream'),
('Jazz', 'Música jazz e improvisação'),
('Classical', 'Música clássica e erudita'),
('Electronic', 'Música eletrônica e sintetizada'),
('Hip Hop', 'Rap, hip hop e R&B'),
('Blues', 'Blues e suas influências'),
('Country', 'Country e folk americano'),
('Reggae', 'Reggae e música caribenha'),
('Metal', 'Heavy metal e suas variações');


