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
        if($('#categories').val() == '0')
            $('#name').text("Название категории");
        else
            $('#name').text("Название подкатегории");

        var table = $('#fileTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Russian.json"
            },
            "colReorder": true,
            "processing": true,
            "serverSide": true,
            "ajax": function(data, callback, settings) {
                var selectedValue = $('#categories').val();
                $.ajax({
                    url: "/api/categories/get_categories?s=" + selectedValue,
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
                { "data": 'name' }
            ],
            "paging": true,
            "lengthMenu": [ 10, 25, 50 ],
            "pageLength": 10,
            "order": [[ 0, "asc" ]]
        });

        // Обработчик события изменения значения в селекте
        $('#categories').on('change', function() {
            table.ajax.reload(); // Перезагрузка таблицы при изменении значения
            if($('#categories').val() == '0')
                $('#name').text("Название категории");
            else
                $('#name').text("Название подкатегории");
        });
    });
</script>
<div class="container mt-3">
    <div class="row align-items-center">
        <div class="col-6">
            <p class="page-title">Управление категориями</p>
        </div>
        <div class="col-6 text-right">
            <a href="/app/categories/create" class="btn btn-primary">Создать категорию</a>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label for="categories">Выберите категорию для просмотра подкатегорий:</label>
            <select id="categories" class="form-control">
                <option value="0">--Режим просмотра категорий--</option>
                <?php
                    $db = $system->db();
                    $query = $db->query("SELECT * FROM `categories`");
                    for($i = 0; $i < $query->num_rows; $i++) {
                        $result = $query->fetch_assoc();
                        $r = "";
                        if($_REQUEST['s'])
                            if($_REQUEST['s'] == $result['id'])
                                $r = "selected";
                        echo '<option '.$r.' value="' . $result['id'] . '">' . $result['name'] . '</option>';
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="col-12 mt-5">
        <table id="fileTable" class="table table-striped table-bordered" style="background-color: #fff">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col" id="name">Название категории</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>