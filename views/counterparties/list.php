<?php ?>

<h1>Контрагенты</h1>

<a href="create.php">Добавить контрагента</a>

<table border="1" cellpadding="6" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Название</th>
        <th>Форма собств.</th>
        <th>ЕДРПОУ</th>
        <th>ИНН</th>
        <th>Св-во налогоплательщика</th>
        <th>Юр. адрес</th>
        <th>Почтовый адрес</th>
        <th>Город</th>
        <th>Создан</th>
    </tr>

    <?php foreach ($counterparties as $c) : ?>
        <tr style="cursor: pointer"
            onclick="window.location='/counterparties/edit?id=<?= $c['id'] ?>'">
            <td><?= $c['id'] ?></td>
            <td><?= htmlspecialchars($c['name'])?> </td>
            <td><?= htmlspecialchars($c['legal_form'])?> </td>
            <td><?= htmlspecialchars($c['edrpou'])?> </td>
            <td><?= htmlspecialchars($c['inn'])?> </td>
            <td><?= htmlspecialchars($c['vat_certificate'])?> </td>
            <td><?= htmlspecialchars($c['legal_address'])?> </td>
            <td><?= htmlspecialchars($c['postal_address'])?> </td>
            <td><?= $c['city_id']?> </td>
            <td><?= $c['created_by_user_id']?> </td>
        </tr>
    <?php endforeach; ?>
</table>