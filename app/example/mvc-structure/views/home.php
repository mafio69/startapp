<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; padding: 2em; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 1em; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f8f8; }
        h1 { color: #1d4ed8; }
    </style>
</head>
<body>

<h1><?php echo htmlspecialchars($pageTitle); ?></h1>
<p>Witaj w prostym przykładzie aplikacji MVC w PHP uruchomionej na Dockerze!</p>

<h2>Lista użytkowników (dane z modelu)</h2>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Imię i nazwisko</th>
        <th>Email</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['id']); ?></td>
            <td><?php echo htmlspecialchars($user['name']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
