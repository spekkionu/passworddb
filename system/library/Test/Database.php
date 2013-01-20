<?php

/**
 * Database schema initializer
 *
 * @author Jonathan Bernardi <spekkionu@spekkionu.com>
 * @license MIT http://opensource.org/licenses/MIT
 * @package Test
 */
class Test_Database
{

    /**
     * Initializes test database
     * @param string $file Path to sql file
     * @param PDO $dbh The database connection to use, if not provided an in-memory sqlite database will be created
     * @return \PDO
     */
    public static function initTestDatabase($file, $dbh = null)
    {
        if (is_null($dbh)) {
            $dbh = new PDO('sqlite::memory:');
        }
        self::runSqlFile($file, $dbh);
        return $dbh;
    }

    public static function runSqlFile($file, $dbh)
    {
        $sql = file_get_contents($file);
        $sql = preg_replace('%/\*(?:(?!\*/).)*\*/%s', "", $sql);
        $sql = explode("\n", $sql);
        self::load($sql, $dbh);
    }

    protected static function load($sql, $dbh)
    {
        $query = "";
        foreach ($sql as $sql_line) {

            $parsed = self::sqlLine($sql_line);
            if ($parsed) {
                continue;
            }
            $query .= $sql_line;

            if (substr(rtrim($query), -1, 1) == ';') {
                $dbh->exec($query);
                $query = "";
            }
        }
    }

    public static function sqlLine($sql)
    {
        if (trim($sql) == "") {
            return true;
        }
        if (trim($sql) == ";") {
            return true;
        }
        if (preg_match('~^--.*?~s', $sql)) {
            return true;
        }
        return false;
    }
}
