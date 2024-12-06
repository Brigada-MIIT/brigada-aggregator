<div class="container">
    <p class="page-title">Редактирование категории «<?php echo $name_category; ?>»</p>
    <div class="form">
        <div class="col-12">
            <div class="in">
                <label for="name">Название:</label><br>
                <input id="name" type="text" placeholder="Название категории..." value="<?php echo $result['name'] ?>">
            </div>
        </div>
        <div class="col-12">
            <div class="in">
                <label for="description">Описание:</label><br>
                <textarea id="description" type="text" placeholder="Описание категории..." style="width: 75%; display: block;"><?php echo $result['description'] ?></textarea>
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
                    <button id="submit" type="submit" class="btn btn-danger mr-4 mb-2" onclick="submit_delete();">Удалить категорию</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function edit() {
        $.ajax({
            type: 'post',
            url: "/api/categories/edit",
            data: 'id=<?php echo $result["id"] ?>&name='+$("#name").val().trim()+'&description='+$("#description").val().trim()+'&picture_url='+$("#picture_url").val().trim(),
            dataType: 'json',
            success: function(data){
                console.log(data);
                if (data.result == 1) {
                    Swal.fire({
                        title: "Успешно!",
                        text: "Категория была отредактирована",
                        icon: "success"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            return location.reload();
                        }
                    });

                    document.getElementById('submit').onclick = "";
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

    function submit_delete() {
        Swal.fire({
            title: "Вы хотите удалить категорию?",
            text: "Перед удалением вы должны убедиться, что никакие подкатегории не наследуют данную категорию",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#d33",
            confirmButtonText: "Да, хочу удалить!",
            cancelButtonText: "Отменить",
        }).then((result) => {
            if (result.isConfirmed) {
                delete_category();
            }
        });
    }

    function delete_category() {
        $.ajax({
            type: 'POST',
            url: '/api/categories/delete?id=<?php echo $result["id"] ?>',
            success: async function(data) {
                var res = $.parseJSON(data);
                console.log(res);
                if (res.result == 1) {
                    Swal.fire({
                        title: "Успешно!",
                        text: "Категория «<?php echo $name_category; ?>» была удалена",
                        icon: "success"
                    }).then((result) => {
                        location.replace("/app/categories");
                    });
                }
                else if (res.result == 2) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ошибка!',
                        text: 'Категория имеет подкатегории. Пожалуйста, уберите зависимость подкатегорий от этой категории',
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
