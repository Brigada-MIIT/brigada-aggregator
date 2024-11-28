<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            Информация о загрузке
        </div>
        <div class="card-body">
            <h5 class="card-title"><b>Название:</b> <?php echo $result['name'] ?></h5>
            <p class="card-text"><b>Описание:</b> <?php echo $result['description'] ?></p>
            <p class="card-text"><b>Автор:</b> <?php echo (empty($result_author['lastname']) ? "Пользователь удалён" : "<a target='_blank' href='/profile/".$result_author['id']."'>".($result_author['surname'])." ".$result_author['lastname'])."</a>" ?></p>
            <p class="card-text"><b>Дата:</b> <?php echo unixDateToString(intval($result['created'])) ?></p>
            <p class="card-text"><b>Статус:</b> <?php echo ($result['status'] != -1 ? (($result['status'] == 1) ? "Опубликовано" : "Не опубликовано") : "Скрыто администратором") ?></p>
            <p class="card-text"><b>Категория:</b> <?php echo $result_category['name']; ?></p>
            <hr>
            <h5 class="card-title"><b>Файлы:</b></h5>
            <ul class="list-group">
                <?php 
                    $files = json_decode($result['files']);
                    $count = (!empty($files) ? count($files) : 0);
                    for($i = 0; $i < count($files); $i++) {
                        echo "
                            <li class='list-group-item'>
                                <div class='d-flex justify-content-between align-items-center'>
                                    <a href='/uploads/files/download/".$files[$i]->id."' target='_blank' class='btn btn-link' style='text-overflow: ellipsis; overflow: hidden;' title='".$files[$i]->name." (".formatFileSize($files[$i]->size).")'>
                                        <img src='/assets/img/files/".fileIconName(substr($files[$i]->name,strripos($files[$i]->name,'.')+1)).".png' width='16' height='16' class='mr-1'>
                                        ".$files[$i]->name."
                                    </a>
                                    <span id='size' style='white-space: nowrap;'>".formatFileSize($files[$i]->size)."</span>
                                </div>
                            </li>
                        ";
                    }
                ?>
            </ul>
            <?php if(!$check2) echo "
                <a href='/uploads/edit/".$result['id']."' class='btn ".(($result['status'] == -1 && !$system->haveUserPermission($system_user_id, "EDIT_ALL_UPLOADS")) ? "disabled " : "")."btn-primary float-right mr-2' style='margin-top: 20px;'>Редактировать пост</a>"; ?>
        </div>
    </div>
</div>

      