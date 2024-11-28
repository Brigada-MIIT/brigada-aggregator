<link href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/colreorder/1.5.6/js/dataTables.colReorder.min.js"></script>
<style>
    .row {
        overflow-x: auto;
    }
</style>
<script>
  $(document).ready(function() {
        $('#fileTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Russian.json"
            },
            "colReorder": true,
            "resize": true,
            "processing": true,
            "serverSide": true,
            "ajax": function(data, callback, settings) {
                console.log(data);
                $.ajax({
                    url: "/api/profile/get_uploads/<?php echo $args['id'] ?>",
                    method: "POST",
                    data: {
                        "limit": data.length,
                        "page": Math.ceil(data.start / data.length) + 1,
                        "search": data.search.value,
                        "order": data.order[0].column,
                        "dir": data.order[0].dir
                    },
                    success: function(response) {
                        let result = JSON.parse(response);
                        callback({
                            draw: data.draw,
                            recordsTotal: result.count,
                            recordsFiltered: result.filtred_count,
                            data: result.data
                        });
                    },
                    dataSrc: ''
                });
            },
            "columns": [
                { "data": 'id' },
                { "data": 'name' },
                { "data": 'created' },
                <?php if($system->haveUserPermission($system_user_id, "VIEW_HIDDEN_UPLOADS")) echo "
                { 'data': 'status' }," ?>
            ],
            "paging": true,
            "lengthMenu": [ 10, 25, 50 ], // Опции выбора количества строк на странице
            "pageLength": 10, // Количество строк на странице по умолчанию
            "order": [[ 0, "desc" ]]
        });
  });
</script>
<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-column flex-sm-row">
                <div class="d-flex align-items-center">
                    <img src="<?php echo $user['avatar'] ?>" class="rounded-circle my-3" alt="Avatar" style="border: 2px solid black; width: 96px; height: 96px; object-fit: cover;">
                    <h2 class="card-title" style="margin-left: 20px;"><?php echo $user["lastname"] ?> <?php echo $user["surname"] ?><?php if(!empty($user["patronymic"])) echo " " . $user["patronymic"] ?></h2>
                </div>
                <div class="mt-3 mt-sm-0">
                    <?php if($user['id'] == $system_user_id || $system->haveUserPermission($system_user_id, "MANAGE_USERS")) echo "
                    <a href='".(($user['id'] == $system_user_id) ? "/profile/edit" : "/app/users/".$user['id']."/edit")."' class='btn btn-primary'>Редактировать профиль</a>" ?>
                </div>
            </div>
            <hr>
            <h3>Информация о пользователе</h3>
            <p><strong>Роль:</strong> <?php echo $system->getNameRole($user['user_type']) ?></p>
            <?php if(!empty($user['biography'])) echo "
            <p><strong>О себе:</strong> ". $user['biography'] ."</p>" ?>
            <hr>
            <h3>Последние загрузки</h3>
            <table id="fileTable" class="table table-striped table-bordered" style="background-color: #fff">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Имя загрузки</th>
                        <th scope="col">Дата загрузки</th>
                        <?php if($system->haveUserPermission($system_user_id, "VIEW_HIDDEN_UPLOADS")) echo "
                        <th scope='col'>Статус</th>" ?>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>

