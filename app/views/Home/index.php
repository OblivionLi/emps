<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Welcome</h1>
    <p>Hello <?php echo htmlspecialchars($name); ?>!</p>

    <ul>
        <?php foreach ($colors as $color): ?>
            <li><?php echo htmlspecialchars($color); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>