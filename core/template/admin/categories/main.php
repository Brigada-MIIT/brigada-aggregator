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
                { "data": 'category_name' }
            ],
            "paging": true,
            "lengthMenu": [ 10, 25, 50 ],
            "pageLength": 10,
            "order": [[ 0, "desc" ]]
        });

        // Обработчик события изменения значения в селекте
        $('#selectValue').on('change', function() {
            table.ajax.reload(); // Перезагрузка таблицы при изменении значения
        });
    });
</script>
<div class="container mt-4">
    <div class="col-12">
        <p class="page-title">Управление категориями</p>
        <div class="form-group">
            <label for="categories">Выберите категорию для просмотра подкатегорий:</label>
            <select id="categories" class="form-control">
                <option value="0">--Основной режим--</option>
                <?php
                    $db = $system->db();
                    $query = $db->query("SELECT * FROM `categories`");
                    $result = $query->fetch_assoc();
                    for($i = 0; $i < $query->num_rows; $i++)
                        echo '<option value="'.$result['id'].'">'.$result['name'].'</option>';
                ?>
            </select>
        </div>
    </div>
    <table id="fileTable" class="table table-striped table-bordered" style="background-color: #fff">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Название категории</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>