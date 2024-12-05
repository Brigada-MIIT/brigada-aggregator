<div class="container">
    <p class="page-title">Настройки сайта</p>
    <div class="form">
        <div class="col-12">
            <div class="in">
                <label for="max_size_avatar">Максимальный размер загрузки аватарки (МБ):</label><br>
                <input id="max_size_avatar" type="number" value="<?php echo $settings['max_size_avatar']?>"><br>
            </div>
        </div>
        <div class="col-12">
            <div class="in">
                <label for="link_to_admin">Ссылка на администратора:</label><br>
                <input id="link_to_admin" type="text" value="<?php echo $settings['link_to_admin']?>"><br>
            </div>
        </div>
        <div class="col-12">
            <div class="in">
                <br><button id="submit" type="submit" class="submit" onclick="save();">Сохранить</button>
            </div>
        </div>
    </div>
</div>
<script>
    function save() {
        $.ajax({
            type: 'post',
            url: "/api/settings/update",
            data: '&max_size_avatar='+$("#max_size_avatar").val()+'&link_to_admin='+$("#link_to_admin").val().trim(),
            dataType: 'json',
            success: function(data){
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: true,
                    timer: 6100,
                    timerProgressBar: true
                });
                if (data.result == 1) {
                    Toast.fire({
                        icon: 'success',
                        title: data.text,
                    }).then((result) => {
                        if (result.isConfirmed) {
                           return location.replace("/app/settings"); 
                        }
                    });

                    document.getElementById('submit').onclick = "";
                    document.getElementById('max_size_avatar').disabled = true;
                    document.getElementById('link_to_admin').disabled = true;

                    function reload() {
                        return location.replace("/app/settings");
                    }

                    setTimeout(reload, 6075);
                }
                else {
                    Toast.fire({
                        icon: 'error',
                        title: data.text
                    });
                }
           }
       });
    }
</script>