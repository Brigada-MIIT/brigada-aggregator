<link rel="stylesheet" href="/assets/css/front/categories.css">
<div class="container mt-4">
    <div class="search-bar-container mt-5">
        <input type="text" class="form-control search-bar" placeholder="Поиск товаров...">
        <button class="btn btn-primary search-button">Найти</button>
    </div>

    <h2 class="popular-products-title mt-5" style="margin-bottom: 1rem;">Товары подкатегории «<?php echo $name_category; ?>»</h2>
    <div class="product-categories row">
        <?php
        for($i = 0; $i < $query->num_rows; $i++) {
            $result = $query->fetch_assoc();
            $id = $result["id"];
            $name = $result["name"];
            $description = $result["description"];
            $picture_url = $result["picture_url"];
            $relationships = json_decode($result["relationships"]);
            $cost = -1;
            $product_url = "";
            for($j = 0; $j < count($relationships); $j++) {
                if ($cost == -1 || $cost > $relationships[$j][1]) {
                    $cost = $relationships[$j][1];
                    $product_url = $relationships[$j][0];
                    print($product_url);
                }
            }
            if($picture_url == null)
                $picture_url = "/assets/img/image_not_found.png";
            echo "
                    <div class='col-md-12 mb-4'>
                        <div class='product-card horizontal-card'>
                            <img src='${picture_url}' alt='${name}' class='img-fluid'>
                            <div class='card-body'>
                                <a style='color: unset; width: min-content;' href='${$product_url}'><h2>${name}</h2></a>
                                <p style='margin-bottom: 1rem;'>${description}</p>
                                <p style='font-size: 1.25rem; font-weight: bold; margin-bottom: 1rem; color: #f13f36;'>Минимальная стоимость: ${cost} ₽</p>
                                <a style='color: unset; width: min-content;' href='${$product_url}'><button class='btn btn-success' onclick=''>Перейти к товару</button></a>
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
