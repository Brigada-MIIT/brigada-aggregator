<!--<link rel="stylesheet" href="/assets/css/fron/main.css">-->
<main class="container mt-4">
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
</main>