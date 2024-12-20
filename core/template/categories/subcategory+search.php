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

    <?php
        $main_title = "Товары подкатегории «".$name_category."»";
        if($mode == "search")
            $main_title = "Результат поиска товаров по запросу «".$search."»...";
    ?>
    <h2 class="popular-products-title mt-5" style="margin-bottom: 1rem;"><?php echo $main_title; ?></h2>
    <div class="product-categories row">
        <?php
            $modal = "";
            for($i = 0; $i < $query->num_rows; $i++) {
                $result = $query->fetch_assoc();
                $id = $result['id'];
                $name = $result['name'];
                $description = $result['description'];
                $picture_url = $result['picture_url'];
                $relationships = json_decode($result['relationships']);
                $cost = -1;
                $product_url = "";
                $list_websites = "";
                for($j = 0; $j < count($relationships); $j++) {
                    if ($cost == -1 || $cost > $relationships[$j][1]) {
                        $cost = $relationships[$j][1];
                        $product_url = $relationships[$j][0];
                    }
                    $list_websites .= "<li><a href='".$relationships[$j][0]."'><img src='".$relationships[$j][3]."' alt='".$relationships[$j][2]."' width='20' height='20'> ".$relationships[$j][2]." - ".$relationships[$j][1]." ₽</a></li>";
                }
                if($picture_url == null)
                    $picture_url = "/assets/img/image_not_found.png";
                echo "
                        <div class='col-md-12 mb-4'>
                            <div class='product-card horizontal-card'>
                                <img src='${picture_url}' alt='${name}' class='img-fluid'>
                                <div class='card-body'>
                                    <a style='color: unset; width: min-content;' href='$product_url'><h2>${name}</h2></a>
                                    <p style='margin-bottom: 1rem;'>${description}</p>
                                    <p style='font-size: 1.25rem; font-weight: bold; margin-bottom: 1rem; color: #f13f36;'>Минимальная стоимость: ${cost} ₽</p>
                                    <a style='color: unset; width: min-content;' href='$product_url' target='_blank'><button class='btn btn-success'>Перейти к товару</button></a>
                                    <a style='color: unset; width: min-content;' href='#' data-toggle='modal' data-target='#modal".($i+1)."'><button class='btn btn-primary'>Сравнение цен</button></a>
                                </div>
                            </div>
                        </div>
                    ";
                $modal .= "
                    <div class='modal fade' id='modal".($i+1)."' tabindex='-1' aria-labelledby='comparisonModalLabel".($i+1)."' style='display: none;' aria-hidden='true'>
                        <div class='modal-dialog modal-dialog-centered' role='document'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='comparisonModalLabel".($i+1)."'>Сравнение цен для «${name}»</h5>
                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                        <span aria-hidden='true'>×</span>
                                    </button>
                                </div>
                                <div class='modal-body'>
                                    <ul class='list-unstyled'>
                                        ".$list_websites."
                                    </ul>
                                    <hr class='divider'>
                                    <div class='text-center'>
                                        <span class='text-muted'>Минимальная цена:</span> <strong>${cost} ₽</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ";
            }
        ?>
    </div>

    <?php
        echo $modal;
    ?>

    <div class="pagination justify-content-center mt-4">

    </div>
</div>