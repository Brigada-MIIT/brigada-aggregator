<link rel="stylesheet" href="/assets/css/front/categories.css">
<div class="container mt-4">
    <div class="search-bar-container">
        <input type="text" class="form-control search-bar" placeholder="Поиск товаров...">
        <button class="btn btn-primary search-button">Найти</button>
    </div>

    <h2 class="popular-products-title mt-5">Категории товаров</h2>
    <div class="product-categories row">
        <?php
            $db = $system->db();
            $query = $db->query("SELECT * FROM `categories`");
            for($i = 0; $i < $query->num_rows; $i++) {
                $result = $query->fetch_assoc();
                $name = $result["name"];
                $description = $result["description"];
                $picture_url = $result["picture_url"];
                if($picture_url == null)
                    $picture_url = "/assets/img/image_not_found.png";
                echo
                `<div class="col-md-12 mb-4">
                    <div class="product-card horizontal-card">
                        <img src="${picture_url}" alt="${name}" class="img-fluid">
                        <div class="card-body">
                            <h2>${name}</h2>
                            <p>${description}</p>
                            <div id="priceRange1" class="mt-3" style="display: none;">
                                <div class="alert alert-info">
                                    <strong>Минимальная цена:</strong> <span id="minPrice1">10000 руб.</span><br>
                                    <strong>Максимальная цена:</strong> <span id="maxPrice1">100000 руб.</span>
                                </div>
                            </div>
                            <button class="btn btn-primary" onclick="togglePrice(1)">Узнать цену</button>
                            <button class="btn btn-success" onclick="window.location.href='Phones.html'">Товары</button>
                            <button class="btn btn-info" data-toggle="modal" data-target="#storesModal1">Магазины</button>
                        </div>
                    </div>
                </div>`;
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