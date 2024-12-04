document.addEventListener('DOMContentLoaded', function () {
    const searchBar = document.querySelector('.search-bar');
    const searchButton = document.querySelector('.search-button');
    const searchResults = document.querySelector('.search-results');
    const resultCount = document.querySelector('.result-count');

    function updateResultCount(query) {
        const apiUrl = `https://brigada-miit.ru/api/search?s=${encodeURIComponent(query)}`;

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                resultCount.textContent = data.result;
                searchResults.style.display = 'block';
            })
            .catch(error => {
                console.error('Ошибка при запросе к API:', error);
            });
    }

    searchBar.addEventListener('input', function () {
        const query = searchBar.value;
        if (query.length > 0) {
            updateResultCount(query);
        } else {
            searchResults.style.display = 'none';
        }
    });

    searchButton.addEventListener('click', function () {
        const query = searchBar.value;
        if (query.length > 0) {
            window.location.href = `/search/${encodeURIComponent(query)}`;
        }
    });
});
