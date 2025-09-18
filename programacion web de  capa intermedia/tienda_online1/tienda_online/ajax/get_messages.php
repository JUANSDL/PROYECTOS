<?php
require_once '../includes/db.php';

$id_chat = intval($_GET['id_chat']);

$sql = "
    SELECT m.Texto, m.Fecha, u.username, u.id
    FROM mensajes m
    JOIN usuarios u ON u.id = m.ID_Usuario
    WHERE m.ID_Chat = ?
    ORDER BY m.Fecha ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$id_chat]);
$mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php foreach ($mensajes as $msg): ?>
    <?php $mine = $msg['id'] == $_SESSION['id'] ? 'text-end' : ''; ?>
    <div class="mb-2 <?= $mine ?>">
        <div class="d-inline-block p-2 rounded bg-light border">
            <strong><?= htmlspecialchars($msg['username']) ?>:</strong><br>
            <?= nl2br(htmlspecialchars($msg['Texto'])) ?>
            <small class="d-block mt-1"><?= $msg['Fecha'] ?></small>
        </div>
    </div>
<?php endforeach; ?>