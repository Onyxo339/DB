<?php

class DB {
    private $conn;

    public function __construct($host, $user, $password, $database) {
        $this->conn = new mysqli($host, $user, $password, $database);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function query($sql) {
        $result = $this->conn->query($sql);

        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        return $result;
    }

    public function multiQuery($sql, $data) {
        $this->conn->query($sql . implode(", ", $data));

        if ($this->conn->error) {
            die("Query failed: " . $this->conn->error);
        }
    }

    public function close() {
        $this->conn->close();
    }
}

class raNa {
    public static function chars($length = 10) {
        $characters = "abcdefghijklmnopqrstuvwxyz";
        return substr(str_shuffle(str_repeat($characters, $length)), 0, rand(1, $length));
    }
}

class Stopwatch {
    private $startTime;

    public function start() {
        $this->startTime = microtime(true);
    }

    public function stop() {
        $elapsed = (microtime(true) - $this->startTime) * 1000;
        printf("[Stopwatch: %.6f ms]\n", $elapsed);
    }
}

$vendorCount = 10;
$productCount = 100000;

$db = new DB('localhost', 'root', '', 'products');
$stopwatch = new Stopwatch();

$stopwatch->start();
$vendorValues = [];
for ($j = 1; $j <= $vendorCount; $j++) {
    $vendorName = raNa::chars();
    $vendorValues[] = "('$j', '$vendorName')";
}
$db->multiQuery("INSERT INTO vendor (id, name) VALUES ", $vendorValues);

$productValues = [];
for ($i = 1; $i <= $productCount; $i++) {
    $productName = raNa::chars();
    $productPrice = rand(50, 100);
    $vendorId = rand(1, $vendorCount);
    $productValues[] = "('$i', '$productName', '$productPrice', '$vendorId')";

    if ($i % 1000 == 0 || $i == $productCount) {
        $db->multiQuery("INSERT INTO product (id, name, price, vendor_id) VALUES ", $productValues);
        $productValues = [];
    }
}
$stopwatch->stop();

$db->close();
