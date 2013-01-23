
PRAGMA foreign_keys = OFF;

-- ----------------------------
-- Table structure for "logins"
-- ----------------------------
DROP TABLE "main"."logins";
CREATE TABLE "logins" (
"id"  INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
"username"  TEXT(25) NOT NULL,
"password"  TEXT(60),
"email"  TEXT(127) NOT NULL,
"name"  TEXT(100)
);

-- ----------------------------
-- Records of logins
-- ----------------------------
INSERT INTO "main"."logins" VALUES (1, 'admin', 'password', 'email@example.com', 'Administrator');

-- ----------------------------
-- Indexes structure for table logins
-- ----------------------------
CREATE UNIQUE INDEX "main"."email" ON "logins" ("email" ASC);
CREATE UNIQUE INDEX "main"."username" ON "logins" ("username" ASC);

-- ----------------------------
-- Table structure for "main"."websites"
-- ----------------------------
DROP TABLE "main"."websites";
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
INSERT INTO "main"."websites" ("id","name","domain","url","notes") VALUES (1, "First Website", "first.com", "http://www.first.com", "Notes for first website.");
INSERT INTO "main"."websites" ("id","name","domain","url","notes") VALUES (2, "Second Website", "second.com", "http://www.second.com", "Notes for second website.");
INSERT INTO "main"."websites" ("id","name","domain","url","notes") VALUES (3, "Third Website", "third.com", "http://www.third.com", "Notes for third website.");
INSERT INTO "main"."websites" ("id","name","domain","url","notes") VALUES (4, "Fourth Website", "fourth.com", "http://www.fourth.com", "Notes for fourth website.");
INSERT INTO "main"."websites" ("id","name","domain","url","notes") VALUES (5, "Fifth Website", "fifth.com", "http://www.fifth.com", "Notes for fifth website.");

-- ----------------------------
-- Indexes structure for table websites
-- ----------------------------
CREATE INDEX "main"."search"
ON "websites" ("name" ASC, "domain" ASC, "url" ASC);


-- ----------------------------
-- Table structure for "main"."admin_logins"
-- ----------------------------
DROP TABLE "main"."admin_logins";
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
INSERT INTO "main"."admin_logins" ("id","website_id","username","password","url","notes") VALUES (1, 1, "admin", "password", "http://www.first.com/admin/login", "Notes for first admin login.");
INSERT INTO "main"."admin_logins" ("id","website_id","username","password","url","notes") VALUES (2, 1, "superadmin", "password", "http://www.first.com/admin/login", "Notes for first admin login.");

-- ----------------------------
-- Table structure for "main"."control_panels"
-- ----------------------------
DROP TABLE "main"."control_panels";
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
INSERT INTO "main"."control_panels" ("id","website_id","username","password","url","notes") VALUES (1, 1, "username", "password", "http://www.first.com/cpanel", "Notes for first cpanel login.");

-- ----------------------------
-- Table structure for "main"."database_data"
-- ----------------------------
DROP TABLE "main"."database_data";
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
INSERT INTO "main"."database_data" ("id","website_id","type","hostname","username","password","database","url","notes") VALUES (1, 1, "mysql", "localhost", "username", "password", "dbname", "http://www.first.com/phpmyadmin", "Notes for first database login.");

-- ----------------------------
-- Table structure for "main"."ftp_data"
-- ----------------------------
DROP TABLE "main"."ftp_data";
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
INSERT INTO "main"."ftp_data" ("id","website_id","type","hostname","username","password","path","notes") VALUES (1, 1, "sftp", "first.com", "username", "password", "/var/www", "Notes for first ftp login.");

