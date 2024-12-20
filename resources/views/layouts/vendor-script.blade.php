<script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const targetNode = document.body;

// Konfigurasi observer untuk memonitor perubahan anak
const config = { childList: true, subtree: true };

// Fungsi callback untuk menangani perubahan
const callback = function(mutationsList, observer) {
    mutationsList.forEach(mutation => {
        mutation.addedNodes.forEach(node => {
            // Cek apakah elemen yang ditambahkan adalah figcaption
            if (node.tagName === 'FIGCAPTION') {
                node.classList.add('d-none');
            }
            // Jika elemen yang ditambahkan memiliki figcaption di dalamnya
            if (node.querySelector && node.querySelector('figcaption')) {
                node.querySelectorAll('figcaption').forEach(figcaption => {
                    figcaption.classList.add('d-none');
                });
            }
        });
    });
};

// Buat observer instance
const observer = new MutationObserver(callback);

// Mulai observe
observer.observe(targetNode, config);

</script>