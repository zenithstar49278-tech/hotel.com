```php
<?php
// process_booking.php
// Process booking via AJAX-like request
ob_start();

$host = 'localhost';
$dbname = 'db8hcpfk0p8w38';
$user = 'ubpkik01jujna';
$pass = 'f0ahnf2qsque';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hotelId = $_POST['hotel_id'] ?? 0;
    $guestName = $_POST['guest_name'] ?? '';
    $checkin = $_POST['checkin'] ?? '';
    $checkout = $_POST['checkout'] ?? '';

    if ($hotelId && $guestName && $checkin && $checkout) {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $pdo->prepare("INSERT INTO bookings (hotel_id, guest_name, checkin, checkout) VALUES (?, ?, ?, ?)");
            $stmt->execute([$hotelId, $guestName, $checkin, $checkout]);
            echo "Booking successful";
        } catch (PDOException $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    } else {
        echo "Missing required data";
    }
} else {
    echo "Invalid request method";
}

ob_end_flush();
?>
```
