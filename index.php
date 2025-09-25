```php
<?php
// index.php
// Homepage with search bar and featured hotels
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking - Homepage</title>
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
        .search-bar {
            max-width: 900px;
            margin: 30px auto;
            padding: 25px;
            background-color: #f9f9f9;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            transition: transform 0.3s ease;
        }
        .search-bar:hover {
            transform: translateY(-5px);
        }
        .search-bar form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .search-bar input {
            flex: 1;
            padding: 12px;
            border: 2px solid #d7bde2;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s;
        }
        .search-bar input:focus {
            border-color: #c39fd9;
            outline: none;
        }
        .search-bar button {
            padding: 12px 30px;
            background-color: #d7bde2;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s, transform 0.2s;
        }
        .search-bar button:hover {
            background-color: #c39fd9;
            transform: scale(1.05);
        }
        .featured {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
        }
        .featured h2 {
            text-align: center;
            color: #d7bde2;
            font-size: 2em;
            margin-bottom: 30px;
        }
        .hotel-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }
        .hotel-card {
            background-color: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .hotel-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        .hotel-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 12px 12px 0 0;
        }
        .hotel-info {
            padding: 20px;
        }
        .hotel-info h3 {
            color: #d7bde2;
            font-size: 1.5em;
            margin: 0 0 10px;
        }
        .hotel-info p {
            margin: 5px 0;
            font-size: 0.95em;
        }
        @media (max-width: 768px) {
            .search-bar form {
                flex-direction: column;
            }
            .search-bar input, .search-bar button {
                width: 100%;
            }
            header h1 {
                font-size: 1.8em;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Hotel Booking Platform</h1>
    </header>
    <section class="search-bar">
        <form id="searchForm">
            <input type="text" id="destination" placeholder="Destination (e.g., New York)" required>
            <input type="date" id="checkin" required>
            <input type="date" id="checkout" required>
            <button type="submit">Search Hotels</button>
        </form>
    </section>
    <section class="featured">
        <h2>Featured Hotels</h2>
        <div class="hotel-grid">
            <?php
            $host = 'localhost';
            $dbname = 'db8hcpfk0p8w38';
            $user = 'ubpkik01jujna';
            $pass = 'f0ahnf2qsque';

            try {
                $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $pdo->query("SELECT * FROM hotels ORDER BY rating DESC LIMIT 4");
                while ($hotel = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '
                    <div class="hotel-card">
                        <img src="' . htmlspecialchars($hotel['image']) . '" alt="' . htmlspecialchars($hotel['name']) . '">
                        <div class="hotel-info">
                            <h3>' . htmlspecialchars($hotel['name']) . '</h3>
                            <p>' . htmlspecialchars($hotel['location']) . '</p>
                            <p>$' . number_format($hotel['price'], 2) . '/night</p>
                            <p>Rating: ' . number_format($hotel['rating'], 1) . '</p>
                        </div>
                    </div>';
                }
            } catch (PDOException $e) {
                echo "<p>Error loading featured hotels: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>
        </div>
    </section>
    <script>
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const destination = document.getElementById('destination').value.trim();
            const checkin = document.getElementById('checkin').value;
            const checkout = document.getElementById('checkout').value;
            if (destination && checkin && checkout) {
                window.location.href = `search.php?destination=${encodeURIComponent(destination)}&checkin=${checkin}&checkout=${checkout}`;
            } else {
                alert('Please fill in all fields.');
            }
        });
    </script>
</body>
</html>
<?php
ob_end_flush();
?>
```
