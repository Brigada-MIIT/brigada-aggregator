<link rel="stylesheet" href="/assets/css/front/categories.css">
<script src="/assets/js/front/search.js"></script>
<div class="container mt-4">
    <div class="search-bar-container mt-5">
        <input type="text" class="form-control search-bar" placeholder="Поиск товаров...">
        <button class="btn btn-primary search-button">Найти</button>
    </div>
    <div class="search-results mt-2">
        Найдено количество вариантов: <span class="result-count">0</span>
    </div>

    <h2 class="popular-products-title mt-5" style="margin-bottom: 1rem;">Категории товаров 123</h2>
    <div class="product-categories row">
        <?php
            $db = $system->db();
            $query = $db->query("SELECT * FROM `categories`");
            for($i = 0; $i < $query->num_rows; $i++) {
                $result = $query->fetch_assoc();
                $id = $result["id"];
                $name = $result["name"];
                $description = $result["description"];
                $picture_url = $result["picture_url"];
                if($picture_url == null)
                    $picture_url = "/assets/img/image_not_found.png";
                echo "
                    <div class='col-md-12 mb-4'>
                        <div class='product-card horizontal-card'>
                            <img src='${picture_url}' alt='${name}' class='img-fluid'>
                            <div class='card-body'>
                                <a style='color: unset; width: min-content;' href='/category/${id}'><h2>${name}</h2></a>
                                <p style='margin-bottom: 1rem;'>${description}</p>
                                <a style='color: unset; width: min-content;' href='/category/${id}'><button class='btn btn-success' onclick=''>Перейти к категории</button></a>
                            </div>
                        </div>
                    </div>
                ";
            }
        ?>
    </div>

    <div class="pagination justify-content-center mt-4">

    </div>
</div>

<!--<footer class="bg-light text-center text-lg-start mt-4 fixed-bottom">
    <div class="container p-4">
        <div class="row">
            <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                <h5 class="text-uppercase">Ссылки</h5>
                <ul class="list-unstyled">
                    <li><a href="Team.html" class="text-primary">Наша команда</a></li>
                </ul>
            </div>
            <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                <h5 class="text-uppercase">Социальные сети</h5>
                <ul class="list-unstyled">
                    <li><a href="https://web.telegram.org/k/#-4047480590" class="text-primary"><i class="fab fa-telegram"></i> Telegram</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
        © 2024 Copyright:
        <a class="text-dark" href="#">Бригада</a>
    </div>
</footer>-->