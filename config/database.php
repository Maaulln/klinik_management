<?php
// Database connection management

/**
 * Get database connection
 * 
 * @return PDO Database connection
 */
function getDbConnection() {
    static $conn = null;
    
    if ($conn === null) {
        $dsn = sprintf(
            "pgsql:host=%s;port=%s;dbname=%s;user=%s;password=%s",
            DB_HOST,
            DB_PORT,
            DB_NAME,
            DB_USER,
            DB_PASS
        );
        
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        try {
            $conn = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                die("Database connection failed: " . $e->getMessage());
            } else {
                die("Database connection failed. Please try again later.");
            }
        }
    }
    
    return $conn;
}

/**
 * Execute a query and return the result
 * 
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return array Query results
 */
function dbQuery($sql, $params = []) {
    $conn = getDbConnection();
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        if (DEBUG_MODE) {
            die("Query failed: " . $e->getMessage());
        } else {
            die("An error occurred while processing your request.");
        }
    }
}

/**
 * Execute a query and return a single row
 * 
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return array|false Single row or false if not found
 */
function dbQuerySingle($sql, $params = []) {
    $conn = getDbConnection();
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    } catch (PDOException $e) {
        if (DEBUG_MODE) {
            die("Query failed: " . $e->getMessage());
        } else {
            die("An error occurred while processing your request.");
        }
    }
}

/**
 * Execute a query that doesn't return a result set
 * 
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return int Number of affected rows
 */
function dbExecute($sql, $params = []) {
    $conn = getDbConnection();
    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        if (DEBUG_MODE) {
            die("Query failed: " . $e->getMessage());
        } else {
            die("An error occurred while processing your request.");
        }
    }
}

/**
 * Get the last inserted ID
 * 
 * @return string Last inserted ID
 */
function dbLastInsertId() {
    $conn = getDbConnection();
    return $conn->lastInsertId();
}

/**
 * Begin a transaction
 */
function dbBeginTransaction() {
    $conn = getDbConnection();
    $conn->beginTransaction();
}

/**
 * Commit a transaction
 */
function dbCommit() {
    $conn = getDbConnection();
    $conn->commit();
}

/**
 * Rollback a transaction
 */
function dbRollback() {
    $conn = getDbConnection();
    $conn->rollBack();
}
?>