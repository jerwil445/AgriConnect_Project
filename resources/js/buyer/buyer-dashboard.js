document.addEventListener('DOMContentLoaded', function() {
    // Mobile Toggle logic
    var filterToggleBtn = document.getElementById('filter-toggle-btn');
    var filterPanel = document.getElementById('filter-panel');
    if (filterToggleBtn && filterPanel) {
        filterToggleBtn.addEventListener('click', function() {
            filterPanel.classList.toggle('hidden');
            var icon = filterToggleBtn.querySelector('i');
            if (filterPanel.classList.contains('hidden')) {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-filter');
            } else {
                icon.classList.remove('fa-filter');
                icon.classList.add('fa-chevron-up');
            }
        });
    }

    // AJAX Live Search Logic
    const filterForm = document.querySelector('#filter-form');
    const listContainer = document.querySelector('#product-list-container');
    let debounceTimer;

    function refreshResults(url = null) {
        if (!filterForm || !listContainer) return;

        const formData = new FormData(filterForm);
        const params = new URLSearchParams();

        for (const [key, value] of formData.entries()) {
            if (value) params.append(key, value);
        }

        const fetchUrl = url || `${filterForm.action}?${params.toString()}`;

        listContainer.style.opacity = '0.5';
        listContainer.style.pointerEvents = 'none';

        fetch(fetchUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(html => {
            listContainer.innerHTML = html;
            listContainer.style.opacity = '1';
            listContainer.style.pointerEvents = 'auto';
            window.history.pushState({ path: fetchUrl }, '', fetchUrl);
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            listContainer.style.opacity = '1';
            listContainer.style.pointerEvents = 'auto';
        });
    }

    if (filterForm) {
        filterForm.querySelectorAll('input[type="text"]').forEach(input => {
            input.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => refreshResults(), 400);
            });
        });

        filterForm.querySelectorAll('select').forEach(select => {
            select.addEventListener('change', () => {
                refreshResults();
            });
        });

        filterForm.addEventListener('submit', (e) => {
            e.preventDefault();
            refreshResults();
        });
    }

    document.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('.ajax-pagination a');
        if (paginationLink) {
            e.preventDefault();
            refreshResults(paginationLink.href);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    window.addEventListener('popstate', function() {
        window.location.reload();
    });
});
