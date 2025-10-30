<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Company Not Found</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
      background: #f5f7fa;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      text-align: center;
      padding: 40px;
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      max-width: 500px;
      width: 90%;
    }

    .illustration {
      width: 150px;
      margin-bottom: 20px;
    }

    h1 {
      font-size: 2.5rem;
      color: #1f2937;
      margin-bottom: 10px;
    }

    p {
      font-size: 1rem;
      color: #4b5563;
      margin-bottom: 30px;
    }

    a.button {
      display: inline-block;
      padding: 12px 25px;
      font-weight: 600;
      color: #fff;
      background: #3b82f6;
      border-radius: 8px;
      text-decoration: none;
      transition: background 0.3s;
    }

    a.button:hover {
      background: #2563eb;
    }
  </style>
</head>
<body>
<div class="container">
  <img class="illustration" src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png" alt="Company Not Found">
  <h1>Company Not Found</h1>
  <p>Oops! The company you are looking for does not exist or has been removed. Please check the URL or try again.</p>
  <a href="{{ url('/') }}" class="button">Go Back Home</a>
</div>
</body>
</html>
