
PRAGMA foreign_keys = OFF;

-- ----------------------------
-- Table structure for "main"."websites"
-- ----------------------------
CREATE TABLE "websites" (
"id"  INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
"name"  TEXT(100),
"domain"  TEXT(100),
"url"  TEXT(255),
"notes"  TEXT
);

-- ----------------------------
-- Records of websites
-- ----------------------------

-- ----------------------------
-- Indexes structure for table websites
-- ----------------------------
CREATE INDEX "main"."search"
ON "websites" ("name" ASC, "domain" ASC, "url" ASC);


-- ----------------------------
-- Table structure for "main"."admin_logins"
-- ----------------------------
CREATE TABLE "admin_logins" (
"id"  INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
"website_id"  INTEGER NOT NULL,
"username"  TEXT(100),
"password"  TEXT(100),
"url"  TEXT(255),
"notes"  TEXT,
CONSTRAINT "website" FOREIGN KEY ("website_id") REFERENCES "websites" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

-- ----------------------------
-- Records of admin_logins
-- ----------------------------

-- ----------------------------
-- Table structure for "main"."control_panels"
-- ----------------------------
CREATE TABLE "control_panels" (
"id"  INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
"website_id"  INTEGER NOT NULL,
"username"  TEXT(100),
"password"  TEXT(100),
"url"  TEXT(255),
"notes"  TEXT,
CONSTRAINT "website" FOREIGN KEY ("website_id") REFERENCES "websites" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

-- ----------------------------
-- Records of control_panels
-- ----------------------------

-- ----------------------------
-- Table structure for "main"."database_data"
-- ----------------------------
CREATE TABLE "database_data" (
"id"  INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
"website_id"  INTEGER NOT NULL,
"type"  TEXT(20) NOT NULL DEFAULT mysql,
"hostname"  TEXT(100),
"username"  TEXT(100),
"password"  TEXT(100),
"database"  TEXT(100),
"url"  TEXT(255),
"notes"  TEXT,
CONSTRAINT "websites" FOREIGN KEY ("website_id") REFERENCES "websites" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

-- ----------------------------
-- Records of database_data
-- ----------------------------


-- ----------------------------
-- Table structure for "main"."ftp_data"
-- ----------------------------
CREATE TABLE "ftp_data" (
"id"  INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
"website_id"  INTEGER NOT NULL,
"type"  TEXT(20) NOT NULL DEFAULT ftp,
"hostname"  TEXT(100),
"username"  TEXT(100),
"password"  TEXT(100),
"path"  TEXT(255),
"notes"  TEXT,
CONSTRAINT "website" FOREIGN KEY ("website_id") REFERENCES "websites" ("id") ON DELETE CASCADE ON UPDATE CASCADE
);

-- ----------------------------
-- Records of ftp_data
-- ----------------------------


