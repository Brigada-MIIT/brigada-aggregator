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
<div class="container mt-4">
    <div class="col-12" style="display: flex;flex-direction: row;align-items: center;">
        <p class="page-title">Последние загрузки</p>
        <br class="d-sm-none">
        <?php if($system->auth() && $_user['ban_upload'] == 0) echo '
            <a href="/uploads/create"><button style="width: 170px;" class="submit">Создать загрузку</button></a>'; ?>
    </div>
    <table id="fileTable" class="table table-striped table-bordered" style="background-color: #fff">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Имя загрузки</th>
                <th scope="col">Дата загрузки</th>
                <th scope="col">Имя пользователя</th>
                <?php if($system->haveUserPermission($system_user_id, "VIEW_HIDDEN_UPLOADS")) echo "
                <th scope='col'>Статус</th>" ?>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
</div>