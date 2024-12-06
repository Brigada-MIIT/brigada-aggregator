<div class="container">
    <p class="page-title">Редактирование подкатегории «<?php echo $name_category; ?>»</p>
    <div class="form">
        <div class="col-12">
            <div class="in">
                <label for="name">Название:</label><br>
                <input id="name" type="text" placeholder="Название подкатегории..." value="<?php echo $result['name'] ?>">
            </div>
        </div>
        <div class="col-12">
            <div class="in">
                <label for="name">Категория:</label><br>
                <select id="category_id" class="form-control">
                    <option value="0">--Выберите категорию--</option>
                    <?php
                        $db = $system->db();
                        $query = $db->query("SELECT * FROM `categories`");
                        for($i = 0; $i < $query->num_rows; $i++) {
                            $res = $query->fetch_assoc();
                            $r = "";
                            if($res['id'] == $result['category_id'])
                                $r = "selected";
                            echo '<option '.$r.' value="' . $res['id'] . '">' . $res['name'] . '</option>';
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="in">
                <label for="description">Описание:</label><br>
                <textarea id="description" type="text" placeholder="Описание подкатегории..." style="width: 75%; display: block;"><?php echo $result['description'] ?></textarea>
            </div>
        </div>
        <div class="col-12">
            <div class="in">
                <label for="picture_url">Превью:</label><br>
                <input id="picture_url" type="text" placeholder="Ссылка на превью..." value="<?php echo $result['picture_url'] ?>">
            </div>
        </div>
        <div class="col-12" style="margin-top: 5%;">
            <div class="in">
                <div class="d-flex flex-wrap">
                    <button id="submit" type="submit" class="btn btn-primary mr-4 mb-2" onclick="edit();">Сохранить</button>
                    <button id="submit" type="submit" class="btn btn-danger mr-4 mb-2" onclick="submit_delete();">Удалить подкатегорию</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function edit() {
        $.ajax({
            type: 'post',
            url: "/api/subcategories/edit",
            data: 'id=<?php echo $result["id"] ?>&category_id='+$("#category_id").val().trim()+'&name='+$("#name").val().trim()+'&description='+$("#description").val().trim()+'&picture_url='+$("#picture_url").val().trim(),
            dataType: 'json',
            success: function(data){
                console.log(data);
                if (data.result == 1) {
                    Swal.fire({
                        title: "Успешно!",
                        text: "Подкатегория была отредактирована",
                        icon: "success"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            return location.reload();
                        }
                    });

                    document.getElementById('submit').onclick = "";
                    document.getElementById('category_id').disabled = true;
                    document.getElementById('name').disabled = true;
                    document.getElementById('description').disabled = true;
                    document.getElementById('picture_url').disabled = true;

                    function reload() {
                        return location.reload();
                    }

                    setTimeout(reload, 5575);
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

    function submit_change_password() {
        Swal.fire({
            title: "Вы хотите сменить пароль?",
            text: "На вашу электронную почту будет выслана ссылка для смены пароля",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#d33",
            confirmButtonText: "Да, хочу сменить!",
            cancelButtonText: "Отменить",
        }).then((result) => {
            if (result.isConfirmed) {
                change_password();
            }
        });
    }

    function change_password() {
        $.ajax({
            type: 'POST',
            url: '/api/profile/change_password',
            success: async function(data) {
                var res = $.parseJSON(data);
                console.log(res);
                if (res.result == 1) {
                    Swal.fire({
                        title: "Успешно!",
                        text: "На вашу электронную почту была выслана инструкция по смене пароля. Если письмо не пришло, пожалуйста, проверьте папку «Спам»",
                        icon: "success"
                    })
                }
                else if (res.result == 2) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ошибка!',
                        text: 'Вы слишком часто пытаетесь сменить пароль',
                        footer: '<a href="<?php echo $settings['link_to_admin'] ?>">Возникли вопросы?</a>'
                    })
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
