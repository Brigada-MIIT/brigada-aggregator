<!--<link rel="stylesheet" href="/assets/css/fron/main.css">-->
<div class="container mt-4">
    <div class="search-bar-container">
        <input type="text" class="form-control search-bar" placeholder="Поиск товаров...">
        <button class="btn btn-primary search-button">Найти</button>
    </div>

    <h2 class="popular-products-title mt-5">Популярные товары</h2>
    <div class="product-categories row">
        <div class="col-md-3 mb-4">
            <div class="product-card">
                <img src="/assets/img/front/metal.png" alt="Рельсы" class="img-fluid">
                <h2>Рельсы</h2>
                <button class="btn btn-primary">Узнать цену</button>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="product-card">
                <img src="/assets/img/front/stone.png" alt="Кирпич" class="img-fluid">
                <h2>Кирпич</h2>
                <button class="btn btn-primary">Узнать цену</button>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="product-card">
                <img src="/assets/img/front/wood.png" alt="Дерево" class="img-fluid">
                <h2>Дерево</h2>
                <button class="btn btn-primary">Узнать цену</button>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="product-card">
                <img src="/assets/img/front/other_services.jpg" alt="Прочие услуги" class="img-fluid">
                <h2>Прочие услуги</h2>
                <button class="btn btn-primary">Узнать цену</button>
            </div>
        </div>
    </div>

    <div class="product-selection mt-4">
        <h2>Категории товаров</h2>
        <div class="form-group">
            <select class="form-control" id="productCategory">
                <option value="">Выберите категорию</option>
                <option value="electronics">Электроника</option>
                <option value="kitchen">Кухонные товары</option>
            </select>
        </div>
    </div>

    <div class="pagination justify-content-center mt-4">

    </div>
</div>

<footer class="bg-light text-center text-lg-start mt-4">
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
</footer>