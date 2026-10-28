<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escáner de puertos</title>
    <style>
        body { font-family: sans-serif; max-width: 760px; margin: 2rem auto; padding: 0 1rem; color: #1f2937; }
        h1 { margin-bottom: .25rem; }
        .sub { color: #6b7280; margin-top: 0; }
        form { display: grid; grid-template-columns: 1fr 1fr 120px auto; gap: .75rem; align-items: end; margin: 1.5rem 0; }
        label { display: flex; flex-direction: column; font-size: .85rem; gap: .25rem; }
        input { padding: .5rem; border: 1px solid #d1d5db; border-radius: 6px; font-size: 1rem; }
        button { padding: .55rem 1.1rem; background: #1a56db; color: #fff; border: 0; border-radius: 6px; font-size: 1rem; cursor: pointer; }
        .error { background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: .75rem 1rem; border-radius: 6px; }
        .resumen { display: flex; gap: .75rem; flex-wrap: wrap; margin: 1rem 0; }
        .dato { border: 1px solid #e5e7eb; border-radius: 8px; padding: .6rem 1rem; }
        .dato b { display: block; font-size: 1.4rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: .55rem .75rem; border-bottom: 1px solid #e5e7eb; }
        th { background: #f9fafb; font-size: .85rem; }
        .riesgo { color: #b45309; font-size: .9rem; }
        .ok { color: #047857; }
        @media (max-width: 640px) { form { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<h1>Escáner de puertos</h1>
<p class="sub">Analiza los puertos TCP de un equipo propio (localhost o red privada).</p>

<form method="POST" action="<?= htmlspecialchars($basePath) ?>/escaneo">
    <label>Host
        <input type="text" name="host" value="<?= htmlspecialchars($form['host']) ?>" required>
    </label>
    <label>Puertos
        <input type="text" name="puertos" value="<?= htmlspecialchars($form['puertos']) ?>" placeholder="1-1024 o 22,80,443" required>
    </label>
    <label>Timeout (s)
        <input type="text" name="timeout" value="<?= htmlspecialchars($form['timeout']) ?>" required>
    </label>
    <button type="submit">Escanear</button>
</form>

<?php if ($error !== null): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if ($resultado !== null): ?>
    <h2>Resultado para <?= htmlspecialchars($resultado['host']) ?> (<?= htmlspecialchars($resultado['ip']) ?>)</h2>

    <div class="resumen">
        <div class="dato"><b><?= (int) $resultado['escaneados'] ?></b>puertos escaneados</div>
        <div class="dato"><b><?= (int) $resultado['abiertos'] ?></b>abiertos</div>
        <div class="dato"><b><?= (int) $resultado['riesgosos'] ?></b>con riesgo conocido</div>
        <div class="dato"><b><?= (int) $resultado['duracion_ms'] ?> ms</b>duración</div>
    </div>

    <?php if (empty($resultado['puertos'])): ?>
        <p class="ok">No se encontraron puertos abiertos en el rango analizado.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr><th>Puerto</th><th>Servicio</th><th>Observación</th></tr>
            </thead>
            <tbody>
                <?php foreach ($resultado['puertos'] as $puerto): ?>
                    <tr>
                        <td><?= (int) $puerto['puerto'] ?></td>
                        <td><?= htmlspecialchars($puerto['servicio']) ?></td>
                        <td class="<?= $puerto['riesgo'] !== null ? 'riesgo' : 'ok' ?>">
                            <?= htmlspecialchars($puerto['riesgo'] ?? 'Sin riesgo conocido') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p class="sub">Escaneo iniciado el <?= htmlspecialchars($resultado['inicio']) ?>.</p>
<?php endif; ?>

</body>
</html>
