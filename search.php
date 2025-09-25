```php
<?php
// search.php
// Hotel listing page with filters and sorting
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Listing</title>
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
        .filters {
            max-width: 1200px;
            margin: 30px auto;
            padding: 25px;
            background-color: #f9f9f9;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .filters select, .filters input {
            padding: 12px;
            border: 2px solid #d7bde2;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s;
        }
        .filters select:focus, .filters input:focus {
            border-color: #c39fd9;
            outline: none;
        }
        .filters button {
            padding: 12px 30px;
            background-color: #d7bde2;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s, transform 0.2s;
        }
        .filters button:hover {
            background-color: #c39fd9;
            transform: scale(1.05);
        }
        .hotel-list {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
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
        .book-btn {
            display: block;
            text-align: center;
            padding: 12px;
            background-color: #d7bde2;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 15px;
            font-size: 1em;
            transition: background-color 0.3s, transform 0.2s;
        }
        .book-btn:hover {
            background-color: #c39fd9;
            transform: scale(1.05);
        }
        @media (max-width: 768px) {
            .filters {
                flex-direction: column;
            }
            .filters select, .filters input, .filters button {
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
        <h1>Available Hotels</h1>
    </header>
    <section class="filters">
        <form id="filterForm">
            <input type="hidden" id="destination" value="<?php echo htmlspecialchars($_GET['destination'] ?? ''); ?>">
            <input type="hidden" id="checkin" value="<?php echo htmlspecialchars($_GET['checkin'] ?? ''); ?>">
            <input type="hidden" id="checkout" value="<?php echo htmlspecialchars($_GET['checkout'] ?? ''); ?>">
            <select id="sort">
                <option value="price_asc" <?php echo ($_GET['sort'] ?? '') == 'price_asc' ? 'selected' : ''; ?>>Price Low to High</option>
                <option value="price_desc" <?php echo ($_GET['sort'] ?? '') == 'price_desc' ? 'selected' : ''; ?>>Price High to Low</option>
                <option value="rating_desc" <?php echo ($_GET['sort'] ?? '') == 'rating_desc' ? 'selected' : ''; ?>>Best Rated</option>
            </select>
            <input type="number" id="min_price" placeholder="Min Price" value="<?php echo htmlspecialchars($_GET['min_price'] ?? ''); ?>">
            <input type="number" id="max_price" placeholder="Max Price" value="<?php echo htmlspecialchars($_GET['max_price'] ?? ''); ?>">
            <input type="number" id="min_rating" placeholder="Min Rating" min="0" max="5" step="0.1" value="<?php echo htmlspecialchars($_GET['min_rating'] ?? ''); ?>">
            <button type="submit">Apply Filters</button>
        </form>
    </section>
    <section class="hotel-list">
        <div class="hotel-grid">
            <?php
            $host = 'localhost';
            $dbname = 'db8hcpfk0p8w38';
            $user = 'ubpkik01jujna';
            $pass = 'f0ahnf2qsque';

            $destination = $_GET['destination'] ?? '';
            $sort = $_GET['sort'] ?? 'price_asc';
            $minPrice = $_GET['min_price'] ?? '';
            $maxPrice = $_GET['max_price'] ?? '';
            $minRating = $_GET['min_rating'] ?? '';

            $whereClauses = [];
            $params = [];
            if ($destination) {
                $whereClauses[] = "location LIKE :destination";
                $params[':destination'] = "%$destination%";
            }
            if ($minPrice !== '') {
                $whereClauses[] = "price >= :min_price";
                $params[':min_price'] = $minPrice;
            }
            if ($maxPrice !== '') {
                $whereClauses[] = "price <= :max_price";
                $params[':max_price'] = $maxPrice;
            }
            if ($minRating !== '') {
                $whereClauses[] = "rating >= :min_rating";
                $params[':min_rating'] = $minRating;
            }
            $where = !empty($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

            $orderBy = 'price ASC';
            if ($sort == 'price_desc') $orderBy = 'price DESC';
            if ($sort == 'rating_desc') $orderBy = 'rating DESC';

            try {
                $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $pdo->prepare("SELECT * FROM hotels $where ORDER BY $orderBy");
                $stmt->execute($params);
                if ($stmt->rowCount() === 0) {
                    echo "<p>No hotels found matching your criteria.</p>";
                } else {
                    while ($hotel = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '
                        <div class="hotel-card">
                            <img src="' . htmlspecialchars($hotel['image']) . '" alt="' . htmlspecialchars($hotel['name']) . '">
                            <div class="hotel-info">
                                <h3>' . htmlspecialchars($hotel['name']) . '</h3>
                                <p>' . htmlspecialchars($hotel['description']) . '</p>
                                <p>$' . number_format($hotel['price'], 2) . '/night</p>
                                <p>Rating: ' . number_format($hotel['rating'], 1) . '</p>
                                <p>Amenities: ' . htmlspecialchars($hotel['amenities']) . '</p>
                            </div>
                            <a href="#" class="book-btn" data-hotel-id="' . $hotel['id'] . '">Book Now</a>
                        </div>';
                    }
                }
            } catch (PDOException $e) {
                echo "<p>Error loading hotels: " . htmlspecialchars($e->getMessage()) . "</p>";
            }
            ?>
        </div>
    </section>
    <script>
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const destination = document.getElementById('destination').value.trim();
            const checkin = document.getElementById('checkin').value;
            const checkout = document.getElementById('checkout').value;
            const sort = document.getElementById('sort').value;
            const minPrice = document.getElementById('min_price').value;
            const maxPrice = document.getElementById('max_price').value;
            const minRating = document.getElementById('min_rating').value;

            let url = `search.php?destination=${encodeURIComponent(destination)}&checkin=${checkin}&checkout=${checkout}`;
            if (sort) url += `&sort=${sort}`;
            if (minPrice) url += `&min_price=${minPrice}`;
            if (maxPrice) url += `&max_price=${maxPrice}`;
            if (minRating) url += `&min_rating=${minRating}`;
            window.location.href = url;
        });

        document.querySelectorAll('.book-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const hotelId = this.dataset.hotelId;
                const checkin = document.getElementById('checkin').value;
                const checkout = document.getElementById('checkout').value;
                window.location.href = `book.php?hotel_id=${hotelId}&checkin=${checkin}&checkout=${checkout}`;
            });
        });
    </script>
</body>
</html>
<?php
ob_end_flush();
?>
```
