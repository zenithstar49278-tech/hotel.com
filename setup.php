<?php
// setup.php
// Run this once to create tables and insert sample data

$host = 'localhost';
$dbname = 'db8hcpfk0p8w38';
$user = 'ubpkik01jujna';
$pass = 'f0ahnf2qsque';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create hotels table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS hotels (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            location VARCHAR(255) NOT NULL,
            description TEXT,
            price DECIMAL(10,2) NOT NULL,
            image VARCHAR(255),
            rating FLOAT DEFAULT 0,
            amenities TEXT
        )
    ");

    // Create bookings table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS bookings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            hotel_id INT NOT NULL,
            guest_name VARCHAR(255) NOT NULL,
            checkin DATE NOT NULL,
            checkout DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (hotel_id) REFERENCES hotels(id)
        )
    ");

    // Insert sample hotels
    $sampleHotels = [
        ['Luxury Inn', 'New York', 'A luxurious hotel in the heart of NYC.', 250.00, 'https://via.placeholder.com/300x200?text=Luxury+Inn', 4.5, 'WiFi, Pool, Gym'],
        ['Beach Resort', 'Miami', 'Relax by the beach with ocean views.', 180.00, 'https://via.placeholder.com/300x200?text=Beach+Resort', 4.2, 'WiFi, Beach Access, Spa'],
        ['City Hotel', 'New York', 'Modern hotel near Times Square.', 200.00, 'https://via.placeholder.com/300x200?text=City+Hotel', 4.0, 'WiFi, Restaurant, Parking'],
        ['Mountain Lodge', 'Denver', 'Cozy lodge with mountain views.', 150.00, 'https://via.placeholder.com/300x200?text=Mountain+Lodge', 4.7, 'WiFi, Hiking Trails, Fireplace'],
        ['Urban Stay', 'Chicago', 'Stylish hotel in downtown Chicago.', 220.00, 'https://via.placeholder.com/300x200?text=Urban+Stay', 4.3, 'WiFi, Gym, Bar'],
    ];

    $stmt = $pdo->prepare("INSERT INTO hotels (name, location, description, price, image, rating, amenities) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($sampleHotels as $hotel) {
        $stmt->execute($hotel);
    }

    echo "Database setup complete with tables and sample data.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
