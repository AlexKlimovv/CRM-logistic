<?php if (!empty($errors)): ?>
    <div class="error-box">
        <ul>
            <?php foreach ($errors as $message): ?>
            <li><?= $message ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif;?>

<link rel="stylesheet" href="/css/style.css">

<form method="post" action="/counterparties/create" novalidate>
    <?php if (!empty($data['id'])): ?>
        <input type="hidden" name="id" value="<?= $data['id'] ?>">
    <?php endif; ?>

    <div class="form-group">
        <label>Название</label>
        <input type="text" name="name" class="<?= isset($errors['name']) ? 'error' : ''?>"
               value="<?= htmlspecialchars($data['name'] ?? '')?>" placeholder="Название">
    </div>

    <div class="form-group">
        <label>Форма собственности</label>
        <select name="legal_form" required>
            <option value="ТОВ" <?= ($data['legal_form'] ?? '') === 'ТОВ' ? 'selected' : ''?>>ТОВ</option>
            <option value="ФОП"<?= ($data['legal_form'] ?? '') === 'ФОП' ? 'selected' : ''?>>ФОП</option>
            <option value="ПП" <?= ($data['legal_form'] ?? '') === 'ПП' ? 'selected' : ''?>>ПП</option>
        </select>
    </div>

    <div class="form-group">
        <label>ЕДРПОУ</label>
        <input type="text" name="edrpou" class="<?= isset($errors['edrpou']) ? 'error' : ''?>"
               value="<?= htmlspecialchars($data['edrpou'] ?? '')?>" placeholder="ЕДРПОУ">
    </div>

    <div class="form-group">
        <label>ИНН</label>
        <input type="text" name="inn" class="<?= isset($errors['inn']) ? 'error' : ''?>"
               value="<?= htmlspecialchars($data['inn'] ?? '')?>" placeholder="ИНН">
    </div>

    <div class="form-group">
        <label>№ св-ва налогоплательщика</label>
        <input type="text" name="vat_certificate" class="<?= isset($errors['vat_certificate']) ? 'error' : ''?>"
               value="<?= htmlspecialchars($data['vat_certificate'] ?? '')?>" placeholder="№ НДС">
    </div>

    <div class="form-group">
        <label>Юридический адрес</label>
        <input type="text" name="legal_address" class="<?= isset($errors['legal_address']) ? 'error' : ''?>"
               value="<?= htmlspecialchars($data['legal_address'] ?? '')?>" placeholder="Юр. адрес">
    </div>

    <div class="form-group">
        <label>Почтовый адрес</label>
        <input type="text" name="postal_address" class="<?= isset($errors['postal_address']) ? 'error' : ''?>"
               value="<?= htmlspecialchars($data['postal_address'] ?? '')?>" placeholder="Почтовый адрес">
    </div>

    <div class="form-group">
        <label>Город</label>
        <input type="text" id="city_search" class="<?= isset($errors['city_id']) ? 'error' : '' ?>" placeholder="Город">
        <input type="hidden" name="city_id" id="city_id">
    </div>

    <button type="submit">Сохранить</button>
</form>

<script>
    const input = document.getElementById('city_search');
    const hidden = document.getElementById('city_id');

    const list = document.createElement('div');
    list.style.border = '1px solid #ccc';
    list.style.position = 'absolute';
    list.style.background = '#fff';
    input.after(list);

    input.addEventListener('input', async () => {
        const q = input.value;
        hidden.value = '';
        list.innerHTML = '';

        if (q.length < 2) return;

        const res = await fetch('/cities/search?q=' + encodeURIComponent(q));
        const cities = await res.json();

        cities.forEach(city => {
            const item = document.createElement('div');
            item.textContent = city.name;
            item.style.padding = '4px';
            item.style.cursor = 'pointer';

            item.onclick = () => {
                input.value = city.name;
                hidden.value = city.id;
                list.innerHTML = '';
            };

            list.appendChild(item);
        });
    });
</script>
