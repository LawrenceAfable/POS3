<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clickable Cards</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      background-color: #f4f4f4;
    }

    .card-container {
      display: flex;
      gap: 20px;
    }

    .card {
      width: 200px;
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      cursor: pointer;
      transition: transform 0.3s ease;
      text-align: center;
    }

    .card:hover {
      transform: scale(1.05);
    }

    .card h2 {
      margin: 0;
      font-size: 18px;
      color: #333;
    }

    .card p {
      color: #777;
    }

    a.card-link {
      display: block;
      width: 100%;
      text-decoration: none;
      color: inherit;
    }
  </style>
</head>

<body>
  <div class="card-container">
    <!-- First card is a clickable link -->
    <a href="{{ route('products.index') }}" class="card-link">
      <div class="card">
        <h2>Card 1</h2>
        <p>Fetch API from In-Store Inventory</p>
      </div>
    </a>

    <!-- Second card has an onclick alert -->
    <a href="{{ route('products.index1') }}" class="card-link">
      <div class="card">
        <h2>Card 2</h2>
        <p>Local DB of Online Inventory</p>
      </div>
    </a>
  </div>
</body>

</html>