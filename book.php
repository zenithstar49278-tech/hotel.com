```php
<?php
// book.php
// Booking page for selected hotel
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Hotel</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #ffffff;
            color: #333;
            line-height: 1.6;
        }
        header {
            background: linear-gradient(135deg, #d7bde2, #e8d4f1);
            padding: 30px;
            text-align: center;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        header h1 {
            font-size: 2.5em;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .booking-form {
            max-width: 600px;
            margin: 40px auto;
            padding: 25px;
            background-color: #f9f9f9;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            transition: transform 0.3s ease;
        }
        .booking-form:hover {
            transform: translateY(-5px);
        }
        .booking-form h2 {
            color: #d7bde2;
            font-size: 1.8em;
            margin-bottom: 20px;
        }
        .booking-form p {
            font-size: 1em;
            margin: 10px 0;
        }
        .booking-form input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 2px solid #d7bde2;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s;
        }
        .booking-form input:focus {
            border-color: #c39fd9;
            outline: none;
        }
        .booking-form button {
            width: 100%;
            padding: 12px;
            background-color: #d7bde2;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s, transform 0.2s;
        }
        .booking-form button:hover {
            background-color: #c39fd9;
            transform: scale(1.05);
        }
        .confirmation {
            text-align: center;
            color: #d7bde2;
            font-size: 1.2em;
            margin-top: 20px;
            display: none;
        }
        @media (max-width: 768px) {
            .booking-form {
                margin: 20px;
                padding: 20px;
            }
            header h1 {
                font-size: 1.8em;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Book Your Stay</h1>
    </header>
    <?php
    $host = 'localhost';
    $dbname = 'db8hcpfk0p8w38';
    $user = 'ubpkik01jujna';
    $pass = 'f0ahnf2qsque';

    $hotelId = $_GET['hotel_id'] ?? 0;
    $checkin = $_GET['checkin'] ?? '';
    $checkout = $_GET['checkout'] ?? '';

    if (!$hotelId || !$checkin || !$checkout) {
        echo "<p>Missing required information. Please go back and try again.</p>";
        exit;
    }

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("SELECT * FROM hotels WHERE id = ?");
        $stmt->execute([$hotelId]);
        $hotel = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$hotel) {
            echo "<p>Hotel not found.</p>";
            exit;
        }

        echo '
        <section class="booking-form">
            <h2>' . htmlspecialchars($hotel['name']) . '</h2>
            <p>Location: ' . htmlspecialchars($hotel['location']) . '</p>
            <p>Price: $' . number_format($hotel['price'], 2) . '/night</p>
            <p>Check-in: ' . htmlspecialchars($checkin) . '</p>
            <p>Check-out: ' . htmlspecialchars($checkout) . '</p>
            <form id="bookingForm">
                <input type="hidden" name="hotel_id" value="' . $hotelId . '">
                <input type="hidden" name="checkin" value="' . htmlspecialchars($checkin) . '">
                <input type="hidden" name="checkout" value="' . htmlspecialchars($checkout) . '">
                <input type="text" name="guest_name" placeholder="Your Full Name" required>
                <button type="submit">Confirm Booking</button>
            </form>
            <p class="confirmation" id="confirmation">Booking confirmed! Redirecting to homepage...</p>
        </section>';
    } catch (PDOException $e) {
        echo "<p>Error loading hotel details: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    ?>
    <script>
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('process_booking.php', {
                method: 'POST',
                body: formData
            }).then(response => response.text())
              .then(data => {
                if (data.includes('successful')) {
                    document.getElementById('confirmation').style.display = 'block';
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 3000);
                } else {
                    alert('Booking failed: ' + data);
                }
            }).catch(error => {
                alert('Error: ' + error.message);
            });
        });
    </script>
</body>
</html>
<?php
ob_end_flush();
?>
```
