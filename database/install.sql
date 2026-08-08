-- ============================================================
-- 森码云实人认证系统 - 数据库初始化脚本
-- 数据库: senma_face_auth  字符集: utf8mb4
-- ============================================================
CREATE DATABASE IF NOT EXISTS senma_face_auth CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE senma_face_auth;

-- 管理员表
CREATE TABLE sm_admin (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(100) DEFAULT NULL,
  real_name VARCHAR(50) DEFAULT NULL,
  avatar VARCHAR(255) DEFAULT NULL,
  role TINYINT DEFAULT 1 COMMENT '1=超管 2=普通',
  last_login_ip VARCHAR(45) DEFAULT NULL,
  last_login_time DATETIME DEFAULT NULL,
  status TINYINT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='管理员';

-- 用户表
CREATE TABLE sm_user (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  id_card VARCHAR(18) NOT NULL,
  phone VARCHAR(20) DEFAULT NULL,
  face_feature TEXT DEFAULT NULL,
  status TINYINT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户';

-- 认证记录表
CREATE TABLE sm_verification (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED DEFAULT 0,
  token VARCHAR(255) NOT NULL,
  name VARCHAR(50) DEFAULT NULL,
  id_card VARCHAR(18) DEFAULT NULL,
  verify_type ENUM('liveness','face_compare','full') DEFAULT 'full',
  api_source VARCHAR(50) DEFAULT NULL,
  liveness_passed TINYINT DEFAULT 0,
  face_match_score DECIMAL(5,2) DEFAULT 0,
  status ENUM('pending','passed','failed','expired') DEFAULT 'pending',
  fail_reason VARCHAR(255) DEFAULT NULL,
  face_image VARCHAR(255) DEFAULT NULL,
  ip VARCHAR(45) DEFAULT NULL,
  user_agent TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  completed_at DATETIME DEFAULT NULL,
  INDEX idx_token(token),
  INDEX idx_user(user_id),
  INDEX idx_status(status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='认证记录';

-- API通道表
CREATE TABLE sm_api_channel (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  provider VARCHAR(50) NOT NULL COMMENT 'tencent/baidu/local/azure/facepp',
  secret_id VARCHAR(255) DEFAULT NULL,
  secret_key VARCHAR(255) DEFAULT NULL,
  api_key VARCHAR(255) DEFAULT NULL,
  endpoint VARCHAR(255) DEFAULT NULL,
  is_enabled TINYINT DEFAULT 1,
  is_default TINYINT DEFAULT 0,
  priority INT DEFAULT 10,
  daily_limit INT DEFAULT 0,
  used_today INT DEFAULT 0,
  last_used_at DATETIME DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='API通道';

-- Token表
CREATE TABLE sm_token (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  token VARCHAR(255) NOT NULL UNIQUE,
  user_id INT UNSIGNED DEFAULT 0,
  order_id VARCHAR(100) DEFAULT NULL,
  scene VARCHAR(50) DEFAULT 'face_verify',
  status ENUM('pending','used','expired','revoked') DEFAULT 'pending',
  result TEXT DEFAULT NULL,
  expire_time DATETIME NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  used_at DATETIME DEFAULT NULL,
  INDEX idx_token(token),
  INDEX idx_status(status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Token';

-- API调用日志
CREATE TABLE sm_api_log (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  api_name VARCHAR(100) NOT NULL,
  channel_id INT UNSIGNED DEFAULT 0,
  request_data TEXT DEFAULT NULL,
  response_data TEXT DEFAULT NULL,
  status_code INT DEFAULT 200,
  duration_ms INT DEFAULT 0,
  ip VARCHAR(45) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_api(api_name),
  INDEX idx_created(created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='API日志';

-- 活体检测记录
CREATE TABLE sm_liveness (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  verification_id INT UNSIGNED NOT NULL,
  action_type VARCHAR(20) NOT NULL,
  action_result TINYINT DEFAULT 0,
  confidence DECIMAL(5,2) DEFAULT 0,
  details TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_verification(verification_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='活体检测';

-- 系统配置
CREATE TABLE sm_config (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  config_key VARCHAR(100) NOT NULL UNIQUE,
  config_value TEXT DEFAULT NULL,
  description VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='系统配置';

-- 默认数据
INSERT INTO sm_admin (username,password,email,real_name,role) VALUES
('admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin@face.builds.codes','系统管理员',1);

INSERT INTO sm_api_channel (name,provider,is_enabled,is_default,priority) VALUES
('腾讯云慧眼','tencent',0,1,1),
('百度人脸识别','baidu',0,0,2),
('自研通道','local',1,0,10);

INSERT INTO sm_config (config_key,config_value,description) VALUES
('site_name','森码云实人认证系统','站点名称'),
('face_match_threshold','80','人脸比对阈值'),
('liveness_threshold','75','活体检测阈值'),
('token_expire','15','Token有效期(分钟)'),
('max_attempts','5','最大认证尝试次数'),
('enable_audio','1','启用提示音'),
('encryption_key','change_me_32_chars_minimum','数据加密密钥');