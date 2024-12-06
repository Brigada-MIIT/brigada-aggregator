<div class="container">
    <p class="page-title">Создание нового товара</p>
    <div class="form">
        <div class="col-12">
            <div class="in">
                <label for="name">Название:</label><br>
                <input id="name" type="text" placeholder="Название товара...">
            </div>
        </div>
        <div class="col-12">
            <div class="in">
                <label for="name">Зависимость от подкатегории:</label><br>
                <select id="category_id" class="form-control">
                    <option value="0">--Выберите подкатегорию--</option>
                    <?php
                        $db = $system->db();
                        $query = $db->query("SELECT * FROM `subcategories` ORDER BY `category_id` ASC");
                        for($i = 0; $i < $query->num_rows; $i++) {
                            $res = $query->fetch_assoc();
                            $category_id = $res['category_id'];
                            $query1 = $db->query("SELECT `name` FROM `categories` WHERE `id` = '$category_id'");
                            $category_name1 = $query1->fetch_assoc()['name'];
                            $category_name = isset($category_name1) ? $category_name1 : "null";
                            echo '<option value="' . $res['id'] . '">' . $res['name'] . ' [' . $category_name . ']</option>';
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="in">
                <label for="description">Описание:</label><br>
                <textarea id="description" type="text" placeholder="Описание категории..." style="width: 75%; display: block; height: 80px"></textarea>
            </div>
        </div>
        <div class="col-12">
            <div class="in">
                <label for="picture_url">Превью:</label><br>
                <input id="picture_url" type="text" placeholder="Ссылка на превью...">
            </div>
        </div>
        <div class="col-12 mt-4" id="shops-container">
            <div class="in">
                <label>Магазины:</label><br>
                <div class="shop-form" id="shop-template" style="display: none; padding-bottom: 10px">
                    <div class="row">
                        <div class="col-3">
                            <input type="text" class="shop-url form-control" placeholder="Ссылка на товар...">
                        </div>
                        <div class="col-2">
                            <input type="number" class="shop-price form-control" placeholder="Цена товара..." min="0" max="9999999999">
                        </div>
                        <div class="col-3">
                            <input type="text" class="shop-name form-control" placeholder="Название магазина...">
                        </div>
                        <div class="col-3">
                            <input type="text" class="shop-logo form-control" placeholder="Ссылка на лого магазина...">
                        </div>
                        <div class="col-1">
                            <button type="button" class="btn btn-danger remove-shop">Удалить</button>
                        </div>
                    </div>
                </div>
                <div id="shops" style="padding-bottom: 10px"></div>
                <button type="button" class="btn btn-info mt-2" id="add-shop">Добавить магазин</button>
            </div>
        </div>
        <div class="col-12" style="margin-top: 5%;">
            <div class="in">
                <div class="d-flex flex-wrap">
                    <button id="submit" type="submit" class="btn btn-primary mr-4 mb-2" onclick="create();">Создать</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let shopCount = 0;
    const maxShops = 10;

    function addShop() {
        if (shopCount >= maxShops) {
            alert("Максимальное количество магазинов: " + maxShops);
            return;
        }

        const shopTemplate = document.getElementById('shop-template').cloneNode(true);
        shopTemplate.style.display = 'block';
        shopTemplate.id = '';
        document.getElementById('shops').appendChild(shopTemplate);
        shopCount++;
    }

    document.getElementById('add-shop').addEventListener('click', addShop);

    document.getElementById('shops').addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-shop')) {
            event.target.closest('.shop-form').remove();
            shopCount--;
        }
    });

    let count_null = 0

    function validateShops() {
        count_null = 0;
        const shops = document.querySelectorAll('.shop-form');
        const shopsData = [];
        let isFirst = true;

        shops.forEach(shop => {
            if(isFirst) {
                isFirst = false;
                return;
            }
            console.log(shop)
            const url = shop.querySelector('.shop-url').value.trim();
            const price = parseFloat(shop.querySelector('.shop-price').value.trim());
            const name = shop.querySelector('.shop-name').value.trim();
            const logo = shop.querySelector('.shop-logo').value.trim();


            if (!url) {
                count_null++;
                return false;
            }

            if (isNaN(price)) {
                count_null++;
                return false;
            }

            if (price < 0 || price > 10000000000) {
                count_null++;
                return false;
            }

            if (!name) {
                count_null++;
                return false;
            }

            if (!logo) {
                count_null++;
                return false;
            }

            shopsData.push([url, price, name, logo]);
        });

        return shopsData;
    }

    function create() {
        const shopsData = validateShops();
        console.log(shopsData)
        if (!shopsData || !shopsData.length) {
            Swal.fire({
                icon: 'error',
                title: 'Не заполнен раздел магазинов',
                text: 'Для создания товара как минимум нужен один полностью заполненный магазин',
                footer: '<a href="<?php echo $settings['link_to_admin'] ?>">Возникли вопросы?</a>'
            });
            return;
        }
        if(count_null) {
            Swal.fire({
                icon: 'error',
                title: 'Не заполен раздел магазинов',
                text: 'В разделе магазина есть незаполненные поля',
                footer: '<a href="<?php echo $settings['link_to_admin'] ?>">Возникли вопросы?</a>'
            });
            return;
        }

        const productData = {
            subcategory_id: $("#category_id").val().trim(),
            name: $("#name").val().trim(),
            description: $("#description").val().trim(),
            picture_url: $("#picture_url").val().trim(),
            relationships: JSON.stringify(shopsData).trim()
        };

        console.log(JSON.stringify(shopsData))

        $.ajax({
            type: 'post',
            url: "/api/products/create",
            data: productData,
            dataType: 'json',
            success: function(data){
                console.log(data);
                if (data.result == 1) {
                    Swal.fire({
                        title: "Успешно!",
                        text: "Товар был успешно создан",
                        icon: "success"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.replace("/app/products?s="+$("#category_id").val().trim());
                        }
                    });

                    document.getElementById('submit').onclick = "";
                    document.getElementById('category_id').disabled = true;
                    document.getElementById('name').disabled = true;
                    document.getElementById('description').disabled = true;
                    document.getElementById('picture_url').disabled = true;
                }
                else if (data.result == 0) {
                    Swal.fire({
                        title: "Ошибка!",
                        text: data.text,
                        icon: "error",
                        footer: '<a href="<?php echo $settings['link_to_admin'] ?>">Возникли вопросы?</a>'
                    });
                }
                else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Произошла неизвестная ошибка!',
                        text: 'Обратитесь к администратору.',
                        footer: '<a href="<?php echo $settings['link_to_admin'] ?>">Возникли вопросы?</a>'
                    });
                }
            }
        });
    }
</script>
