<div class="container">
    <p class="page-title">Создание категории</p>
    <div class="form">
        <div class="col-12">
            <div class="in">
                <label for="name">Название:</label><br>
                <input id="name" type="text" placeholder="Название категории...">
            </div>
        </div>
        <div class="col-12">
            <div class="in">
                <label for="name">Зависимость от категории (если это подкатегория):</label><br>
                <select id="category_id" class="form-control">
                    <option value="0">--Выберите категорию--</option>
                    <?php
                    $db = $system->db();
                    $query = $db->query("SELECT * FROM `categories`");
                    for($i = 0; $i < $query->num_rows; $i++) {
                        $res = $query->fetch_assoc();
                        echo '<option '.$r.' value="' . $res['id'] . '">' . $res['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="in">
                <label for="description">Описание:</label><br>
                <textarea id="description" type="text" placeholder="Описание категории..." style="width: 75%; display: block;"></textarea>
            </div>
        </div>
        <div class="col-12">
            <div class="in">
                <label for="picture_url">Превью:</label><br>
                <input id="picture_url" type="text" placeholder="Ссылка на превью...">
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
    function create() {
        $.ajax({
            type: 'post',
            url: "/api/categories/create",
            data: 'category_id='+$("#category_id").val().trim()+'&name='+$("#name").val().trim()+'&description='+$("#description").val().trim()+'&picture_url='+$("#picture_url").val().trim(),
            dataType: 'json',
            success: function(data){
                console.log(data);
                if (data.result == 1) {
                    Swal.fire({
                        title: "Успешно!",
                        text: "Категория была создана",
                        icon: "success"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.replace("/app/categories?s="+$("#category_id").val().trim());
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
